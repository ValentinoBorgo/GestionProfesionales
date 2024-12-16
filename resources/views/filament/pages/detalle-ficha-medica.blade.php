<x-filament::page>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            font-weight: bold;
        }

        .btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #218838;
        }

        .back-btn {
            margin-top: 20px;
            display: inline-block;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>

    <h1>Detalle de la Ficha Médica</h1>

    <table>
        <tr>
            <th>Nombre:</th>
            <td>{{ $ficha->nombre }} {{ $ficha->apellido }}</td>
        </tr>
        <tr>
            <th>DNI:</th>
            <td>{{ $ficha->dni }}</td>
        </tr>
        <tr>
            <th>Edad:</th>
            <td>{{ $ficha->edad }}</td>
        </tr>
        <tr>
            <th>Fecha de Nacimiento:</th>
            <td>{{ $ficha->fecha_nac }}</td>
        </tr>
        <tr>
            <th>Ocupación:</th>
            <td>{{ $ficha->ocupacion }}</td>
        </tr>
        <tr>
            <th>Domicilio:</th>
            <td>{{ $ficha->domicilio }}</td>
        </tr>
        <tr>
            <th>Teléfono:</th>
            <td>{{ $ficha->telefono }}</td>
        </tr>
        <tr>
            <th>Localidad:</th>
            <td>{{ $ficha->localidad }}</td>
        </tr>
        <tr>
            <th>Provincia:</th>
            <td>{{ $ficha->provincia }}</td>
        </tr>
        <tr>
            <th>Persona Responsable:</th>
            <td>{{ $ficha->persona_responsable }}</td>
        </tr>
        <tr>
            <th>Vínculo:</th>
            <td>{{ $ficha->vinculo }}</td>
        </tr>
        <tr>
            <th>Teléfono Responsable:</th>
            <td>{{ $ficha->telefono_persona_responsable }}</td>
        </tr>
    </table>

    <div>
        <a href="{{ route('secretario.editar-ficha', $ficha->id) }}" class="btn">Editar Ficha Médica</a>
    </div>

    <div>
        <a href="{{ route('secretario.ver-pacientes') }}" class="back-btn">Volver a la Lista de Pacientes</a>
    </div>

</x-filament::page>
