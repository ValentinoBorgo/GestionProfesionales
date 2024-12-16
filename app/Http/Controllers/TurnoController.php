<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Turno;
use App\Models\User;
use App\Models\Paciente;
use App\Models\TipoTurno;
use App\Models\EstadoTurno;
use App\Services\TurnoService;
use App\Mail\Recordatorio;
use Illuminate\Support\Facades\Mail;

class TurnoController extends Controller
{
    protected $turnoService;

    public function __construct(TurnoService $turnoService)
    {
        $this->turnoService = $turnoService;
    }

    public function turnos()
    {
        $secretarios = User::where('id_tipo', 2)->get();
        $profesionales = User::where('id_tipo', 3)->get();
        $pacientes = Paciente::with(['fichaMedica' => function ($query) {
            $query->select('id', 'nombre', 'apellido');
        }])->get();

        $tipoTurnos = TipoTurno::all();
        $estadoTurnos = EstadoTurno::all();

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

    public function storeTurno(Request $request)
    {
        $validatedData = $this->turnoService->validarTurno($request);

        $horaFecha = new \DateTime($validatedData['hora_fecha']);
        $this->turnoService->validarFechaHora($horaFecha);
        $this->turnoService->disponibilidadProfesional($validatedData['id_profesional'], $horaFecha);
        $this->turnoService->ausenciaProfesional($validatedData['id_profesional'], $horaFecha);

        $secretario = $this->turnoService->getSecretario(auth()->user());
        $this->turnoService->validarHorarioSucursal($horaFecha, $secretario);

        $salaDisponible = $this->turnoService->getSalaDisponible($horaFecha, $validatedData['id_tipo_turno'], $secretario);

        $turno = Turno::create([
            'hora_fecha' => $validatedData['hora_fecha'],
            'id_profesional' => $validatedData['id_profesional'],
            'id_paciente' => $validatedData['id_paciente'],
            'id_tipo_turno' => $validatedData['id_tipo_turno'],
            'id_estado' => $validatedData['id_estado'],
            'id_secretario' => $secretario->id,
            'id_sala' => $salaDisponible->id,
        ]);

    // Obtener datos del paciente y ficha médica
    $paciente = $turno->paciente; // Relación definida en Turno
    $fichaMedica = $paciente->fichaMedica; // Relación definida en Paciente

    $correoPaciente = $fichaMedica->email; // Email del paciente
    $nombrePaciente = "{$fichaMedica->nombre} {$fichaMedica->apellido}";

    // Enviar correo al paciente
    Mail::to($correoPaciente)->send(new Recordatorio($nombrePaciente, $validatedData['hora_fecha']));

        return redirect()->route('secretario.turnos')->with('success', 'Turno creado exitosamente');
    }

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
        $turno = Turno::findOrFail($id);
    $this->turnoService->validarTurno($request);

    $horaFecha = new \DateTime($request->hora_fecha);

    $this->turnoService->validarFechaHora($horaFecha);

    $secretario = $this->turnoService->getSecretario(auth()->user());

    $this->turnoService->validarHorarioSucursal($horaFecha, $secretario);

    $this->turnoService->disponibilidadProfesional($request->id_profesional, $horaFecha, $turno->id);

    $this->turnoService->ausenciaProfesional($request->id_profesional, $horaFecha);

    $sala = $this->turnoService->getSalaDisponible($horaFecha, $request->id_tipo_turno, $secretario, $turno->id);

    $turno->update([
        'hora_fecha' => $horaFecha,
        'id_profesional' => $request->id_profesional,
        'id_paciente' => $request->id_paciente,
        'id_tipo_turno' => $request->id_tipo_turno,
        'id_estado' => $request->id_estado,
        'id_sala' => $sala->id,
    ]);
    // Obtener datos del paciente y ficha médica
    $paciente = $turno->paciente; // Relación definida en Turno
    $fichaMedica = $paciente->fichaMedica; // Relación definida en Paciente

    $correoPaciente = $fichaMedica->email; // Email del paciente
    $nombrePaciente = "{$fichaMedica->nombre} {$fichaMedica->apellido}";

    // Enviar correo al paciente
    Mail::to($correoPaciente)->send(new Recordatorio($nombrePaciente, $validatedData['hora_fecha']));
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
    }
