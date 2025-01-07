<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use App\Models\FichaMedica;
use App\Models\Paciente;
use Carbon\Carbon;

class DarAltaPaciente extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static string $view = 'filament.pages.dar-alta-paciente';
    protected static ?string $title = 'Dar de Alta Paciente';
    protected static ?string $navigationLabel = 'Dar de Alta Paciente - Secretario';
    
    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return ($user->roles->pluck('nombre')->contains(fn ($role) => in_array($role, ['ROLE_SECRETARIO']))) ? true : false;
    }

    public ?array $data = []; // Estado del formulario

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    TextInput::make('nombre')->label('Nombre')->required()->maxLength(255),
                    TextInput::make('apellido')->label('Apellido')->required()->maxLength(255),
                    TextInput::make('email')->label('Email')->email()->required(),
                    DatePicker::make('fecha_nac')->label('Fecha Nacimiento')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $edad = \Carbon\Carbon::parse($state)->age; // Calcula la edad
                            $set('edad', $edad); // Establece el valor de edad
                        }
                    }),
                    TextInput::make('edad')->label('Edad')->readonly(),
                    TextInput::make('ocupacion')->label('Ocupación')->required()->maxLength(255),
                    TextInput::make('domicilio')->label('Domicilio')->required()->maxLength(255),
                    TextInput::make('telefono')
                    ->label('Teléfono')
                    ->required()
                    ->maxLength(10) // Limita la entrada a 11 caracteres
                    ->reactive(),
                    TextInput::make('dni')->label('DNI')->required()->maxLength(20),
                    TextInput::make('localidad')->label('Localidad')->required()->maxLength(255),
                    TextInput::make('provincia')->label('Provincia')->required()->maxLength(255),
                    TextInput::make('persona_responsable')->label('Persona Responsable')->required()->maxLength(255),
                    TextInput::make('vinculo')->label('Vínculo')->required()->maxLength(255),
                    TextInput::make('telefono_persona_responsable')->label('Tel. Responsable')->required()->maxLength(20),
                ]),
            ])
            ->statePath('data') // Vincula el estado del formulario
            ->columns(2);
    }

    public function submit()
    {
        // Obtener el estado del formulario
        $data = $this->form->getState();

        // Crear la ficha médica
        $fichaMedica = FichaMedica::create([
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'email' => $data['email'],
            'edad' => $data['edad'],
            'fecha_nac' => $data['fecha_nac'],
            'ocupacion' => $data['ocupacion'],
            'domicilio' => $data['domicilio'],
            'telefono' => $data['telefono'],
            'dni' => $data['dni'],
            'localidad' => $data['localidad'],
            'provincia' => $data['provincia'],
            'persona_responsable' => $data['persona_responsable'],
            'vinculo' => $data['vinculo'],
            'telefono_persona_responsable' => $data['telefono_persona_responsable'],
        ]);

        // Crear el paciente
        Paciente::create([
            'fecha_alta' => Carbon::now(),
            'id_ficha_medica' => $fichaMedica->id,
        ]);

        Notification::make()
            ->title('Paciente dado de alta correctamente')
            ->success()
            ->send();
        $this->redirect(static::getUrl());
    }
}
