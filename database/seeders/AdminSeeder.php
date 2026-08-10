<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Buat admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@absensi.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}