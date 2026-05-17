<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siklus_tanam_id')->unique()->constrained('siklus_tanam')->onDelete('cascade');
            $table->date('tanggal_panen');
            $table->decimal('jumlah', 8, 2)->comment('Jumlah panen dalam Ton');
            $table->enum('kualitas', ['baik', 'sedang', 'buruk'])->default('baik');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panen');
    }
};