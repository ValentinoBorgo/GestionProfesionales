<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use App\Models\FichaMedica;

class EditarFichaMedica extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static string $view = 'filament.pages.editar-ficha-medica';
    //HACES QUE SE OCULTE DEL NAVBAR IZQUIERDO
    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public ?array $data = [];
    public FichaMedica $ficha;

    public function mount($id)
    {
        $this->ficha = FichaMedica::findOrFail($id);
        $this->form->fill($this->ficha->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Editar Ficha Médica')
                    ->schema([
                        TextInput::make('nombre')->label('Nombre')->required(),
                        TextInput::make('apellido')->label('Apellido')->required(),
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
                        TextInput::make('ocupacion')->label('Ocupación')->required(),
                        TextInput::make('domicilio')->label('Domicilio')->required(),
                        TextInput::make('telefono')->label('Teléfono')->required(),
                        TextInput::make('dni')->label('DNI')->required(),
                        TextInput::make('localidad')->label('Localidad')->required(),
                        TextInput::make('provincia')->label('Provincia')->required(),
                        TextInput::make('persona_responsable')->label('Persona Responsable')->required(),
                        TextInput::make('vinculo')->label('Vínculo')->required(),
                        TextInput::make('telefono_persona_responsable')->label('Teléfono del Responsable')->required(),
                    ]),
            ])
            ->statePath('data'); // Almacena el estado en $data
    }

    public function save()
    {
        $this->validate();

        $this->ficha->update($this->form->getState());

        session()->flash('success', 'Ficha médica actualizada correctamente.');
        return redirect()->route('pacientes.index');
    }
}
