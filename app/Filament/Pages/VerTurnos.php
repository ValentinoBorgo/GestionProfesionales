<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Turno;

class VerTurnos extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Ver Turnos';
    protected static string $view = 'filament.pages.ver-turnos';
    public $turnos;

    public function mount()
    {
        // Cargar todos los turnos con relaciones necesarias
        $this->turnos = Turno::with([
            'secretario.usuario',
            'profesional.persona',
            'paciente.fichaMedica',
            'tipoTurno',
            'estado',
            'sala.sucursal',
        ])->get();
    }
}

