<x-filament::page>
    <h1>Agenda</h1>
    <table>
        <thead>
            <tr>
                <th>Paciente</th>
                <th>Sucursal</th>
                <th>Hora</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($this->getTurnos() as $turno)
                <tr>
                    <td>{{ $turno->paciente->nombre }}</td>
                    <td>{{ $turno->sucursal->nombre }}</td>
                    <td>{{ $turno->hora_fecha }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-filament::page>
