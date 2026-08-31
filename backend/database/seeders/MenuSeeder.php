<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menus = [
            [
                'id' => 1,
                'name' => 'Dashboard',
                'url' => 'dashboard',
                'parent_id' => null,
                'order' => 1,
                'is_active' => true,
                'user_created' => 1,
                'user_updated' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Usuarios',
                'url' => 'UserList',
                'parent_id' => null,
                'order' => 2,
                'is_active' => true,
                'user_created' => 1,
                'user_updated' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Roles',
                'url' => 'RolList',
                'parent_id' => null,
                'order' => 3,
                'is_active' => true,
                'user_created' => 1,
                'user_updated' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Organizaciones',
                'url' => 'OrganizationList',
                'parent_id' => null,
                'order' => 4,
                'is_active' => true,
                'user_created' => 1,
                'user_updated' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Sucursales',
                'url' => 'BranchList',
                'parent_id' => null,
                'order' => 5,
                'is_active' => true,
                'user_created' => 1,
                'user_updated' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'name' => 'Menús',
                'url' => 'MenuList',
                'parent_id' => null,
                'order' => 6,
                'is_active' => true,
                'user_created' => 1,
                'user_updated' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 7,
                'name' => 'Permisos',
                'url' => 'PermissionList',
                'parent_id' => null,
                'order' => 7,
                'is_active' => true,
                'user_created' => 1,
                'user_updated' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('menus')->insert($menus);
    }
}
