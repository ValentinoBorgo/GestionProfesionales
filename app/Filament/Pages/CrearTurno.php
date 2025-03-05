<?php

namespace App\Filament\Pages;

use App\Models\Turno;
use App\Models\User;
use App\Models\Paciente;
use App\Models\TipoTurno;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Mail;
use App\Mail\Recordatorio;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Services\TurnoService;
use Illuminate\Validation\ValidationException;
use App\Filament\Widgets\CalendarWidget;

class CrearTurno extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static string $view = 'filament.pages.crear-turno';
    protected static ?string $navigationLabel = 'Crear Turno - Secretario';
    protected static ?string $title = 'Crear Turno';

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user->roles->pluck('nombre')->contains(fn ($role) => in_array($role, ['ROLE_SECRETARIO']));
    }

    public ?array $data = [
        'hora_fecha'     => null,
        'id_profesional' => null,
        'id_paciente'    => null,
        'id_tipo_turno'  => null,
        'id_estado'      => 1,
    ];

    protected TurnoService $turnoService;
    public function __construct()
    {
        $this->turnoService = app(TurnoService::class);
    }

    public function form(Form $form): Form
{
    // Si en la URL existe el parámetro, asignalo al estado del formulario
    if (request()->has('id_profesional')) {
        $this->data['id_profesional'] = request()->get('id_profesional');
    }

    return $form->schema([
        Select::make('id_profesional')
            ->label('Profesional')
            ->default($this->data['id_profesional'] ?? null)
            ->searchable()
            ->extraAttributes([
                // Al cambiar, se recarga la página actualizando el query string
                'x-on:change' => 'window.location = updateQueryStringParameter(window.location.href, "id_profesional", $event.target.value)'
            ])
            ->options(
                User::where('id_tipo', 3)
                    ->with('profesional')
                    ->get()
                    ->mapWithKeys(function ($user) {
                        $profesional = $user->profesional;
                        if ($profesional) {
                            return [$profesional->id => "{$user->name} {$user->apellido} - {$profesional->titulo}"];
                        }
                        return [];
                    })
            )
            ->required(),
        DateTimePicker::make('hora_fecha')
            ->label('Fecha y Hora')
            ->seconds(false)
            ->required(),
        Select::make('id_paciente')
            ->label('Paciente')
            ->searchable()
            ->live()
            ->options(Paciente::with('fichaMedica')->get()->pluck('fichaMedica.nombre', 'id'))
            ->required(),

        Select::make('id_tipo_turno')
            ->label('Tipo de Turno')
            ->searchable()
            ->options(TipoTurno::all()->pluck('nombre', 'id'))
            ->live()
            ->required(),

        Hidden::make('id_estado')
            ->default(1),
    ])
    ->statePath('data');
}

    
    public function submit()
    {
        try {
            $data = $this->form->getState();
            $secretario = Auth::user();
            $horaFecha = new \DateTime($data['hora_fecha']);

            $this->turnoService->validarFechaHora($horaFecha);
            $this->turnoService->disponibilidadProfesional($data['id_profesional'], $horaFecha);
            $this->turnoService->ausenciaProfesional($data['id_profesional'], $horaFecha);
            $this->turnoService->validarDisponibilidadProfesional($data['id_profesional'], $horaFecha);
            if (!$secretario->secretario) {
                throw ValidationException::withMessages([
                    'id_secretario' => 'El usuario actual no es un secretario.',
                ]);
            }
            $idSecretario = $secretario->secretario->id;

            $this->turnoService->validarHorarioSucursal($horaFecha, $secretario);
            $salaDisponible = $this->turnoService->getSalaDisponible($horaFecha, $data['id_tipo_turno'], $secretario);

            if (!$salaDisponible) {
                throw ValidationException::withMessages([
                    'hora_fecha' => 'No hay salas disponibles en este horario.',
                ]);
            }

            $turno = Turno::create([
                'hora_fecha'     => $data['hora_fecha'],
                'id_profesional' => $data['id_profesional'],
                'id_paciente'    => $data['id_paciente'],
                'id_tipo_turno'  => $data['id_tipo_turno'],
                'id_estado'      => 1,
                'id_secretario'  => $idSecretario,
                'id_sala'        => $salaDisponible->id,
            ]);

            $fichaMedica = $turno->paciente->fichaMedica ?? null;

            if ($fichaMedica && $fichaMedica->email) {
                Mail::to($fichaMedica->email)->send(
                    new Recordatorio("{$fichaMedica->nombre} {$fichaMedica->apellido}", $data['hora_fecha'])
                );
            }

            Notification::make()
                ->title('Turno creado correctamente')
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
