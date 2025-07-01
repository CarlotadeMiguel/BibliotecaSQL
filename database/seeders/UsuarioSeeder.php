<?php

namespace Database\Seeders;
//database/seeders/UsuarioSeeder.php
use Illuminate\Database\Seeder;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        $usuarios = [
            ['nombre' => 'Ana García',   'email' => 'ana.garcia@example.com',   'telefono' => '600123456'],
            ['nombre' => 'Luis Martínez','email' => 'luis.martinez@example.com','telefono' => '600234567'],
            ['nombre' => 'María Ruiz',   'email' => 'maria.ruiz@example.com',   'telefono' => '600345678'],
        ];

        foreach ($usuarios as $data) {
            Usuario::create($data)->assignRole('user');
        }

        // Crea un administrador
        $admin = Usuario::create([
            'nombre'   => 'Admin Biblioteca',
            'email'    => 'admin@biblioteca.com',
            'telefono' => '600000000',
        ]);
        $admin->assignRole('admin');
    }
}
