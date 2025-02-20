<?php

namespace App\Filament\Pages;

use App\Models\Disponibilidad;
use App\Models\Sucursal;
use App\Models\Salas;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class CalendarioDisponibilidad extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static string $view = 'filament.pages.calendario-disponibilidad';

    public $disponibilidad;
    public $fechasSemana = [];
    public $idprofesional;
    public $diasSemana = [
        'lunes', 'martes', 'miercoles',
        'jueves', 'viernes', 'sabado', 'domingo'
    ];
    public $mostrarModalEdicion = false;
    public $disponibilidadSeleccionada;
    public $sucursales;
    public $salas;

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->roles->pluck('nombre')->contains('ROLE_PROFESIONAL');
    }

    public function mount()
    {
        $this->cargarDisponibilidad();
        $this->generarRangoSemanal();
        $this->sucursales = Sucursal::all();
        $this->salas = Salas::all();
    }

    protected function cargarDisponibilidad()
    {
        $idprofesional = Auth::id();
        $this->disponibilidad = Disponibilidad::with(['sucursal', 'sala'])
            ->where('id_usuario', $idprofesional)
            ->get()
            ->groupBy('dia');

        // Convertimos a colección explícitamente para evitar errores
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
                'id' => $item->id,
                'sucursal' => optional($item->sucursal)->nombre,
                'sala' => optional($item->sala)->nombre,
                'inicio' => Carbon::parse($item->horario_inicio)->format('H:i'),
                'fin' => Carbon::parse($item->horario_fin)->format('H:i')
            ];
        });
    }

    public function editarDisponibilidad($id)
{
    $model = Disponibilidad::find($id);
    $this->disponibilidadSeleccionada = $model->toArray();
    $this->mostrarModalEdicion = true;
}


public function actualizarDisponibilidad()
{
    $this->validate([
        'disponibilidadSeleccionada.id_sucursal'  => 'required',
        'disponibilidadSeleccionada.id_sala'        => 'required',
        'disponibilidadSeleccionada.horario_inicio' => 'required|date_format:H:i',
        'disponibilidadSeleccionada.horario_fin'    => 'required|date_format:H:i|after:disponibilidadSeleccionada.horario_inicio',
    ]);

    // Depuración: Verifica el contenido del arreglo
    logger('Datos a actualizar:', $this->disponibilidadSeleccionada);
    // o en desarrollo: dd($this->disponibilidadSeleccionada);
    
    $model = Disponibilidad::find($this->disponibilidadSeleccionada['id']);
    // Otra forma: llenar manualmente los campos para descartar problemas de asignación masiva
    $model->id_sucursal   = $this->disponibilidadSeleccionada['id_sucursal'];
    $model->id_sala       = $this->disponibilidadSeleccionada['id_sala'];
    $model->horario_inicio = $this->disponibilidadSeleccionada['horario_inicio'];
    $model->horario_fin    = $this->disponibilidadSeleccionada['horario_fin'];
    

    $this->mostrarModalEdicion = false;
    $this->cargarDisponibilidad();
}



}
