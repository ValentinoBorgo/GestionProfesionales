<!DOCTYPE html>
<html>
<head>
    <title>Ver Pacientes</title>
</head>
<body>
    <h1>Listado de Pacientes con Ficha Médica</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
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
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fichasMedicas as $ficha)
            <tr>
                <td>{{ $ficha->nombre }}</td>
                <td>{{ $ficha->apellido }}</td>
                <td>{{ $ficha->dni }}</td>
                <td>{{ $ficha->edad }}</td>
                <td>{{ $ficha->fecha_nac }}</td>
                <td>{{ $ficha->ocupacion }}</td>
                <td>{{ $ficha->domicilio }}</td>
                <td>{{ $ficha->telefono }}</td>
                <td>{{ $ficha->localidad }}</td>
                <td>{{ $ficha->provincia }}</td>
                <td>{{ $ficha->persona_responsable }}</td>
                <td>{{ $ficha->vinculo }}</td>
                <td>{{ $ficha->telefono_persona_responsable }}</td>
                <td>
                    <a href="{{ route('secretario.editar-ficha', $ficha->id) }}">Gestionar Ficha Médica</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('pacientes.create') }}">Dar de Alta Paciente</a>
    <a href="{{ url('secretario') }}">Volver</a>
</body>
</html>
