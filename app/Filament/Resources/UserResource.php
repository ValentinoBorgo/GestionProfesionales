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
                    ->maxLength(255),
                Forms\Components\TextInput::make('telefono')
                    ->maxLength(255),
                Forms\Components\TextInput::make('edad')
                    ->numeric()
                    ->minValue(0),
                Forms\Components\DateTimePicker::make('fecha_nac'),
                Forms\Components\TextInput::make('domicilio')
                    ->maxLength(255),
                Forms\Components\TextInput::make('id_rol')
                    ->label('rol')
                    ->numeric(),
                Forms\Components\TextInput::make('id_tipo')
                    ->numeric(),
                Forms\Components\TextInput::make('id_sucursal')
                    ->numeric(),
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
                Tables\Columns\TextColumn::make('telefono')
                    ->searchable(),
                Tables\Columns\TextColumn::make('edad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fecha_nac')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('domicilio')
                    ->searchable(),
                Tables\Columns\TextColumn::make('id_rol')
                    ->label('rol')
                    ->searchable(),
                Tables\Columns\TextColumn::make('id_tipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('id_sucursal')
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
