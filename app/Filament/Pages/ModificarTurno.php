<?php

namespace App\Filament\Pages;

use App\Models\Turno;
use App\Models\User;
use App\Models\Paciente;
use App\Models\TipoTurno;
use App\Models\EstadoTurno;
use App\Services\TurnoService;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\Recordatorio;
use Illuminate\Validation\ValidationException;

class ModificarTurno extends Page
{
    protected static ?string $title = 'Modificar Turno';
    protected static ?string $navigationLabel = 'Modificar Turno';
    protected static string $view = 'filament.pages.modificar-turno';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public Turno $turno;
    protected TurnoService $turnoService;

    public $data = []; // Estado del formulario

    public function __construct()
    {
        $this->turnoService = app(TurnoService::class);
    }

    public function mount($id)
    {
        $this->turno = Turno::findOrFail($id);
        $data = $this->turno->toArray();
    
        // Si se envía 'id_profesional' en la query, se usa ese valor
        if (request()->has('id_profesional')) {
            $data['id_profesional'] = request()->get('id_profesional');
        }
        
        $this->form->fill($data);
    }
    

    public function form(Form $form): Form
    { 
        $CANCELADO_CLIENTE = EstadoTurno::where('codigo', EstadoTurno::CANCELADO_CLIENTE)->first()->id ?? null;
        $CANCELADO_PROFESIONAL = EstadoTurno::where('codigo', EstadoTurno::CANCELADO_PROFESIONAL)->first()->id ?? null;

        return $form->schema([
            DateTimePicker::make('hora_fecha')
            ->native(false)
            ->timezone('America/Argentina/Buenos_Aires')
            ->minutesStep(30)
            ->label('Fecha y Hora')
            ->seconds(false)
            ->reactive()
            ->required(function ($get) use ($CANCELADO_CLIENTE, $CANCELADO_PROFESIONAL) {
                return !in_array($get('id_estado'), [$CANCELADO_CLIENTE, $CANCELADO_PROFESIONAL]);
            }),
            Select::make('id_profesional')
    ->label('Profesional')
    ->searchable()
    ->extraAttributes([
        'x-on:change' => 'window.location = updateQueryStringParameter(window.location.href, "id_profesional", $event.target.value)'
    ])
    ->options(
        User::where('id_tipo', 3)
            ->with('profesional')
            ->get()
            ->mapWithKeys(function ($user) {
                return [
                    $user->profesional->id => $user->name . ' ' . $user->apellido,
                ];
            })
    )
    ->default(request()->has('id_profesional') ? request()->get('id_profesional') : $this->turno->id_profesional)
    ->required(),

            Select::make('id_paciente')
                ->label('Paciente')
                ->options(Paciente::with('fichaMedica')->get()->pluck('fichaMedica.nombre', 'id'))
                ->required(),
            Select::make('id_tipo_turno')
                ->label('Tipo de Turno')
                ->options(TipoTurno::all()->pluck('nombre', 'id'))
                ->required(),
            Select::make('id_estado')
                ->label('Estado')
                ->options(EstadoTurno::all()->pluck('nombre', 'id'))
                ->reactive() // Detecta cambios
                ->required(),
        ])->statePath('data');
    }

    public function submit()
    {
        try {
            $horaFecha = new \DateTime($this->data['hora_fecha']);

            $secretario = $this->turnoService->getSecretario(auth()->user());
            $this->turnoService->validarFechaHora($horaFecha);
            $this->turnoService->validarHorarioSucursal($horaFecha, $secretario);
            $this->turnoService->disponibilidadProfesional($this->data['id_profesional'], $horaFecha, $this->turno->id, $this->data['id_estado']);
            $this->turnoService->ausenciaProfesional($this->data['id_profesional'], $horaFecha);
            $this->turnoService->validarDisponibilidadProfesional($this->data['id_profesional'], $horaFecha);
            $sala = $this->turnoService->getSalaDisponible($horaFecha, $this->data['id_tipo_turno'], $secretario, $this->turno->id, $this->data['id_estado']);

            $this->turno->update([
                'hora_fecha' => $horaFecha,
                'id_profesional' => $this->data['id_profesional'],
                'id_paciente' => $this->data['id_paciente'],
                'id_tipo_turno' => $this->data['id_tipo_turno'],
                'id_estado' => $this->data['id_estado'],
                'id_sala' => $sala ? $sala->id : null,
            ]);
            
            $paciente = $this->turno->paciente;
            $fichaMedica = $paciente->fichaMedica;
            Mail::to($fichaMedica->email)->send(new Recordatorio("{$fichaMedica->nombre} {$fichaMedica->apellido}", $this->data['hora_fecha']));

            Notification::make()
                ->title('Turno actualizado correctamente')
                ->success()
                ->send();

            $this->redirect(route('filament.ver-turnos'));
        } catch (ValidationException $e) {
            $this->addError('error', $e->getMessage());
        } catch (\Exception $e) {
            $this->addError('general', 'Error inesperado: ' . $e->getMessage());
        }
    }
}
