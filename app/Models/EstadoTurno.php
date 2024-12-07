<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class EstadoTurno extends Model
{
    protected $fillable = ['codigo', 'nombre'];

    public function turnos()
    {
        return $this->hasMany(Turno::class, 'id_estado');
    }
}