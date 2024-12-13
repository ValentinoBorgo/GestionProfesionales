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
    <label>Profesional</label>
    <select name="id_profesional" class="form-control" required>
        @foreach($profesionales as $profesional)
            <option value="{{ $profesional->profesional->id }}">
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
<a href="{{ url('secretario') }}">Volver</a>