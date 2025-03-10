<?php
namespace App\Services;

use App\Models\Turno;
use App\Models\EstadoTurno;
use App\Models\TipoTurno;
use App\Models\Salas;
use App\Models\Sucursal;
use App\Models\User;
use App\Models\Disponibilidad;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class DisponibilidadService
{
    public function verificarSalasPertenecenASucursales(array $sucursalIds, array $horarios)
    {
        $sucursales = Sucursal::whereIn('id', $sucursalIds)->get();
        if ($sucursales->isEmpty()) {
            return false;
        }
        foreach ($horarios as $horario) {
            $sala = Salas::find(intval($horario['idSala']) ?? null);
            if (!$sala || !$sucursales->contains('id', $sala->id_sucursal)) {
                return array_merge($horario, ['idSala' => $sala->id ?? null]);
            }
        }
        return true;
    }
    

    public function verificarHorarioPorProfesional(array $sucursalIds, array $horarios, $profesionalId = null)
    {
        $sucursalIds = array_map('intval', $sucursalIds);

        // foreach ($sucursalIds as $sucursalId) {
        // $sucursal = Sucursal::find($sucursalId);

        foreach ($horarios as $horario) {
            $salaId = intval($horario['idSala']) ?? null;
    
            // $sala = Salas::where('id', $salaId)
            //              ->where('id_sucursal', $sucursal->id)
            //              ->first();
    
            // if (!$sala) {
            //     return ['error' => 'La sala no pertenece a las sucursales proporcionadas.', 'horario' => $horario];
            // }
    
            $disponibilidad = Disponibilidad::whereIn('id_sucursal', $sucursalIds)
                ->where('id_sala', $salaId)
                ->where('dia', $horario['dias_semana'])
                ->where(function ($query) use ($horario) {
                    $query->where(function ($q) use ($horario) {
                        $q->where('horario_inicio', '<', $horario['hora_salida'])
                          ->where('horario_fin', '>', $horario['hora_entrada']);
                    });
                })
                ->get();
            //  if($horario['idSala'] === 7){
            //      dd($disponibilidad, $sucursalIds);
            // }
            if ($disponibilidad->count() > 0 && $disponibilidad->first()->id_usuario !== $profesionalId) {
                return ['disponibilidad' => $disponibilidad, 'horario' => $horario];
            }
        }

        return true;
    }


    public function verificarHorarioAperturaCierrePorSucursal(array $sucursalIds, array $horarios)
    {
        foreach ($sucursalIds as $sucursalId) {
        $sucursal = Sucursal::find($sucursalId);

        if (!$sucursal) {
            $resultados[$sucursalId] = [
                'error' => 'La sucursal con ID ' . $sucursalId . ' no fue encontrada.'
            ];
            continue;
        }
    
        // Definir el horario de apertura y cierre de la sucursal
        $horarioApertura = Carbon::createFromFormat('H:i:s', $sucursal->horario_apertura);
        $horarioCierre = Carbon::createFromFormat('H:i:s', $sucursal->horario_cierre);
    
        foreach ($horarios as $horario) {
            $horaEntrada = Carbon::createFromFormat('H:i:s', $horario['hora_entrada']);
            $horaSalida = Carbon::createFromFormat('H:i:s', $horario['hora_salida']);
    
            // Caso 1: Hora de entrada antes del horario de apertura
            if ($horaEntrada->lessThan($horarioApertura)) {
                return [
                    'entrada_antes_de_apertura' => 'El horario de entrada es antes de la apertura.',
                    'sucursal' => $sucursal,
                    'horario' => $horario
                ];
            }
    
            // Caso 2: Hora de salida después del horario de cierre
            if ($horaSalida->greaterThan($horarioCierre)) {
                return [
                    'salida_despues_de_cierre' => 'El horario de salida es después del cierre.',
                    'sucursal' => $sucursal,
                    'horario' => $horario
                ];
            }
    
            // Caso 3: Hora de entrada después del horario de cierre
            if ($horaEntrada->greaterThan($horarioCierre)) {
                return [
                    'entrada_despues_de_cierre' => 'El horario de entrada es después del horario de cierre.',
                    'sucursal' => $sucursal,
                    'horario' => $horario
                ];
            }
    
            // Caso 4: Hora de salida antes del horario de apertura
            if ($horaSalida->lessThan($horarioApertura)) {
                return [
                    'salida_antes_de_apertura' => 'El horario de salida es antes del horario de apertura.',
                    'sucursal' => $sucursal,
                    'horario' => $horario
                ];
            }
        }
        }
    
        return true;
    }
    

    public function verificarHorarioParaNoDividirMiPersonaEn2PorQueNoEsPosibleEsoXD($horarios)
    {
        $totalHorarios = count($horarios);
        for ($i = 0; $i < $totalHorarios; $i++) {
            for ($j = $i + 1; $j < $totalHorarios; $j++) {
                $horarioA = $horarios[$i];
                $horarioB = $horarios[$j];
                if ($horarioA['dias_semana'][0] === $horarioB['dias_semana'][0]) {
                    if (
                        $horarioA['hora_entrada'] < $horarioB['hora_salida'] &&
                        $horarioA['hora_salida'] > $horarioB['hora_entrada']
                    ) {
                        return [
                            'error' => 'El profesional tiene horarios que se superponen.',
                            'horario_conflictivo_1' => $horarioA,
                            'horario_conflictivo_2' => $horarioB
                        ];
                    }
                }
            }
        }
        return true;
    }
}
