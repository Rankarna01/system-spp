<?php

namespace Database\Seeders;

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
        // 1. Akun Admin (TU / Bendahara)
        User::create([
            'name'     => 'Administrator TU',
            'email'    => 'admin@spp.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
        ]);

        // 2. Akun Kepala Sekolah
        User::create([
            'name'     => 'Bapak Kepala Sekolah',
            'email'    => 'kepsek@spp.com',
            'password' => Hash::make('password123'),
            'role'     => 'kepsek',
        ]);

        // 3. Akun Siswa (Sebagai contoh/testing awal)
        User::create([
            'name'     => 'Randy (Siswa)',
            'email'    => 'siswa@spp.com',
            'password' => Hash::make('password123'),
            'role'     => 'siswa',
        ]);
    }
}