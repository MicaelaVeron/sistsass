<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usuario administrador
         DB::table('users')->insert([
            'id' => 1,
            'name' => 'admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
            'remember_token' => Str::random(10),
            'status' => 'active',
            'user_created' => 1,
            'user_updated' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Usuario normal
        DB::table('users')->insert([
            'id' => 2,
            'name' => 'user',
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
            'remember_token' => Str::random(10),
            'status' => 'active',
            'user_created' => 1,
            'user_updated' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
