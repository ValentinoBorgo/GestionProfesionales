<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FichaMedica;
use App\Models\Paciente;
use Carbon\Carbon;

class PacienteController extends Controller
{
    public function create()
    {
        return view('secretario.dar-alta-paciente');
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'edad' => 'required|string|max:10',
            'fecha_nac' => 'required|date',
            'ocupacion' => 'required|string|max:255',
            'domicilio' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'localidad' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'persona_responsable' => 'required|string|max:255',
            'vinculo' => 'required|string|max:50',
            'dni' => 'required|string|max:20',
            'telefono_persona_responsable' => 'required|string|max:20'
        ]);


        $fichaMedica = FichaMedica::create($validatedData);


        $paciente = Paciente::create([
            'fecha_alta' => Carbon::now(),
            'id_ficha_medica' => $fichaMedica->id
        ]);

        return redirect()->route('secretario.ver-pacientes')
            ->with('success', 'Paciente dado de alta correctamente');
    }
}