<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FichaMedica;
use App\Models\Turno;

class FichaMedicaController extends Controller
{
    public function verPacientes()
    {
        // Esto devuelve una colección, aunque no tenga resultados, nunca es null.
        $fichasMedicas = FichaMedica::all(); 
        return view('filament.pages.ver-pacientes', compact('fichasMedicas'));
    }
    



    public function editarFicha($id)
    {
        $ficha = FichaMedica::findOrFail($id);
        return view('secretario.editar-ficha', compact('ficha'));
    }

    public function show($id)
    {
        $ficha = FichaMedica::findOrFail($id);  // Encuentra la ficha médica por ID
        return view('secretario.detalle-ficha-medica', compact('ficha'));  // Devuelve la vista con los detalles
    }

    public function actualizarFicha(Request $request, $id)
    {
        $ficha = FichaMedica::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'email' => 'required|email',
            'edad' => 'required|string|max:10',
            'fecha_nac' => 'required|date',
            'ocupacion' => 'required|string|max:255',
            'domicilio' => 'required|string|max:255',
            'telefono' => 'required|integer',
            'localidad' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'persona_responsable' => 'required|string|max:255',
            'vinculo' => 'required|string|max:50',
            'dni' => 'required|string|max:20',
            'telefono_persona_responsable' => 'required|string|max:20'
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
            'email.required' => 'El formato del correo electrónico no es válido',
            'edad.required' => 'La edad es obligatoria',
            'fecha_nac.required' => 'La fecha de nacimiento es obligatoria',
            'ocupacion.required' => 'La ocupación es obligatoria',
            'domicilio.required' => 'El domicilio es obligatorio',
            'telefono.required' => 'El teléfono es obligatorio',
            'localidad.required' => 'La localidad es obligatoria',
            'provincia.required' => 'La provincia es obligatoria',
            'persona_responsable.required' => 'El nombre del responsable es obligatorio',
            'vinculo.required' => 'El vínculo es obligatorio',
            'dni.required' => 'El DNI es obligatorio',
            'telefono_persona_responsable.required' => 'El teléfono del responsable es obligatorio'
        ]);

        $ficha->update($request->all());

        return redirect()->route('secretario.ver-pacientes')->with('success', 'Ficha médica actualizada');
    }

    public function buscarFichasMedicas(Request $request)
{
    $user = auth()->user();
    $query = FichaMedica::query();
    // Filtrar por profesional o secretario logueado
    if ($user->profesional) {
        $profesionalId = $user->profesional->id;
        $turnos = Turno::where('id_profesional', $profesionalId)->with('paciente')->get();
        $pacientes = $turnos->pluck('paciente')->unique();
        $query->whereIn('id', $pacientes->pluck('id_ficha_medica'));
    } elseif ($user->secretario) {
        $secretarioId = $user->secretario->id;
        $turnos = Turno::where('id_secretario', $secretarioId)->with('paciente')->get();
        $pacientes = $turnos->pluck('paciente')->unique();
        $query->whereIn('id', $pacientes->pluck('id_ficha_medica'));
    }

    // Búsqueda por nombre o apellido
    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('nombre', 'LIKE', "%{$search}%")
              ->orWhere('apellido', 'LIKE', "%{$search}%");
        });
    }

    $fichasMedicas = $query->get();

    if ($request->ajax()) {
        return view('partials.lista_fichas', compact('fichasMedicas'))->render();
    }

    return view('nombre_de_tu_vista', compact('fichasMedicas'));
}

public function buscarFichasMedicasSecretario(Request $request)
{
    $user = auth()->user();
    $query = FichaMedica::query();
    // Filtrar por profesional o secretario logueado
    if ($user->profesional) {
        $profesionalId = $user->profesional->id;
        $turnos = Turno::where('id_profesional', $profesionalId)->with('paciente')->get();
        $pacientes = $turnos->pluck('paciente')->unique();
        $query->whereIn('id', $pacientes->pluck('id_ficha_medica'));
    } elseif ($user->secretario) {
        $secretarioId = $user->secretario->id;
        $turnos = Turno::where('id_secretario', $secretarioId)->with('paciente')->get();
        $pacientes = $turnos->pluck('paciente')->unique();
        $query->whereIn('id', $pacientes->pluck('id_ficha_medica'));
    }

    // Búsqueda por nombre o apellido
    if ($request->has('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('nombre', 'LIKE', "%{$search}%")
              ->orWhere('apellido', 'LIKE', "%{$search}%");
        });
    }

    $fichasMedicas = $query->get();

    if ($request->ajax()) {
        return view('partials.lista_fichas_paciente', compact('fichasMedicas'))->render();
    }

    return view('nombre_de_tu_vista', compact('fichasMedicas'));
}


}

