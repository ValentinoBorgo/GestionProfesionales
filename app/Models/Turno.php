<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    protected $fillable = [
        'hora_fecha', 
        'id_profesional', 
        'id_paciente', 
        'id_secretario', 
        'id_tipo_turno', 
        'id_estado'
    ];

    public function profesional()
    {
        return $this->belongsTo(Profesional::class, 'id_profesional');
    }

    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'id_paciente');
    }

    public function secretario()
    {
        return $this->belongsTo(Secretario::class, 'id_secretario');
    }

    public function tipoTurno()
    {
        return $this->belongsTo(TipoTurno::class, 'id_tipo_turno');
    }

    public function estado()
    {
        return $this->belongsTo(EstadoTurno::class, 'id_estado');
    }
}