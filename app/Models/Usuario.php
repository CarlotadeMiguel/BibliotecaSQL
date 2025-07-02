<?php

namespace App\Models;

// app/Models/Usuario.php

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasRoles, HasApiTokens;
    protected $guard_name = 'web';
    protected $fillable = ['nombre', 'email', 'telefono', 'password'];

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }
}
