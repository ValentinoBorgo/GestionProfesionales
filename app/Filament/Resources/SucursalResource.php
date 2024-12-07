<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SucursalResource\Pages;
use App\Filament\Resources\SucursalResource\RelationManagers;
use App\Models\Sucursal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SucursalResource extends Resource
{
    protected static ?string $model = Sucursal::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Sucursales';

    protected static ?string $pluralLabel = 'Sucursales';

   protected static ?string $label = 'Sucursal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('direccion')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('ciudad')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('provincia')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('razon_social')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('codigo_postal')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('telefono')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('horarios')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('direccion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ciudad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('provincia')
                    ->searchable(),
                Tables\Columns\TextColumn::make('razon_social')
                    ->searchable(),
                Tables\Columns\TextColumn::make('codigo_postal'),
                Tables\Columns\TextColumn::make('telefono'),
                Tables\Columns\TextColumn::make('ihorarios'),
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
            'index' => Pages\ListSucursals::route('/'),
            'create' => Pages\CreateSucursal::route('/create'),
            'edit' => Pages\EditSucursal::route('/{record}/edit'),
        ];
    }
}
