<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hama_penyakit', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('jenis', ['hama', 'penyakit']);
            $table->text('gejala')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hama_penyakit');
    }
};