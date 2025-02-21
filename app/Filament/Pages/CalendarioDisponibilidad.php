<?php

namespace App\Filament\Pages;

use App\Models\Disponibilidad;
use App\Models\Sucursal;
use App\Models\Salas;
use Filament\Pages\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class CalendarioDisponibilidad extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static string $view = 'filament.pages.calendario-disponibilidad';

    public $disponibilidad;
    public $fechasSemana = [];
    public $diasSemana = [
        'lunes', 'martes', 'miercoles',
        'jueves', 'viernes', 'sabado', 'domingo'
    ];
    public $mostrarModalEdicion = false;
    public $disponibilidadSeleccionada = [];
    public $sucursales;
    public $sucursalSeleccionada = '';
    public $salaSeleccionada = '';

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->roles->pluck('nombre')->contains('ROLE_PROFESIONAL');
    }

    public function mount()
    {
        $this->cargarDisponibilidad();
        $this->generarRangoSemanal();

        // Cargar solo las sucursales a las que pertenece el usuario
        $this->sucursales = Sucursal::whereIn('id', function ($query) {
            $query->select('id_sucursal')
                  ->from('sucursal_usuario')
                  ->where('id_usuario', Auth::id());
        })->get();

        $this->salas = collect();
    }

    protected function cargarDisponibilidad()
    {
        $idprofesional = Auth::id();
        $this->disponibilidad = Disponibilidad::with(['sucursal', 'sala'])
            ->where('id_usuario', $idprofesional)
            ->get()
            ->groupBy('dia');

        // Forzamos a que sea una colección
        $this->disponibilidad = collect($this->disponibilidad);
    }

    protected function generarRangoSemanal()
    {
        $now = Carbon::now();
        $startOfWeek = $now->startOfWeek(Carbon::MONDAY);
        for ($i = 0; $i < 7; $i++) {
            $this->fechasSemana[] = $startOfWeek->copy()->addDays($i);
        }
    }
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

    // Abre el modal para editar disponibilidad
    public function editarDisponibilidad($id)
    {
        $model = Disponibilidad::find($id);
        $this->disponibilidadSeleccionada = $model->toArray();
        $this->sucursalSeleccionada = $this->disponibilidadSeleccionada['id_sucursal'] ?? '';
        $this->salaSeleccionada = $this->disponibilidadSeleccionada['id_sala'] ?? '';
        $this->mostrarModalEdicion = true;
    }
    

    // Abre el modal para crear una nueva disponibilidad
    public function crearDisponibilidad()
    {
        logger('crearDisponibilidad disparado');
        $this->disponibilidadSeleccionada = [
            'id_sucursal'    => '',
            'id_sala'        => '',
            'horario_inicio' => '',
            'horario_fin'    => '',
            'dia'            => ''
        ];
        $this->sucursalSeleccionada = '';
        $this->salaSeleccionada = '';
        $this->mostrarModalEdicion = true;
    }
    


    // Método para guardar (crear o actualizar)
    public function guardarDisponibilidad()
    {

 // En el método guardarDisponibilidad
$this->disponibilidadSeleccionada['id_sucursal'] = (int)$this->sucursalSeleccionada;
$this->disponibilidadSeleccionada['id_sala'] = (int)$this->salaSeleccionada;
        $rules = [
            'disponibilidadSeleccionada.id_sucursal'  => 'required',
            'disponibilidadSeleccionada.id_sala'        => 'required',
            'disponibilidadSeleccionada.horario_inicio' => 'required|date_format:H:i',
            'disponibilidadSeleccionada.horario_fin'    => 'required|date_format:H:i|after:disponibilidadSeleccionada.horario_inicio',
        ];
        // En creación, el campo "dia" es obligatorio
        if (!isset($this->disponibilidadSeleccionada['id'])) {
            $rules['disponibilidadSeleccionada.dia'] = 'required|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo';
        }
        $this->validate($rules);

        if (isset($this->disponibilidadSeleccionada['id'])) {
            // Actualizar registro existente
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
            // Crear un nuevo registro
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
    public function getSalasListProperty()
    {
        if ($this->sucursalSeleccionada) {
            return Salas::where('id_sucursal', $this->sucursalSeleccionada)->get();
        }
        return collect();
    }
// En tu componente PHP
public function updatedSucursalSeleccionada($value)
{
    $this->salas = Salas::where('id_sucursal', $value)->get();
    $this->salaSeleccionada = '';
}
    



}
