<?php
        namespace App\Http\Controllers;

        use App\Models\Turno;
        use Illuminate\Http\Request;
        use App\Models\User;
        use App\Models\Paciente;
        use App\Models\TipoTurno;
        use App\Models\EstadoTurno;
        use App\Models\Profesional;
        use App\Models\Secretario;
        use App\Models\Salas;
        use App\Models\Sucursal;

        class TurnoController extends Controller
        {
        public function turnos()
        {

            $secretarios = User::where('id_tipo', 2)->get();

            $profesionales = User::where('id_tipo', 3)->get();

            $pacientes = Paciente::with(['fichaMedica' => function ($query) {
                $query->select('id', 'nombre', 'apellido');
            }])->get();

        $tipoTurnos = TipoTurno::all();

        $estadoTurnos = EstadoTurno::all();

        // Obtener todos los turnos para mostrar en la tabla
        $turnos = Turno::with([
            'secretario',
            'profesional',
            'paciente.fichaMedica',
            'tipoTurno',
            'estado'
        ])->get();

        return view('secretario.turnos', compact(
            'secretarios',
            'profesionales',
            'pacientes',
            'tipoTurnos',
            'estadoTurnos',
            'turnos'
        ));
    }
    public function verTurnos()
        {

            $secretarios = User::where('id_tipo', 2)->get();

            $profesionales = User::where('id_tipo', 3)->get();

            $pacientes = Paciente::with(['fichaMedica' => function ($query) {
                $query->select('id', 'nombre', 'apellido');
            }])->get();

        $tipoTurnos = TipoTurno::all();

        $estadoTurnos = EstadoTurno::all();

        // Obtener todos los turnos para mostrar en la tabla
        $turnos = Turno::with([
            'secretario',
            'profesional',
            'paciente.fichaMedica',
            'tipoTurno',
            'estado'
        ])->get();

        return view('secretario.ver-turnos', compact(
            'secretarios',
            'profesionales',
            'pacientes',
            'tipoTurnos',
            'estadoTurnos',
            'turnos'
        ));
    }
    public function storeTurno(Request $request)
    {
        $validatedData = $this->validarTurno($request);
    
        $horaFecha = new \DateTime($validatedData['hora_fecha']);
        $this->validarFechaHora($horaFecha);
        $this->disponibilidadProfesional($validatedData['id_profesional'], $horaFecha);
    
        $this->ausenciaProfesional($validatedData['id_profesional'], $horaFecha);
    
        $secretario = $this->getSecretario(auth()->user());

        $this->validarHorarioSucursal($horaFecha, $secretario);
    
        $salaDisponible = $this->getSalaDisponible($horaFecha, $validatedData['id_tipo_turno'], $secretario);
    
        // Crear el turno
        $turno = Turno::create([
            'hora_fecha' => $validatedData['hora_fecha'],
            'id_profesional' => $validatedData['id_profesional'],
            'id_paciente' => $validatedData['id_paciente'],
            'id_tipo_turno' => $validatedData['id_tipo_turno'],
            'id_estado' => $validatedData['id_estado'],
            'id_secretario' => $secretario->id,
            'id_sala' => $salaDisponible->id,
        ]);
    
        return redirect()->route('secretario.turnos')->with('success', 'Turno creado exitosamente');
    }
    
    // Validar datos del request
    private function validarTurno(Request $request)
    {
        return $request->validate([
            'hora_fecha' => 'required|date',
            'id_profesional' => 'required|exists:profesional,id',
            'id_paciente' => 'required|exists:paciente,id',
            'id_tipo_turno' => 'required|exists:tipo_turnos,id',
            'id_estado' => 'required|exists:estado_turnos,id',
        ]);
    }
    // q ue el turno esté dentro del horario  de las sucursales
    private function validarHorarioSucursal(\DateTime $horaFecha, $secretario)
{
    $horaTurno = $horaFecha->format('H:i:s'); // Extraer solo la hora del turno

    // obtener las sucursales asignadas al secretario
    $sucursalesUsuario = \DB::table('sucursal_usuario')
        ->where('id_usuario', $secretario->id_usuario)
        ->pluck('id_sucursal');

    if ($sucursalesUsuario->isEmpty()) {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'error' => 'El usuario no tiene sucursales asignadas.',
        ]);
    }

    // verificar si alguna sucursal tiene el turno dentro del horario de apertura y cierre
    $horarioValido = \DB::table('sucursal')
        ->whereIn('id', $sucursalesUsuario)
        ->where('horario_apertura', '<=', $horaTurno)
        ->where('horario_cierre', '>=', $horaTurno)
        ->exists();

    if (!$horarioValido) {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'hora_fecha' => 'El turno debe estar dentro del horario de apertura y cierre de las sucursales disponibles.',
        ]);
    }
}
    
    // valida que la fecha no sea anterior a la actial ej: hoy 20/12 no se puede crear un turno en 19/12
    private function validarFechaHora(\DateTime $horaFecha)
    {
        $horaActual = new \DateTime(now());
        if ($horaFecha < $horaActual) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'hora_fecha' => 'No se puede agendar un turno con una fecha y hora anterior a la actual.',
            ]);
        }
    }
    
    // q el profesional este disponible
    private function disponibilidadProfesional($idProfesional, \DateTime $horaFecha, $idTurnoExcluido = null)
{
    $inicioRango = (clone $horaFecha)->modify('-1 hour');
    $finRango = (clone $horaFecha)->modify('+1 hour');

    $query = Turno::where('id_profesional', $idProfesional)
        ->whereBetween('hora_fecha', [$inicioRango, $finRango])
        ->whereHas('estado', function ($query) {
            $query->whereIn('codigo', ['ASIGNADO', 'REPROGRAMADO']);
        });

    if ($idTurnoExcluido) {
        $query->where('id', '<>', $idTurnoExcluido);
    }

    if ($query->exists()) {
        throw \Illuminate\Validation\ValidationException::withMessages([
            'hora_fecha' => 'El profesional ya tiene un turno asignado en el rango de tiempo indicado.',
        ]);
    }
}
    
    // q el profesional no este ausente, por ejemplo q este de vacaciones
    private function ausenciaProfesional($idProfesional, \DateTime $horaFecha)
    {
        $ausente = \DB::table('ausencias')
            ->where('id_usuario', $idProfesional)
            ->where(function ($query) use ($horaFecha) {
                $query->where('fecha_inicio', '<=', $horaFecha)
                    ->where('fecha_fin', '>=', $horaFecha);
            })
            ->exists();
    
        if ($ausente) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'hora_fecha' => 'El profesional no está disponible en la fecha y hora seleccionada debido a una ausencia.',
            ]);
        }
    }
    
    // get del secretario q esta logueado
    private function getSecretario($user)
    {
        $secretario = $user->secretario;
        if (!$secretario) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'error' => 'El usuario logueado no está registrado como secretario.',
            ]);
        }
        return $secretario;
    }
    
    // get para tener las salas dispobinles
    private function getSalaDisponible(\DateTime $horaFecha, $idTipoTurno, $secretario)
    {
        $tipoTurno = TipoTurno::find($idTipoTurno);
        $sucursalesUsuario = \DB::table('sucursal_usuario')
            ->where('id_usuario', $secretario->id_usuario)
            ->pluck('id_sucursal');
    
        if ($sucursalesUsuario->isEmpty()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'error' => 'El usuario no tiene sucursales asignadas.',
            ]);
        }
    
        // mismo tipo de sala con mismo tipo de turno
        $salasDisponibles = Salas::whereIn('id_sucursal', $sucursalesUsuario)
            ->where('tipo', $tipoTurno->codigo)
            ->get();
    
        $inicioRango = (clone $horaFecha)->modify('-1 hour');
        $finRango = (clone $horaFecha)->modify('+1 hour');
    
        $salasOcupadas = Turno::whereBetween('hora_fecha', [$inicioRango, $finRango])
            ->whereHas('sala', function ($query) use ($tipoTurno, $sucursalesUsuario) {
                $query->where('tipo', $tipoTurno->codigo)
                    ->whereIn('id_sucursal', $sucursalesUsuario);
            })
            ->whereHas('estado', function ($query) {
                $query->whereIn('codigo', ['ASIGNADO', 'REPROGRAMADO']);
            })
            ->count();
    
        if ($salasOcupadas >= $salasDisponibles->count()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'hora_fecha' => 'No hay salas disponibles en este rango de tiempo.',
            ]);
        }
    
        return $salasDisponibles->first();
    }
    //muestra los turnos del dia actual
    public function index()
{
    $hoy = now()->startOfDay(); 
    $manana = now()->endOfDay(); 

    
    $turnosHoy = Turno::with([
        'secretario',
        'profesional',
        'paciente.fichaMedica',
        'tipoTurno',
        'estado',
        'sala.sucursal',
    ])->whereBetween('hora_fecha', [$hoy, $manana])->get();

    return view('secretario.index', compact('turnosHoy'));
}
public function editarTurno($id)
{
    $turno = Turno::findOrFail($id);

    $secretarios = User::where('id_tipo', 2)->get();
    $profesionales = User::where('id_tipo', 3)->get();
    $pacientes = Paciente::with(['fichaMedica' => function ($query) {
        $query->select('id', 'nombre', 'apellido');
    }])->get();
    $tipoTurnos = TipoTurno::all();
    $estadoTurnos = EstadoTurno::all();

    return view('secretario.modificar-turno', compact(
        'turno',
        'secretarios',
        'profesionales',
        'pacientes',
        'tipoTurnos',
        'estadoTurnos'
    ));
}

