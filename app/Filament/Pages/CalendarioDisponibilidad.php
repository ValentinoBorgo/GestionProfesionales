<?php

namespace App\Filament\Pages;

use App\Models\Disponibilidad;
use App\Models\Sucursal;
use App\Models\Salas;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CalendarioDisponibilidad extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static string $view = 'filament.pages.calendario-disponibilidad';

    public $disponibilidad;
    public $todasLasDisponibilidades;
    public $fechasSemana = [];
    public $diasSemana = [
        'lunes', 'martes', 'miercoles',
        'jueves', 'viernes', 'sabado', 'domingo'
    ];
    public $horarios = [];
    public $mostrarModalEdicion = false;
    public $disponibilidadSeleccionada = [];
    public $sucursales;
    public $sucursalSeleccionada = '';
    public $salaSeleccionada = '';

    // solo podes entrar aca si tenes el rol de profesional pibe
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->roles->pluck('nombre')->contains('ROLE_PROFESIONAL');
    }



    // esto se ejecuta cuando el componente se "monta" se
    public function mount()
    {
        $this->cargarDisponibilidad();
        $this->generarRangoSemanal();

        //  rango de horarios por defecto pq sino se rompe xd(08:00 - 20:00, 30 minutos de intervalo)
        $horaInicio = Carbon::createFromTimeString('08:00');
        $horaFin = Carbon::createFromTimeString('20:00');
        $periodo = CarbonPeriod::create($horaInicio, '30 minutes', $horaFin);
        $this->horarios = collect($periodo)->map(function($time) {
            return $time->format('H:i');
        })->toArray();

        // solo sucursales q pertenece el usuario
        $this->sucursales = Sucursal::whereIn('id', function ($query) {
            $query->select('id_sucursal')
                  ->from('sucursal_usuario')
                  ->where('id_usuario', Auth::id());
        })->get();

        $this->salas = collect();
    }

    // carga la disponibilidad del profesional logueado
    protected function cargarDisponibilidad()
    {
        $idprofesional = Auth::id();
        $this->disponibilidad = Disponibilidad::with(['sucursal', 'sala'])
            ->where('id_usuario', $idprofesional)
            ->get()
            ->groupBy('dia');
        $this->disponibilidad = collect($this->disponibilidad);
    }

    // genera el el rango de fechas correspondiente a la semana actual
    protected function generarRangoSemanal()
    {
        $now = Carbon::now();
        $startOfWeek = $now->startOfWeek(Carbon::MONDAY);
        for ($i = 0; $i < 7; $i++) {
            $this->fechasSemana[] = $startOfWeek->copy()->addDays($i);
        }
    }

    // carga las disponibilidades de TODOSlos profesionales según sucursal, sala y día
    protected function cargarTodasLasDisponibilidades($sucursalId, $salaId, $dia)
    {
        $this->todasLasDisponibilidades = Disponibilidad::with(['sucursal', 'sala'])
            ->where('id_sucursal', $sucursalId)
            ->where('id_sala', $salaId)
            ->where('dia', $dia)
            ->get()
            ->groupBy(function ($item) {
                return $item->dia . '-' . $item->id_sucursal . '-' . $item->id_sala;
            });
        $this->todasLasDisponibilidades = collect($this->todasLasDisponibilidades);
    }


    // getssssssssssssssssssssssssssssssssssssssssssssssssssssssss

    // retorna los horarios asignados a un día específico
    public function getHorariosDia($dia)
    {
        $horarios = $this->disponibilidad->get($dia, collect());
        return collect($horarios)->map(function ($item) {
            return [
                'id'       => $item->id,
                'sucursal' => optional($item->sucursal)->nombre,
                'sala'     => optional($item->sala)->nombre,
                'inicio'   => Carbon::parse($item->horario_inicio)->format('H:i'),
                'fin'      => Carbon::parse($item->horario_fin)->format('H:i')
            ];
        });
    }

    // lista de salas en función de la sucursal seleccionada
    public function getSalasListProperty()
    {
        return $this->sucursalSeleccionada
            ? Salas::where('id_sucursal', $this->sucursalSeleccionada)->get()
            : collect();
    }

    // lista de horarios según la sucursal seleccionada (o un rango por defecto)
    public function getHorariosListProperty()
    {
        if ($this->sucursalSeleccionada) {
            $sucursal = Sucursal::find($this->sucursalSeleccionada);
            if ($sucursal) {
                $apertura = Carbon::createFromTimeString($sucursal->horario_apertura);
                $cierre   = Carbon::createFromTimeString($sucursal->horario_cierre);
                $periodo  = CarbonPeriod::create($apertura, '30 minutes', $cierre);
                return collect($periodo)->map(function($time) {
                    return $time->format('H:i');
                })->toArray();
            }
        }
        $horaInicio = Carbon::createFromTimeString('08:00');
        $horaFin    = Carbon::createFromTimeString('20:00');
        $periodo    = CarbonPeriod::create($horaInicio, '30 minutes', $horaFin);
        return collect($periodo)->map(function($time) {
            return $time->format('H:i');
        })->toArray();
    }

    public function getHorariosDisponiblesFiltradosProperty()
    {
        $horarios = $this->horariosList;
        
        // tenemos el día en minúsculas
        $dia = strtolower(trim($this->disponibilidadSeleccionada['dia'] ?? ''));
        if (!$dia) {
            return $horarios;
        }
        
        // sala y sucursal seleccionados
        $sucursalId = $this->sucursalSeleccionada;
        $salaId = $this->salaSeleccionada;
        
        // consulta  lower para minusculas a veces no anda filament de ireda TODAS las disponibilidades para ese día, sucursal y sala
        $disponibilidadesDelDia = Disponibilidad::withoutGlobalScopes()
            ->whereRaw('LOWER(dia) = ?', [$dia])
            ->where('id_sucursal', $sucursalId)
            ->where('id_sala', $salaId)
            ->when(!empty($this->disponibilidadSeleccionada['id']), function ($query) {
                $query->where('id', '!=', $this->disponibilidadSeleccionada['id']);
            })
            ->get();
        
        logger("disponibilidadesDelDia (todos): " . json_encode($disponibilidadesDelDia));
        
        // filtro delos horarios que se ponen uno arriba de otrocon alguno de esos registros
        $horariosFiltrados = array_filter($horarios, function ($horaSlot) use ($disponibilidadesDelDia) {
            $hora = Carbon::createFromFormat('H:i', $horaSlot);
            foreach ($disponibilidadesDelDia as $disp) {
                $inicio = Carbon::createFromFormat('H:i:s', $disp->horario_inicio);
                $fin = Carbon::createFromFormat('H:i:s', $disp->horario_fin);
                if ($hora->between($inicio, $fin, true)) {
                    return false;
                }
            }
            return true;
        });
        
        return array_values($horariosFiltrados);
    }
    
    
    



    public function getHorariosFinProperty()
{
    // Si no se ha seleccionado una hora de inicio, devolvemos la lista completa.
    $horaInicio = trim($this->disponibilidadSeleccionada['horario_inicio'] ?? '');
    if (!$horaInicio) {
        return $this->getHorariosListProperty();
    }
    $horaInicio = substr($horaInicio, 0, 5);
    $startTime = Carbon::createFromFormat('H:i', $horaInicio);

    // Obtenemos el día en minúsculas para la comparación (por consistencia)
    $dia = strtolower(trim($this->disponibilidadSeleccionada['dia'] ?? ''));
    if (!$dia) {
        return $this->getHorariosListProperty();
    }

    // Obtenemos la lista base de horarios (según la sucursal)
    $horarios = $this->getHorariosListProperty();

    // Definición del límite:
    // Consultamos TODAS las disponibilidades (sin filtrar por id_usuario) para ese día, sucursal y sala
    $disponibilidadQuery = Disponibilidad::withoutGlobalScopes()
        ->whereRaw('LOWER(dia) = ?', [$dia])
        ->where('id_sucursal', $this->sucursalSeleccionada)
        ->where('id_sala', $this->salaSeleccionada)
        ->where('horario_inicio', '>', $horaInicio)
        ->orderBy('horario_inicio', 'asc');

    if (!empty($this->disponibilidadSeleccionada['id'])) {
        $disponibilidadQuery->where('id', '!=', $this->disponibilidadSeleccionada['id']);
    }
    $nextAvailability = $disponibilidadQuery->first();

    if ($nextAvailability) {
        $nextStart = substr(trim($nextAvailability->horario_inicio), 0, 5);
        // El límite es el inicio de la siguiente disponibilidad
        $limit = Carbon::createFromFormat('H:i', $nextStart);
    } else {
        // Si no existe una siguiente disponibilidad, se toma el último slot (cierre de la sucursal)
        $lastSlot = substr(trim(end($horarios)), 0, 5);
        $limit = Carbon::createFromFormat('H:i', $lastSlot);
    }

    // Filtrar los horarios de la lista base: se permiten aquellos mayores que el inicio y menores o iguales al límite.
    $availableSlots = array_filter($horarios, function($slot) use ($startTime, $limit) {
        $slot = substr(trim($slot), 0, 5);
        $slotTime = Carbon::createFromFormat('H:i', $slot);
        return $slotTime->greaterThan($startTime) && $slotTime->lessThanOrEqualTo($limit);
    });

    logger("Hora inicio: " . $startTime->format('H:i') . ", Límite: " . $limit->format('H:i'));
    logger("Slots disponibles para fin: " . json_encode(array_values($availableSlots)));

    return array_values($availableSlots);
}


