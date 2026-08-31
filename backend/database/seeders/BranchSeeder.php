<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \Illuminate\Support\Facades\DB::table('branches')->insert([
            [
                'id' => 1,
                'name' => 'Sucursal Centro',
                'number' => '001',
                'telephone' => '021123456',
                'address' => 'Centro',
                'organization_id' => 1,
                'user_created' => 1,
                'user_updated' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'Sucursal Norte',
                'number' => '002',
                'telephone' => '021654321',
                'address' => 'Norte',
                'organization_id' => 2,
                'user_created' => 1,
                'user_updated' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
