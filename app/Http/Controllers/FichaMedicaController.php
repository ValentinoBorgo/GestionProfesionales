<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FichaMedica;

class FichaMedicaController extends Controller
{
    public function verPacientes()
    {
        $fichasMedicas = FichaMedica::all();
        return view('secretario.ver-pacientes', compact('fichasMedicas'));
    }

    public function editarFicha($id)
    {
        $ficha = FichaMedica::findOrFail($id);
        return view('secretario.editar-ficha', compact('ficha'));
    }

    public function actualizarFicha(Request $request, $id)
    {
        $ficha = FichaMedica::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
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
}