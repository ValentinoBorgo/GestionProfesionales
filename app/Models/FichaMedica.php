<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FichaMedica extends Model
{
    use HasFactory;

    protected $table= 'ficha_medica';

    protected $fillable = [
        'id',
        'id_paciente',
        'nombre',
        'apellido',
        'edad',
        'fecha_nac',
        'ocupacion',
        'domicilio',
        'telefono',
        'localidad',
        'provincia',
        'persona_responsable',
        'vinculo',
        'dni',
        'telefono_persona_responsable',
    ];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }
}
