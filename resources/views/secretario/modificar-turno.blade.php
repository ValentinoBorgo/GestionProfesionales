<form method="POST" action="{{ route('secretario.actualizar-turno', $turno->id) }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Fecha y Hora</label>
        <input type="datetime-local" name="hora_fecha" class="form-control" value="{{ $turno->hora_fecha->format('Y-m-d\TH:i') }}" required>
        @error('hora_fecha')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="form-group">
        <label>Profesional</label>
        <select name="id_profesional" class="form-control" required>
            @foreach($profesionales as $profesional)
                <option value="{{ $profesional->profesional->id }}" {{ $turno->id_profesional == $profesional->profesional->id ? 'selected' : '' }}>
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
                <option value="{{ $paciente->id }}" {{ $turno->id_paciente == $paciente->id ? 'selected' : '' }}>
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
                <option value="{{ $tipoTurno->id }}" {{ $turno->id_tipo_turno == $tipoTurno->id ? 'selected' : '' }}>
                    {{ $tipoTurno->nombre }}
                </option>
            @endforeach
        </select>
        @error('id_tipo_turno')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label>Estado</label>
        <select name="id_estado" class="form-control" required>
            @foreach($estadoTurnos as $estadoTurno)
                <option value="{{ $estadoTurno->id }}" {{ $turno->id_estado == $estadoTurno->id ? 'selected' : '' }}>
                    {{ $estadoTurno->nombre }}
                </option>
            @endforeach
        </select>
        @error('id_estado')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Actualizar Turno</button>
</form>
<a href="{{ url('secretario/ver-turnos') }}">Volver</a>