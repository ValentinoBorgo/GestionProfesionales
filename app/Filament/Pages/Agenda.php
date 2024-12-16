<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Turno;

class Agenda extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.agenda';

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user && $user->profesional ? true : false;
    }

    public $turnos;


    public function mount()
    {
        $user = auth()->user();

        // Verificar si el usuario tiene un profesional asociado
        if ($user->profesional) {
            $profesionalId = $user->profesional->id;

            // Obtener los turnos del profesional autenticado y que no estén cancelados
            $this->turnos = Turno::with(['paciente', 'sala.sucursal', 'paciente.fichaMedica', 'estado'])
                ->where('id_profesional', $profesionalId) // Filtra por el profesional autenticado
                ->whereHas('estado', function ($query) {
                    $query->whereNotIn('codigo', ['CANCELADO_PROFESIONAL', 'CANCELADO_CLIENTE']);
                }) // Excluye los turnos cancelados por el profesional o cliente
                ->orderBy('hora_fecha', 'asc') // Ordena por hora
                ->get();
        } else {
            // Si no hay un profesional asociado, dejar el objeto vacío o manejar el caso
            $this->turnos = collect(); // Devuelve una colección vacía
        }
    }
}
