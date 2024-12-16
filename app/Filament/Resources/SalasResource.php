<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalasResource\Pages;
use App\Models\Salas;
use App\Models\Sucursal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SalasResource extends Resource
{
    protected static ?string $model = Salas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Salas';

    protected static ?string $pluralLabel = 'Salas';

    protected static ?string $label = 'Sala';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\Select::make('tipo')
                    ->label('Tipo de Sala')
                    ->options([
                        'Consulta' => 'Consulta',
                        'Cirugía' => 'Cirugía',
                        'Rehabilitación' => 'Rehabilitación',
                        'Otra' => 'Otra',
                    ])
                    ->required()
                    ->placeholder('Seleccione un tipo'),

                Forms\Components\Select::make('id_sucursal')
                    ->label('Sucursal')
                    ->relationship('sucursal', 'nombre') // Relación con la sucursal
                    ->required()
                    ->preload()
                    ->placeholder('Seleccione una sucursal'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('tipo')
                    ->label('Tipo de Sala')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('sucursal.nombre')
                    ->label('Sucursal')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Última Actualización')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Puedes añadir filtros si es necesario
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
            // Puedes añadir relaciones si son necesarias
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSalas::route('/'),
            'create' => Pages\CreateSalas::route('/create'),
            'edit' => Pages\EditSalas::route('/{record}/edit'),
        ];
    }
}
