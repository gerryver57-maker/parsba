<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiklusTanam extends Model
{
    use HasFactory;

    protected $table = 'siklus_tanam';

    protected $fillable = [
        'user_id',
        'lahan_id',
        'varietas_padi_id',
        'tanggal_tanam',
        'perkiraan_panen',
        'tanggal_panen_aktual',
        'hasil_panen',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_tanam' => 'date',
        'perkiraan_panen' => 'date',
        'tanggal_panen_aktual' => 'date',
    ];

    // ========== RELASI ==========

    /**
     * Siklus tanam dibuat oleh satu petani
     */
    public function petani()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Siklus tanam menggunakan satu lahan
     */
    public function lahan()
    {
        return $this->belongsTo(Lahan::class);
    }

    /**
     * Siklus tanam menanam satu varietas padi
     */
    public function varietasPadi()
    {
        return $this->belongsTo(VarietasPadi::class);
    }

    /**
     * Siklus tanam menghasilkan banyak jadwal otomatis
     */
    public function jadwalOtomatis()
    {
        return $this->hasMany(JadwalOtomatis::class);
    }

    /**
     * Siklus tanam menghasilkan satu data panen
     */
    public function panen()
    {
        return $this->hasOne(Panen::class);
    }

    // ========== METHOD TAMBAHAN ==========

    /**
     * Generate jadwal otomatis berdasarkan fase tumbuh varietas
     */
    public function generateJadwal()
    {
        $faseTumbuh = $this->varietasPadi->faseTumbuh;
        $luasLahan = $this->lahan->luas;

        foreach ($faseTumbuh as $fase) {
            JadwalOtomatis::create([
                'siklus_tanam_id' => $this->id,
                'nama_fase' => $fase->nama_fase,
                'deskripsi_aktivitas' => $fase->deskripsi,
                'tanggal_rekomendasi' => $this->tanggal_tanam->addDays($fase->hari_setelah_tanam),
                'pupuk_id' => $fase->pupuk_id,
                'dosis_dihitung' => $fase->pupuk ? $fase->pupuk->dosis_standar_ha * $luasLahan : null,
                'pestisida_id' => $fase->pestisida_id,
            ]);
        }
    }

    /**
     * Selesaikan siklus tanam (saat panen)
     */
    public function selesaikan($tanggalPanen, $hasil, $kualitas, $catatan = null)
    {
        $this->update([
            'tanggal_panen_aktual' => $tanggalPanen,
            'hasil_panen' => $hasil,
            'status' => 'selesai',
        ]);

        Panen::create([
            'siklus_tanam_id' => $this->id,
            'tanggal_panen' => $tanggalPanen,
            'jumlah' => $hasil,
            'kualitas' => $kualitas,
            'catatan' => $catatan,
        ]);
    }
}