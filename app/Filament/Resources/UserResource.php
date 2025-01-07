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
                Forms\Components\DateTimePicker::make('fecha_nac')
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
                    ->searchable()
                    ->options([
                        'Cardiologo' => 'Cardiologo',
                        'Cirujano' => 'Cirujano',
                        'Consultor' => 'Consultor',
                        'Especialista' => 'Especialista',
                        'Estomatologo' => 'Estomatologo',
                        'Farmacia' => 'Farmacia',
                        'Hematologo' => 'Hematologo',
                        'Medico' => 'Medico',
                        'Neurologo' => 'Neurologo',
                        'Oftalmología' => 'Oftalmología',
                        'Ortopedia' => 'Ortopedia',
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
                    ->relationship('sucursales', 'nombre') // acordarse acer q muestre todas las
                    ->label('Sucursal')
                    ->preload()
                    ->multiple(),
                Forms\Components\TextInput::make('nombre_usuario')
                    ->maxLength(255),
                Forms\Components\Select::make('roles')
                    ->multiple()
                    ->relationship('roles', 'nombre')
                    ->label('Rol')
                    ->preload()
                    ->multiple()
                    ->required(),
                Forms\Components\DateTimePicker::make('email_verified_at'),
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
                    ->dateTime(),
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
                    })
                    ->searchable(),    
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


// acordarse adaptar para titulos profesionales meka  
// Forms\Components\Select::make('tipo')
// ->label('Tipo de Sala')
// ->options([
//     'Consulta' => 'Consulta',
//     'Cirugía' => 'Cirugía',
//     'Rehabilitación' => 'Rehabilitación',
//     'Otra' => 'Otra',
// ])
// ->required()
// ->placeholder('Seleccione un tipo'),