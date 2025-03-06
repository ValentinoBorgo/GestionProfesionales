<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\TipoPersona;
use App\Models\Profesional;
use App\Models\Salas;
use App\Models\Sucursal;
use App\Models\User;
use App\Models\Disponibilidad;
use App\Models\Secretario;
use App\Services\DisponibilidadService;
use Illuminate\Validation\ValidationException;
use Filament\Notifications\Notification;


class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public array $horariosGuardados = [];

    protected DisponibilidadService $disponibilidadService;

    protected $listeners = ['guardarHorario' => 'guardarHorario'];

    public function __construct()
    {
        $this->disponibilidadService = app(DisponibilidadService::class);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {

        if (!empty($this->roles)) {
            $this->record->roles()->sync($this->roles);
        }

        if (!empty($this->sucursales)) {
            $this->record->sucursales()->sync($this->sucursales);
        }

        $tipoPersonaProfesional = TipoPersona::where('tipo', 'PROFESIONAL')->first();
        if ($tipoPersonaProfesional && intval($this->record->id_tipo) === intval($tipoPersonaProfesional->id)) {
            $titulo = $this->data['titulo'] ?? 'Sin especificar';
            Profesional::create([
                'id_persona' => $this->record->id,
                'titulo' => $titulo,
            ]);
            //
            $sucursal = Sucursal::find(intval($this->data['sucursales'][0]));
            foreach ($this->horariosGuardados as $horario) {
                $sala = Salas::find(intval($horario['idSala']) ?? null);
                Disponibilidad::create([
                    'id_sucursal' => $sucursal->id,
                    'id_sala' => $sala->id,
                    'id_usuario' => $this->record->id,
                    'dia' => $horario['dias_semana'],
                    'horario_inicio' => $horario['hora_entrada'],
                    'horario_fin' => $horario['hora_salida'],
                ]);
            }
            //
        }
        $tipoPersonaSecreatrio = TipoPersona::where('tipo', 'SECRETARIO')->first();
        if ($tipoPersonaSecreatrio && intval($this->record->id_tipo) === intval($tipoPersonaSecreatrio->id)) {
            $titulo = $this->data['titulo'] ?? 'Sin especificar';
            Secretario::create([
                'id_usuario' => $this->record->id,
            ]);
        }
    }

    protected function beforeCreate(): void
    {
        $isTypeProfesional = TipoPersona::find($this->data['id_tipo']);

        if($isTypeProfesional->tipo === 'PROFESIONAL'){
            if (empty($this->horariosGuardados)) {
                throw ValidationException::withMessages([
                    'horarios_guardados' => 
                        "⚠️ <strong>Error:</strong> No se puede crear el usuario.<br><br>" .
                        "👨‍⚕️ <strong>Tipo:</strong> PROFESIONAL<br>" .
                        "⏰ <strong>Requisito:</strong> Debes agregar al menos un horario antes de crear el usuario.<br><br>" .
                        "❌ <em>Por favor, selecciona un horario válido antes de continuar.</em>" . "<br><br>"
                ]);
            }else {
                $isSalaRelationalToSucursal = $this->disponibilidadService->verificarSalasPertenecenASucursales(
                    $this->data['sucursales'] ?? null, 
                    $this->horariosGuardados
                );
                $isHorarioIsNotAvailable = $this->disponibilidadService->verificarHorarioPorProfesional(
                    $this->data['sucursales'] ?? null, 
                    $this->horariosGuardados
                );
                $isHorarioIsNotAvailableBySucursalScheme = $this->disponibilidadService->verificarHorarioAperturaCierrePorSucursal(
                    $this->data['sucursales'] ?? null, 
                    $this->horariosGuardados
                );
                $isHorarioIsPosibleToDividedAPerson = $this->disponibilidadService->verificarHorarioParaNoDividirMiPersonaEn2PorQueNoEsPosibleEsoXD(
                    $this->horariosGuardados
                );
                //La sala seleccionada no pertenece a la sucursal seleccionada, nunca va a entrar por qe ya lo filtro pero buneo XD
                if($isSalaRelationalToSucursal !== true){
                    $sala = Salas::find(intval($isSalaRelationalToSucursal['idSala']) ?? null);
                    throw ValidationException::withMessages([
                        'horarios_guardados' => 
                            "⚠️ <strong>Error de selección:</strong><br>" .
                            "🛏️ <strong>Sala:</strong> <span style='color: red;'>" . ($sala->nombre ?? 'desconocida') . "</span><br>" .
                            "🏥 <strong>Estado:</strong> No pertenece a la sucursal seleccionada.<br><br>" .
                            "❌ <em>Por favor, seleccione una sala válida dentro de la sucursal correspondiente.</em>". "<br><br>"
                    ]);
                }
                //Una sala de una sucursal se encuentra ya ocupada por otro profesional
                if($isHorarioIsNotAvailable !== true){
                    $sala = Salas::find(intval($isHorarioIsNotAvailable['horario']['idSala']) ?? null);
                    $profesionalOcupando = User::find($isHorarioIsNotAvailable['disponibilidad'][0]->id_usuario ?? null);
                    throw ValidationException::withMessages([
                        'horarios_guardados' => 
                            "⏰ <strong>Conflicto de horario detectado:</strong><br>" .
                            "🛏️ <strong>Sala:</strong> <span style='color: red;'>" . $sala->nombre . "</span><br>" .
                            "🕒 <strong>Horario:</strong> " . $isHorarioIsNotAvailable['horario']['hora_entrada'] . 
                            " - " . $isHorarioIsNotAvailable['horario']['hora_salida'] . "<br>" .
                            "👨‍⚕️ <strong>Ocupado por:</strong> " . $profesionalOcupando->name . " " . $profesionalOcupando->apellido . "<br><br>" .
                            "⚠️ <em>Este horario ya está asignado a otro profesional. Por favor, seleccione un horario diferente.</em>". "<br><br>"
                    ]);
                }
                //Horarios no disponibles por apertura cierre de sucursal
                if ($isHorarioIsNotAvailableBySucursalScheme !== true) {
                    $sala = Salas::find(intval($isHorarioIsNotAvailableBySucursalScheme['horario']['idSala']) ?? null);
                    $horario = $isHorarioIsNotAvailableBySucursalScheme['horario'];
                    $sucursal = $isHorarioIsNotAvailableBySucursalScheme['sucursal'];
                
                    // Caso 1: Hora de entrada antes del horario de apertura
                    if (isset($isHorarioIsNotAvailableBySucursalScheme['entrada_antes_de_apertura'])) {
                        throw ValidationException::withMessages([
                            'horarios_guardados' => 
                                "⏰ <strong>Conflicto de horario detectado:</strong><br>" .
                                "🔹 <strong>Horario de apertura:</strong> <span style='color: red;'>" . $sucursal->horario_apertura . "</span><br>" .
                                "🏥 <strong>Sucursal:</strong> " . $sucursal->nombre . "<br>" .
                                "🛏️ <strong>Sala:</strong> " . $sala->nombre . "<br>" .
                                "❌ <strong>Hora de entrada seleccionada:</strong> <span style='color: red;'>" . $horario['hora_entrada'] . "</span><br><br>" .
                                "⚠️ <em>El horario de entrada seleccionado es anterior al horario de apertura de la sucursal.</em><br><br>"
                        ]);
                    }
                
                    // Caso 2: Hora de salida después del horario de cierre
                    if (isset($isHorarioIsNotAvailableBySucursalScheme['salida_despues_de_cierre'])) {
                        throw ValidationException::withMessages([
                            'horarios_guardados' => 
                                "⏰ <strong>Conflicto de horario detectado:</strong><br>" .
                                "🔹 <strong>Horario de cierre:</strong> <span style='color: red;'>" . $sucursal->horario_cierre . "</span><br>" .
                                "🏥 <strong>Sucursal:</strong> " . $sucursal->nombre . "<br>" .
                                "🛏️ <strong>Sala:</strong> " . $sala->nombre . "<br>" .
                                "❌ <strong>Hora de salida seleccionada:</strong> <span style='color: red;'>" . $horario['hora_salida'] . "</span><br><br>" .
                                "⚠️ <em>El horario de salida seleccionado es posterior al horario de cierre de la sucursal.</em><br><br>"
                        ]);
                    }
                
                    // Caso 3: Hora de entrada después del horario de cierre
                    if (isset($isHorarioIsNotAvailableBySucursalScheme['entrada_despues_de_cierre'])) {
                        throw ValidationException::withMessages([
                            'horarios_guardados' => 
                                "⏰ <strong>Conflicto de horario detectado:</strong><br>" .
                                "🔹 <strong>Horario de cierre:</strong> <span style='color: red;'>" . $sucursal->horario_cierre . "</span><br>" .
                                "🏥 <strong>Sucursal:</strong> " . $sucursal->nombre . "<br>" .
                                "🛏️ <strong>Sala:</strong> " . $sala->nombre . "<br>" .
                                "❌ <strong>Hora de entrada seleccionada:</strong> <span style='color: red;'>" . $horario['hora_entrada'] . "</span><br><br>" .
                                "⚠️ <em>El horario de entrada seleccionado es posterior al horario de cierre de la sucursal.</em><br><br>"
                        ]);
                    }
                
                    // Caso 4: Hora de salida antes del horario de apertura
                    if (isset($isHorarioIsNotAvailableBySucursalScheme['salida_antes_de_apertura'])) {
                        throw ValidationException::withMessages([
                            'horarios_guardados' => 
                                "⏰ <strong>Conflicto de horario detectado:</strong><br>" .
                                "🔹 <strong>Horario de apertura:</strong> <span style='color: red;'>" . $sucursal->horario_apertura . "</span><br>" .
                                "🏥 <strong>Sucursal:</strong> " . $sucursal->nombre . "<br>" .
                                "🛏️ <strong>Sala:</strong> " . $sala->nombre . "<br>" .
                                "❌ <strong>Hora de salida seleccionada:</strong> <span style='color: red;'>" . $horario['hora_salida'] . "</span><br><br>" .
                                "⚠️ <em>El horario de salida seleccionado es anterior al horario de apertura de la sucursal.</em><br><br>"
                        ]);
                    }
                }
                //No es posible dividir una persona en 2
                if ($isHorarioIsPosibleToDividedAPerson !== true) {
                    $diasSemana1 = $isHorarioIsPosibleToDividedAPerson['horario_conflictivo_1']['dias_semana'];
                    $diasSemana2 = $isHorarioIsPosibleToDividedAPerson['horario_conflictivo_2']['dias_semana'];
                    if (!is_array($diasSemana1)) {
                        $diasSemana1 = explode(',', $diasSemana1);
                    }
                    if (!is_array($diasSemana2)) {
                        $diasSemana2 = explode(',', $diasSemana2);
                    }
                    throw ValidationException::withMessages([
                        'horarios_guardados' => 
                            "El profesional tiene horarios que se superponen.<br>".
                            "<strong>Primer horario:</strong><br>".
                            " - Día: " . implode(', ', $diasSemana1) . "<br>".
                            " - Sala: " . $isHorarioIsPosibleToDividedAPerson['horario_conflictivo_1']['sala'] . "<br>".
                            " - Entrada: " . $isHorarioIsPosibleToDividedAPerson['horario_conflictivo_1']['hora_entrada'] . "<br>".
                            " - Salida: " . $isHorarioIsPosibleToDividedAPerson['horario_conflictivo_1']['hora_salida'] . "<br><br>".
                            "<strong>Segundo horario:</strong><br>".
                            " - Día: " . implode(', ', $diasSemana2) . "<br>".
                            " - Sala: " . $isHorarioIsPosibleToDividedAPerson['horario_conflictivo_2']['sala'] . "<br>".
                            " - Entrada: " . $isHorarioIsPosibleToDividedAPerson['horario_conflictivo_2']['hora_entrada'] . "<br>".
                            " - Salida: " . $isHorarioIsPosibleToDividedAPerson['horario_conflictivo_2']['hora_salida'] . "<br><br>"
                    ]);
                }
            }
        }
    }


    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (request()->routeIs('filament.resources.user-resource.pages.create-user')) {
            return $data;
        }

        return parent::mutateFormDataBeforeSave($data);
    }

    public function guardarHorario()
    {
        $datos = $this->form->getRawState();

        $sala = Salas::find(intval($datos['sala']) ?? null);
    
        $nuevoHorario = [
            'dias_semana' => $datos['dias_semana'] ?? [],
            'hora_entrada' => $datos['hora_entrada'] ?? null,
            'hora_salida' => $datos['hora_salida'] ?? null,
            'sala' => $sala->nombre ?? null,
            'idSala' => $datos['sala'] ?? null,
        ];
    
        $this->horariosGuardados[] = $nuevoHorario;
    
        $this->form->fill(array_merge($datos, [
            'horarios_guardados' => $this->horariosGuardados,
            'dias_semana' => null, 
            'hora_entrada' => null,
            'hora_salida' => null,
            'sala' => null,
        ]));
    }

    public function eliminarHorario($index)
    {
        $datos = $this->form->getRawState();
        
        if (isset($this->horariosGuardados[$index])) {
            unset($this->horariosGuardados[$index]);
            $this->horariosGuardados = array_values($this->horariosGuardados);
        }
    
        $this->form->fill(array_merge($datos,[
            'horarios_guardados' => $this->horariosGuardados
        ]));

        Notification::make()
        ->title('Horario eliminado exitosamente.')
        ->success()
        ->send();
    }
}
