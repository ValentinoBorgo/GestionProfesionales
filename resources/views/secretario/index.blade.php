<!DOCTYPE html>
<html>
<head>
    <title>Secretario</title>
</head>
<body>
    <h1>Página principal del secretario</h1>
    <ul>
        <li><a href="{{ url('secretario/ver-pacientes') }}">Ver Pacientes</a></li>
        <li><a href="{{ url('secretario/modificar-turnos') }}">Modificar Turnos</a></li>
        <li><a href="{{ url('secretario/agendar-turnos') }}">Agendar Turnos</a></li>
    </ul>
</body>
</html>
