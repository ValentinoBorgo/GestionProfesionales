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

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user->roles->pluck('nombre')->contains(fn ($role) => in_array($role, ['ROLE_ADMIN', 'ROLE_PROFESIONAL']));
    }
    
    

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
                    ->formatStateUsing(fn ($record) => $record->persona
                        ? ($record->persona->name . ' ' . $record->persona->apellido)
                        : '-')
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('persona.email')
                    ->label('Email')
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('persona.telefono')
                    ->label('Teléfono')
                    ->searchable(),
    
                Tables\Columns\TextColumn::make('persona.sucursales')
                    ->label('Sucursales')
                    ->formatStateUsing(fn ($record) => $record->persona?->sucursales?->pluck('nombre')?->join(', ') ?? '-'),
            ])
            ->actions([
                Tables\Actions\Action::make('editarUsuario')
                    ->label('Editar Usuario')
                    ->icon('heroicon-o-pencil')
                    ->url(fn ($record) => url("/dashboard/users/{$record->persona->id}/edit")) // Ruta personalizada
                    ->openUrlInNewTab(), // Opcional: abrir en nueva pestaña
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProfesionals::route('/'),
            'edit' => Pages\EditProfesional::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
