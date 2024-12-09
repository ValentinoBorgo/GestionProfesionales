<form method="POST" action="{{ route('secretario.turnos.store') }}">
    @csrf

    <div class="form-group">
        <label>Fecha y Hora</label>
        <input type="datetime-local" name="hora_fecha" class="form-control" required>
        @error('hora_fecha')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Secretario</label>
        <select name="id_secretario" class="form-control" required>
            @foreach($secretarios as $secretario)
                <option value="{{ $secretario->id }}">
                    {{ $secretario->name }} {{ $secretario->apellido }}
                </option>
            @endforeach
        </select>
        @error('id_secretario')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Profesional</label>
        <select name="id_profesional" class="form-control" required>
            @foreach($profesionales as $profesional)
                <option value="{{ $profesional->id }}">
                    {{ $profesional->name }} {{ $profesional->apellido }}
                </option>
            @endforeach
        </select>
        @error('id_profesional')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Paciente</label>
        <select name="id_paciente" class="form-control" required>
            @foreach($pacientes as $paciente)
                <option value="{{ $paciente->id }}">
                    {{ $paciente->fichaMedica->nombre }} {{ $paciente->fichaMedica->apellido }}
                </option>
            @endforeach
        </select>
        @error('id_paciente')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Tipo de Turno</label>
        <select name="id_tipo_turno" class="form-control" required>
            @foreach($tipoTurnos as $tipoTurno)
                <option value="{{ $tipoTurno->id }}">
                    {{ $tipoTurno->nombre }}
                </option>
            @endforeach
        </select>
        @error('id_tipo_turno')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <input type="hidden" name="id_estado" value="1">

    <button type="submit" class="btn btn-primary">Crear Turno</button>
</form>
            <h3>Turnos Existentes</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Fecha y Hora</th>
                        <th>Secretario</th>
                        <th>Profesional</th>
                        <th>Paciente</th>
                        <th>Tipo de Turno</th>
                        <th>Estado</th>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>