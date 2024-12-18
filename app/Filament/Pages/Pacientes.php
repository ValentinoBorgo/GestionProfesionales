<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\FichaMedica;
use App\Models\Turno;

class Pacientes extends Page
{
    protected static string $view = 'filament.pages.pacientes';
    protected static ?string $navigationLabel = 'Ver Pacientes - Profesional';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user->roles->pluck('nombre')->contains(fn ($role) => in_array($role, ['ROLE_PROFESIONAL'])) ? true : false;
    }

    public $fichasMedicas;
    public function mount()
    {
        $user = auth()->user();
        $this->fichasMedicas = collect();
    
        if ($user->profesional) {
            // Si existe un profesional, obtener sus turnos y fichas médicas
            $profesionalId = $user->profesional->id;
    
            $turnos = Turno::where('id_profesional', $profesionalId)
                ->with('paciente')
                ->get();
    
            $pacientes = $turnos->pluck('paciente')->unique();
            $this->fichasMedicas = FichaMedica::whereIn('id', $pacientes->pluck('id_ficha_medica'))->get();
        } elseif ($user->secretario) {
            // Si no hay profesional, pero existe un secretario, obtener sus turnos y fichas médicas
            $secretarioId = $user->secretario->id;
    
            $turnos = Turno::where('id_secretario', $secretarioId)
                ->with('paciente')
                ->get();
    
            $pacientes = $turnos->pluck('paciente')->unique();
            $this->fichasMedicas = FichaMedica::whereIn('id', $pacientes->pluck('id_ficha_medica'))->get();
        }
    }    
}
