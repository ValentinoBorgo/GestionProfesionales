<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\FichaMedica;

class VerPacientes extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Ver Pacientes - Secretario';
    protected static string $view = 'filament.pages.ver-pacientes';

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user->roles->pluck('nombre')->contains(fn ($role) => in_array($role, ['ROLE_SECRETARIO'])) ? true : false;
    }

    public $pacientes;

    public function mount()
    {
        // Cargamos los pacientes con sus datos relacionados
        $this->pacientes = FichaMedica::with([
            'paciente', // Datos adicionales del paciente
        ])->get();
    }
}
