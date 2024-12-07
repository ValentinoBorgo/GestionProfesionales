<?php

namespace App\Filament\Resources\ProfesionalResource\Pages;

use App\Filament\Resources\ProfesionalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;


class ListProfesionals extends ListRecords
{
    protected static string $resource = ProfesionalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // protected function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    // {
    //     return parent::getTableQuery()
    //         ->whereHas('persona.tipoPersona', function($query) {
    //             $query->where('tipo', 'PROFESIONAL');
    //         });
    // }
}
