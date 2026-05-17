<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siklus_tanam', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('Petani');
            $table->foreignId('lahan_id')->constrained('lahan')->onDelete('cascade');
            $table->foreignId('varietas_padi_id')->constrained('varietas_padi')->onDelete('cascade');
            $table->date('tanggal_tanam');
            $table->date('perkiraan_panen');
            $table->date('tanggal_panen_aktual')->nullable();
            $table->decimal('hasil_panen', 8, 2)->nullable()->comment('Ton');
            $table->enum('status', ['aktif', 'selesai'])->default('aktif');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siklus_tanam');
    }
};