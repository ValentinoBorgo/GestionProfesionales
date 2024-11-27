<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FichaMedica;

class FichaMedicaController extends Controller
{
    public function index()
    {
        $fichasMedicas = FichaMedica::all();
        return view('fichas-medicas.index', compact('fichasMedicas'));
    }
}
