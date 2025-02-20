<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disponibilidad extends Model
{
    protected $table = 'disponibilidad';

    protected $fillable = [
        'id_sucursal',
        'id_sala',
        'id_usuario',
        'dia',
        'horario_inicio',
        'horario_fin',
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal');
    }

    public function sala()
    {
        return $this->belongsTo(Salas::class, 'id_sala');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}