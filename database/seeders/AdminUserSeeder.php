<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'dev',
            'email' => 'dev@dev.com', // Email untuk login
            'role' => 'admin',
            'password' => Hash::make('aVeryStrongPassword!1'), // Password (Ganti jika mau)
            'email_verified_at' => now(),
        ]);
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@betabadri.com', // Email untuk login
            'role' => 'admin',
            'password' => Hash::make('password123'), // Password (Ganti jika mau)
            'email_verified_at' => now(),
        ]);
    }
}
