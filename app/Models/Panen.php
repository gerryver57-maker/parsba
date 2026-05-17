<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Panen extends Model
{
    use HasFactory;

    protected $table = 'panen';

    protected $fillable = [
        'siklus_tanam_id',
        'tanggal_panen',
        'jumlah',
        'kualitas',
        'catatan',
    ];

    protected $casts = [
        'tanggal_panen' => 'date',
    ];

    // ========== RELASI ==========

    /**
     * Panen dimiliki oleh satu siklus tanam
     */
    public function siklusTanam()
    {
        return $this->belongsTo(SiklusTanam::class);
    }
}