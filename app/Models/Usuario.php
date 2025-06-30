<?php

namespace App\Models;

// app/Models/Usuario.php


use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $fillable = ['nombre', 'email', 'telefono'];

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }
}
