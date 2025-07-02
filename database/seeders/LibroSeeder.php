<?php

namespace Database\Seeders;
//database/seeders/LibroSeeder.php
use Illuminate\Database\Seeder;
use App\Models\Libro;

class LibroSeeder extends Seeder
{
    public function run()
    {
        $libros = [
            ['titulo' => 'Cien Años de Soledad',   'autor' => 'Gabriel García Márquez', 'isbn' => '978-0307474728', 'ejemplares' => 3],
            ['titulo' => 'Don Quijote de la Mancha','autor' => 'Miguel de Cervantes',     'isbn' => '978-8491050294', 'ejemplares' => 2],
            ['titulo' => 'La Sombra del Viento',    'autor' => 'Carlos Ruiz Zafón',     'isbn' => '978-0143126393', 'ejemplares' => 4],
            ['titulo' => '1984',                    'autor' => 'George Orwell',         'isbn' => '978-0451524935', 'ejemplares' => 5],
        ];

        foreach ($libros as $data) {
            Libro::create($data);
        }
    }
}
