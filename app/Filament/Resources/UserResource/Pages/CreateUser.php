<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\TipoPersona;
use App\Models\Profesional;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $tipoPersonaProfesional = TipoPersona::where('tipo', 'PROFESIONAL')->first();
        if ($tipoPersonaProfesional && intval($this->record->id_tipo) === intval($tipoPersonaProfesional->id)) {
            $titulo = $this->data['titulo'] ?? 'Sin especificar';
            Profesional::create([
                'id_persona' => $this->record->id,
                'titulo' => $titulo,
            ]);
        }
    }
}
