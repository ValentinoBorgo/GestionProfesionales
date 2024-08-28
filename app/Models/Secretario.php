<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secretario extends Model
{
    use HasFactory;

    protected $table = 'secretario';

    protected $fillable = [
        'fecha_baja',
        'id',
    ];

    public function persona()
    {
        return $this->belongsTo(User::class, 'id');
    }
}
