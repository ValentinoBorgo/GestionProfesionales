<!DOCTYPE html>
<html>
<head>
    <title>Crear Turno</title>
</head>
<body>
    <h1>Crear Nuevo Turno</h1>

    @if(session('success'))
        <div style="color: green;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('secretario.turnos.store') }}" method="POST">
        @csrf

        <div>
            <label>Fecha y Hora:</label>
            <input type="datetime-local" name="hora_fecha" required>
        </div>

        <div>
            <label>Secretario:</label>
            <select name="id_secretario" required>
                @foreach($secretarios as $secretario)
                    <option value="{{ $secretario->secretario->id }}">
                        {{ $secretario->name }} {{ $secretario->apellido }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Profesional:</label>
            <select name="id_profesional" required>
                @foreach($profesionales as $profesional)
                    <option value="{{ $profesional->profesional->id }}">
                        {{ $profesional->name }} {{ $profesional->apellido }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
    <label>Paciente:</label>
    <select name="id_paciente" required>
        @foreach($pacientes as $paciente)
            <option value="{{ $paciente->id }}">
                {{ $paciente->fichaMedica->nombre }} {{ $paciente->fichaMedica->apellido }}
            </option>
        @endforeach
    </select>
    </div>

        <div>
            <label>Tipo de Turno:</label>
            <select name="id_tipo_turno" required>
                @foreach($tipoTurnos as $tipoTurno)
                    <option value="{{ $tipoTurno->id }}">
                        {{ $tipoTurno->nombre }}
                    </option>
                @endforeach
            </select>
        </div>


        <button type="submit">Crear Turno</button>
    </form>
</body>
</html>