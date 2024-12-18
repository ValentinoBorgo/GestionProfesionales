<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secretario extends Model
{
    use HasFactory;

    protected $table = 'secretario';

    protected $fillable = [
        'fecha_baja',
        'id',
        'id_usuario',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function sucursalesGenerales()
    {
        return $this->belongsToMany(Sucursal::class, 'sucursal_usuario', 'id_usuario', 'id_sucursal', 'id_usuario', 'id');
    }
    
    public function sucursales()
     {
         return $this->belongsToMany(Sucursal::class, 'sucursal_usuario', 'id_usuario', 'id_sucursal');
     }
}
