<?php

namespace Database\Seeders;

// database/seeders/RolePermissionSeeder.php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Crear permisos
        $permissions = [
            'manage-users',
            'manage-books',
            'manage-all-loans',
            'manage-own-loans',
            'view-reports'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Crear roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);

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
