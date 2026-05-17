<?php
// database/seeders/PadiSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PadiSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('padis')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Padi milik Budi Santoso
        DB::table('padis')->insert([
            'user_id' => 2,
            'lahan_id' => 1,
            'varietas' => 'IR64',
            'jenis' => 'padi_sawah',
            'luas_tanam' => 2.5,
            'tanggal_tanam' => '2026-01-15',
            'perkiraan_panen' => '2026-05-15',
            'musim_tanam' => 'hujan',
            'usia_hari' => 104,
            'status' => 'panen',
            'catatan' => 'Tanam perdana tahun ini',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('padis')->insert([
            'user_id' => 2,
            'lahan_id' => 2,
            'varietas' => 'Ciherang',
            'jenis' => 'padi_gogo',
            'luas_tanam' => 1.8,
            'tanggal_tanam' => '2026-03-10',
            'perkiraan_panen' => '2026-07-10',
            'musim_tanam' => 'kemarau',
            'usia_hari' => 50,
            'status' => 'aktif',
            'catatan' => 'Menggunakan pupuk organik',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Padi milik Siti Aminah
        DB::table('padis')->insert([
            'user_id' => 3,
            'lahan_id' => 3,
            'varietas' => 'Inpari 32',
            'jenis' => 'padi_hibrida',
            'luas_tanam' => 3.2,
            'tanggal_tanam' => '2026-02-20',
            'perkiraan_panen' => '2026-06-20',
            'musim_tanam' => 'hujan',
            'usia_hari' => 68,
            'status' => 'aktif',
            'catatan' => 'Varietas unggul tahan hama',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}