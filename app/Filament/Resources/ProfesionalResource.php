<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfesionalResource\Pages;
use App\Models\Profesional;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProfesionalResource extends Resource
{
    protected static ?string $model = Profesional::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Profesionales';
    protected static ?string $pluralLabel = 'Profesionales';
    protected static ?string $label = 'Profesional';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('titulo')
                    ->label('Título')
                    ->maxLength(255)
                    ->required(),
                
                Forms\Components\Select::make('id_persona')
                    ->label('Persona (Usuario)')
                    ->relationship('persona', 'email')
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
                
                Tables\Columns\TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('persona.name')
                    ->label('Nombre y Apellido')
                    ->formatStateUsing(function ($record) {
                        return $record->persona
                            ? ($record->persona->name . ' ' . $record->persona->apellido)
                            : '-';
                    })
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('persona.email')
                    ->label('Email')
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('persona.telefono')
                    ->label('Teléfono')
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('persona.sucursales')
                    ->label('Sucursal')
                    ->formatStateUsing(function ($record) {
                        return $record->persona && $record->persona->sucursales
                            ? $record->persona->sucursales->pluck('nombre')->join(', ')
                            : '-';
                    })
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->sortable(),
    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Actualizado el')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('editarSucursal')
                    ->label('Editar Sucursal')
                    ->icon('heroicon-o-pencil')
                    ->modalHeading('Editar Sucursal del Profesional')
                    ->form([
                        Forms\Components\Select::make('sucursales')
                            ->label('Sucursales disponibles')
                            ->options(\App\Models\Sucursal::all()->pluck('nombre', 'id')->toArray())
                            ->required(),
                    ])
                    ->action(function ($record, $data) {
                        $persona = $record->persona;
                        
                        if ($persona) {
                            $persona->sucursales()->sync($data['sucursales']);
                        }
                    })
                    ->requiresConfirmation()
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProfesionals::route('/'),
            'create' => Pages\CreateProfesional::route('/create'),
            'edit' => Pages\EditProfesional::route('/{record}/edit'),
        ];
    }
}
