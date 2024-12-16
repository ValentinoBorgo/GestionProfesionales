<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\FichaMedica;

class DetalleFichaMedica extends Page
{
    protected static string $view = 'filament.pages.detalle-ficha-medica';

    public $ficha;

    protected static bool $shouldRegisterNavigation = false;

    public function mount($id)
    {
        $this->ficha = FichaMedica::findOrFail($id);
    }
}
