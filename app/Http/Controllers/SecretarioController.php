<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SecretarioController extends Controller
{
    public function index()
    {
        return view('secretario.index');
    }


    public function modificarTurnos()
    {
        return view('secretario.modificar-turnos');
    }

    public function agendarTurnos()
    {
        return view('secretario.agendar-turnos');
    }
}

