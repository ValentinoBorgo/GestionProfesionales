<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\TipoPersona;
use App\Models\Profesional;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterSave(): void
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

            $profesional = Profesional::where('id_persona', $this->record->id)->first();

            if ($profesional) {
                $profesional->update([
                    'titulo' => $titulo,
                ]);
            } else {
                Profesional::create([
                    'id_persona' => $this->record->id,
                    'titulo' => $titulo,
                ]);
            }
        } else {
            Profesional::where('id_persona', $this->record->id)->delete();
        }
    }
}
