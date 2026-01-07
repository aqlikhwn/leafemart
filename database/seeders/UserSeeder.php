<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@leafemart.com',
            'password' => Hash::make('password'),
            'phone' => '0123456789',
            'role' => 'admin',
        ]);

        // Sample customer
        User::create([
            'name' => 'Customer',
            'email' => 'customer@iium.edu.my',
            'password' => Hash::make('password'),
            'phone' => '0198765432',
            'role' => 'customer',
        ]);
    }
}
