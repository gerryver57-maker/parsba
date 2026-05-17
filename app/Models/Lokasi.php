<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    use HasFactory;

    protected $table = 'lokasi';

    protected $fillable = [
        'kode_desa',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'desa',
        'bujur',
        'lintang',
        'zona_waktu',
    ];

    // ========== RELASI ==========
    public function prakiraanCuaca()
    {
        return $this->hasMany(PrakiraanCuaca::class);
    }
}