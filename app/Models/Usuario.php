<?php

namespace App\Models;

// app/Models/Usuario.php


use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Usuario extends Model
{
    use HasRoles;
    protected $guard_name = 'web';
    protected $fillable = ['nombre', 'email', 'telefono'];

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }
}
