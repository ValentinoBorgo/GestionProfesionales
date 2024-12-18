<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class EstadoTurno extends Model
{
    protected $fillable = ['codigo', 'nombre', 'id'];

    public const CANCELADO_CLIENTE = 'CANCELADO_CLIENTE';
    public const CANCELADO_PROFESIONAL = 'CANCELADO_PROFESIONAL';

    public function turnos()
    {
        return $this->hasMany(Turno::class, 'id_estado');
    }

    public static function getEstadoId(string $estadoNombre): int
    {
        $id = self::where('codigo', $estadoNombre)->value('id');
        return $id;
    }
}