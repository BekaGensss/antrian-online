<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Admin
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@antrian.com', 
            'password' => Hash::make('password'), 
            'role' => 'admin', 
        ]);

        // Akun Petugas
        User::create([
            'name' => 'Petugas Loket A',
            'email' => 'petugas@antrian.com', 
            'password' => Hash::make('password'), // <-- PASSWORD WAJIB 'password'
            'role' => 'petugas', 
        ]);
    }
}