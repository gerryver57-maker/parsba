<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('varietas_padi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('umur_panen')->comment('Umur panen dalam hari');
            $table->decimal('potensi_hasil', 8, 2)->nullable()->comment('Potensi hasil Ton/Ha');
            $table->text('deskripsi')->nullable();
            $table->foreignId('dibuat_oleh')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('varietas_padi');
    }
};