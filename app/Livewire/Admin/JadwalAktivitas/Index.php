<?php

namespace App\Livewire\Admin\JadwalAktivitas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JadwalOtomatis;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public $cari = '';
    public $jumlahData = 10;
    public $kolomUrut = 'tanggal_rekomendasi';
    public $arahUrut = 'asc';
    public $filterStatus = '';
    public $filterTanggal = '';

    protected $listeners = [
        'refreshComponent' => '$refresh',
    ];

    public function mount()
    {
        $this->filterTanggal = Carbon::now()->format('Y-m-d');
    }

    public function updatingFilterStatus() { $this->resetPage(); }
    public function updatingFilterTanggal() { $this->resetPage(); }

    public function urutkan($kolom)
    {
        if ($this->kolomUrut === $kolom) {
            $this->arahUrut = $this->arahUrut === 'asc' ? 'desc' : 'asc';
        } else {
            $this->kolomUrut = $kolom;
            $this->arahUrut = 'asc';
        }
    }

    public function resetFilter()
    {
        $this->reset(['filterStatus', 'filterTanggal', 'cari']);
        $this->filterTanggal = Carbon::now()->format('Y-m-d');
    }

    // ========== KONFIRMASI MANUAL OLEH ADMIN ==========
    public function konfirmasi($id)
    {
        $jadwal = JadwalOtomatis::findOrFail($id);
        
        $this->dispatch('tampilKonfirmasi', [
            'id' => $jadwal->id,
            'nama' => $jadwal->nama_fase . ' - ' . ($jadwal->siklusTanam->petani->nama ?? ''),
        ]);
    }

    public function prosesKonfirmasi($id)
    {
        try {
            $jadwal = JadwalOtomatis::findOrFail($id);
            $jadwal->update([
                'sudah_dikonfirmasi' => true,
                'tanggal_konfirmasi' => Carbon::now(),
            ]);

            $this->dispatch('tampilPesan', [
                'tipe' => 'success',
                'judul' => 'Berhasil!',
                'teks' => 'Aktivitas berhasil dikonfirmasi.',
            ]);
            $this->dispatch('refreshComponent');

        } catch (\Exception $e) {
            $this->dispatch('tampilPesan', [
                'tipe' => 'error',
                'judul' => 'Gagal!',
                'teks' => $e->getMessage(),
            ]);
        }
    }

    // ========== BATALKAN KONFIRMASI ==========
    public function batalKonfirmasi($id)
    {
        try {
            $jadwal = JadwalOtomatis::findOrFail($id);
            $jadwal->update([
                'sudah_dikonfirmasi' => false,
                'tanggal_konfirmasi' => null,
            ]);

            $this->dispatch('tampilPesan', [
                'tipe' => 'success',
                'judul' => 'Berhasil!',
                'teks' => 'Konfirmasi berhasil dibatalkan.',
            ]);
            $this->dispatch('refreshComponent');

        } catch (\Exception $e) {
            $this->dispatch('tampilPesan', [
                'tipe' => 'error',
                'judul' => 'Gagal!',
                'teks' => $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        $dataJadwal = JadwalOtomatis::query()
            ->with(['siklusTanam.petani', 'siklusTanam.lahan', 'siklusTanam.varietasPadi', 'pupuk', 'pestisida'])
            ->when($this->filterStatus !== '', fn($q) => $q->where('sudah_dikonfirmasi', $this->filterStatus))
            ->when($this->filterTanggal, fn($q) => $q->whereDate('tanggal_rekomendasi', $this->filterTanggal))
            ->when($this->cari, fn($q) => $q->where('nama_fase', 'like', '%' . $this->cari . '%')
                ->orWhereHas('siklusTanam.petani', fn($p) => $p->where('nama', 'like', '%' . $this->cari . '%'))
                ->orWhereHas('siklusTanam.lahan', fn($l) => $l->where('nama', 'like', '%' . $this->cari . '%'))
                ->orWhereHas('pupuk', fn($p) => $p->where('nama', 'like', '%' . $this->cari . '%'))
                ->orWhereHas('pestisida', fn($ps) => $ps->where('nama', 'like', '%' . $this->cari . '%')))
            ->orderBy($this->kolomUrut, $this->arahUrut)
            ->paginate($this->jumlahData);

        // Statistik
        $totalHariIni = JadwalOtomatis::whereDate('tanggal_rekomendasi', Carbon::now())->count();
        $totalSelesai = JadwalOtomatis::whereDate('tanggal_rekomendasi', Carbon::now())->where('sudah_dikonfirmasi', true)->count();
        $totalPending = JadwalOtomatis::whereDate('tanggal_rekomendasi', Carbon::now())->where('sudah_dikonfirmasi', false)->count();
        $totalMingguIni = JadwalOtomatis::whereBetween('tanggal_rekomendasi', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();

        return view('livewire.admin.jadwal-aktivitas.index', [
            'dataJadwal' => $dataJadwal,
            'totalHariIni' => $totalHariIni,
            'totalSelesai' => $totalSelesai,
            'totalPending' => $totalPending,
            'totalMingguIni' => $totalMingguIni,
        ])->layout('layouts.admin');
    }
}