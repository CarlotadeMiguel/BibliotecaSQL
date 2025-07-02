<?php

namespace Database\Seeders;
//database/seeders/PrestamoSeeder.php
use Illuminate\Database\Seeder;
use App\Models\Prestamo;
use App\Models\Usuario;
use App\Models\Libro;
use Carbon\Carbon;

class PrestamoSeeder extends Seeder
{
    public function run()
    {
        // Obtener algunos usuarios y libros
        $usuarios = Usuario::role('user')->get();
        $libros   = Libro::all();

        $prestamos = [
            [
                'usuario_id'      => $usuarios[0]->id,
                'libro_id'        => $libros->where('titulo','1984')->first()->id,
                'fecha_prestamo'  => Carbon::now()->subDays(10),
                'fecha_devolucion'=> Carbon::now()->addDays(5),
                'estado'          => 'prestado',
            ],
            [
                'usuario_id'      => $usuarios[1]->id,
                'libro_id'        => $libros->where('titulo','Cien Años de Soledad')->first()->id,
                'fecha_prestamo'  => Carbon::now()->subDays(20),
                'fecha_devolucion'=> Carbon::now()->subDays(5),
                'estado'          => 'devuelto',
            ],
            [
                'usuario_id'      => $usuarios[2]->id,
                'libro_id'        => $libros->where('titulo','La Sombra del Viento')->first()->id,
                'fecha_prestamo'  => Carbon::now()->subDays(15),
                'fecha_devolucion'=> Carbon::now()->subDays(1),
                'estado'          => 'retrasado',
            ],
        ];

        foreach ($prestamos as $data) {
            Prestamo::create($data);
            // Ajustar ejemplares en libro
            $libro = Libro::find($data['libro_id']);
            if (in_array($data['estado'], ['prestado','retrasado'])) {
                $libro->decrement('ejemplares');
            }
        }
    }
}
