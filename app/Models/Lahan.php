<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lahan extends Model
{
    use HasFactory;

    protected $table = 'lahan';

    protected $fillable = [
        'user_id',
        'nama',
        'luas',
        'jenis_irigasi',
        'catatan',
    ];

    // ========== RELASI ==========

    /**
     * Lahan dimiliki oleh satu petani
     */
    public function petani()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Lahan digunakan di banyak siklus tanam
     */
    public function siklusTanam()
    {
        return $this->hasMany(SiklusTanam::class);
    }
}