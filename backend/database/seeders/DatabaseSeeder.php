<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            OrganizationSeeder::class,
            BranchSeeder::class,
            RolSeeder::class,
            PermissionSeeder::class,
            MenuSeeder::class,
        ]);

        // Asignar Rol Admin a Organización 1
        \Illuminate\Support\Facades\DB::table('organization_rol')->insert([
            'id' => 1,
            'organization_id' => 1,
            'rol_id' => 1, // Admin
            'user_created' => 1,
            'user_updated' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Asignar Rol User a Organización 2
        \Illuminate\Support\Facades\DB::table('organization_rol')->insert([
            'id' => 2,
            'organization_id' => 2,
            'rol_id' => 2, // User
            'user_created' => 1,
            'user_updated' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Asignar Usuario Admin (1) al Rol Admin de Org 1 (organization_rol_id: 1)
        \Illuminate\Support\Facades\DB::table('organization_rol_user')->insert([
            'organization_rol_id' => 1,
            'user_id' => 1,
            'user_created' => 1,
            'user_updated' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Asignar Usuario User (2) al Rol User de Org 2 (organization_rol_id: 2)
        \Illuminate\Support\Facades\DB::table('organization_rol_user')->insert([
            'organization_rol_id' => 2,
            'user_id' => 2,
            'user_created' => 1,
            'user_updated' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Asignar todos los menús al rol Admin de Organización 1 (organization_rol_id: 1)
        $menuIds = [1, 2, 3, 4, 5, 6, 7]; // Todos los menús creados en MenuSeeder
        foreach ($menuIds as $menuId) {
            \Illuminate\Support\Facades\DB::table('menu_organization_rol')->insert([
                'organization_rol_id' => 1, // Admin de Org 1
                'menu_id' => $menuId,
                'user_created' => 1,
                'user_updated' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Asignar algunos menús básicos al rol User de Organización 2 (organization_rol_id: 2)
        $userMenuIds = [1, 2]; // Dashboard y Usuarios
        foreach ($userMenuIds as $menuId) {
            \Illuminate\Support\Facades\DB::table('menu_organization_rol')->insert([
                'organization_rol_id' => 2, // User de Org 2
                'menu_id' => $menuId,
                'user_created' => 1,
                'user_updated' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
