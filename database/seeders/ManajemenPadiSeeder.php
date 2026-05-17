<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Lahan;
use App\Models\VarietasPadi;
use App\Models\Pupuk;
use App\Models\Pestisida;
use App\Models\HamaPenyakit;
use App\Models\FaseTumbuh;
use App\Models\SiklusTanam;
use App\Models\JadwalOtomatis;
use App\Models\Panen;
use App\Models\Lokasi;
use App\Models\PrakiraanCuaca;
use Carbon\Carbon;

class ManajemenPadiSeeder extends Seeder
{
    public function run(): void
    {
        // =============================================
        // 1. USERS
        // =============================================
        $admin = User::create([
            'NIK' => '1234567890123456',
            'nama' => 'Admin SIPADI',
            'ttl' => '1990-01-01',
            'alamat' => 'Nagari Bahagia Padang Gelugua',
            'nohp' => '081234567890',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $petani1 = User::create([
            'NIK' => '1234567890123457',
            'nama' => 'Bapak Ahmad',
            'ttl' => '1980-05-15',
            'alamat' => 'Nagari Bahagia Padang Gelugua',
            'nohp' => '081234567891',
            'role' => 'petani',
            'password' => Hash::make('password'),
        ]);

        $petani2 = User::create([
            'NIK' => '1234567890123458',
            'nama' => 'Bapak Budi',
            'ttl' => '1985-08-20',
            'alamat' => 'Nagari Bahagia Padang Gelugua',
            'nohp' => '081234567892',
            'role' => 'petani',
            'password' => Hash::make('password'),
        ]);

        $petani3 = User::create([
            'NIK' => '1234567890123459',
            'nama' => 'Bapak Candra',
            'ttl' => '1975-12-10',
            'alamat' => 'Nagari Bahagia Padang Gelugua',
            'nohp' => '081234567893',
            'role' => 'petani',
            'password' => Hash::make('password'),
        ]);

        $this->command->info('✅ Users created: 1 Admin + 3 Petani');

        // =============================================
        // 2. LAHAN
        // =============================================
        $lahan1 = Lahan::create([
            'user_id' => $petani1->id,
            'nama' => 'Sawah Belakang Rumah',
            'luas' => 0.5,
            'jenis_irigasi' => 'irigasi',
            'catatan' => 'Dekat sumber air',
        ]);

        $lahan2 = Lahan::create([
            'user_id' => $petani1->id,
            'nama' => 'Sawah Pinggir Kali',
            'luas' => 0.33,
            'jenis_irigasi' => 'irigasi',
            'catatan' => 'Kadang kebanjiran',
        ]);

        $lahan3 = Lahan::create([
            'user_id' => $petani2->id,
            'nama' => 'Sawah Blok A',
            'luas' => 0.67,
            'jenis_irigasi' => 'tadah_hujan',
            'catatan' => null,
        ]);

        $lahan4 = Lahan::create([
            'user_id' => $petani2->id,
            'nama' => 'Sawah Blok B',
            'luas' => 0.25,
            'jenis_irigasi' => 'rawa',
            'catatan' => 'Lahan rawa',
        ]);

        $lahan5 = Lahan::create([
            'user_id' => $petani3->id,
            'nama' => 'Sawah Warisan',
            'luas' => 0.83,
            'jenis_irigasi' => 'irigasi',
            'catatan' => 'Lahan subur',
        ]);

        $this->command->info('✅ Lahan created: 5 lahan');

        // =============================================
        // 3. VARIETAS PADI
        // =============================================
        $ciherang = VarietasPadi::create([
            'nama' => 'Ciherang',
            'umur_panen' => 110,
            'potensi_hasil' => 6.5,
            'deskripsi' => 'Varietas unggul nasional, tahan wereng coklat biotipe 2, rasa nasi pulen.',
            'dibuat_oleh' => $admin->id,
        ]);

        $inpari32 = VarietasPadi::create([
            'nama' => 'Inpari 32',
            'umur_panen' => 120,
            'potensi_hasil' => 8.0,
            'deskripsi' => 'Varietas unggul baru, produktivitas tinggi, tahan penyakit blast.',
            'dibuat_oleh' => $admin->id,
        ]);

        $mekongga = VarietasPadi::create([
            'nama' => 'Mekongga',
            'umur_panen' => 105,
            'potensi_hasil' => 7.0,
            'deskripsi' => 'Cocok untuk lahan kering, umur pendek, hasil melimpah.',
            'dibuat_oleh' => $admin->id,
        ]);

        $this->command->info('✅ Varietas Padi created: Ciherang, Inpari 32, Mekongga');

        // =============================================
        // 4. PUPUK
        // =============================================
        $urea = Pupuk::create([
            'nama' => 'Urea',
            'jenis' => 'Tunggal',
            'dosis_standar_ha' => 200,
            'satuan' => 'Kg',
        ]);

        $npk = Pupuk::create([
            'nama' => 'NPK Phonska',
            'jenis' => 'Majemuk',
            'dosis_standar_ha' => 300,
            'satuan' => 'Kg',
        ]);

        $sp36 = Pupuk::create([
            'nama' => 'SP-36',
            'jenis' => 'Tunggal',
            'dosis_standar_ha' => 100,
            'satuan' => 'Kg',
        ]);

        $organik = Pupuk::create([
            'nama' => 'Pupuk Organik',
            'jenis' => 'Organik',
            'dosis_standar_ha' => 500,
            'satuan' => 'Kg',
        ]);

        $this->command->info('✅ Pupuk created: Urea, NPK, SP-36, Organik');

        // =============================================
        // 5. PESTISIDA
        // =============================================
        $regent = Pestisida::create([
            'nama' => 'Regent 50 SC',
            'hama_target' => 'Wereng',
            'dosis_standar_ha' => 500,
            'satuan' => 'ml',
        ]);

        $dithane = Pestisida::create([
            'nama' => 'Dithane M-45',
            'hama_target' => 'Blast',
            'dosis_standar_ha' => 1000,
            'satuan' => 'gram',
        ]);

        $furadan = Pestisida::create([
            'nama' => 'Furadan 3G',
            'hama_target' => 'Penggerek Batang',
            'dosis_standar_ha' => 20,
            'satuan' => 'Kg',
        ]);

        $this->command->info('✅ Pestisida created: Regent, Dithane, Furadan');

        // =============================================
        // 6. HAMA & PENYAKIT
        // =============================================
        HamaPenyakit::create([
            'nama' => 'Wereng Coklat',
            'jenis' => 'hama',
            'gejala' => 'Daun menguning, tanaman kerdil, terdapat bercak coklat pada batang. Populasi wereng terlihat di pangkal batang.',
            'rekomendasi' => 'Gunakan varietas tahan seperti Ciherang. Semprot dengan Regent 50 SC dosis 500 ml/ha. Jaga jarak tanam tidak terlalu rapat.',
        ]);

        HamaPenyakit::create([
            'nama' => 'Penggerek Batang',
            'jenis' => 'hama',
            'gejala' => 'Pucuk tanaman mati (sundep), malai kosong (beluk). Terdapat lubang pada batang padi.',
            'rekomendasi' => 'Aplikasikan Furadan 3G saat tanam. Semprot insektisida pada fase vegetatif. Musnahkan telur dan larva.',
        ]);

        HamaPenyakit::create([
            'nama' => 'Blast (Pyricularia)',
            'jenis' => 'penyakit',
            'gejala' => 'Bercak belah ketupat pada daun, leher malai membusuk, gabah kosong.',
            'rekomendasi' => 'Gunakan varietas tahan Inpari 32. Semprot Dithane M-45. Hindari pemupukan N berlebihan. Jaga jarak tanam.',
        ]);

        HamaPenyakit::create([
            'nama' => 'Hawar Daun Bakteri',
            'jenis' => 'penyakit',
            'gejala' => 'Daun menguning dari tepi, layu, terdapat lendir bakteri pada batang.',
            'rekomendasi' => 'Gunakan benih sehat. Kurangi pupuk N. Semprot bakterisida. Atur drainase lahan.',
        ]);

        $this->command->info('✅ Hama & Penyakit created');

        // =============================================
        // 7. FASE TUMBUH untuk Ciherang
        // =============================================
        $faseCiherang = [
            ['nama_fase' => 'Persiapan Lahan', 'hst' => -7, 'pupuk' => $organik->id, 'pestisida' => null, 'deskripsi' => 'Bajak lahan, tabur pupuk organik 500 Kg/Ha'],
            ['nama_fase' => 'Tanam', 'hst' => 0, 'pupuk' => null, 'pestisida' => null, 'deskripsi' => 'Tanam bibit umur 21 hari, 2-3 bibit per lubang'],
            ['nama_fase' => 'Pemupukan Dasar', 'hst' => 7, 'pupuk' => $urea->id, 'pestisida' => null, 'deskripsi' => 'Pemupukan urea 30% dari dosis + SP-36 100%'],
            ['nama_fase' => 'Penyemprotan Awal', 'hst' => 14, 'pupuk' => null, 'pestisida' => $furadan->id, 'deskripsi' => 'Tabur Furadan untuk cegah penggerek batang'],
            ['nama_fase' => 'Pemupukan Susulan I', 'hst' => 28, 'pupuk' => $npk->id, 'pestisida' => null, 'deskripsi' => 'Pemupukan NPK 50% dosis, saat anakan aktif'],
            ['nama_fase' => 'Pemupukan Susulan II', 'hst' => 45, 'pupuk' => $urea->id, 'pestisida' => null, 'deskripsi' => 'Pemupukan urea 40% dosis, fase primordia'],
            ['nama_fase' => 'Penyemprotan Hama', 'hst' => 55, 'pupuk' => null, 'pestisida' => $regent->id, 'deskripsi' => 'Cek populasi wereng, semprot jika perlu'],
            ['nama_fase' => 'Pemupukan Akhir', 'hst' => 65, 'pupuk' => $urea->id, 'pestisida' => null, 'deskripsi' => 'Pemupukan urea 30% dosis, fase bunting'],
            ['nama_fase' => 'Penyemprotan Blast', 'hst' => 75, 'pupuk' => null, 'pestisida' => $dithane->id, 'deskripsi' => 'Semprot fungisida untuk cegah blast leher'],
            ['nama_fase' => 'Panen', 'hst' => 110, 'pupuk' => null, 'pestisida' => null, 'deskripsi' => 'Panen saat gabah 80% menguning, gunakan sabit'],
        ];

        foreach ($faseCiherang as $fase) {
            FaseTumbuh::create([
                'varietas_padi_id' => $ciherang->id,
                'nama_fase' => $fase['nama_fase'],
                'hari_setelah_tanam' => $fase['hst'],
                'pupuk_id' => $fase['pupuk'],
                'pestisida_id' => $fase['pestisida'],
                'deskripsi' => $fase['deskripsi'],
            ]);
        }

        $this->command->info('✅ Fase Tumbuh Ciherang created: 10 fase');

        // =============================================
        // 8. SIKLUS TANAM
        // =============================================

        // Siklus 1 - Petani 1 (Aktif)
        $siklus1 = SiklusTanam::create([
            'user_id' => $petani1->id,
            'lahan_id' => $lahan1->id,
            'varietas_padi_id' => $ciherang->id,
            'tanggal_tanam' => Carbon::now()->subDays(60),
            'perkiraan_panen' => Carbon::now()->addDays(50),
            'status' => 'aktif',
            'catatan' => 'Musim tanam pertama, cuaca bagus',
        ]);

        // Siklus 2 - Petani 1 (Selesai)
        $siklus2 = SiklusTanam::create([
            'user_id' => $petani1->id,
            'lahan_id' => $lahan2->id,
            'varietas_padi_id' => $mekongga->id,
            'tanggal_tanam' => Carbon::now()->subDays(160),
            'perkiraan_panen' => Carbon::now()->subDays(55),
            'tanggal_panen_aktual' => Carbon::now()->subDays(55),
            'hasil_panen' => 2.1,
            'status' => 'selesai',
            'catatan' => 'Hasil bagus, panen 2.1 Ton',
        ]);

        // Siklus 3 - Petani 2 (Aktif)
        $siklus3 = SiklusTanam::create([
            'user_id' => $petani2->id,
            'lahan_id' => $lahan3->id,
            'varietas_padi_id' => $inpari32->id,
            'tanggal_tanam' => Carbon::now()->subDays(90),
            'perkiraan_panen' => Carbon::now()->addDays(30),
            'status' => 'aktif',
            'catatan' => 'Gunakan varietas baru',
        ]);

        // Siklus 4 - Petani 3 (Aktif)
        $siklus4 = SiklusTanam::create([
            'user_id' => $petani3->id,
            'lahan_id' => $lahan5->id,
            'varietas_padi_id' => $ciherang->id,
            'tanggal_tanam' => Carbon::now()->subDays(100),
            'perkiraan_panen' => Carbon::now()->addDays(10),
            'status' => 'aktif',
            'catatan' => 'Sudah mendekati panen',
        ]);

        // Siklus 5 - Petani 2 (Selesai)
        $siklus5 = SiklusTanam::create([
            'user_id' => $petani2->id,
            'lahan_id' => $lahan4->id,
            'varietas_padi_id' => $mekongga->id,
            'tanggal_tanam' => Carbon::now()->subDays(180),
            'perkiraan_panen' => Carbon::now()->subDays(75),
            'tanggal_panen_aktual' => Carbon::now()->subDays(75),
            'hasil_panen' => 1.6,
            'status' => 'selesai',
            'catatan' => 'Lahan rawa, hasil lumayan',
        ]);

        $this->command->info('✅ Siklus Tanam created: 5 siklus');

        // =============================================
        // 9. JADWAL OTOMATIS (Generate dari Siklus)
        // =============================================
        foreach ([$siklus1, $siklus3, $siklus4] as $siklus) {
            $siklus->generateJadwal();
        }

        // Konfirmasi beberapa jadwal yang sudah lewat
        $jadwalSelesai = JadwalOtomatis::where('siklus_tanam_id', $siklus1->id)
            ->where('tanggal_rekomendasi', '<', Carbon::now())
            ->limit(5)
            ->get();

        foreach ($jadwalSelesai as $jadwal) {
            $jadwal->update([
                'sudah_dikonfirmasi' => true,
                'tanggal_konfirmasi' => Carbon::now()->subDays(rand(1, 5)),
            ]);
        }

        $this->command->info('✅ Jadwal Otomatis generated untuk 3 siklus aktif');

        // =============================================
        // 10. DATA PANEN
        // =============================================
        Panen::create([
            'siklus_tanam_id' => $siklus2->id,
            'tanggal_panen' => Carbon::now()->subDays(55),
            'jumlah' => 2.1,
            'kualitas' => 'baik',
            'catatan' => 'Hasil bagus, gabah kering',
        ]);

        Panen::create([
            'siklus_tanam_id' => $siklus5->id,
            'tanggal_panen' => Carbon::now()->subDays(75),
            'jumlah' => 1.6,
            'kualitas' => 'sedang',
            'catatan' => 'Ada sedikit hama wereng',
        ]);

        $this->command->info('✅ Data Panen created: 2 panen');

        // =============================================
        // 11. LOKASI (BMKG)
        // =============================================
        $lokasi = Lokasi::create([
            'kode_desa' => '13.08.17.2004',
            'provinsi' => 'Sumatera Barat',
            'kabupaten' => 'Pasaman',
            'kecamatan' => 'Padang Gelugur',
            'desa' => 'Bahagia Padang Gelugua',
            'bujur' => 100.0473243743,
            'lintang' => 0.3972844404,
            'zona_waktu' => 'Asia/Jakarta',
        ]);

        // =============================================
        // 12. DATA CUACA SAMPLE
        // =============================================
        $cuacaSample = [
            ['waktu' => '03:00', 'suhu' => 20, 'kelembapan' => 92, 'hujan' => 0, 'cuaca' => 'Berawan', 'angin' => 0.8, 'arah' => 'SE'],
            ['waktu' => '06:00', 'suhu' => 21, 'kelembapan' => 90, 'hujan' => 0, 'cuaca' => 'Berawan', 'angin' => 2.2, 'arah' => 'NE'],
            ['waktu' => '09:00', 'suhu' => 25, 'kelembapan' => 80, 'hujan' => 0, 'cuaca' => 'Cerah Berawan', 'angin' => 5.8, 'arah' => 'N'],
            ['waktu' => '12:00', 'suhu' => 27, 'kelembapan' => 75, 'hujan' => 0.4, 'cuaca' => 'Cerah Berawan', 'angin' => 5.2, 'arah' => 'N'],
            ['waktu' => '15:00', 'suhu' => 27, 'kelembapan' => 74, 'hujan' => 0.3, 'cuaca' => 'Cerah Berawan', 'angin' => 5.7, 'arah' => 'NE'],
            ['waktu' => '18:00', 'suhu' => 22, 'kelembapan' => 98, 'hujan' => 1.0, 'cuaca' => 'Berawan', 'angin' => 3.9, 'arah' => 'NE'],
            ['waktu' => '21:00', 'suhu' => 21, 'kelembapan' => 99, 'hujan' => 0.2, 'cuaca' => 'Berawan', 'angin' => 0.6, 'arah' => 'N'],
        ];

        for ($i = 0; $i < 3; $i++) {
            foreach ($cuacaSample as $c) {
                PrakiraanCuaca::create([
                    'lokasi_id' => $lokasi->id,
                    'waktu_utc' => Carbon::now()->addDays($i)->setTimeFromTimeString($c['waktu'])->subHours(7),
                    'waktu_lokal' => Carbon::now()->addDays($i)->setTimeFromTimeString($c['waktu']),
                    'tanggal_analisis' => Carbon::now()->format('Y-m-d'),
                    'suhu' => $c['suhu'] + rand(-2, 2),
                    'kelembapan' => $c['kelembapan'] + rand(-5, 5),
                    'curah_hujan' => $c['hujan'],
                    'deskripsi_cuaca' => $c['cuaca'],
                    'arah_angin' => $c['arah'],
                    'kecepatan_angin' => $c['angin'],
                ]);
            }
        }

        $this->command->info('✅ Data Lokasi & Cuaca created');
        
        $this->command->info('');
        $this->command->info('🎉 SEEDER SELESAI!');
        $this->command->info('');
        $this->command->info('📧 Login Admin:');
        $this->command->info('   NIK: 1234567890123456');
        $this->command->info('   Password: password');
        $this->command->info('');
        $this->command->info('👨‍🌾 Login Petani:');
        $this->command->info('   NIK: 1234567890123457 (Ahmad)');
        $this->command->info('   NIK: 1234567890123458 (Budi)');
        $this->command->info('   NIK: 1234567890123459 (Candra)');
        $this->command->info('   Password: password');
    }
}