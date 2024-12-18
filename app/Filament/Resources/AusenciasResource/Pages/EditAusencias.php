<?php

namespace App\Filament\Resources\AusenciasResource\Pages;

use App\Filament\Resources\AusenciasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAusencias extends EditRecord
{
    protected static string $resource = AusenciasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
