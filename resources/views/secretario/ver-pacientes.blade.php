<!DOCTYPE html>
<html>
<head>
    <title>Lista de Pacientes</title>
</head>
<body>
<h1>Lista de Pacientes</h1>

//recorro la lista de pacientes y los muestro
<ul>
    @foreach ($usuariosPacientes as $paciente)
    <li>
        <strong>Nombre:</strong> {{ $paciente->name }} | 
        <strong>Apellido:</strong> {{ $paciente->apellido }} | 
        <strong>Email:</strong> {{ $paciente->email }} | 
        <strong>Teléfono:</strong> {{ $paciente->telefono }} | 
        <strong>Edad:</strong> {{ $paciente->edad }} | 
        <strong>Domicilio:</strong> {{ $paciente->domicilio }}


        <a>Gestionar ficha médica</a>
    </li>
    @endforeach
</ul>

    <a href="{{ url('secretario') }}">Volver</a>
    <a href="{{ url('secretario/dar-alta-paciente') }}">Dar de alta paciente</a>
</body>
</html>

