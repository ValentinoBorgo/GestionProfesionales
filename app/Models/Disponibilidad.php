<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disponibilidad extends Model
{
    use HasFactory;

    protected $table = 'disponibilidad';

    public $timestamps = false;

    protected $fillable = [
        'id_sucursal',
        'id_sala',
        'id_usuario',
        'dia',
        'horario_inicio',
        'horario_fin',
    ];

    /**
     * Relación con Sucursal
     * Una disponibilidad pertenece a una sucursal
     */
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'id_sucursal');
    }

    /**
     * Relación con Sala
     * Una disponibilidad pertenece a una sala
     */
    public function sala()
    {
        return $this->belongsTo(Salas::class, 'id_sala');
    }

    /**
     * Relación con Usuario
     * Una disponibilidad pertenece a un usuario (profesional, secretario, etc.)
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    /**
     * Enum de los días disponibles
     */
    public static function diasDisponibles(): array
    {
        return [
            'lunes'     => 'lunes',
            'martes'    => 'martes',
            'miercoles' => 'miercoles',
            'jueves'    => 'jueves',
            'viernes'   => 'viernes',
        ];
    }
}