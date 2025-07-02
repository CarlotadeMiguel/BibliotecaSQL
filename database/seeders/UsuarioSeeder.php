<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class UsuarioSeeder extends Seeder
{
    public function run()
    {
        // Limpiar caché de permisos para evitar problemas
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear roles si no existen (por si el seeder se ejecuta varias veces)
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web']
        );
        $userRole = Role::firstOrCreate(
            ['name' => 'user', 'guard_name' => 'web']
        );

        // Crear permisos si no existen (esto normalmente va en otro seeder, pero aquí por claridad)
        $permissions = [
            'manage-users',
            'manage-books',
            'manage-all-loans',
            'manage-own-loans',
            'view-reports'
        ];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // Asignar permisos a roles
        $adminRole->givePermissionTo([
            'manage-users',
            'manage-books',
            'manage-all-loans',
            'view-reports'
        ]);
        $userRole->givePermissionTo(['manage-own-loans']);

        // Crear usuarios normales
        $usuarios = [
            ['nombre' => 'Ana García',    'email' => 'ana.garcia@example.com',    'telefono' => '600123456', 'password' => Hash::make('password123')],
            ['nombre' => 'Luis Martínez', 'email' => 'luis.martinez@example.com', 'telefono' => '600234567', 'password' => Hash::make('password123')],
            ['nombre' => 'María Ruiz',    'email' => 'maria.ruiz@example.com',    'telefono' => '600345678', 'password' => Hash::make('password123')],
        ];

        foreach ($usuarios as $data) {
            $user = Usuario::create($data);
            $user->assignRole($userRole); // Asignar rol 'user'
        }

        // Crear administrador
        $admin = Usuario::create([
            'nombre'   => 'Admin Biblioteca',
            'email'    => 'admin@biblioteca.com',
            'telefono' => '600000000',
            'password' => Hash::make('password123')
        ]);
        $admin->assignRole($adminRole); // Asignar rol 'admin'
    }
}
