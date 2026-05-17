<?php
// database/seeders/LahanSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LahanSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('lahans')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Lahan milik Budi Santoso (user_id = 2)
        DB::table('lahans')->insert([
            'user_id' => 2,
            'nama_lahan' => 'Sawah Makmur 1',
            'lokasi' => 'Desa Sukamakmur, Kec. Pertanian',
            'luas_hektar' => 2.5,
            'jenis_tanah' => 'Aluvial',
            'ketinggian' => 50,
            'koordinat' => '-6.2088, 106.8456',
            'status' => 'aktif',
            'deskripsi' => 'Lahan sawah irigasi teknis',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('lahans')->insert([
            'user_id' => 2,
            'nama_lahan' => 'Lahan Kering 2',
            'lokasi' => 'Desa Sukamakmur, Kec. Pertanian',
            'luas_hektar' => 1.8,
            'jenis_tanah' => 'Andosol',
            'ketinggian' => 75,
            'koordinat' => '-6.2100, 106.8500',
            'status' => 'tanam',
            'deskripsi' => 'Lahan tadah hujan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Lahan milik Siti Aminah (user_id = 3)
        DB::table('lahans')->insert([
            'user_id' => 3,
            'nama_lahan' => 'Lahan Berkah',
            'lokasi' => 'Desa Sumberrejo, Kec. Tani Makmur',
            'luas_hektar' => 3.2,
            'jenis_tanah' => 'Latosol',
            'ketinggian' => 100,
            'koordinat' => '-6.2200, 106.8600',
            'status' => 'panen',
            'deskripsi' => 'Lahan sawah subur',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}