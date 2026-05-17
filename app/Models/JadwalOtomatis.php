<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalOtomatis extends Model
{
    use HasFactory;

    protected $table = 'jadwal_otomatis';

    protected $fillable = [
        'siklus_tanam_id',
        'nama_fase',
        'deskripsi_aktivitas',
        'tanggal_rekomendasi',
        'pupuk_id',
        'dosis_dihitung',
        'pestisida_id',
        'sudah_dikonfirmasi',
        'tanggal_konfirmasi',
        'catatan',
    ];

    protected $casts = [
        'tanggal_rekomendasi' => 'date',
        'tanggal_konfirmasi' => 'datetime',
        'sudah_dikonfirmasi' => 'boolean',
    ];

    // ========== RELASI ==========

    /**
     * Jadwal otomatis dimiliki oleh satu siklus tanam
     */
    public function siklusTanam()
    {
        return $this->belongsTo(SiklusTanam::class);
    }

    /**
     * Jadwal otomatis merekomendasikan satu pupuk (nullable)
     */
    public function pupuk()
    {
        return $this->belongsTo(Pupuk::class);
    }

    /**
     * Jadwal otomatis merekomendasikan satu pestisida (nullable)
     */
    public function pestisida()
    {
        return $this->belongsTo(Pestisida::class);
    }

    // ========== METHOD TAMBAHAN ==========

    /**
     * Konfirmasi bahwa aktivitas sudah dilakukan petani
     */
    public function konfirmasi($catatan = null)
    {
        $this->update([
            'sudah_dikonfirmasi' => true,
            'tanggal_konfirmasi' => now(),
            'catatan' => $catatan,
        ]);
    }
}