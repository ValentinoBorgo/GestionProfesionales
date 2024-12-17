<x-filament::page>
    <h1 style="margin-bottom: 1rem;">Bienvenido, Secretario</h1>
    <!-- Tabla de turnos del día -->
    <h2>Turnos del Día de Hoy</h2>

    <table style="width: 100%; border-collapse: collapse; margin-top: 1rem;">
        <thead>
            <tr style="background-color: #f4f4f4; color: black; font-weight: bold;">
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
            @forelse ($turnosHoy as $turno)
                <tr style="border-bottom: 1px solid #ddd;">
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
                    <td colspan="8" style="text-align: center; padding: 1rem;">No hay turnos programados para hoy.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-filament::page>

