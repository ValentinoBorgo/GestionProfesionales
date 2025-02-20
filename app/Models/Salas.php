<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Salas extends Model
{
    use HasFactory;
    
    protected $fillable = ['tipo', 'id_sucursal', 'nombre'];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal');
    }

    public function turnos()
    {
        return $this->hasMany(Turno::class, 'id_sala');
    }
    public function disponibilidad()
    {
        return $this->hasMany(Disponibilidad::class, 'id_profesional');
    }
}