public function actualizarTurno(Request $request, $id)
{
    $validatedData = $this->validarTurno($request);

    $turno = Turno::findOrFail($id);

    $horaFecha = new \DateTime($validatedData['hora_fecha']);
    $this->validarFechaHora($horaFecha);
    $this->disponibilidadProfesional($validatedData['id_profesional'], $horaFecha, $id);
    $this->ausenciaProfesional($validatedData['id_profesional'], $horaFecha);

    $secretario = $this->getSecretario(auth()->user());
    $this->validarHorarioSucursal($horaFecha, $secretario);

    $salaDisponible = $this->getSalaDisponible($horaFecha, $validatedData['id_tipo_turno'], $secretario);

    // Actualizar los datos del turno
    $turno->update([
        'hora_fecha' => $validatedData['hora_fecha'],
        'id_profesional' => $validatedData['id_profesional'],
        'id_paciente' => $validatedData['id_paciente'],
        'id_tipo_turno' => $validatedData['id_tipo_turno'],
        'id_estado' => $validatedData['id_estado'],
        'id_sala' => $salaDisponible->id,
    ]);

    return redirect()->route('secretario.ver-turnos')->with('success', 'Turno actualizado exitosamente');
}

public function cancelarTurno($id)
{
    $turno = Turno::findOrFail($id); // Encuentra el turno por ID

    // Cambia el estado del turno a "Cancelado"
    $estadoCancelado = EstadoTurno::where('codigo', 'CANCELADO_PROFESIONAL')->first();

    if (!$estadoCancelado) {
        return redirect()->back()->with('error', 'No se encontró el estado "Cancelado".');
    }

    $turno->update([
        'id_estado' => $estadoCancelado->id,
    ]);

    return redirect()->back()->with('success', 'Turno cancelado exitosamente.');
}

}
