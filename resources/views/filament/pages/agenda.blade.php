<x-filament::page>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px 12px;
            text-align: left; /* Alinea todo a la izquierda */
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
            color: black;
        }

        td {
            border-bottom: 1px solid #ddd; /* Agrega un borde entre las filas */
        }

        tbody tr:hover {
            background-color: #f9f9f9; /* Resalta las filas al pasar el ratón */
            color: black;
        }

        /* Estilo personalizado para el enlace "Gestionar Ficha Médica" */
        .btn-gestion {
            display: inline-block;
            padding: 8px 16px;
            background-color: #28a745; /* Verde de fondo */
            color: white; /* Color de texto */
            text-decoration: none; /* Elimina el subrayado */
            border-radius: 5px; /* Bordes redondeados */
            font-weight: bold; /* Negrita */
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-gestion:hover {
            background-color: #218838; /* Color verde más oscuro al pasar el ratón */
            transform: scale(1.05); /* Efecto de agrandar ligeramente el tamaño */
        }

        /* Botón de Cancelar Turno */
        .btn-cancelar {
            display: inline-block;
            padding: 8px 16px;
            background-color: #dc3545; /* Rojo de fondo */
            color: white; /* Color de texto */
            text-decoration: none; /* Elimina el subrayado */
            border-radius: 5px; /* Bordes redondeados */
            font-weight: bold; /* Negrita */
            text-align: center;
            margin-left: 10px; /* Espaciado entre botones */
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-cancelar:hover {
            background-color: #c82333; /* Color rojo más oscuro al pasar el ratón */
            transform: scale(1.05); /* Efecto de agrandar ligeramente el tamaño */
        }

        .btn-cancelar:active {
            background-color: #bd2130; /* Color más oscuro cuando se hace clic */
        }
    </style>

    <table>
        <thead>
            <tr>
                <th>Paciente</th>
                <th>Sucursal</th>
                <th>Hora</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($turnos as $turno)
                <tr>
                    <td>{{ $turno->paciente->fichaMedica->nombre }} {{ $turno->paciente->fichaMedica->apellido }}</td>
                    <td>{{ $turno->sala->sucursal->nombre }}</td>
                    <td>{{ $turno->hora_fecha }}</td>
                    <td>
                        <a href="{{ route('fichaMedica.show', $turno->paciente->fichaMedica->id) }}" class="btn-gestion">Detalle Ficha Médica</a>
                        <a href="{{ route('turno.cancelarTurno', $turno->id) }}" class="btn-cancelar">Cancelar Turno</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No tiene turnos asignados.</td> <!-- Ajustar el colspan -->
                </tr>
            @endforelse
        </tbody>
    </table>
</x-filament::page>
