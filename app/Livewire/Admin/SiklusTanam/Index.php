<?php

namespace App\Livewire\Admin\SiklusTanam;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SiklusTanam;
use App\Models\JadwalOtomatis;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public $cari = '';
    public $jumlahData = 10;
    public $kolomUrut = 'created_at';
    public $arahUrut = 'desc';
    public $filterStatus = '';
    public $tampilDetail = false;
    public $detailSiklus;
    public $detailJadwal = [];

    protected $listeners = [
        'refreshComponent' => '$refresh',
    ];

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function urutkan($kolom)
    {
        if ($this->kolomUrut === $kolom) {
            $this->arahUrut = $this->arahUrut === 'asc' ? 'desc' : 'asc';
        } else {
            $this->kolomUrut = $kolom;
            $this->arahUrut = 'asc';
        }
    }

    public function lihatDetail($id)
    {
        $this->detailSiklus = SiklusTanam::with(['petani', 'lahan', 'varietasPadi', 'panen'])->findOrFail($id);
        $this->detailJadwal = JadwalOtomatis::with(['pupuk', 'pestisida'])
            ->where('siklus_tanam_id', $id)
            ->orderBy('tanggal_rekomendasi')
            ->get();
        $this->tampilDetail = true;
    }

    public function tutupDetail()
    {
        $this->tampilDetail = false;
        $this->detailSiklus = null;
        $this->detailJadwal = [];
    }

    public function render()
    {
        $dataSiklus = SiklusTanam::query()
            ->with(['petani', 'lahan', 'varietasPadi', 'panen'])
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->when($this->cari, fn($q) => $q->whereHas('petani', fn($p) => $p->where('nama', 'like', '%' . $this->cari . '%'))
                ->orWhereHas('lahan', fn($l) => $l->where('nama', 'like', '%' . $this->cari . '%'))
                ->orWhereHas('varietasPadi', fn($v) => $v->where('nama', 'like', '%' . $this->cari . '%')))
            ->orderBy($this->kolomUrut, $this->arahUrut)
            ->paginate($this->jumlahData);

        // Statistik
        $totalAktif = SiklusTanam::where('status', 'aktif')->count();
        $totalSelesai = SiklusTanam::where('status', 'selesai')->count();
        $totalHasilPanen = SiklusTanam::where('status', 'selesai')->sum('hasil_panen');

        return view('livewire.admin.siklus-tanam.index', [
            'dataSiklus' => $dataSiklus,
            'totalAktif' => $totalAktif,
            'totalSelesai' => $totalSelesai,
            'totalHasilPanen' => $totalHasilPanen,
        ])->layout('layouts.admin');
    }
}