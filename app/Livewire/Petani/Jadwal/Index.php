<?php

namespace App\Livewire\Petani\Jadwal;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JadwalOtomatis;
use App\Models\SiklusTanam;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'tanggal_rekomendasi';
    public $sortDirection = 'asc';

    // Filter
    public $filterSiklus = '';
    public $filterStatus = '';
    public $filterTanggal = '';

    // Statistik
    public $totalJadwal = 0;
    public $totalSelesai = 0;
    public $totalPending = 0;

    // List siklus untuk filter
    public $listSiklus = [];

    public function mount()
    {
        $this->listSiklus = SiklusTanam::where('user_id', Auth::id())
            ->where('status', 'aktif')
            ->orderBy('created_at', 'desc')
            ->get();

        if (request()->has('siklus')) {
            $this->filterSiklus = request('siklus');
        }

        $this->loadStatistik();
    }

    public function updatedFilterSiklus()
    {
        $this->resetPage();
        $this->loadStatistik();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
        $this->loadStatistik();
    }

    public function sortData($column)
    {
        $this->sortDirection = ($this->sortBy === $column && $this->sortDirection === 'asc') ? 'desc' : 'asc';
        $this->sortBy = $column;
    }

    private function getBaseQuery()
    {
        return JadwalOtomatis::with(['siklusTanam.lahan', 'siklusTanam.varietasPadi', 'pupuk', 'pestisida'])
            ->whereHas('siklusTanam', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->when($this->filterSiklus, fn($q) => $q->where('siklus_tanam_id', $this->filterSiklus))
            ->when($this->filterStatus !== '', fn($q) => $q->where('sudah_dikonfirmasi', $this->filterStatus == '1'))
            ->when($this->search, function ($q) {
                $q->where('nama_fase', 'like', '%'.$this->search.'%')
                  ->orWhere('deskripsi_aktivitas', 'like', '%'.$this->search.'%')
                  ->orWhereHas('siklusTanam.lahan', fn($s) => $s->where('nama', 'like', '%'.$this->search.'%'));
            });
    }

    public function loadStatistik()
    {
        $query = $this->getBaseQuery();
        $this->totalJadwal = $query->count();
        $this->totalSelesai = (clone $query)->where('sudah_dikonfirmasi', true)->count();
        $this->totalPending = (clone $query)->where('sudah_dikonfirmasi', false)->count();
    }

    // ========== KONFIRMASI ==========
    public function konfirmasi($id)
    {
        $jadwal = JadwalOtomatis::whereHas('siklusTanam', fn($q) => $q->where('user_id', Auth::id()))->findOrFail($id);

        // 🆕 Cek urutan
        $jadwalSebelumnya = JadwalOtomatis::where('siklus_tanam_id', $jadwal->siklus_tanam_id)
            ->where('tanggal_rekomendasi', '<', $jadwal->tanggal_rekomendasi)
            ->where('sudah_dikonfirmasi', false)
            ->orderBy('tanggal_rekomendasi', 'desc')
            ->first();

        if ($jadwalSebelumnya) {
            $this->dispatch('tampilPeringatanUrutan', [
                'nama' => $jadwalSebelumnya->nama_fase,
                'tanggal' => $jadwalSebelumnya->tanggal_rekomendasi->format('d M Y'),
            ]);
            return;
        }

        $namaAktivitas = $jadwal->nama_fase;
        if ($jadwal->pupuk) {
            $namaAktivitas .= ' - ' . $jadwal->pupuk->nama . ' (' . $jadwal->dosis_dihitung . ' ' . $jadwal->pupuk->satuan . ')';
        } elseif ($jadwal->pestisida) {
            $namaAktivitas .= ' - ' . $jadwal->pestisida->nama;
        }

        $this->dispatch('tampilKonfirmasi', [
            'id' => $jadwal->id,
            'nama' => $namaAktivitas,
        ]);
    }

    // ========== PROSES KONFIRMASI ==========
    public function prosesKonfirmasi($id)
    {
        try {
            $jadwal = JadwalOtomatis::whereHas('siklusTanam', fn($q) => $q->where('user_id', Auth::id()))->findOrFail($id);
            
            // 🆕 Cek ulang urutan saat proses
            $jadwalSebelumnya = JadwalOtomatis::where('siklus_tanam_id', $jadwal->siklus_tanam_id)
                ->where('tanggal_rekomendasi', '<', $jadwal->tanggal_rekomendasi)
                ->where('sudah_dikonfirmasi', false)
                ->exists();

            if ($jadwalSebelumnya) {
                $this->dispatch('tampilPeringatanUrutan', [
                    'nama' => $jadwal->nama_fase,
                    'tanggal' => $jadwal->tanggal_rekomendasi->format('d M Y'),
                ]);
                return;
            }

            $jadwal->update([
                'sudah_dikonfirmasi' => true,
                'tanggal_konfirmasi' => Carbon::now(),
            ]);

            $this->loadStatistik();
            $this->dispatch('tampilPesan', [
                'tipe' => 'success',
                'judul' => 'Berhasil!',
                'teks' => 'Aktivitas berhasil dikonfirmasi.',
            ]);

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
        $jadwal = $this->getBaseQuery()
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.petani.jadwal.index', compact('jadwal'));
    }
}