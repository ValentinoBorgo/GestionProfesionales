<x-filament::page>
    <style>
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

    <h1 style="margin-bottom: 1rem;">Listado de Pacientes</h1>
    <!-- Tabla de pacientes -->
    <div style="margin-bottom: 1rem;">
    <a href="{{ \App\Filament\Pages\DarAltaPaciente::getUrl() }}" class="btn-gestion">
    Dar de Alta Paciente
    </a>
    </div>
    <h2>Pacientes Registrados</h2>

    <div style="overflow-x: auto; width: 100%; margin-top: 1rem;">
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>DNI</th>
                    <th>Edad</th>
                    <th>Fecha Nacimiento</th>
                    <th>Ocupación</th>
                    <th>Domicilio</th>
                    <th>Teléfono</th>
                    <th>Localidad</th>
                    <th>Provincia</th>
                    <th>Persona Responsable</th>
                    <th>Vínculo</th>
                    <th>Tel. Responsable</th>
                    <th>Editar Ficha Médica</th> <!-- Nueva columna -->
                </tr>
            </thead>
            <tbody>
                @forelse ($pacientes as $fichaMedica)
                    <tr>
                        <td>{{ $fichaMedica->nombre }}</td>
                        <td>{{ $fichaMedica->apellido }}</td>
                        <td>{{ $fichaMedica->email }}</td>
                        <td>{{ $fichaMedica->dni }}</td>
                        <td>{{ $fichaMedica->edad }}</td>
                        <td>{{ $fichaMedica->fecha_nac }}</td>
                        <td>{{ $fichaMedica->ocupacion }}</td>
                        <td>{{ $fichaMedica->domicilio }}</td>
                        <td>{{ $fichaMedica->telefono }}</td>
                        <td>{{ $fichaMedica->localidad }}</td>
                        <td>{{ $fichaMedica->provincia }}</td>
                        <td>{{ $fichaMedica->persona_responsable }}</td>
                        <td>{{ $fichaMedica->vinculo }}</td>
                        <td>{{ $fichaMedica->telefono_persona_responsable }}</td>
                        <td>
                            <!-- Enlace para editar la ficha médica -->
                            <a href="{{ route('filament.editar-ficha', ['id' => $fichaMedica->id]) }}" class="btn-accion">Editar</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="14" style="text-align: center; padding: 1rem;">No hay pacientes registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament::page>
