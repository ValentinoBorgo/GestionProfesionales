<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Turno;

class Agenda extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.agenda';

    public $turnos; // Definir la variable para los turnos

    // Sobrescribir el método mount() para obtener los turnos
    public function mount()
    {
        $profesionalId = auth()->user()->profesional->id;

        // Obtener los turnos del profesional autenticado y que no estén cancelados
        $this->turnos = Turno::with(['paciente', 'sala.sucursal', 'paciente.fichaMedica', 'estado'])
            ->where('id_profesional', $profesionalId) // Filtra por el profesional autenticado
            ->whereHas('estado', function ($query) {
                $query->whereNotIn('codigo', ['CANCELADO_PROFESIONAL', 'CANCELADO_CLIENTE']);
            }) // Excluye los turnos cancelados por el profesional o cliente
            ->orderBy('hora_fecha', 'asc') // Ordena por hora
            ->get();
    }
}
