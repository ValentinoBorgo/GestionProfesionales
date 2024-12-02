<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProfesionalResource\Pages;
use App\Filament\Resources\ProfesionalResource\RelationManagers;
use App\Models\Profesional;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProfesionalResource extends Resource
{
    protected static ?string $model = Profesional::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Profesional';

    protected static ?string $pluralLabel = 'Profesionales';

   protected static ?string $label = 'Profesional';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListProfesionals::route('/'),
            'create' => Pages\CreateProfesional::route('/create'),
            'edit' => Pages\EditProfesional::route('/{record}/edit'),
        ];
    }
}
