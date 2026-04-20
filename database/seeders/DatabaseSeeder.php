<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin TU',
            'email' => 'admin@spp.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Bapak Kepala Sekolah',
            'email' => 'kepsek@spp.com',
            'password' => Hash::make('password'),
            'role' => 'kepsek',
        ]);

        User::create([
            'name' => 'Siswa Example',
            'email' => 'siswa@spp.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);
    }
}
