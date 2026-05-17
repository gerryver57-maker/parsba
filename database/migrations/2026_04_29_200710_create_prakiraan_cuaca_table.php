<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prakiraan_cuaca', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lokasi_id')->constrained('lokasi')->onDelete('cascade');
            $table->dateTime('waktu_utc');
            $table->dateTime('waktu_lokal');
            $table->float('suhu')->nullable()->comment('Suhu dalam Celcius');
            $table->float('kelembapan')->nullable()->comment('Kelembapan dalam Persen');
            $table->float('curah_hujan')->nullable()->comment('Curah hujan dalam mm');
            $table->string('deskripsi_cuaca')->nullable();
            $table->string('arah_angin', 10)->nullable(); // Diperbesar dari 5 ke 10
            $table->float('kecepatan_angin')->nullable();
            $table->String('Gambar',255)->nullable();
            $table->timestamps();
        });

        // Tambahkan unique constraint setelah tabel dibuat (nama pendek)
        Schema::table('prakiraan_cuaca', function (Blueprint $table) {
            $table->unique(
                ['lokasi_id', 'waktu_lokal'], 
                'cuaca_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prakiraan_cuaca');
    }
};