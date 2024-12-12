<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SucursalUsuario extends Model
{
    protected $table = 'sucursal_usuario';
    protected $fillable = ['id_usuario', 'id_sucursal'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal');
    }

}   

