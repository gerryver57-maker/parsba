<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pestisida extends Model
{
    use HasFactory;

    protected $table = 'pestisida';

    protected $fillable = [
        'nama',
        'hama_target',
        'dosis_standar_ha',
        'satuan',
    ];

    // ========== RELASI ==========

    /**
     * Pestisida direkomendasikan di banyak fase tumbuh
     */
    public function faseTumbuh()
    {
        return $this->hasMany(FaseTumbuh::class);
    }

    /**
     * Pestisida muncul di banyak jadwal otomatis
     */
    public function jadwalOtomatis()
    {
        return $this->hasMany(JadwalOtomatis::class);
    }
}