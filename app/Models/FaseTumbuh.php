<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaseTumbuh extends Model
{
    use HasFactory;

    protected $table = 'fase_tumbuh';

    protected $fillable = [
        'varietas_padi_id',
        'nama_fase',
        'hari_setelah_tanam',
        'pupuk_id',
        'pestisida_id',
        'deskripsi',
    ];

    // ========== RELASI ==========

    /**
     * Fase tumbuh dimiliki oleh satu varietas padi
     */
    public function varietasPadi()
    {
        return $this->belongsTo(VarietasPadi::class);
    }

    /**
     * Fase tumbuh merekomendasikan satu pupuk (nullable)
     */
    public function pupuk()
    {
        return $this->belongsTo(Pupuk::class);
    }

    /**
     * Fase tumbuh merekomendasikan satu pestisida (nullable)
     */
    public function pestisida()
    {
        return $this->belongsTo(Pestisida::class);
    }
}