<?php

namespace App\Livewire\Petani\Dashboard;

use Livewire\Component;
use App\Models\Lahan;
use App\Models\SiklusTanam;
use App\Models\Panen;
use App\Models\JadwalOtomatis;
use App\Models\PrakiraanCuaca;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    // Statistik
    public $totalLahan;
    public $totalSiklusAktif;
    public $totalPanen;
    public $totalJumlahPanen;
    public $totalAktivitasPending;

    // Cuaca
    public $cuacaSekarang;

    // Jadwal hari ini
    public $jadwalHariIni = [];

    // Siklus mendekati panen
    public $siklusPanen = [];

    // Grafik panen sederhana
    public $grafikLabels = [];
    public $grafikData = [];

    public function mount()
    {
        $this->loadStatistik();
        $this->loadCuaca();
        $this->loadJadwalHariIni();
        $this->loadSiklusPanen();
        $this->loadGrafik();
    }

    private function loadStatistik()
    {
        $userId = Auth::id();
        
        $this->totalLahan = Lahan::where('user_id', $userId)->count();
        $this->totalSiklusAktif = SiklusTanam::where('user_id', $userId)->where('status', 'aktif')->count();
        $this->totalPanen = Panen::whereHas('siklusTanam', fn($q) => $q->where('user_id', $userId))->count();
        $this->totalJumlahPanen = Panen::whereHas('siklusTanam', fn($q) => $q->where('user_id', $userId))->sum('jumlah');
        $this->totalAktivitasPending = JadwalOtomatis::whereHas('siklusTanam', fn($q) => $q->where('user_id', $userId))
            ->where('sudah_dikonfirmasi', false)
            ->count();
    }

    private function loadCuaca()
    {
        $this->cuacaSekarang = PrakiraanCuaca::where('waktu_lokal', '<=', Carbon::now()->addHour())
            ->orderBy('waktu_lokal', 'desc')
            ->first();

        if (!$this->cuacaSekarang) {
            $this->cuacaSekarang = PrakiraanCuaca::latest('waktu_lokal')->first();
        }
    }

    private function loadJadwalHariIni()
    {
        $userId = Auth::id();
        
        $this->jadwalHariIni = JadwalOtomatis::with(['siklusTanam.lahan', 'pupuk', 'pestisida'])
            ->whereHas('siklusTanam', fn($q) => $q->where('user_id', $userId))
            ->whereDate('tanggal_rekomendasi', Carbon::now())
            ->orderBy('tanggal_rekomendasi')
            ->get();
    }

    private function loadSiklusPanen()
    {
        $userId = Auth::id();
        
        $this->siklusPanen = SiklusTanam::with(['lahan', 'varietasPadi'])
            ->where('user_id', $userId)
            ->where('status', 'aktif')
            ->orderBy('perkiraan_panen')
            ->get();
    }

    private function loadGrafik()
    {
        $userId = Auth::id();
        $tahun = Carbon::now()->year;
        
        $this->grafikLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $this->grafikData = [];

        for ($i = 1; $i <= 12; $i++) {
            $this->grafikData[] = Panen::whereHas('siklusTanam', fn($q) => $q->where('user_id', $userId))
                ->whereYear('tanggal_panen', $tahun)
                ->whereMonth('tanggal_panen', $i)
                ->sum('jumlah');
        }
    }

    public function render()
    {
        return view('livewire.petani.dashboard.index')->layout('layouts.petani');
    }
}