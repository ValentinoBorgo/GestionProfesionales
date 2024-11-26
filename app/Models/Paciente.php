<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'paciente';

    protected $fillable = [
        'fecha_alta',
        'id',
        'id_usuario',
    ];

    public function fichaMedica()
    {
        return $this->hasOne(FichaMedica::class, 'id_paciente');
    }

    public function user()
{
    return $this->belongsTo(User::class, 'id_usuario');
}
}
