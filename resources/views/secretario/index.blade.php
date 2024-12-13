<!DOCTYPE html>
<html>
<head>
    <title>Secretario - Index</title>
</head>
<body>
    <h1>Bienvenido, Secretario</h1>

    <ul>
        <li><a href="{{ url('secretario/ver-pacientes') }}">Ver Pacientes</a></li>
        <li><a href="{{ url('secretario/ver-turnos') }}">Ver turnos</a></li>
        <li><a href="{{ url('secretario/turnos') }}">Agendar Turno</a></li>
    </ul>

    <h2>Turnos del Día de Hoy</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Fecha y Hora</th>
                <th>Secretario</th>
                <th>Profesional</th>
                <th>Paciente</th>
                <th>Tipo de Turno</th>
                <th>Estado</th>
                <th>Sala</th>
                <th>Sucursal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($turnosHoy as $turno)
                <tr>
                    <td>{{ $turno->hora_fecha }}</td>
                    <td>{{ $turno->secretario->usuario->name }} {{ $turno->secretario->usuario->apellido }}</td>
                    <td>{{ $turno->profesional->persona->name }} {{ $turno->profesional->persona->apellido }}</td>
                    <td>{{ $turno->paciente->fichaMedica->nombre }} {{ $turno->paciente->fichaMedica->apellido }}</td>
                    <td>{{ $turno->tipoTurno->nombre }}</td>
                    <td>{{ $turno->estado->nombre }}</td>
                    <td>{{ $turno->sala->nombre }}</td>
                    <td>{{ $turno->sala->sucursal->nombre }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">No hay turnos programados para hoy.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

