<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoPersona extends Model
{
    use HasFactory;

    protected $table = 'tipo_persona';

    protected $fillable = ['tipo'];

    public function user()
    {
        return $this->hasMany(User::class, 'id_tipo');
    }
}

