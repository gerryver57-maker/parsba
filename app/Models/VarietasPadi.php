<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VarietasPadi extends Model
{
    use HasFactory;

    protected $table = 'varietas_padi';

    protected $fillable = [
        'nama',
        'umur_panen',
        'potensi_hasil',
        'deskripsi',
        'dibuat_oleh',
    ];

    // ========== RELASI ==========

    /**
     * Varietas padi diinput oleh admin
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Varietas padi memiliki banyak fase tumbuh
     */
    public function faseTumbuh()
    {
        return $this->hasMany(FaseTumbuh::class);
    }

    /**
     * Varietas padi ditanam di banyak siklus tanam
     */
    public function siklusTanam()
    {
        return $this->hasMany(SiklusTanam::class);
    }
}