<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'sucursal';

    protected $fillable = [
    'nombre', 
    'direccion', 
    'ciudad', 
    'provincia', 
    'razon_social', 
    'codigo_postal', 
    'telefono', 
    'horario_apertura',
    'horario_cierre',
    ];

    public function users()
    {
    return $this->belongsToMany(User::class, 'sucursal_usuario', 'id_sucursal', 'id_usuario');
    }
    public function salas()
    {
        return $this->hasMany(Salas::class, 'id_sucursal');
    }
    public function disponibilidad()
    {
        return $this->hasMany(Disponibilidad::class, 'id_profesional');
    }

}

