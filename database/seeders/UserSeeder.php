<?php
// database/seeders/UserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Buat user admin
        User::create([
            'NIK' => '1234567890123456',
            'nama' => 'Admin PARSBA',
            'ttl' => '1990-01-01',
            'alamat' => 'Kantor Pusat PARSBA, Jakarta',
            'nohp' => '081234567890',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        // Buat user petani contoh
        User::create([
            'NIK' => '2345678901234567',
            'nama' => 'Budi Santoso',
            'ttl' => '1985-05-15',
            'alamat' => 'Desa Sukamakmur, Kec. Pertanian',
            'nohp' => '081234567891',
            'role' => 'petani',
            'password' => Hash::make('password'),
        ]);

        // Buat user petani kedua
        User::create([
            'NIK' => '3456789012345678',
            'nama' => 'Siti Aminah',
            'ttl' => '1992-08-20',
            'alamat' => 'Desa Sumberrejo, Kec. Tani Makmur',
            'nohp' => '081234567892',
            'role' => 'petani',
            'password' => Hash::make('password'),
        ]);
    }
}