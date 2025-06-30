<?php

namespace App\Models;

// app/Models/Prestamo.php

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
        protected $fillable = ['usuario_id', 'libro_id', 'fecha_prestamo', 'fecha_devolucion', 'estado'];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function libro()
    {
        return $this->belongsTo(Libro::class);
    }
}
