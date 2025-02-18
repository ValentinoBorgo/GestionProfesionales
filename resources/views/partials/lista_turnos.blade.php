<table>
    <thead>
        <tr>
            <th><a href="?sort=paciente">Paciente</a></th>
            <th><a href="?sort=sucursal">Sucursal</a></th>
            <th><a href="?sort=hora_fecha">Hora</a></th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
    @forelse ($turnos as $turno)
        <tr class="{{ $turno->id_estado == 4 ? 'fila-cancelada' : '' }}">
            <td>{{ $turno->paciente->fichaMedica->nombre }} {{ $turno->paciente->fichaMedica->apellido }}</td>
            <td>{{ $turno->sala->sucursal->nombre }}</td>
            <td>{{ $turno->hora_fecha }}</td>
            <td>
                <a href="{{ route('fichaMedica.show', $turno->paciente->fichaMedica->id) }}" class="btn-gestion">Detalle Ficha Médica</a>
                @if ($turno->id_estado == 4)
                    <a href="{{ route('turno.revertirTurno', $turno->id) }}" class="btn-revertir">Revertir Cancelación</a>
                    <span class="estado-cancelado">Turno Cancelado</span>
                @else
                    <a href="{{ route('turno.cancelarTurno', $turno->id) }}" class="btn-cancelar">Cancelar Turno</a>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4">No tiene turnos asignados.</td>
        </tr>
    @endforelse
    </tbody>
</table>
