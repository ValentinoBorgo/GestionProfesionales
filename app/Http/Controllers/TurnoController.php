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
    $validatedData = $this->turnoService->validarTurno($request);

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
    public function revertirTurno($id)
    {
        $turno = Turno::findOrFail($id); // Encuentra el turno por ID

        // Cambia el estado del turno a "Reprogramado"
        $estadoReprogramado = EstadoTurno::where('codigo', 'REPROGRAMADO')->first();

        if (!$estadoReprogramado) {
            return redirect()->back()->with('error', 'No se encontró el estado "Reprogramado".');
        }

        $turno->update([
            'id_estado' => $estadoReprogramado->id,
        ]);

        return redirect()->back()->with('success', 'Turno reprogramado exitosamente.');
    }

    public function verTurnosProfesional()
    {
    $hoy = now()->startOfDay();
    $manana = now()->endOfDay();

    $profesionalId = auth()->user()->profesional->id;

    $turnosHoy = Turno::with([
        'secretario.usuario',
        'paciente.fichaMedica',
        'tipoTurno',
        'estado',
        'sala.sucursal',
    ])
    ->where('id_profesional', $profesionalId)
    ->whereBetween('hora_fecha', [$hoy, $manana])
    ->get();

    return view('profesional.index', compact('turnosHoy'));
    }
    
    public function buscarPacienteProfesional(Request $request)
{
    $query = Turno::query();

    // Búsqueda por paciente, sucursal o estado
    if ($request->has('search')) {
        $search = $request->input('search');
        $query->whereHas('paciente.fichaMedica', function ($q) use ($search) {
            $q->where('nombre', 'LIKE', "%{$search}%")
              ->orWhere('apellido', 'LIKE', "%{$search}%");
        })
        ->orWhereHas('sala.sucursal', function ($q) use ($search) {
            $q->where('nombre', 'LIKE', "%{$search}%");
        })
        ->orWhere('id_estado', 'LIKE', "%{$search}%");
    }

    // Ordenamiento por estado (Todos, Cancelado o Programado)
    if ($request->has('sort') && $request->input('sort') === 'estado') {
        $order = $request->input('order');
        if ($order === 'cancelado') {
            $query->whereIn('id_estado', [3, 4]); // IDs para estados cancelados
        } elseif ($order === 'programado') {
            $query->whereIn('id_estado', [1, 2]); // IDs para estados programados/reprogramados
        } elseif ($order === 'todos') {
            // No se aplica ningún filtro, se muestran todos los turnos
        }
    }

    $turnos = $query->get();

    if (request()->ajax()) {
        return view('partials.lista_turnos', compact('turnos'))->render();
    }

    return view('nombre_de_tu_vista', compact('turnos'));
}

public function buscarPacienteSecretario(Request $request)
{
    $query = Turno::query();

    // Búsqueda por secretario, profesional, paciente y sucursal
    if ($request->has('search')) {
        $search = $request->input('search');

        $query->where(function($q) use ($search) {
            $q->whereHas('paciente.fichaMedica', function ($q2) use ($search) {
                $q2->where('ficha_medica.nombre', 'LIKE', "%{$search}%")
                   ->orWhere('ficha_medica.apellido', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('sala.sucursal', function ($q3) use ($search) {
                $q3->where('sucursal.nombre', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('secretario.usuario', function ($q4) use ($search) {
                $q4->where('name', 'LIKE', "%{$search}%")
                   ->orWhere('apellido', 'LIKE', "%{$search}%");
            })
            ->orWhereHas('profesional.persona', function ($q5) use ($search) {
                $q5->where('name', 'LIKE', "%{$search}%")
                   ->orWhere('apellido', 'LIKE', "%{$search}%");
            })
            ->orWhere('id_estado', 'LIKE', "%{$search}%");
        });
    }

    $turnos = $query->get();

    // Retorna la vista parcial con los resultados actualizados
    return view('partials.lista_turnos_secretario', compact('turnos'));
}



}

