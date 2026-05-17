<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pupuk extends Model
{
    use HasFactory;

    protected $table = 'pupuk';

    protected $fillable = [
        'nama',
        'jenis',
        'dosis_standar_ha',
        'satuan',
    ];

    // ========== RELASI ==========

    /**
     * Pupuk direkomendasikan di banyak fase tumbuh
     */
    public function faseTumbuh()
    {
        return $this->hasMany(FaseTumbuh::class);
    }

    /**
     * Pupuk muncul di banyak jadwal otomatis
     */
    public function jadwalOtomatis()
    {
        return $this->hasMany(JadwalOtomatis::class);
    }
}