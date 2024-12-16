<?php

namespace App\Filament\Resources\SalasResource\Pages;

use App\Filament\Resources\SalasResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSalas extends ListRecords
{
    protected static string $resource = SalasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
