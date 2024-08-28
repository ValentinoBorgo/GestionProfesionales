<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'rol';

    protected $primaryKey = 'id'; // Definir la clave primaria si no sigue la convención estándar

    protected $fillable = [
        'nombre',
    ];

     // public function permisos()
    // {
    //     return $this->belongsToMany(Permiso::class, 'rol_permiso', 'id_rol', 'id_permiso');
    // }
}
