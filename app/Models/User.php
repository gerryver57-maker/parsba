<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use HasApiTokens, Notifiable;

    protected $table = 'users';
    
    protected $primaryKey = 'id';
    
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'NIK',
        'nama',
        'ttl',
        'alamat',
        'nohp',
        'role',
        'password',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'ttl' => 'date',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========== SCOPES ==========
    
    /**
     * Scope untuk filter petani
     */
    public function scopePetani($query)
    {
        return $query->where('role', 'petani');
    }

    /**
     * Scope untuk filter admin
     */
    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope untuk user yang sudah pernah login
     */
    public function scopeSudahLogin($query)
    {
        return $query->whereNotNull('last_login_at');
    }

    /**
     * Scope untuk user yang belum pernah login
     */
    public function scopeBelumLogin($query)
    {
        return $query->whereNull('last_login_at');
    }

    /**
     * Scope untuk user yang aktif login dalam 7 hari terakhir (WIB)
     */
    public function scopeAktif($query)
    {
        $now = Carbon::now('Asia/Jakarta');
        return $query->where('last_login_at', '>=', $now->subDays(7));
    }

    /**
     * Scope untuk user yang tidak aktif (lebih dari 30 hari tidak login)
     */
    public function scopeTidakAktif($query)
    {
        $now = Carbon::now('Asia/Jakarta');
        return $query->where('last_login_at', '<', $now->subDays(30))
                    ->orWhereNull('last_login_at');
    }

    /**
     * Scope untuk user yang login hari ini (WIB)
     */
    public function scopeLoginHariIni($query)
    {
        $now = Carbon::now('Asia/Jakarta');
        return $query->whereDate('last_login_at', $now->toDateString());
    }

    /**
     * Scope untuk user yang login bulan ini (WIB)
     */
    public function scopeLoginBulanIni($query)
    {
        $now = Carbon::now('Asia/Jakarta');
        return $query->whereYear('last_login_at', $now->year)
                     ->whereMonth('last_login_at', $now->month);
    }

    // ========== ACCESSORS ==========
    
    /**
     * Get the email for password reset (required by Laravel)
     */
    public function getEmailAttribute()
    {
        return $this->NIK . '@parsba.local';
    }

    /**
     * Format last login menjadi d/m/Y H:i:s (WIB)
     */
    public function getLastLoginFormattedAttribute()
    {
        if ($this->last_login_at) {
            Carbon::setLocale('id');
            $carbon = Carbon::parse($this->last_login_at)->setTimezone('Asia/Jakarta');
            return $carbon->format('d/m/Y H:i:s') . ' WIB';
        }
        return 'Belum pernah login';
    }

    /**
     * Format last login dengan hari (WIB)
     * Contoh: Senin, 28 April 2026 14:30:00 WIB
     */
    public function getLastLoginFullAttribute()
    {
        if ($this->last_login_at) {
            Carbon::setLocale('id');
            $carbon = Carbon::parse($this->last_login_at)->setTimezone('Asia/Jakarta');
            return $carbon->translatedFormat('l, d F Y H:i:s') . ' WIB';
        }
        return 'Belum pernah login';
    }

    /**
     * Format last login dengan hari singkat (WIB)
     * Contoh: Sen, 28/04/2026 14:30 WIB
     */
    public function getLastLoginShortAttribute()
    {
        if ($this->last_login_at) {
            Carbon::setLocale('id');
            $carbon = Carbon::parse($this->last_login_at)->setTimezone('Asia/Jakarta');
            return $carbon->translatedFormat('D, d/m/Y H:i') . ' WIB';
        }
        return '-';
    }

    /**
     * Format last login menjadi human readable dengan zona WIB
     * Contoh: 2 jam yang lalu, kemarin, 3 hari yang lalu, dll
     */
    public function getLastLoginHumanAttribute()
    {
        if ($this->last_login_at) {
            Carbon::setLocale('id');
            $carbon = Carbon::parse($this->last_login_at)->setTimezone('Asia/Jakarta');
            return $carbon->diffForHumans();
        }
        return 'Belum pernah login';
    }

    /**
     * Get hari dan jam login (WIB)
     * Contoh: "Hari ini, 14:30 WIB" atau "Kemarin, 09:15 WIB"
     */
    public function getLastLoginDayTimeAttribute()
    {
        if (!$this->last_login_at) {
            return '-';
        }
        
        Carbon::setLocale('id');
        $carbon = Carbon::parse($this->last_login_at)->setTimezone('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');
        
        if ($carbon->isToday()) {
            return 'Hari ini, ' . $carbon->format('H:i') . ' WIB';
        } elseif ($carbon->isYesterday()) {
            return 'Kemarin, ' . $carbon->format('H:i') . ' WIB';
        } else {
            return $carbon->translatedFormat('l, H:i') . ' WIB';
        }
    }

    /**
     * Get status login badge dengan warna (WIB)
     */
    public function getLoginStatusBadgeAttribute()
    {
        if (!$this->last_login_at) {
            return '<span class="badge bg-secondary rounded-pill px-3 py-2">
                        <i class="ti ti-eye-off me-1"></i> Belum Login
                    </span>';
        }
        
        Carbon::setLocale('id');
        $carbon = Carbon::parse($this->last_login_at)->setTimezone('Asia/Jakarta');
        $days = $carbon->diffInDays(Carbon::now('Asia/Jakarta'));
        
        if ($days == 0) {
            return '<span class="badge bg-success rounded-pill px-3 py-2">
                        <i class="ti ti-circle-check me-1"></i> Login Hari Ini
                    </span>';
        } elseif ($days == 1) {
            return '<span class="badge bg-info rounded-pill px-3 py-2">
                        <i class="ti ti-clock me-1"></i> Kemarin
                    </span>';
        } elseif ($days <= 7) {
            return '<span class="badge bg-warning rounded-pill px-3 py-2">
                        <i class="ti ti-clock me-1"></i> ' . $days . ' hari lalu
                    </span>';
        } else {
            return '<span class="badge bg-danger rounded-pill px-3 py-2">
                        <i class="ti ti-circle-off me-1"></i> ' . $days . ' hari lalu
                    </span>';
        }
    }

    /**
     * Get waktu login dalam format jam saja (WIB)
     */
    public function getLastLoginTimeAttribute()
    {
        if ($this->last_login_at) {
            Carbon::setLocale('id');
            $carbon = Carbon::parse($this->last_login_at)->setTimezone('Asia/Jakarta');
            return $carbon->format('H:i:s') . ' WIB';
        }
        return '-';
    }

    /**
     * Get tanggal login (WIB)
     */
    public function getLastLoginDateAttribute()
    {
        if ($this->last_login_at) {
            Carbon::setLocale('id');
            $carbon = Carbon::parse($this->last_login_at)->setTimezone('Asia/Jakarta');
            return $carbon->translatedFormat('d F Y');
        }
        return '-';
    }

    /**
     * Get usia dari tanggal lahir (tahun)
     */
    public function getUsiaAttribute()
    {
        if ($this->ttl) {
            return $this->ttl->age . ' tahun';
        }
        return '-';
    }

    /**
     * Get formatted tanggal lahir
     */
    public function getTtlFormattedAttribute()
    {
        if ($this->ttl) {
            Carbon::setLocale('id');
            return Carbon::parse($this->ttl)->translatedFormat('d F Y');
        }
        return '-';
    }

    /**
     * Get avatar URL from UI Avatars
     */
    public function getAvatarAttribute()
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->nama) . '&background=2b5e2b&color=fff&size=100';
    }

    /**
     * Get created at formatted (WIB)
     */
    public function getCreatedAtFormattedAttribute()
    {
        if ($this->created_at) {
            Carbon::setLocale('id');
            $carbon = Carbon::parse($this->created_at)->setTimezone('Asia/Jakarta');
            return $carbon->translatedFormat('d F Y H:i:s') . ' WIB';
        }
        return '-';
    }

    // ========== MUTATORS ==========
    
    /**
     * Update last login information dengan waktu WIB
     */
    public function updateLastLogin()
    {
        $now = Carbon::now('Asia/Jakarta');
        
        $this->update([
            'last_login_at' => $now,
            'last_login_ip' => request()->ip(),
        ]);
    }

    /**
     * Set NIK to always be unique and formatted
     */
    public function setNIKAttribute($value)
    {
        $this->attributes['NIK'] = preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Set nama to uppercase first letter each word
     */
    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = ucwords(strtolower($value));
    }

    // ========== HELPER METHODS ==========
    
    /**
     * Cek apakah user adalah admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah petani
     */
    public function isPetani()
    {
        return $this->role === 'petani';
    }

    /**
     * Get total lahan milik petani
     */
    public function getTotalLahanAttribute()
    {
        return $this->lahans()->count();
    }

    /**
     * Get total luas lahan (hektar)
     */
    public function getTotalLuasLahanAttribute()
    {
        return $this->lahans()->sum('luas_hektar');
    }

    /**
     * Get total produksi padi (ton)
     */
    public function getTotalProduksiAttribute()
    {
        return $this->produksi()->sum('jumlah_ton');
    }

    /**
     * Check if user is online (login within last 5 minutes) dengan WIB
     */
    public function getIsOnlineAttribute()
    {
        if (!$this->last_login_at) {
            return false;
        }
        
        $carbon = Carbon::parse($this->last_login_at)->setTimezone('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');
        
        return $carbon->diffInMinutes($now) <= 5;
    }

    /**
     * Get user activity status text (WIB)
     */
    public function getActivityStatusAttribute()
    {
        if (!$this->last_login_at) {
            return 'Tidak Pernah Login';
        }
        
        Carbon::setLocale('id');
        $carbon = Carbon::parse($this->last_login_at)->setTimezone('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');
        $days = $carbon->diffInDays($now);
        
        if ($days == 0) {
            return 'Login Hari Ini';
        } elseif ($days == 1) {
            return 'Kemarin';
        } elseif ($days <= 7) {
            return $days . ' hari yang lalu';
        } elseif ($days <= 30) {
            return floor($days / 7) . ' minggu yang lalu';
        } else {
            return floor($days / 30) . ' bulan yang lalu';
        }
    }

    /**
     * Get last login for display (recommended for tables)
     */
    public function getLastLoginDisplayAttribute()
    {
        if (!$this->last_login_at) {
            return [
                'icon' => 'ti ti-eye-off text-secondary',
                'text' => 'Belum pernah login',
                'badge' => 'secondary'
            ];
        }
        
        $carbon = Carbon::parse($this->last_login_at)->setTimezone('Asia/Jakarta');
        $now = Carbon::now('Asia/Jakarta');
        $days = $carbon->diffInDays($now);
        
        if ($days == 0) {
            return [
                'icon' => 'ti ti-circle-check text-success',
                'text' => $carbon->format('H:i:s') . ' WIB',
                'badge' => 'success',
                'full' => $carbon->translatedFormat('l, d F Y H:i:s') . ' WIB'
            ];
        } elseif ($days == 1) {
            return [
                'icon' => 'ti ti-clock text-warning',
                'text' => 'Kemarin, ' . $carbon->format('H:i') . ' WIB',
                'badge' => 'warning',
                'full' => $carbon->translatedFormat('l, d F Y H:i:s') . ' WIB'
            ];
        } elseif ($days <= 7) {
            return [
                'icon' => 'ti ti-clock text-info',
                'text' => $days . ' hari lalu, ' . $carbon->format('H:i') . ' WIB',
                'badge' => 'info',
                'full' => $carbon->translatedFormat('l, d F Y H:i:s') . ' WIB'
            ];
        } else {
            return [
                'icon' => 'ti ti-calendar text-muted',
                'text' => $carbon->translatedFormat('d M Y, H:i') . ' WIB',
                'badge' => 'secondary',
                'full' => $carbon->translatedFormat('l, d F Y H:i:s') . ' WIB'
            ];
        }
    }

    // ========== RELATIONS ==========
    
    /**
     * User (petani) memiliki banyak lahan
     */
    public function lahan()
    {
        return $this->hasMany(Lahan::class);
    }

    /**
     * User (petani) memiliki banyak siklus tanam
     */
    public function siklusTanam()
    {
        return $this->hasMany(SiklusTanam::class);
    }

    /**
     * User (admin) menginput banyak varietas padi
     */
    public function varietasPadi()
    {
        return $this->hasMany(VarietasPadi::class, 'dibuat_oleh');
    }
}