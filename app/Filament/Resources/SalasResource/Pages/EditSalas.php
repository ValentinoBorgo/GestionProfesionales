<?php

namespace App\Filament\Resources\SalasResource\Pages;

use App\Filament\Resources\SalasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSalas extends EditRecord
{
    protected static string $resource = SalasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
