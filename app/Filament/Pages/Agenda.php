<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Turno;

class Agenda extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Agenda';

    protected static string $view = 'filament.pages.agenda';

    public function getTurnos()
    {
        return Turno::with(['paciente', 'sucursal'])
            ->where('id_profesional', auth()->id())
            ->orderBy('hora_fecha', 'asc')
            ->get();
    }
}
