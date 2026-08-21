<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        // Guru
        $guru = [
            ['name' => 'Ahmad Fauzi', 'email' => 'ahmad.fauzi@guru.com', 'kelas' => 'X PPLG'],
            ['name' => 'Budi Raharjo', 'email' => 'budi.raharjo@guru.com', 'kelas' => 'X PPLG'],
            ['name' => 'Citra Lestari', 'email' => 'citra.lestari@guru.com', 'kelas' => 'X PPLG'],
            ['name' => 'Dewi Anggraini', 'email' => 'dewi.anggraini@guru.com', 'kelas' => 'X PPLG'],
            ['name' => 'Eko Prasetyo', 'email' => 'eko.prasetyo@guru.com', 'kelas' => 'X PPLG'],
            ['name' => 'Fajar Nugroho', 'email' => 'fajar.nugroho@guru.com', 'kelas' => 'XI TJKT'],
            ['name' => 'Gita Permata', 'email' => 'gita.permata@guru.com', 'kelas' => 'XI TJKT'],
            ['name' => 'Hendra Gunawan', 'email' => 'hendra.gunawan@guru.com', 'kelas' => 'XI TJKT'],
            ['name' => 'Indah Sari', 'email' => 'indah.sari@guru.com', 'kelas' => 'XI TJKT'],
            ['name' => 'Joko Susilo', 'email' => 'joko.susilo@guru.com', 'kelas' => 'XI TJKT'],
            ['name' => 'Kartika Dewi', 'email' => 'kartika.dewi@guru.com', 'kelas' => 'XII AKL'],
            ['name' => 'Lukman Hakim', 'email' => 'lukman.hakim@guru.com', 'kelas' => 'XII AKL'],
            ['name' => 'Maya Sari', 'email' => 'maya.sari@guru.com', 'kelas' => 'XII AKL'],
            ['name' => 'Nugroho Wicaksono', 'email' => 'nugroho.wicaksono@guru.com', 'kelas' => 'XII AKL'],
            ['name' => 'Oktavia Putri', 'email' => 'oktavia.putri@guru.com', 'kelas' => 'XII AKL'],
        ];

        foreach ($guru as $g) {
            User::create([
                'name' => $g['name'],
                'email' => $g['email'],
                'password' => Hash::make('password'),
                'role' => 'guru',
                'kelas' => $g['kelas'],
            ]);
        }
    }
}
