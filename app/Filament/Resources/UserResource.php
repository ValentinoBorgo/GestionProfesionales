<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Models\Rol;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\SucursalResource;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Hidden;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Usuarios';

    protected static ?string $pluralLabel = 'Usuarios';

   protected static ?string $label = 'Usuario';

   public static function shouldRegisterNavigation(): bool
   {
       $user = auth()->user();
       return $user->roles->pluck('nombre')->contains(fn ($role) => in_array($role, ['ROLE_ADMIN']));
   }

    public static function form(Form $form): Form
    {
        return $form    
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nombre')
                    ->maxLength(255),
                    Forms\Components\TextInput::make('email')
                    ->email()  // Validación para formato de email
                    ->required()  // Obligatorio
                    ->maxLength(255)
                    ->label('Correo Electrónico')
                    ->unique(ignoreRecord: true) // Valida que el correo sea único, ignorando el registro actual al editar
                    ->helperText('Por favor, utiliza un correo único.')
                    ->validationAttribute('correo electrónico'), // Cambia el nombre del campo en los mensajes de error                
                Forms\Components\TextInput::make('apellido')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telefono')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('fecha_nac')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $edad = \Carbon\Carbon::parse($state)->age; // Calcula la edad
                            $set('edad', $edad); // Establece el valor de edad
                        }
                    }),
                Forms\Components\TextInput::make('edad')
                    ->readonly()
                    ->maxLength(255),
                Forms\Components\TextInput::make('domicilio')
                    ->maxLength(255),
                Forms\Components\Select::make('id_tipo')
                    ->label('Tipo de Persona')
                    ->relationship('tipoPersona', 'tipo')
                    ->required()
                    ->preload()
                    ->placeholder('Seleccione un tipo')
                    ->options(function () {
                        return \App\Models\TipoPersona::where('tipo', '!=', 'PACIENTE')
                            ->pluck('tipo', 'id');
                    })
                    ->reactive(),
                Forms\Components\Select::make('titulo')
                    ->label('Título')
                    ->options([
                        'Cardiologo' => 'Cardiologo',
                        'Cirujano' => 'Cirujano',
                        'Clínico' => 'Clínico',
                        'Especialista' => 'Especialista',
                        'Estomatologo' => 'Estomatologo',
                        'Otorrinolaringologo' => 'Otorrinolaringologo',
                        'Hematologo' => 'Hematologo',
                        'Ginecologo' => 'Ginecologo',
                        'Neurologo' => 'Neurologo',
                        'Oftalmologo' => 'Oftalmologo',
                        'Dermatologo' => 'Dermatologo',
                        'Deportologo' => 'Deportologo',
                        'Pediatra' => 'Pediatra',
                        'Psiquiatra' => 'Psiquiatra',
                        'Urologo' => 'Urologo',
                    ])
                    ->required()
                    ->placeholder('Seleccione un tipo')
                    ->visible(function (callable $get) {
                        $tipoSeleccionado = $get('id_tipo');
                        
                        if (!$tipoSeleccionado) {
                            return false;
                        }
                        $tipoPersona = \App\Models\TipoPersona::find($tipoSeleccionado);
                        return $tipoPersona && $tipoPersona->tipo === 'PROFESIONAL';
                    })
                    ->required(function (callable $get) {
                        $tipoSeleccionado = $get('id_tipo');
                        if (!$tipoSeleccionado) {
                            return false;
                        }
                        $tipoPersona = \App\Models\TipoPersona::find($tipoSeleccionado);
                        return $tipoPersona && $tipoPersona->tipo === 'PROFESIONAL';
                    })
                    ->afterStateHydrated(function ($state, callable $set, $get, $livewire) {
                        // Sólo intentamos cargar el título si estamos en la página de edición y existe el registro
                        if ($livewire instanceof \App\Filament\Resources\UserResource\Pages\EditUser && $livewire->record) {
                            // Verificar el valor de la relación profesional
                            $profesional = $livewire->record->profesional;
                            // Puedes usar \Log::info() para debug si lo necesitas
                            // \Log::info('Debug Profesional', ['profesional' => $profesional]);

                            if ($profesional) {
                                $set('titulo', $profesional->titulo);
                            }
                        }
                    }),
                    Forms\Components\Select::make('sucursales')
                    ->relationship('sucursales', 'nombre')
                    ->label('Sucursal')
                    ->preload()
                    ->multiple()
                    ->searchable()
                    ->reactive(), // <- Hacerlo reactivo para detectar cambios
                
                Fieldset::make('Horario Completo de Trabajo')
                    ->schema([
                        Radio::make('dias_semana')
                        ->label('Días disponibles')
                        ->options([
                            'lunes'     => 'Lunes',
                            'martes'    => 'Martes',
                            'miercoles' => 'Miércoles',
                            'jueves'    => 'Jueves',
                            'viernes'   => 'Viernes',
                        ])
                        ->inline(),
                    
                
                        Select::make('hora_entrada')
                        ->label('Hora de entrada')
                        ->options([
                            '00:00:00' => '00:00:00', '00:30:00' => '00:30:00',
                            '01:00:00' => '01:00:00', '01:30:00' => '01:30:00',
                            '02:00:00' => '02:00:00', '02:30:00' => '02:30:00',
                            '03:00:00' => '03:00:00', '03:30:00' => '03:30:00',
                            '04:00:00' => '04:00:00', '04:30:00' => '04:30:00',
                            '05:00:00' => '05:00:00', '05:30:00' => '05:30:00',
                            '06:00:00' => '06:00:00', '06:30:00' => '06:30:00',
                            '07:00:00' => '07:00:00', '07:30:00' => '07:30:00',
                            '08:00:00' => '08:00:00', '08:30:00' => '08:30:00',
                            '09:00:00' => '09:00:00', '09:30:00' => '09:30:00',
                            '10:00:00' => '10:00:00', '10:30:00' => '10:30:00',
                            '11:00:00' => '11:00:00', '11:30:00' => '11:30:00',
                            '12:00:00' => '12:00:00', '12:30:00' => '12:30:00',
                            '13:00:00' => '13:00:00', '13:30:00' => '13:30:00',
                            '14:00:00' => '14:00:00', '14:30:00' => '14:30:00',
                            '15:00:00' => '15:00:00', '15:30:00' => '15:30:00',
                            '16:00:00' => '16:00:00', '16:30:00' => '16:30:00',
                            '17:00:00' => '17:00:00', '17:30:00' => '17:30:00',
                            '18:00:00' => '18:00:00', '18:30:00' => '18:30:00',
                            '19:00:00' => '19:00:00', '19:30:00' => '19:30:00',
                            '20:00:00' => '20:00:00', '20:30:00' => '20:30:00',
                            '21:00:00' => '21:00:00', '21:30:00' => '21:30:00',
                            '22:00:00' => '22:00:00', '22:30:00' => '22:30:00',
                            '23:00:00' => '23:00:00', '23:30:00' => '23:30:00',
                            '24:00:00' => '24:00:00',
                        ])
                        ->searchable(),
                    
                    Select::make('hora_salida')
                        ->label('Hora de salida')
                        ->options([
                            '00:00:00' => '00:00:00', '00:30:00' => '00:30:00',
                            '01:00:00' => '01:00:00', '01:30:00' => '01:30:00',
                            '02:00:00' => '02:00:00', '02:30:00' => '02:30:00',
                            '03:00:00' => '03:00:00', '03:30:00' => '03:30:00',
                            '04:00:00' => '04:00:00', '04:30:00' => '04:30:00',
                            '05:00:00' => '05:00:00', '05:30:00' => '05:30:00',
                            '06:00:00' => '06:00:00', '06:30:00' => '06:30:00',
                            '07:00:00' => '07:00:00', '07:30:00' => '07:30:00',
                            '08:00:00' => '08:00:00', '08:30:00' => '08:30:00',
                            '09:00:00' => '09:00:00', '09:30:00' => '09:30:00',
                            '10:00:00' => '10:00:00', '10:30:00' => '10:30:00',
                            '11:00:00' => '11:00:00', '11:30:00' => '11:30:00',
                            '12:00:00' => '12:00:00', '12:30:00' => '12:30:00',
                            '13:00:00' => '13:00:00', '13:30:00' => '13:30:00',
                            '14:00:00' => '14:00:00', '14:30:00' => '14:30:00',
                            '15:00:00' => '15:00:00', '15:30:00' => '15:30:00',
                            '16:00:00' => '16:00:00', '16:30:00' => '16:30:00',
                            '17:00:00' => '17:00:00', '17:30:00' => '17:30:00',
                            '18:00:00' => '18:00:00', '18:30:00' => '18:30:00',
                            '19:00:00' => '19:00:00', '19:30:00' => '19:30:00',
                            '20:00:00' => '20:00:00', '20:30:00' => '20:30:00',
                            '21:00:00' => '21:00:00', '21:30:00' => '21:30:00',
                            '22:00:00' => '22:00:00', '22:30:00' => '22:30:00',
                            '23:00:00' => '23:00:00', '23:30:00' => '23:30:00',
                            '24:00:00' => '24:00:00',
                        ])
                        ->searchable(),
                    
                
                        Select::make('sala')
                            ->label('Sala')
                            ->options(function (callable $get) {
                                $sucursalId = $get('sucursales');
                
                                if (!$sucursalId) {
                                    return [];
                                }
                
                                return \App\Models\Salas::whereIn('id_sucursal', (array) $sucursalId)
                                    ->pluck('nombre', 'id')
                                    ->toArray();
                            })
                            ->reactive()
                            ->placeholder('Seleccione una sala'),
                
                        Placeholder::make('boton_guardar')
                            ->disableLabel()
                            ->content(new HtmlString(
                                '<button type="button" wire:click="guardarHorario" style="padding: 0.5rem 1rem; background-color: #2563EB; color: #ffffff; border: none; border-radius: 0.25rem;">Guardar Horario</button>'
                            )),
                
                        Hidden::make('horarios_guardados'),
                    ])
                    ->visible(function (callable $get) {
                        $tipoSeleccionado = $get('id_tipo');
                        if (!$tipoSeleccionado) {
                            return false;
                        }
                        $tipoPersona = \App\Models\TipoPersona::find($tipoSeleccionado);
                        return $tipoPersona && $tipoPersona->tipo === 'PROFESIONAL';
                    }),
                    Placeholder::make('lista_horarios')
                    ->disableLabel()
                    ->content(function (callable $get, callable $set, $livewire) {
                        $horarios = $get('horarios_guardados') ?? [];
                        $error = $livewire->getErrorBag()->first('horarios_guardados');
                        $isEditing = $livewire instanceof \App\Filament\Resources\UserResource\Pages\EditUser;
                
                        $html = '';
                
                        if ($error) {
                            $html .= '<p style="color: red; font-weight: bold;">' . $error . '</p>';
                        }
                
                        if (empty($horarios)) {
                            $html .= '<p>No hay horarios guardados.</p>';
                        } else {
                            $html .= '<h4>Listado de Horarios</h4>';
                            $html .= '<ul style="list-style: none; padding: 0;">';
                            foreach ($horarios as $index => $horario) {
                                $dias = is_array($horario['dias_semana']) ? implode(', ', $horario['dias_semana']) : $horario['dias_semana'];
                                $html .= '<li style="margin-bottom: 0.5rem; padding: 0.5rem; border: 1px solid #e5e7eb; border-radius: 0.25rem; display: flex; justify-content: space-between; align-items: center;">';
                                $html .= '<div>';
                                $html .= '<strong>Días:</strong> ' . $dias . ' | ';
                                $html .= '<strong>Entrada:</strong> ' . $horario['hora_entrada'] . ' | ';
                                $html .= '<strong>Salida:</strong> ' . $horario['hora_salida'] . ' | ';
                                $html .= '<strong>Sala:</strong> ' . $horario['sala'];
                                $html .= '</div>';
                                
                                $html .= '<button type="button" wire:click="eliminarHorario(' . $index . ')" wire:confirm="¿Estás seguro de que deseas eliminar este horario?"
                                    style="padding: 0.3rem 0.6rem; background-color: red; color: white; border: none; border-radius: 0.25rem; cursor: pointer;">
                                    ❌ Eliminar
                                </button>';

                                if ($isEditing) {
                                    $html .= '<button type="button" wire:click="editarHorario(' . $index . ')" 
                                        style="padding: 0.3rem 0.6rem; background-color: orange; color: white; border: none; border-radius: 0.25rem; cursor: pointer; margin-left: 0.5rem;">
                                        ✏️ Editar
                                    </button>';
                                }
                
                                $html .= '</li>';
                            }
                            $html .= '</ul>';
                        }
                
                        return new HtmlString($html);
                    })
                    ->visible(function (callable $get) {
                        $tipoSeleccionado = $get('id_tipo');
                        if (!$tipoSeleccionado) {
                            return false;
                        }
                        $tipoPersona = \App\Models\TipoPersona::find($tipoSeleccionado);
                        return $tipoPersona && $tipoPersona->tipo === 'PROFESIONAL';
                    }),                
                Forms\Components\TextInput::make('nombre_usuario')
                    ->maxLength(255),
                Forms\Components\Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'nombre')
                    ->label('Rol')
                    ->preload()
                    ->multiple()
                    ->required(),
                Forms\Components\DatePicker::make('email_verified_at')
                    ->seconds(false),
                Forms\Components\Toggle::make('editar_password')
                ->label('Editar contraseña')
                ->reactive() // Hace que sea reactivo
                ->helperText('Habilita esta opción para modificar la contraseña.')
                ->visible(fn ($livewire) => $livewire instanceof Pages\EditUser),
                Forms\Components\TextInput::make('password')
                ->password()
                ->required()
                ->maxLength(255)
                ->label('Contraseña')
                ->rules([
                    'required', 
                    'string', 
                    'min:8',                           // Mínimo 8 caracteres
                    'regex:/[a-z]/',                   // Al menos una letra minúscula
                    'regex:/[A-Z]/',                   // Al menos una letra mayúscula
                    'regex:/[0-9]/',                   // Al menos un número
                    'regex:/[@$!%*?&]/'                // Al menos un carácter especial
                ])
                ->helperText('Debe contener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.')
                ->visible(fn ($livewire, callable $get) => $livewire instanceof Pages\CreateUser || $get('editar_password'))
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('apellido')
                    ->searchable(),
                Tables\Columns\TextColumn::make('telefono'),
                Tables\Columns\TextColumn::make('fecha_nac')
                    ->date(),
                Tables\Columns\TextColumn::make('edad'),
                Tables\Columns\TextColumn::make('domicilio'),
                Tables\Columns\TextColumn::make('id_tipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sucursales.nombre') // Accede a la relación y a la propiedad
                    ->label('Sucursal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombre_usuario')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('roles')
                    ->label('Roles')
                    ->formatStateUsing(function ($record) {
                        return $record->roles->pluck('nombre')->implode(', ');
                    }),    
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Puedes añadir relaciones aquí si es necesario
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with('profesional');
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}