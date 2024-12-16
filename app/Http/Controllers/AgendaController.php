<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turno;
use Illuminate\Support\Facades\Log;

class AgendaController extends Controller
{
    public function index()
    {
        // Obtén los turnos del profesional autenticado
        $turnos = Turno::with(['paciente', 'sucursal', 'fichaMedica'])
            ->where('id_profesional', auth()->id()) // Filtra por el profesional autenticado
            ->orderBy('hora_fecha', 'asc') // Ordena por hora
            ->get();
            dd($turnos);

        return view('filament.pages.agenda', compact('turnos'));
    }
}


