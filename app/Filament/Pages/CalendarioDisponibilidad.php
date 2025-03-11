<?php

namespace App\Filament\Pages;

use App\Models\Disponibilidad;
use App\Models\Sucursal;
use App\Models\Salas;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
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

    /* =========================================================================
       Métodos de Inicialización y Carga de Datos
    ========================================================================= */

    // esto se ejecuta cuando el componente se "monta"
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

    /* =========================================================================
       Métodos Auxiliares (Getters)
    ========================================================================= */

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
        // Lista base de horarios según la sucursal (o un rango por defecto)
        $horarios = $this->horariosList;
    
        // Se obtiene el día seleccionado
        $dia = $this->disponibilidadSeleccionada['dia'] ?? null;
        if (!$dia) {
            return $horarios;
        }
    
        // Obtener sucursal y sala seleccionados
        $sucursalId = $this->sucursalSeleccionada;
        $salaId = $this->salaSeleccionada;
    
        // Para el profesional logueado: obtenemos sus disponibilidades para ese día, EXCLUYENDO la disponibilidad que se está editando (si existe)
        $queryProfesional = Disponibilidad::where('dia', $dia)
            ->where('id_usuario', Auth::id());
        if (!empty($this->disponibilidadSeleccionada['id'])) {
            $queryProfesional->where('id', '!=', $this->disponibilidadSeleccionada['id']);
        }
        $disponibilidadesDelDiaDelProfesional = $queryProfesional->get();
    
        // Para otros profesionales: obtenemos las disponibilidades para ese día, sucursal y sala
        $disponibilidadesDelDiaOtrosProfesionales = Disponibilidad::where('dia', $dia)
            ->where('id_sucursal', $sucursalId)
            ->where('id_sala', $salaId)
            ->where('id_usuario', '!=', Auth::id())
            ->get();
    
        // Combinar ambas colecciones
        $disponibilidadesDelDia = $disponibilidadesDelDiaDelProfesional->merge($disponibilidadesDelDiaOtrosProfesionales);
    
        // Filtrar los horarios disponibles: descartar aquellos que se solapen con alguna disponibilidad registrada
        $horariosFiltrados = array_filter($horarios, function ($horaSlot) use ($disponibilidadesDelDia) {
            $hora = Carbon::createFromFormat('H:i', $horaSlot);
            foreach ($disponibilidadesDelDia as $disp) {
                // Se asume que en la base los tiempos vienen en formato H:i:s
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
    // Si no se ha seleccionado una hora de inicio, devolvemos el rango completo.
    $horaInicio = trim($this->disponibilidadSeleccionada['horario_inicio'] ?? '');
    if (!$horaInicio) {
        return $this->getHorariosListProperty();
    }
    // Extraemos solo la parte "H:i"
    $horaInicio = substr($horaInicio, 0, 5);
    $startTime = Carbon::createFromFormat('H:i', $horaInicio);
    
    // Obtener el día (debe ser consistente con lo que guardás, por ejemplo "martes")
    $dia = trim($this->disponibilidadSeleccionada['dia'] ?? '');
    if (!$dia) {
        return $this->getHorariosListProperty();
    }
    
    // (En creación, para el límite ignoramos sucursal y sala, ya que la disponibilidad del usuario debe bloquear esa franja)
    $horarios = $this->getHorariosListProperty();
    
    if (!empty($this->disponibilidadSeleccionada['id'])) {
        // En edición: usamos el horario_fin actual del registro
        $horarioFinStr = trim($this->disponibilidadSeleccionada['horario_fin'] ?? '');
        $horarioFinStr = substr($horarioFinStr, 0, 5);
        $limit = Carbon::createFromFormat('H:i', $horarioFinStr);
    } else {
        // En creación: buscamos la próxima disponibilidad del usuario para ese día (sin importar sucursal o sala)
        $nextAvailability = Disponibilidad::where('dia', $dia)
            ->where('id_usuario', Auth::id())
            ->where('horario_inicio', '>', $horaInicio)
            ->orderBy('horario_inicio', 'asc')
            ->first();
        if ($nextAvailability) {
            $nextStart = trim($nextAvailability->horario_inicio);
            $nextStart = substr($nextStart, 0, 5);
            // Establecemos el límite igual al inicio de la próxima disponibilidad.
            $limit = Carbon::createFromFormat('H:i', $nextStart);
        } else {
            // Si no hay próxima disponibilidad, usamos el último slot de la lista base.
            $lastSlot = end($horarios);
            $lastSlot = substr(trim($lastSlot), 0, 5);
            $limit = Carbon::createFromFormat('H:i', $lastSlot);
        }
    }
    
    // Filtrar la lista base de horarios: devolvemos solo aquellos slots que sean mayores que el inicio y menores o iguales al límite.
    $availableSlots = array_filter($horarios, function($slot) use ($startTime, $limit) {
        $slot = substr(trim($slot), 0, 5);
        $slotTime = Carbon::createFromFormat('H:i', $slot);
        return $slotTime->greaterThan($startTime) && $slotTime->lessThanOrEqualTo($limit);
    });
    
    // Depuración: logueamos los valores
    logger("Hora inicio: " . $startTime->format('H:i') . ", Límite: " . $limit->format('H:i'));
    logger("Slots disponibles para fin: " . json_encode(array_values($availableSlots)));
    
    return array_values($availableSlots);
}



    
    
    
    


    



    

    

    /* =========================================================================
       Métodos de Interacción y Eventos
    ========================================================================= */

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
    $diaHoy = date('l'); // Ejemplo: "Wednesday"
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
    
    // Se ejecuta cuando se actualiza la sucursal 
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

    // Actualiza la sucursal a la hora de volver a seleccionarla
    public function actualizarSucursal($value)
    {
        logger('actualizarSucursal: ' . $value);
        $this->sucursalSeleccionada = $value;
        $this->horarios = $this->getHorariosListProperty();
    }

    // Actualiza el día a la hora de volver a seleccionarlo
    public function actualizarDia()
    {
        $this->horarios = $this->getHorariosListProperty();
    }

    // Actualiza la sala a la hora de vovler a seleccionarlo
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
