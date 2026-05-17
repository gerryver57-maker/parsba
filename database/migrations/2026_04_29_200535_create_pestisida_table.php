<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pestisida', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('hama_target')->nullable();
            $table->decimal('dosis_standar_ha', 8, 2)->nullable();
            $table->string('satuan')->default('ml');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pestisida');
    }
};