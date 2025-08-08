<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat Superadmin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'role' => 'superadmin',
            'password' => Hash::make('password'), // Ganti dengan password yang aman
        ]);

        // Membuat Maganger
        User::create([
            'name' => 'Maganger User',
            'email' => 'maganger@gmail.com',
            'role' => 'maganger',
            'password' => Hash::make('password'), // Ganti dengan password yang aman
        ]);
    }
}
