<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AusenciasResource\Pages;
use App\Models\Ausencias;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class AusenciasResource extends Resource
{
    protected static ?string $model = Ausencias::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Ausencias';
    protected static ?string $pluralLabel = 'Ausencias';
    protected static ?string $label = 'Ausencia';

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user->roles->pluck('nombre')->contains(fn ($role) => in_array($role, ['ROLE_PROFESIONAL']));
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id_usuario')
                    ->label('Usuario')
                    ->options(fn () => [auth()->user()->id => auth()->user()->name . ' ' . auth()->user()->apellido])
                    ->default(auth()->user()->id)
                    ->required(),

                Forms\Components\TextInput::make('motivo')
                    ->label('Motivo')
                    ->required()
                    ->maxLength(255),

                Forms\Components\DateTimePicker::make('fecha_inicio')
                    ->native(false)
                    ->timezone('America/Argentina/Buenos_Aires')
                    ->label('Fecha de Inicio')
                    ->minutesStep(30)
                    ->displayFormat('d/m/Y H:i')
                    ->seconds(false)
                    ->required(),

                Forms\Components\DateTimePicker::make('fecha_fin')
                    ->native(false)
                    ->timezone('America/Argentina/Buenos_Aires')
                    ->label('Fecha de Fin')
                    ->minutesStep(30)
                    ->displayFormat('d/m/Y H:i')
                    ->seconds(false)
                    ->required()
                    ->rule(function (\Filament\Forms\Get $get) {
                        return function (string $attribute, $value, \Closure $fail) use ($get) {
                            if ($value <= $get('fecha_inicio')) {
                                $fail('La fecha de fin no puede ser menor que la fecha de inicio.');
                            }
                        };
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('usuario.name')
                    ->label('Usuario')
                    ->formatStateUsing(fn ($record) => $record->usuario->name . ' ' . $record->usuario->apellido)
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('motivo')
                    ->label('Motivo')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->label('Fecha de Inicio')
                    ->datetime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Fecha de Fin')
                    ->datetime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAusencias::route('/'),
            'create' => Pages\CreateAusencias::route('/create'),
            'edit' => Pages\EditAusencias::route('/{record}/edit'),
        ];
    }
}
