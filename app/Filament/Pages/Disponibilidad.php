<?php

namespace App\Filament\Pages;

use App\Models\Sucursal;
use App\Models\Salas;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Actions\Action;
use Filament\Notifications\Notification;

class Disponibilidad extends Page implements HasForms
{
    use InteractsWithForms;
    public $timestamps = false;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.disponibilidad';
    public ?array $data = [];

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user->roles->pluck('nombre')->contains(fn ($role) => in_array($role, ['ROLE_PROFESIONAL'])) ? true : false;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
{
    return $form
        ->schema([
            Section::make('Disponibilidad')
                ->schema([
                    Select::make('id_sucursal')
                        ->label('Sucursal')
                        ->options(Sucursal::all()->pluck('nombre', 'id'))
                        ->required()
                        ->reactive(),

                    Select::make('id_sala')
                        ->label('Sala')
                        ->options(function (callable $get) {
                            $sucursalId = $get('id_sucursal');
                            if ($sucursalId) {
                                return Salas::where('id_sucursal', $sucursalId)->pluck('nombre', 'id');
                            }
                            return [];
                        })
                        ->required(),

                    // Agrupamos los días y sus horarios en columnas
                    Section::make('Horarios')
                        ->schema([
                            Checkbox::make('lunes')->label('Lunes'),
                            TimePicker::make('lunes_inicio')->label('Horario inicio Lunes')->seconds(false),
                            TimePicker::make('lunes_fin')->label('Horario fin Lunes')->seconds(false),

                            Checkbox::make('martes')->label('Martes'),
                            TimePicker::make('martes_inicio')->label('Horario inicio Martes')->seconds(false),
                            TimePicker::make('martes_fin')->label('Horario fin Martes')->seconds(false),

                            Checkbox::make('miercoles')->label('Miércoles'),
                            TimePicker::make('miercoles_inicio')->label('Horario inicio Miércoles')->seconds(false),
                            TimePicker::make('miercoles_fin')->label('Horario fin Miércoles')->seconds(false),

                            Checkbox::make('jueves')->label('Jueves'),
                            TimePicker::make('jueves_inicio')->label('Horario inicio Jueves')->seconds(false),
                            TimePicker::make('jueves_fin')->label('Horario fin Jueves')->seconds(false),

                            Checkbox::make('viernes')->label('Viernes'),
                            TimePicker::make('viernes_inicio')->label('Horario inicio Viernes')->seconds(false),
                            TimePicker::make('viernes_fin')->label('Horario fin Viernes')->seconds(false),

                            Checkbox::make('sabado')->label('Sábado'),
                            TimePicker::make('sabado_inicio')->label('Horario inicio Sábado')->seconds(false),
                            TimePicker::make('sabado_fin')->label('Horario fin Sábado')->seconds(false),

                            Checkbox::make('domingo')->label('Domingo'),
                            TimePicker::make('domingo_inicio')->label('Horario inicio Domingo')->seconds(false),
                            TimePicker::make('domingo_fin')->label('Horario fin Domingo')->seconds(false),
                        ])  
                        ->columns(3),
                ])
                ->columns(2),

            // Botón de guardar
            Section::make()
                ->schema([
                    \Filament\Forms\Components\Actions::make([
                        Action::make('save')
                            ->label('Guardar')
                            ->submit('save')
                            ->action(function () {
                                $this->save();
                            }),
                    ]),
                ]),
        ])
        ->statePath('data')
        ->model(\App\Models\Disponibilidad::class);
}

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Guardar')
                ->submit('save')
                ->action(function () {
                    $data = $this->form->getState();
                    // Aquí puedes procesar y guardar los datos en la base de datos
                    Notification::make()
                        ->title('Disponibilidad guardada')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function save(): void
{
    $data = $this->form->getState();
    $id_profesional = auth()->user()->id;

    foreach (['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'] as $dia) {
        if ($data[$dia]) {
            \App\Models\Disponibilidad::create([
                'id_sucursal' => $data['id_sucursal'],
                'id_sala' => $data['id_sala'],
                'id_profesional' => $id_profesional,
                'dia' => $dia,
                'horario_inicio' => $data[$dia . '_inicio'],
                'horario_fin' => $data[$dia . '_fin'],
            ]);
        }
    }

    Notification::make()
        ->title('Disponibilidad guardada')
        ->success()
        ->send();
}
}