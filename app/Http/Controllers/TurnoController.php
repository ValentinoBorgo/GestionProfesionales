<?php

namespace App\Http\Controllers;

use App\Models\Turno;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Paciente;
use App\Models\TipoTurno;
use App\Models\EstadoTurno;
use App\Models\Profesional;
use App\Models\Secretario;

class TurnoController extends Controller
{
    public function turnos()
{

    $secretarios = User::where('id_tipo', 2)->get();

    $profesionales = User::where('id_tipo', 3)->get();

    $pacientes = Paciente::with(['fichaMedica' => function ($query) {
        $query->select('id', 'nombre', 'apellido');
    }])->get();

    $tipoTurnos = TipoTurno::all();

    $estadoTurnos = EstadoTurno::all();

    // Obtener todos los turnos para mostrar en la tabla
    $turnos = Turno::with([
        'secretario',
        'profesional',
        'paciente.fichaMedica',
        'tipoTurno',
        'estado'
    ])->get();

    return view('secretario.turnos', compact(
        'secretarios',
        'profesionales',
        'pacientes',
        'tipoTurnos',
        'estadoTurnos',
        'turnos'
    ));
}
public function storeTurno(Request $request)
{
    // dd($request->all());

    // primero validamos que los ids enviados sean validos osea q exitan en la tabla user
    $validatedData = $request->validate([
        'hora_fecha' => 'required|date',
        'id_profesional' => 'required|exists:users,id', 
        'id_paciente' => 'required|exists:paciente,id',
        'id_secretario' => 'required|exists:users,id', 
        'id_tipo_turno' => 'required|exists:tipo_turnos,id',
        'id_estado' => 'required|exists:estado_turnos,id'
    ]);

    //id de la tabla profesional
    $profesional = Profesional::where('id_persona', $validatedData['id_profesional'])->first();
    if (!$profesional) {
        return redirect()->back()->withErrors(['id_profesional' => 'Profesional no válido.']);
    }
    // calcular el rango de tiempo no permitido (1 hora de difencia (esto puede cambiar))
    $horaFecha = new \DateTime($validatedData['hora_fecha']);
    $inicioRango = (clone $horaFecha)->modify('-1 hour');
    $finRango = (clone $horaFecha)->modify('+1 hour');

    // verificar si ya existe un turno en ese rango para el profesional
    $existeTurno = Turno::where('id_profesional', $profesional->id)
        ->whereBetween('hora_fecha', [$inicioRango, $finRango])
        ->exists();

    if ($existeTurno) {
        return redirect()->back()->withErrors(['hora_fecha' => 'El profesional ya tiene un turno asignado en el rango de tiempo indicado.']);
    }

    // verificar que el profesional no este ausente durante esa fecha_hora
    $ausente = \DB::table('ausencias')
        ->where('id_usuario', $validatedData['id_profesional']) 
        ->where(function ($query) use ($horaFecha) {
            $query->where('fecha_inicio', '<=', $horaFecha)
                  ->where('fecha_fin', '>=', $horaFecha);
        })
        ->exists();

    if ($ausente) {
        return redirect()->back()->withErrors(['hora_fecha' => 'El profesional no está disponible en la fecha y hora seleccionada debido a una ausencia.']);
    }
    //id de la tabla secretario
    $secretario = Secretario::where('id_usuario', $validatedData['id_secretario'])->first();
    if (!$secretario) {
        return redirect()->back()->withErrors(['id_secretario' => 'Secretario no válido.']);
    }

    // reemplazar los ids enviados (users) con los ids de las tablas profesional y secretario
    $validatedData['id_profesional'] = $profesional->id;
    $validatedData['id_secretario'] = $secretario->id;

    // crear el turno
    $turno = Turno::create($validatedData);

    return redirect()->route('secretario.turnos')->with('success', 'Turno creado exitosamente');
}

public function create()
{
    //obtengo el id q sea de secretario y profesional y dps los filtro
    $tipoSecretario = TipoUsuario::where('tipo', 'SECRETARIO')->first()->id;
    
    $tipoProfesional = TipoUsuario::where('tipo', 'PROFESIONAL')->first()->id;
    
    $secretarios = User::where('id_tipo', $tipoSecretario)->get();
    
    $profesionales = User::where('id_tipo', $tipoProfesional)->get();
    
    $pacientes = Paciente::all();
    $tipoTurnos = TipoTurno::all();
    
    return view('turnos.create', compact(
        'secretarios', 
        'profesionales', 
        'pacientes', 
        'tipoTurnos'
    ));
}
}
