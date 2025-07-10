<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibroMasPrestado extends Model
{
    protected $table = 'libros_mas_prestados';
    
    // Como es una vista, no tiene timestamps
    public $timestamps = false;
    
    // Como es una vista, no permitimos operaciones de escritura
    protected $fillable = [];
    
    // Cast para asegurar tipos correctos
    protected $casts = [
        'total_prestamos' => 'integer',
        'prestamos_activos' => 'integer',
        'prestamos_devueltos' => 'integer',
        'disponibles_actuales' => 'integer',
        'ejemplares' => 'integer',
    ];
}
