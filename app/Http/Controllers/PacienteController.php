<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\User;
use App\Models\FichaMedica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PacienteController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255', // nombre usuario unico
        'nombre' => 'required|string|max:255',
        'apellido' => 'required|string|max:255',
        'telefono' => 'required|string|max:15',
        'edad' => 'required|integer',
        'domicilio' => 'required|string|max:255',
        'nobre_usuario' => 'required|string|max:255|unique:users',
        'email' => 'required|string|email|max:255|unique:users', // mail unico
        'password' => [
            'required',
            'string',
            'min:8', 
            'regex:/[A-Z]/', // Al menos 1 letra mayuscula
            'regex:/[a-z]/', // Al menos 1 letra minúscula
            'regex:/[0-9]/', // Al menos 1 número
            'regex:/[@$!%*#?&]/', // Al menos 1 carácter especial
        ],
        'ocupacion' => 'required|string|max:255',
        'localidad' => 'required|string|max:255',
        'provincia' => 'required|string|max:255',
        'persona_responsable' => 'required|string|max:255',
        'vinculo' => 'required|integer',
        'dni' => 'required|integer',
        'telefono_persona_responsable' => 'required|string|max:15',
    ]);

    $user = User::create([
        'name' => $request->name,
        'apellido' => $request->apellido,
        'telefono' => $request->telefono,
        'edad' => $request->edad,
        'domicilio' => $request->domicilio,
        'nobre_usuario' => $request->nobre_usuario,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);


    $paciente = Paciente::create([
        'id_usuario' => $user->id,  
        'fecha_alta' => now()->format('Y-m-d'),
    ]);

  
    $fichaMedica = FichaMedica::create([
        'id_paciente' => $paciente->id, 
        'nombre' => $request['nombre'],
        'fecha_nac' => $request['fecha_nac'],
        'ocupacion' => $request['ocupacion'],
        'localidad' => $request['localidad'],
        'vinculo' => $request['vinculo'],
        'dni' => $request['dni'],
        'telefono_persona_responsable' => $request['telefono_persona_responsable'],
    ]);

    return view('secretario.index');
}
public function create()
{
    return view('secretario.dar-alta-paciente'); // Asegúrate de que la vista existe
}
    public function verPacientes()
    {
        $idUsuarios = Paciente::pluck('id_usuario')->toArray();

        $usuariosPacientes = User::whereIn('id', $idUsuarios)->get();

       
        return view('secretario.ver-pacientes', compact('usuariosPacientes'));
    }
}
