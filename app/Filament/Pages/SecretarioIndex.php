<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Turno;

class SecretarioIndex extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Secretario - Dashboard';
    protected static string $view = 'filament.pages.secretario-index';

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user && $user->secretario ? true : false;
    }

    public $turnosHoy;

    public function mount()
    {
        $this->turnosHoy = Turno::with([
            'secretario.usuario',
            'profesional.persona',
            'paciente.fichaMedica',
            'tipoTurno',
            'estado',
            'sala.sucursal',
        ])->whereBetween('hora_fecha', [now()->startOfDay(), now()->endOfDay()])
            ->get();
    }
}

