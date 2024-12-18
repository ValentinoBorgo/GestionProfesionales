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
        return $user && $user->profesional ? true : false;
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

                Forms\Components\DatePicker::make('fecha_inicio')
                    ->label('Fecha de Inicio')
                    ->required(),

                Forms\Components\DatePicker::make('fecha_fin')
                    ->label('Fecha de Fin')
                    ->required(),
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
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Fecha de Fin')
                    ->date()
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
