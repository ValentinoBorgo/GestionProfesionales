<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProfesionalController extends Controller
{
    public function index()
    {
        return view('profesional.index');
    }

    public function modificarTurnos()
    {
        return view('profesional.modificar');
    }

    // public function agendarTurnos()
    // {
    //     return view('profesional.agendar-turnos');
    // }
}

