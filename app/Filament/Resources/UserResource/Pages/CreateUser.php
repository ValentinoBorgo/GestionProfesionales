<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\TipoPersona;
use App\Models\Profesional;
use App\Models\Secretario;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {

        if (!empty($this->roles)) {
            $this->record->roles()->sync($this->roles);
        }

        if (!empty($this->sucursales)) {
            $this->record->sucursales()->sync($this->sucursales);
        }

        $tipoPersonaProfesional = TipoPersona::where('tipo', 'PROFESIONAL')->first();
        if ($tipoPersonaProfesional && intval($this->record->id_tipo) === intval($tipoPersonaProfesional->id)) {
            $titulo = $this->data['titulo'] ?? 'Sin especificar';
            Profesional::create([
                'id_persona' => $this->record->id,
                'titulo' => $titulo,
            ]);
        }
        $tipoPersonaSecreatrio = TipoPersona::where('tipo', 'SECRETARIO')->first();
        if ($tipoPersonaSecreatrio && intval($this->record->id_tipo) === intval($tipoPersonaSecreatrio->id)) {
            $titulo = $this->data['titulo'] ?? 'Sin especificar';
            Secretario::create([
                'id_usuario' => $this->record->id,
            ]);
        }
    }
}
