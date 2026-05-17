<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class PrakiraanCuaca extends Model
{
    use HasFactory;
    use HasApiTokens;

    protected $table = 'prakiraan_cuaca';

    protected $fillable = [
        'lokasi_id',
        'waktu_utc',
        'waktu_lokal',
        'tanggal_analisis',
        'suhu',
        'kelembapan',
        'curah_hujan',
        'deskripsi_cuaca',
        'Gambar',
        'arah_angin',
        'kecepatan_angin',
    ];

    protected $casts = [
        'waktu_utc' => 'datetime',
        'waktu_lokal' => 'datetime',
        'tanggal_analisis' => 'date',
    ];

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class);
    }
}