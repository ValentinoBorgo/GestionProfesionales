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
        'id_ficha_medica',
    ];

    public function fichaMedica()
    {
        return $this->belongsTo(fichaMedica::class, 'id_ficha_medica');
    }

}
