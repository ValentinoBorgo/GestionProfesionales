<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profesional extends Model
{
    use HasFactory;

     
    protected $table = 'profesional';

    protected $fillable = [
        'ocupacion',
        'fecha_baja',
        'id',
        'id_usuario',
    ];

    public function persona()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
