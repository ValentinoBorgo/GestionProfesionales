</form>
            <h3>Turnos</h3>
            <table class="table">
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
                    </tr>
                </thead>
                <tbody>
                    @foreach($turnos as $turno)
                        <tr>
                            <td>{{ $turno->hora_fecha }}</td>
                            <td>{{ $turno->secretario->usuario->name }} {{ $turno->secretario->usuario->apellido }}</td>
                            <td>{{ $turno->profesional->persona->name }} {{ $turno->profesional->persona->apellido }}</td>
                            <td>{{ $turno->paciente->fichaMedica->nombre }} {{ $turno->paciente->fichaMedica->apellido }}</td>
                            <td>{{ $turno->tipoTurno->nombre }}</td>
                            <td>{{ $turno->estado->nombre }}</td>
                            <td>{{ $turno->sala->nombre }}</td>
                            <td>{{ $turno->sala->sucursal->nombre }}</td>
                            <td><a href="{{ route('secretario.modificar-turno', $turno->id) }}" class="btn btn-warning">Modificar turno</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>  
<a href="{{ url('secretario') }}">Volver</a>
