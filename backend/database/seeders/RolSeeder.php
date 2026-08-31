<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::table('roles')->insert([
            [
                'id' => 1,
                'name' => 'admin',
                'guard_name' => 'web',
                'user_created' => 1,
                'user_updated' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'user',
                'guard_name' => 'web',
                'user_created' => 1,
                'user_updated' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
