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
    'horarios', 
    ];

    public function users()
{
    return $this->belongsToMany(User::class, 'sucursal_usuario', 'id_sucursal', 'id_usuario');
}

}

