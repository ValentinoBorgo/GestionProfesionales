<!DOCTYPE html>
<html>
<head>
    <title>Dar de Alta Paciente</title>
</head>
<body>
    <h1>Dar de Alta Paciente</h1>
    
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pacientes.store') }}" method="POST">
        @csrf

        <label>Nombre: 
            <input type="text" name="nombre" value="{{ old('nombre') }}" required>
        </label><br>
        <label>Apellido: 
            <input type="text" name="apellido" value="{{ old('apellido') }}" required>
        </label><br>
        <label>Email: 
            <input type="email" name="email" value="{{ old('email') }}" required>
        </label><br>
        <label>Edad: 
            <input type="text" name="edad" value="{{ old('edad') }}" required>
        </label><br>
        <label>Fecha Nacimiento: 
            <input type="date" name="fecha_nac" value="{{ old('fecha_nac') }}" required>
        </label><br>
        <label>Ocupacion: 
            <input type="text" name="ocupacion" value="{{ old('ocupacion') }}" required>
        </label><br>
        <label>Domicilio: 
            <input type="text" name="domicilio" value="{{ old('domicilio') }}" required>
        </label><br>
        <label>Telefono: 
            <input type="text" name="telefono" value="{{ old('telefono') }}" required>
        </label><br>
        <label>DNI: 
            <input type="text" name="dni" value="{{ old('dni') }}" required>
        </label><br>
        <label>Localidad: 
            <input type="text" name="localidad" value="{{ old('localidad') }}" required>
        </label><br>
        <label>Provincia: 
            <input type="text" name="provincia" value="{{ old('provincia') }}" required>
        </label><br>
        <label>Persona Responsable: 
            <input type="text" name="persona_responsable" value="{{ old('persona_responsable') }}" required>
        </label><br>
        <label>Vinculo: 
            <input type="text" name="vinculo" value="{{ old('vinculo') }}" required>
        </label><br>
        <label>Tel. Responsable: 
            <input type="text" name="telefono_persona_responsable" value="{{ old('telefono_persona_responsable') }}" required>
        </label><br>

        <button type="submit">Dar de Alta Paciente</button>
    </form>
    <a href="{{ route('secretario.ver-pacientes') }}">Volver</a>
</body>
</html>