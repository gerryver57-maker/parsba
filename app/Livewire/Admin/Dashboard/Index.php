<?php

namespace App\Livewire\Admin\Dashboard;

use Livewire\Component;
use App\Models\User;
use App\Models\Lahan;
use App\Models\SiklusTanam;
use App\Models\Panen;
use App\Models\JadwalOtomatis;
use App\Models\PrakiraanCuaca;
use Carbon\Carbon;

class Index extends Component
{
    public $totalPetani = 0;
    public $totalLahan = 0;
    public $totalSiklusAktif = 0;
    public $totalPanen = 0;
    public $totalJumlahPanen = 0;
    public $totalAktivitasPending = 0;

    public $cuacaSekarang = null;
    public $grafikLabels = [];
    public $grafikData = [];
    public $aktivitasTerbaru = [];
    public $siklusPanen = [];

    public function mount()
    {
        $this->refreshData();
    }

    private function loadStatistik()
    {
        $this->totalPetani = User::where('role', 'petani')->count();

        $this->totalLahan = Lahan::count();

        $this->totalSiklusAktif = SiklusTanam::where('status', 'aktif')->count();

        $this->totalPanen = Panen::count();

        $this->totalJumlahPanen = Panen::sum('jumlah') ?? 0;

        $this->totalAktivitasPending = JadwalOtomatis::where('sudah_dikonfirmasi', false)->count();
    }

    private function loadCuaca()
    {
        $this->cuacaSekarang = PrakiraanCuaca::where('waktu_lokal', '<=', now()->addHour())
            ->orderByDesc('waktu_lokal')
            ->first()
            ?? PrakiraanCuaca::latest('waktu_lokal')->first();
    }

    private function loadGrafik()
    {
        $tahun = now()->year;

        $this->grafikLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $this->grafikData = [];

        for ($bulan = 1; $bulan <= 12; $bulan++) {
            $this->grafikData[] = Panen::whereYear('tanggal_panen', $tahun)
                ->whereMonth('tanggal_panen', $bulan)
                ->sum('jumlah') ?? 0;
        }
    }

    private function loadAktivitasTerbaru()
    {
        $this->aktivitasTerbaru = JadwalOtomatis::with([
                'siklusTanam.lahan',
                'siklusTanam.petani',
                'pupuk',
                'pestisida'
            ])
            ->where('sudah_dikonfirmasi', true)
            ->latest('tanggal_konfirmasi')
            ->take(5)
            ->get();
    }

    private function loadSiklusPanen()
    {
        $this->siklusPanen = SiklusTanam::with([
                'lahan',
                'petani',
                'varietasPadi'
            ])
            ->where('status', 'aktif')
            ->whereDate('perkiraan_panen', '<=', now()->addDays(14))
            ->orderBy('perkiraan_panen')
            ->take(5)
            ->get();
    }

    public function refreshData()
    {
        $this->loadStatistik();
        $this->loadCuaca();
        $this->loadGrafik();
        $this->loadAktivitasTerbaru();
        $this->loadSiklusPanen();
    }

    public function render()
    {
        return view('livewire.admin.dashboard.index')
            ->layout('layouts.admin');
    }
}