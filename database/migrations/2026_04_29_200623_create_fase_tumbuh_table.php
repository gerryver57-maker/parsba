<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fase_tumbuh', function (Blueprint $table) {
            $table->id();
            $table->foreignId('varietas_padi_id')->constrained('varietas_padi')->onDelete('cascade');
            $table->string('nama_fase');
            $table->integer('hari_setelah_tanam')->comment('HST');
            $table->foreignId('pupuk_id')->nullable()->constrained('pupuk')->onDelete('set null');
            $table->foreignId('pestisida_id')->nullable()->constrained('pestisida')->onDelete('set null');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fase_tumbuh');
    }
};