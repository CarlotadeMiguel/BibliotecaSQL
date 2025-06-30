<?php 
namespace App\Models;

// app/Models/Libro.php

use Illuminate\Database\Eloquent\Model;



class Libro extends Model
{
    protected $fillable = ['titulo', 'autor', 'isbn', 'ejemplares'];

    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }
}
