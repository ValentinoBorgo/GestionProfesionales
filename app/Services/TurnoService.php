<?php
namespace App\Services;

use App\Models\Turno;
use App\Models\TipoTurno;
use App\Models\Salas;
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

        $sucursalesUsuario = \DB::table('sucursal_usuario')
            ->where('id_usuario', $secretario->id_usuario)
            ->pluck('id_sucursal');

        if ($sucursalesUsuario->isEmpty()) {
            throw ValidationException::withMessages([
                'error' => 'El usuario no tiene sucursales asignadas.',
            ]);
        }

        $horarioValido = \DB::table('sucursal')
            ->whereIn('id', $sucursalesUsuario)
            ->where('horario_apertura', '<=', $horaTurno)
            ->where('horario_cierre', '>=', $horaTurno)
            ->exists();

        if (!$horarioValido) {
            throw ValidationException::withMessages([
                'hora_fecha' => 'El turno debe estar dentro del horario de apertura y cierre de las sucursales disponibles.',
            ]);
        }
    }

    public function disponibilidadProfesional($idProfesional, \DateTime $horaFecha, $idTurnoExcluido = null)
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
            throw ValidationException::withMessages([
                'hora_fecha' => 'El profesional ya tiene un turno asignado en el rango de tiempo indicado.',
            ]);
        }
    }

    public function ausenciaProfesional($idProfesional, \DateTime $horaFecha)
    {
        $ausente = \DB::table('ausencias')
            ->where('id_usuario', $idProfesional)
            ->where(function ($query) use ($horaFecha) {
                $query->where('fecha_inicio', '<=', $horaFecha)
                    ->where('fecha_fin', '>=', $horaFecha);
            })
            ->exists();

        if ($ausente) {
            throw ValidationException::withMessages([
                'hora_fecha' => 'El profesional no está disponible en la fecha y hora seleccionada debido a una ausencia.',
            ]);
        }
    }

    public function getSalaDisponible(\DateTime $horaFecha, $idTipoTurno, $secretario, $idTurnoExcluido = null)
{
    $tipoTurno = TipoTurno::find($idTipoTurno);
    $sucursalesUsuario = \DB::table('sucursal_usuario')
        ->where('id_usuario', $secretario->id_usuario)
        ->pluck('id_sucursal');

    if ($sucursalesUsuario->isEmpty()) {
        throw ValidationException::withMessages([
            'error' => 'El usuario no tiene sucursales asignadas.',
        ]);
    }

    $salasDisponibles = Salas::whereIn('id_sucursal', $sucursalesUsuario)
        ->where('tipo', $tipoTurno->codigo)
        ->get();

    $inicioRango = (clone $horaFecha)->modify('-1 hour');
    $finRango = (clone $horaFecha)->modify('+1 hour');

    $salasOcupadas = Turno::whereBetween('hora_fecha', [$inicioRango, $finRango])
        ->when($idTurnoExcluido, function ($query) use ($idTurnoExcluido) {
            $query->where('id', '<>', $idTurnoExcluido); // excluir el turno que se está modificando
        })
        ->whereHas('sala', function ($query) use ($tipoTurno, $sucursalesUsuario) {
            $query->where('tipo', $tipoTurno->codigo)
                ->whereIn('id_sucursal', $sucursalesUsuario);
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
}
