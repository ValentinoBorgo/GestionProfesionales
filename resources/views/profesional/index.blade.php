<!DOCTYPE html>
<html>
<head>
    <title>Profesional - Turnos de Hoy</title>
</head>
<body>
    <h1>Bienvenido, Profesional</h1>

    <ul>
        <li><a href="{{ url('profesional/mis-pacientes') }}">Mis pacientes</a></li>
        <li><a href="{{ url('profesional/ver-mis-turnos') }}">Mis turnos</a></li>
        <li><a href="{{ url('profesional/turnos') }}">Agendar turno</a></li>

    </ul>

    <h2>Turnos del Día de Hoy</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Fecha y Hora</th>
                <th>Paciente</th>
                <th>Tipo de Turno</th>
                <th>Estado</th>
                <th>Sala</th>
                <th>Sucursal</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($turnosHoy as $turno)
                <tr>
                    <td>{{ $turno->hora_fecha }}</td>
                    <td>{{ $turno->paciente->fichaMedica->nombre }} {{ $turno->paciente->fichaMedica->apellido }}</td>
                    <td>{{ $turno->tipoTurno->nombre }}</td>
                    <td>{{ $turno->estado->nombre }}</td>
                    <td>{{ $turno->sala->nombre }}</td>
                    <td>{{ $turno->sala->sucursal->nombre }}</td>
                    <td><a>Modificar turno</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No hay turnos programados para hoy.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
