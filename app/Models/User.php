<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
    'name', 
    'apellido', 
    'telefono', 
    'edad', 
    'fecha_nac', 
    'domicilio', 
    'id_rol', 
    'id_tipo', 
    'nombre_usuario', 
    'email', 
    'password'];

    public function sucursales()
    {
    return $this->belongsToMany(Sucursal::class, 'sucursal_usuario', 'id_usuario', 'id_sucursal');
    }

    public function secretario()
    {
    return $this->hasOne(Secretario::class, 'id_usuario');
    }

    public function pacientes()
    {
    return $this->hasOne(Paciente::class, 'id_usuario');
    }

    public function profesional()
    {
    return $this->hasOne(Profesional::class, 'id_persona');
    }

    public function roles()
    {
    return $this->belongsToMany(Rol::class, 'usuario_rol', 'id_usuario', 'id_rol');
    }

    public function tipoPersona()
    {
        return $this->belongsTo(TipoPersona::class, 'id_tipo');
    }
    public function ausencias()
    {
        return $this->hasMany(Ausencias::class, 'id_usuario');
    }

    public function save(array $options = [])
    {
        $this->validateRoles();
        parent::save($options);
    }

    protected function validateRoles()
    {
        $roles = $this->roles->pluck('nombre')->toArray();

        if (in_array('paciente', $roles) &&  (in_array('secretario', $roles) || in_array('profesional', $roles) || in_array('administrador', $roles))) {
            throw new \Exception('Un usuario no puede ser paciente y tener otros roles al mismo tiempo.');
        }
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