/**
 * Valida si el profesional logueado tiene algún conflicto (solapamiento)
 * con el intervalo (hora de inicio y fin) que se intenta guardar, sin importar
 * la sucursal o sala.
 */
public function checkUserAvailabilityConflict(): bool
{
    // Se obtienen día, hora de inicio y hora de fin de la disponibilidad en edición/creación.
    $dia = strtolower(trim($this->disponibilidadSeleccionada['dia'] ?? ''));
    $horaInicio = trim($this->disponibilidadSeleccionada['horario_inicio'] ?? '');
    $horaFin = trim($this->disponibilidadSeleccionada['horario_fin'] ?? '');

    // Si alguno no está definido, no hay conflicto.
    if (!$dia || !$horaInicio || !$horaFin) {
        return false;
    }

    // Normalizamos las horas (por ejemplo, "08:00:00" a "08:00")
    $horaInicio = substr($horaInicio, 0, 5);
    $horaFin = substr($horaFin, 0, 5);

    // Convertimos a objetos Carbon
    $nuevoInicio = Carbon::createFromFormat('H:i', $horaInicio);
    $nuevoFin    = Carbon::createFromFormat('H:i', $horaFin);

    // Consulta todas las disponibilidades del profesional para ese día
    // Excluyendo, si es el caso, el registro que se está editando.
    $conflictDisponibilidades = Disponibilidad::whereRaw('LOWER(dia) = ?', [$dia])
        ->where('id_usuario', Auth::id())
        ->when(!empty($this->disponibilidadSeleccionada['id']), function($query) {
            $query->where('id', '!=', $this->disponibilidadSeleccionada['id']);
        })
        ->get();

    // Recorremos cada disponibilidad existente y verificamos si se solapa
    foreach ($conflictDisponibilidades as $disp) {
        // Convertimos las horas de la disponibilidad existente (se asume formato H:i:s)
        $existenteInicio = Carbon::createFromFormat('H:i:s', $disp->horario_inicio);
        $existenteFin    = Carbon::createFromFormat('H:i:s', $disp->horario_fin);

        // Dos intervalos se solapan si: nuevoInicio < existenteFin Y nuevoFin > existenteInicio
        if ($nuevoInicio->lessThan($existenteFin) && $nuevoFin->greaterThan($existenteInicio)) {
            return true;
        }
    }

    return false;
}

    
    



    
    
    
    


    



    



    // el modal para editar una disponibilidad existente
    public function editarDisponibilidad($id)
    {
        $model = Disponibilidad::find($id);
        $this->disponibilidadSeleccionada = $model->toArray();
        $this->sucursalSeleccionada = $this->disponibilidadSeleccionada['id_sucursal'] ?? '';
        $this->salaSeleccionada = $this->disponibilidadSeleccionada['id_sala'] ?? '';
        $this->mostrarModalEdicion = true;
    }

    //  formulario para crear una nueva disponibilidad
    public function crearDisponibilidad()
{
    logger('crearDisponibilidad disparado');
    $diaHoy = date('l'); // filament/livewire aggarrar dias en ingles asi q hay q pasarlo a espaol
    $dias = [
        'Monday'    => 'lunes',
        'Tuesday'   => 'martes',
        'Wednesday' => 'miercoles',
        'Thursday'  => 'jueves',
        'Friday'    => 'viernes',
        'Saturday'  => 'sabado',
        'Sunday'    => 'domingo',
    ];
    $dia = $dias[$diaHoy] ?? strtolower($diaHoy);
    
    $this->disponibilidadSeleccionada = [
        'id_sucursal'    => '',
        'id_sala'        => '',
        'horario_inicio' => '',
        'horario_fin'    => '',
        'dia'            => $dia,
    ];
    $this->salaSeleccionada = '';
    $this->mostrarModalEdicion = true;
}


    // guarda (crea o actualiza) la disponibilidad
    public function guardarDisponibilidad()
    {
        $this->disponibilidadSeleccionada['id_sucursal'] = (int)$this->sucursalSeleccionada;
        $this->disponibilidadSeleccionada['id_sala'] = (int)$this->salaSeleccionada;
        $rules = [
            'disponibilidadSeleccionada.id_sucursal'  => 'required',
            'disponibilidadSeleccionada.id_sala'        => 'required',
            'disponibilidadSeleccionada.horario_inicio' => 'required|date_format:H:i',
            'disponibilidadSeleccionada.horario_fin'    => 'required|date_format:H:i|after:disponibilidadSeleccionada.horario_inicio',
        ];
        if (!isset($this->disponibilidadSeleccionada['id'])) {
            $rules['disponibilidadSeleccionada.dia'] = 'required|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo';
        }
        $this->validate($rules);
        if ($this->checkUserAvailabilityConflict()) {
            // Puedes mostrar una notificación o lanzar una excepción para indicar el conflicto.
            \Filament\Notifications\Notification::make()
                ->title('Error')
                ->body('Ya tienes una disponibilidad que se solapa con este intervalo en otro lugar.')
                ->danger()
                ->send();
            return;
        }
        if (isset($this->disponibilidadSeleccionada['id'])) {
            $model = Disponibilidad::find($this->disponibilidadSeleccionada['id']);
            $model->id_sucursal    = $this->disponibilidadSeleccionada['id_sucursal'];
            $model->id_sala        = $this->disponibilidadSeleccionada['id_sala'];
            $model->horario_inicio = $this->disponibilidadSeleccionada['horario_inicio'];
            $model->horario_fin    = $this->disponibilidadSeleccionada['horario_fin'];
            $model->save();
            \Filament\Notifications\Notification::make()
                ->title('Disponibilidad actualizada correctamente')
                ->success()
                ->send();
        } else {
            Disponibilidad::create([
                'id_sucursal'    => $this->disponibilidadSeleccionada['id_sucursal'],
                'id_sala'        => $this->disponibilidadSeleccionada['id_sala'],
                'horario_inicio' => $this->disponibilidadSeleccionada['horario_inicio'],
                'horario_fin'    => $this->disponibilidadSeleccionada['horario_fin'],
                'dia'            => $this->disponibilidadSeleccionada['dia'],
                'id_usuario'     => Auth::id(),
            ]);
            \Filament\Notifications\Notification::make()
                ->title('Disponibilidad creada correctamente')
                ->success()
                ->send();
        }
        $this->mostrarModalEdicion = false;
        $this->cargarDisponibilidad();
    }
    //eliminar disponibilida
    public function eliminarDisponibilidad()
    {
        if (!empty($this->disponibilidadSeleccionada['id'])) {
            $model = Disponibilidad::find($this->disponibilidadSeleccionada['id']);
            if ($model) {
                $model->delete();
                \Filament\Notifications\Notification::make()
                    ->title('Disponibilidad eliminada correctamente')
                    ->success()
                    ->send();
            }
        }
        $this->mostrarModalEdicion = false;
        $this->cargarDisponibilidad();
    }
    
    //  ejecuta cuando se actualiza la sucursal 
    public function updatedSucursalSeleccionada($value)
    {
        $this->sucursalSeleccionada = $value;
        $this->salas = $this->getSalasListProperty();
        $this->horarios = $this->getHorariosListProperty();

        $sucursal = Sucursal::find($value);
        if ($sucursal) {
            $apertura = Carbon::createFromTimeString($sucursal->horario_apertura);
            $cierre = Carbon::createFromTimeString($sucursal->horario_cierre);
            $periodo = CarbonPeriod::create($apertura, '30 minutes', $cierre);
            $this->horarios = collect($periodo)->map(function($time) {
                return $time->format('H:i');
            })->toArray();
        }
    }

    // actualiza la sucursal a la hora de volver a seleccionarla
    public function actualizarSucursal($value)
    {
        logger('actualizarSucursal: ' . $value);
        $this->sucursalSeleccionada = $value;
        $this->horarios = $this->getHorariosListProperty();
    }

    // actualiza eldia a la hora de volver a seleccionarlo
    public function actualizarDia()
    {
        $this->horarios = $this->getHorariosListProperty();
    }

    // actualiza la sala a la hora de vovler a seleccionarlo
    public function actualizarSala($value)
    {
        $this->salaSeleccionada = $value;
    }


    public function actualizarHorariosFin($value = null)
    {
        logger("Actualizando horarios fin, valor recibido: " . ($value ?? 'null'));
        $this->horarios = $this->getHorariosListProperty();
    }
    


}