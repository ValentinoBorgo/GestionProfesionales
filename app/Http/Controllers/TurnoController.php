<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Paciente;
use App\Models\TipoTurno;
use App\Models\EstadoTurno;

class TurnoController extends Controller
{
    public function turnos()
    {

        $secretarios = User::whereHas('roles', function($query) {
            $query->where('nombre', 'secretario');
        })->with('secretario')->get();

   
        $profesionales = User::whereHas('roles', function($query) {
            $query->where('nombre', 'profesional');
        })->with('profesional')->get();


        $pacientes = Paciente::with(['fichaMedica' => function($query) {
            $query->select('id', 'nombre', 'apellido');
        }])->get();

        $tipoTurnos = TipoTurno::all();

        $estadoTurnos = EstadoTurno::all();

        return view('secretario.turnos', compact(
            'secretarios', 
            'profesionales', 
            'pacientes', 
            'tipoTurnos', 
            'estadoTurnos'
        ));
    }

    public function storeTurno(Request $request)
    {
        $validatedData = $request->validate([
            'hora_fecha' => 'required|date',
            'id_profesional' => 'required|exists:profesional,id',
            'id_paciente' => 'required|exists:paciente,id',
            'id_secretario' => 'required|exists:secretario,id',
            'id_tipo_turno' => 'required|exists:tipo_turnos,id',
            'id_estado' => 'required|exists:estado_turnos,id'
        ]);

        $turno = Turno::create($validatedData);

        return redirect()->route('secretario.turnos')->with('success', 'Turno creado exitosamente');
    }
}
