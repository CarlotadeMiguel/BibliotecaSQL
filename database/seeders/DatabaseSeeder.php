<?php

namespace Database\Seeders;
//database/seeders/DatabaseSeeder.php
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Primero roles y permisos
        $this->call(RolePermissionSeeder::class);

        // Luego datos maestros
        $this->call([
            UsuarioSeeder::class,
            LibroSeeder::class,
            PrestamoSeeder::class,
        ]);
    }
}
