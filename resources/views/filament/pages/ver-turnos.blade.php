<x-filament::page>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        tbody tr:hover {
            background-color: #f9f9f9;
        }

        .btn-accion {
            display: inline-block;
            padding: 6px 12px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 0.9rem;
            transition: background-color 0.3s;
        }

        .btn-accion:hover {
            background-color: #0056b3;
        }
    </style>

    <h1 style="margin-bottom: 1rem;">Listado de Turnos</h1>

    <div style="overflow-x: auto;">
        <table>
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
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($turnos as $turno)
                    <tr>
                        <td>{{ $turno->hora_fecha }}</td>
                        <td>{{ $turno->secretario->usuario->name }} {{ $turno->secretario->usuario->apellido }}</td>
                        <td>{{ $turno->profesional->persona->name }} {{ $turno->profesional->persona->apellido }}</td>
                        <td>{{ $turno->paciente->fichaMedica->nombre }} {{ $turno->paciente->fichaMedica->apellido }}</td>
                        <td>{{ $turno->tipoTurno->nombre }}</td>
                        <td>{{ $turno->estado->nombre }}</td>
                        <td>{{ $turno->sala->nombre }}</td>
                        <td>{{ $turno->sala->sucursal->nombre }}</td>
                        <td>
                            <a href="{{ route('secretario.modificar-turno', $turno->id) }}" class="btn-accion">
                                Modificar turno
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center;">No hay turnos registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament::page>
