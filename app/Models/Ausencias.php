<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Ausencias extends Model
{
    use HasFactory;
    
    protected $fillable = ['motivo', 'fecha_inicio', 'fecha_fin','id_usuario'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }
}