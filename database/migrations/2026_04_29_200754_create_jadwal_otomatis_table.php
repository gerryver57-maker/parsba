<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_otomatis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siklus_tanam_id')->constrained('siklus_tanam')->onDelete('cascade');
            $table->string('nama_fase');
            $table->text('deskripsi_aktivitas')->nullable();
            $table->date('tanggal_rekomendasi');
            $table->foreignId('pupuk_id')->nullable()->constrained('pupuk')->onDelete('set null');
            $table->decimal('dosis_dihitung', 8, 2)->nullable()->comment('Dosis dihitung berdasarkan luas lahan');
            $table->foreignId('pestisida_id')->nullable()->constrained('pestisida')->onDelete('set null');
            $table->boolean('sudah_dikonfirmasi')->default(false);
            $table->dateTime('tanggal_konfirmasi')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_otomatis');
    }
};