<x-filament::page>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px 12px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
            color: black;
        }

        td {
            border-bottom: 1px solid #ddd;
        }

        tbody tr:hover {
            background-color: #f9f9f9;
            color: black;
        }

        .btn-gestion {
            display: inline-block;
            padding: 8px 16px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .btn-gestion:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .btn-gestion:active {
            background-color: #1e7e34;
        }
    </style>

    <table>
        <thead>
            <tr>
                <th>Nombre y Apellido</th>
                <th>Código de Ficha Médica</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($fichasMedicas as $ficha)
                <tr>
                    <td>{{ $ficha->nombre }} {{ $ficha->apellido }}</td>
                    <td>{{ $ficha->id }}</td>
                    <td>
                        <a href="{{ route('fichaMedica.show', $ficha->id) }}" class="btn-gestion">Gestionar Ficha Médica</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No hay pacientes asignados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-filament::page>
