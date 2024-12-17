<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\FichaMedica;
use App\Models\Turno;

class Pacientes extends Page
{
    protected static string $view = 'filament.pages.pacientes';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user && $user->profesional ? true : false;
    }

    public $fichasMedicas;
    public function mount()
    {
        $profesionalId = auth()->user()->profesional->id;
    
        if ($profesionalId) {
            $turnos = Turno::where('id_profesional', $profesionalId)
                ->with('paciente')
                ->get();
            $pacientes = $turnos->pluck('paciente')->unique();
            $this->fichasMedicas = FichaMedica::whereIn('id', $pacientes->pluck('id_ficha_medica'))->get();
        } else {
            $this->fichasMedicas = collect();
        }
    }
}
