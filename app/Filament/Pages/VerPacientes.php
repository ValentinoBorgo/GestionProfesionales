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
        return $user->roles->pluck('nombre')->contains(fn ($role) => in_array($role, ['ROLE_SECRETARIO']));
    }

    public $pacientes;
    public $search = '';

    public function mount()
    {
        $this->loadPacientes();
    }

    // Método invocado al hacer clic en el botón "Buscar"
    public function buscar()
    {
        logger("Buscando pacientes con: " . $this->search);
        $this->loadPacientes();
    }

    protected function loadPacientes()
    {
        $this->pacientes = FichaMedica::with('paciente')
            ->when($this->search, function ($query) {
                $query->whereHas('paciente', function ($q) {
                    $q->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('apellido', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->get();
    }
}
