<?php

namespace App\Filament\Pages;

use App\Models\Turno;
use App\Models\User;
use App\Models\Paciente;
use App\Models\TipoTurno;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Mail;
use App\Mail\Recordatorio;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Services\TurnoService;

class CrearTurno extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static string $view = 'filament.pages.crear-turno';
    protected static ?string $navigationLabel = 'Crear Turno';
    protected static ?string $title = 'Crear Turno';

    public ?array $data = [];
// Inyectar el servicio TurnoService
protected TurnoService $turnoService;

public function __construct()
{
    // Inicializar la propiedad $turnoService con el servicio TurnoService
    $this->turnoService = app(TurnoService::class);
}
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                DateTimePicker::make('hora_fecha')
                    ->label('Fecha y Hora')
                    ->required(),

                    Select::make('id_profesional')
                    ->label('Profesional')
                    ->options(
                        User::where('id_tipo', 3) // Filtrar por tipo de profesional
                            ->with('profesional') // Cargar la relación 'profesional' para obtener el nombre y apellido
                            ->get()
                            ->mapWithKeys(function ($user) {
                                $profesional = $user->profesional; // Obtener el profesional relacionado
                                if ($profesional) {
                                    return [$profesional->id => "{$user->name} {$user->apellido}"]; // Concatenar nombre y apellido
                                }
                                return [];
                            })
                    )
                    ->required(),

                Select::make('id_paciente')
                    ->label('Paciente')
                    ->options(Paciente::with('fichaMedica')->get()->pluck('fichaMedica.nombre', 'id'))
                    ->required(),

                Select::make('id_tipo_turno')
                    ->label('Tipo de Turno')
                    ->options(TipoTurno::all()->pluck('nombre', 'id'))
                    ->required(),

                Hidden::make('id_estado')
                    ->default(1),
            ])
            ->statePath('data');
    }

    public function submit()
    {
        // Obtener el estado del formulario
        $data = $this->form->getState();
        
        // Obtener la fecha y hora del turno
        $horaFecha = new \DateTime($data['hora_fecha']);
        $secretario = Auth::user();

        // Validar fecha y hora
        try {
            $this->turnoService->validarFechaHora($horaFecha);
            $this->turnoService->disponibilidadProfesional($data['id_profesional'], $horaFecha, $data['id_profesional']);
            $this->turnoService->ausenciaProfesional($data['id_profesional'], $horaFecha, $data['id_profesional']);

            $secretario = Auth::user();
            if (!$secretario->secretario) {
            // Si el usuario no tiene un secretario asociado, muestra un error o maneja la situación.
                $this->addError('id_secretario', 'El usuario no tiene un secretario asociado.');
            return;
            }
            $idSecretario = $secretario->secretario->id;
            $this->turnoService->validarHorarioSucursal($horaFecha, $secretario);

            $salaDisponible = $this->turnoService->getSalaDisponible($horaFecha, $data['id_tipo_turno'], $secretarioEntidad);
            $idSala = $salaDisponible->id;
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->addError('hora_fecha', $e->getMessage());
            return;
        }

        // Crear el turno
        $turno = Turno::create([
            'hora_fecha' => $data['hora_fecha'],
            'id_profesional' => $data['id_profesional'],
            'id_paciente' => $data['id_paciente'],
            'id_tipo_turno' => $data['id_tipo_turno'],
            'id_estado' => 1,
            'id_secretario' => $idSecretario,
            'id_sala' => $idSala, // Aquí puedes ajustar el ID de la sala según tu lógica
        ]);

        // Enviar correo de recordatorio al paciente
        $paciente = $turno->paciente;
        $fichaMedica = $paciente->fichaMedica;

        Mail::to($fichaMedica->email)->send(new Recordatorio("{$fichaMedica->nombre} {$fichaMedica->apellido}", $data['hora_fecha']));

        // Notificar que el turno se creó correctamente
        Notification::make()
            ->title('Turno creado correctamente')
            ->success()
            ->send();

        // Redirigir a la página de creación de turnos
        $this->redirect(route('filament.pages.create-turno'), navigate: true);
    }
}
