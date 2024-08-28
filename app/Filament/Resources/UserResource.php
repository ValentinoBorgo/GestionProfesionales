<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
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

    public static function form(Form $form): Form
    {
        return $form    
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nombre')
                    ->maxLength(255),
                Forms\Components\TextInput::make('apellido')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telefono')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('edad')
                    ->required()
                    ->numeric()
                    ->minValue(0),
                Forms\Components\DateTimePicker::make('fecha_nac'),
                Forms\Components\TextInput::make('domicilio')
                    ->maxLength(255),
                Forms\Components\Select::make('id_rol')
                    ->options([
                        1 => 'Administrador',
                        2 => 'Secretario',
                        3 => 'Profesional',
                        4 => 'Paciente',
                    ])
                    ->label('Rol')
                    ->required(),
                Forms\Components\TextInput::make('id_tipo') 
                    ->numeric(),
                    Forms\Components\Select::make('sucursales')
                    ->relationship('sucursales', 'nombre') // acordarse acer q muestre todas las sucursales
                    ->multiple(), // para buscar hay q tipear el nombre de la sucursal
                Forms\Components\TextInput::make('nobre_usuario')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('email_verified_at'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('edad'),
                Tables\Columns\TextColumn::make('fecha_nac')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('domicilio'),
                Tables\Columns\TextColumn::make('id_rol')
                    ->label('Rol')
                    ->searchable(),
                Tables\Columns\TextColumn::make('id_tipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sucursales.nombre')
                    ->label('Sucursal')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nobre_usuario')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
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
                // Puedes añadir filtros personalizados aquí si es necesario
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}