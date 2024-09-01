<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SucursalUsuario extends Model
{

    protected $fillable = ['id_usuario', 'id_sucursal'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal');
    }

    public static function afterSave($record)
    {
    
    $sucursalId = $record->id_sucursal;

    SucursalUsuario::updateOrCreate(
        ['id_usuario' => $record->id, 'id_sucursal' => $sucursalId],
        ['id_usuario' => $record->id, 'id_sucursal' => $sucursalId]
    );
}   

}

