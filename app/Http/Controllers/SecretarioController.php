<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FichaMedica;
use App\Models\Turno;

class SecretarioController extends Controller
{
    public function index()
{
    $hoy = now()->startOfDay(); 
    $manana = now()->endOfDay(); 

    $turnosHoy = Turno::with([
        'secretario',
        'profesional',
        'paciente.fichaMedica',
        'tipoTurno',
        'estado',
        'sala.sucursal',
    ])->whereBetween('hora_fecha', [$hoy, $manana])->get();

    return view('secretario.index', compact('turnosHoy'));
}    
}

