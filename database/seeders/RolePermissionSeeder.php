<?php

namespace Database\Seeders;

// database/seeders/RolePermissionSeeder.php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Limpiar caché de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear permisos si no existen
        $permissions = [
            'manage-users',
            'manage-books',
            'manage-all-loans',
            'manage-own-loans',
            'view-reports'
        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Crear roles si no existen
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $userRole  = Role::firstOrCreate(['name' => 'user',  'guard_name' => 'web']);

        // Asignar permisos a roles
        $adminRole->givePermissionTo([
            'manage-users',
            'manage-books',
            'manage-all-loans',
            'view-reports'
        ]);
        $userRole->givePermissionTo(['manage-own-loans']);
    }
}
