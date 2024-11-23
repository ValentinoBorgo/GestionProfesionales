<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make('tipo_usuario')
                ->label('Filtrar por tipo de usuario')
                ->options([
                    'profesional' => 'Profesional',
                    'paciente' => 'Paciente',
                ])
                ->query(function ($query, $value) {
                    if ($value === 'profesional') {
                        return $query->whereHas('roles', function ($subQuery) {
                            $subQuery->where('nombre', 'Profesional');
                        });
                    }

                    if ($value === 'paciente') {
                        return $query->whereHas('roles', function ($subQuery) {
                            $subQuery->where('nombre', 'Paciente');
                        });
                    }
                }),
        ];
    }
}
