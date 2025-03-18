<?php
namespace App\Services;

use App\Models\Turno;
use App\Models\EstadoTurno;
use App\Models\TipoTurno;
use App\Models\Salas;
use App\Models\Sucursal;
use App\Models\Ausencias;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TurnoService
{
    public function validarTurno(Request $request)
    {
        return $request->validate([
            'hora_fecha' => 'required|date',
            'id_profesional' => 'required|exists:profesional,id',
            'id_paciente' => 'required|exists:paciente,id',
            'id_tipo_turno' => 'required|exists:tipo_turnos,id',
            'id_estado' => 'required|exists:estado_turnos,id',
        ]);
    }

    public function validarFechaHora(\DateTime $horaFecha)
    {
        $horaActual = new \DateTime(now());
        if ($horaFecha < $horaActual) {
            throw ValidationException::withMessages([
                'hora_fecha' => 'No se puede agendar un turno con una fecha y hora anterior a la actual.',
            ]);
        }
    }

    public function validarHorarioSucursal(\DateTime $horaFecha, $secretario)
    {
        $horaTurno = $horaFecha->format('H:i:s');
        //aca se usa el sucusales comun de secretario, //AL CREAR UN TURNO CON SECRETARIOS VA SUCUSARLES
        $sucursalesUsuario = collect($secretario->sucursales);

        // Verificar si está vacío y utilizar sucursalesGenerales
        if ($sucursalesUsuario->count() === 0) {
            $sucursalesUsuario = collect($secretario->sucursalesGenerales ?? []);
        }

        if ($sucursalesUsuario->count() === 0) {
            throw ValidationException::withMessages([
                'error' => 'El usuario no tiene sucursales asignadas.',
            ]);
        }
        $bool = false;
        $horariosFiltrados = $sucursalesUsuario->filter(function ($sucursal) use ($horaTurno, &$bool) {
            $resultado = $sucursal->horario_apertura <= $horaTurno && $sucursal->horario_cierre >= $horaTurno;
            if ($resultado) {
                $bool = $resultado;
            }
            return $resultado;
        });
        
        if ($bool === false) {
            throw ValidationException::withMessages([
                'hora_fecha' => 'El turno debe estar dentro del horario de apertura y cierre de las sucursales disponibles.',
            ]);
        }
    }

    public function disponibilidadProfesional($idProfesional, \DateTime $horaFecha, $idTurnoExcluido = null)
    {
        // Para un turno de 30 minutos, consideramos el intervalo desde el inicio hasta 30 minutos después.
        $inicioRango = (clone $horaFecha);
        $finRango = (clone $horaFecha)->modify('+30 minutes');
    
        $query = Turno::where('id_profesional', $idProfesional)
            ->where('hora_fecha', '>=', $inicioRango)
            ->where('hora_fecha', '<', $finRango)
            ->whereHas('estado', function ($query) {
                $query->whereIn('codigo', ['ASIGNADO', 'REPROGRAMADO']);
            });
    
        if ($idTurnoExcluido) {
            $query->where('id', '<>', $idTurnoExcluido);
        }
    
        if ($query->exists()) {
            throw ValidationException::withMessages([
                'hora_fecha' => 'El profesional ya tiene un turno asignado en el rango de tiempo indicado.',
            ]);
        }
    }
    


    public function ausenciaProfesional($idProfesional, \DateTime $horaFecha)
    {


$horaFecha = Carbon::parse($horaFecha) // Parsea la fecha
    ->format('Y-m-d H:i:s');          // Formato compatible con MySQL

// Obtener el ID de la persona asociada al profesional
$idPersona = DB::table('profesional')
    ->where('id', intval($idProfesional))
    ->value('id_persona'); // Devuelve directamente el valor en lugar de una colección

// Verificar que se encontró un id_persona antes de continuar
if (!$idPersona) {
    throw new \Exception("No se encontró el id_persona para el id_profesional: $idProfesional");
}

// Consultar ausencias
$ausente = DB::table('ausencias')
    ->where('id_usuario', intval($idPersona))
    ->where(function ($query) use ($horaFecha) {
        $query->where('fecha_inicio', '<=', $horaFecha)
              ->where('fecha_fin', '>=', $horaFecha);
    })
    ->get();


 // dd($ausente,$horaFecha, $idProfesional);
       

        if ($ausente->isNotEmpty()) {
            throw ValidationException::withMessages([
                'hora_fecha' => 'El profesional no está disponible en la fecha y hora seleccionada debido a una ausencia.',
            ]);
        }
    }

    public function getSalaDisponible(\DateTime $horaFecha, $idTipoTurno, $secretario, $idTurnoExcluido = null)
{
    $tipoTurno = TipoTurno::find($idTipoTurno);
    $sucursalesUsuario = collect($secretario->sucursales);

    if ($sucursalesUsuario->count() === 0) {
        $sucursalesUsuario = collect($secretario->sucursalesGenerales ?? []);
    }

    if ($sucursalesUsuario->count() === 0) {
        throw ValidationException::withMessages([
            'error' => 'El usuario no tiene sucursales asignadas.',
        ]);
    }

    $sucursalIds = $sucursalesUsuario->pluck('id')->toArray();
    
    $salasDisponibles = Salas::whereIn('id_sucursal', $sucursalIds)
        ->where('tipo', $tipoTurno->codigo)
        ->get();

    // Rango de 30 minutos para el turno
    $inicioRango = (clone $horaFecha);
    $finRango = (clone $horaFecha)->modify('+30 minutes');

    $salasOcupadas = Turno::where('hora_fecha', '>=', $inicioRango)
    ->where('hora_fecha', '<', $finRango)
    ->when($idTurnoExcluido, function ($query) use ($idTurnoExcluido) {
        $query->where('id', '<>', $idTurnoExcluido);
    })
    ->whereHas('sala', function ($query) use ($tipoTurno, $sucursalIds) {
        $query->where('tipo', $tipoTurno->codigo)
            ->whereIn('id_sucursal', $sucursalIds);
    })
    ->whereHas('estado', function ($query) {
        $query->whereIn('codigo', ['ASIGNADO', 'REPROGRAMADO']);
    })
    ->count();

        
    if ($salasOcupadas >= $salasDisponibles->count()) {
        throw ValidationException::withMessages([
            'hora_fecha' => 'No hay salas disponibles en este rango de tiempo.',
        ]);
    }

    return $salasDisponibles->first();
}


    // get del secretario q esta logueado
 public function getSecretario($user)
 {
     $secretario = $user->secretario;
     if (!$secretario) {
         throw \Illuminate\Validation\ValidationException::withMessages([
             'error' => 'El usuario logueado no está registrado como secretario.',
         ]);
     }
     return $secretario;
 }

 public function validarDisponibilidadProfesional($idProfesional, \DateTime $horaFecha)
 {
     $profesional = \App\Models\Profesional::find($idProfesional);
     if (!$profesional) {
         throw ValidationException::withMessages([
             'id_profesional' => 'Profesional no encontrado.',
         ]);
     }
     $idPersona = $profesional->id_persona;
     
     // Convertir el día a minúsculas
     $diaEnglish = $horaFecha->format('l'); 
     $dias = [
         'Monday'    => 'lunes',
         'Tuesday'   => 'martes',
         'Wednesday' => 'miercoles',
         'Thursday'  => 'jueves',
         'Friday'    => 'viernes',
         'Saturday'  => 'sabado',
         'Sunday'    => 'domingo',
     ];
     $dia = strtolower($dias[$diaEnglish]);
     
     // Para un turno de 30 minutos, calculamos el inicio y fin del turno
     $horaInicio = $horaFecha->format('H:i:s');
     $horaFin = (clone $horaFecha)->modify('+30 minutes')->format('H:i:s');
 
     $disponibilidad = \App\Models\Disponibilidad::where('id_usuario', $idPersona)
         ->where('dia', $dia)
         ->where('horario_inicio', '<=', $horaInicio)
         ->where('horario_fin', '>=', $horaFin)
         ->first();
 
     if (!$disponibilidad) {
         throw ValidationException::withMessages([
             'hora_fecha' => 'El profesional no tiene disponibilidad en la fecha y hora seleccionada.',
         ]);
     }
 }
 

}
