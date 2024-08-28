<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $primaryKey = 'id'; // Definir la clave primaria si no sigue la convención estándar

    protected $fillable = [
        'nombre',
    ];

    public function users()
    {
    return $this->belongsToMany(User::class, 'usuario_rol', 'id_rol', 'id_usuario');
    }

     // public function permisos()
    // {
    //     return $this->belongsToMany(Permiso::class, 'rol_permiso', 'id_rol', 'id_permiso');
    // }
}
