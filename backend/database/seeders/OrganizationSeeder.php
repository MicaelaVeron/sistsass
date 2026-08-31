<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('organizations')->insert([
            'id' => 1,
            'name' => 'organization1',
            'ruc' => '5025116-3',
            'telephone' => '123456789',
            'logo' => null,
            'address' => 'Asunción',
            'email' => 'organization1@gmail.com',
            'user_created' => 1,
            'user_updated' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('organizations')->insert([
            'id' => 2,
            'name' => 'organization2',
            'ruc' => '8000123-4',
            'telephone' => '987654321',
            'logo' => null,
            'address' => 'San Lorenzo',
            'email' => 'organization2@gmail.com',
            'user_created' => 1,
            'user_updated' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
