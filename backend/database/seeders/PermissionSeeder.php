<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::table('permissions')->insert([
            [
                'id' => 1,
                'name' => 'view_dashboard',
                'guard_name' => 'web',
                'code' => 'VIEW_DASHBOARD',
                'user_created' => 1,
                'user_updated' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'edit_settings',
                'guard_name' => 'web',
                'code' => 'EDIT_SETTINGS',
                'user_created' => 1,
                'user_updated' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
