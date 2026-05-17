<?php

namespace App\Livewire\Admin\Laporanaktivitas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\JadwalOtomatis;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'tanggal_rekomendasi';
    public $sortDirection = 'desc';

    public $filterTahun;
    public $filterBulan = '';
    public $filterStatus = '';
    public $filterJenis = '';

    public $totalAktivitas = 0;
    public $totalSelesai = 0;
    public $totalPending = 0;
    public $persentaseSelesai = 0;

    public $grafikLabels = [];
    public $grafikSelesai = [];
    public $grafikPending = [];

    public function mount()
    {
        $this->filterTahun = now()->year;
        $this->loadStatistik();
        $this->loadGrafik();
    }

    public function updatedFilterTahun()
    {
        $this->resetPage();
        $this->loadStatistik();
        $this->loadGrafik();
        
        $this->dispatch('updateChart', [
            'labels' => $this->grafikLabels,
            'selesai' => $this->grafikSelesai,
            'pending' => $this->grafikPending,
        ]);
    }

    public function updatedFilterBulan() { $this->resetPage(); $this->loadStatistik(); }
    public function updatedFilterStatus() { $this->resetPage(); $this->loadStatistik(); }
    public function updatedFilterJenis() { $this->resetPage(); $this->loadStatistik(); }

    public function sortData($column)
    {
        $this->sortDirection = ($this->sortBy === $column && $this->sortDirection === 'asc') ? 'desc' : 'asc';
        $this->sortBy = $column;
    }

    private function getQuery()
    {
        return JadwalOtomatis::query()
            ->when($this->filterTahun, fn($q) => $q->whereYear('tanggal_rekomendasi', $this->filterTahun))
            ->when($this->filterBulan, fn($q) => $q->whereMonth('tanggal_rekomendasi', $this->filterBulan))
            ->when($this->filterStatus !== '', fn($q) => $q->where('sudah_dikonfirmasi', $this->filterStatus == '1'))
            ->when($this->filterJenis, function ($q) {
                if ($this->filterJenis == 'pemupukan') $q->whereNotNull('pupuk_id');
                elseif ($this->filterJenis == 'penyemprotan') $q->whereNotNull('pestisida_id');
                elseif ($this->filterJenis == 'lainnya') $q->whereNull('pupuk_id')->whereNull('pestisida_id');
            });
    }

    public function loadStatistik()
    {
        $data = $this->getQuery()->selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN sudah_dikonfirmasi = 1 THEN 1 ELSE 0 END) as selesai,
            SUM(CASE WHEN sudah_dikonfirmasi = 0 THEN 1 ELSE 0 END) as pending
        ')->first();

        $this->totalAktivitas = (int) ($data->total ?? 0);
        $this->totalSelesai = (int) ($data->selesai ?? 0);
        $this->totalPending = (int) ($data->pending ?? 0);
        $this->persentaseSelesai = $this->totalAktivitas > 0 ? round(($this->totalSelesai / $this->totalAktivitas) * 100) : 0;
    }

    public function loadGrafik()
    {
        $tahun = $this->filterTahun ?? Carbon::now()->year;
        $this->grafikLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $this->grafikSelesai = [];
        $this->grafikPending = [];

        for ($i = 1; $i <= 12; $i++) {
            $this->grafikSelesai[] = JadwalOtomatis::whereYear('tanggal_rekomendasi', $tahun)->whereMonth('tanggal_rekomendasi', $i)->where('sudah_dikonfirmasi', true)->count();
            $this->grafikPending[] = JadwalOtomatis::whereYear('tanggal_rekomendasi', $tahun)->whereMonth('tanggal_rekomendasi', $i)->where('sudah_dikonfirmasi', false)->count();
        }
    }

    // ========== DOWNLOAD PDF - DOMPDF LANGSUNG ==========
    public function downloadPDF()
    {
        try {
            $aktivitas = $this->getQuery()
                ->with(['siklusTanam.lahan', 'siklusTanam.petani', 'pupuk', 'pestisida'])
                ->when($this->search, function ($q) {
                    $q->where('nama_fase', 'like', '%' . $this->search . '%')
                      ->orWhere('deskripsi_aktivitas', 'like', '%' . $this->search . '%')
                      ->orWhereHas('siklusTanam.lahan', fn($s) => $s->where('nama', 'like', '%' . $this->search . '%'))
                      ->orWhereHas('siklusTanam.petani', fn($s) => $s->where('nama', 'like', '%' . $this->search . '%'));
                })
                ->orderBy('tanggal_rekomendasi', 'desc')
                ->get();

            $total = $aktivitas->count();
            $selesai = $aktivitas->where('sudah_dikonfirmasi', true)->count();
            $pending = $aktivitas->where('sudah_dikonfirmasi', false)->count();

            $data = [
                'aktivitas' => $aktivitas,
                'filterTahun' => $this->filterTahun,
                'bulanText' => $this->filterBulan ? Carbon::create()->month($this->filterBulan)->translatedFormat('F') : 'Semua',
                'statusText' => $this->filterStatus !== '' ? ($this->filterStatus == '1' ? 'Selesai' : 'Pending') : 'Semua',
                'jenisText' => $this->filterJenis ?: 'Semua',
                'totalAktivitas' => $total,
                'totalSelesai' => $selesai,
                'totalPending' => $pending,
                'persentaseSelesai' => $total > 0 ? round(($selesai / $total) * 100) : 0,
            ];

            $filename = 'Laporan_Aktivitas_' . $this->filterTahun . '.pdf';

            $html = view('livewire.admin.laporan-aktivitas.pdf', $data)->render();

            // 🆕 DomPDF langsung
            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();

            return response()->streamDownload(function() use ($dompdf) {
                echo $dompdf->output();
            }, $filename);

        } catch (\Exception $e) {
            $this->dispatch('tampilPesan', [
                'tipe' => 'error',
                'judul' => 'Gagal!',
                'teks' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        $aktivitas = $this->getQuery()
            ->with(['siklusTanam.lahan', 'siklusTanam.petani', 'pupuk', 'pestisida'])
            ->when($this->search, function ($q) {
                $q->where('nama_fase', 'like', '%' . $this->search . '%')
                  ->orWhere('deskripsi_aktivitas', 'like', '%' . $this->search . '%')
                  ->orWhereHas('siklusTanam.lahan', fn($s) => $s->where('nama', 'like', '%' . $this->search . '%'))
                  ->orWhereHas('siklusTanam.petani', fn($s) => $s->where('nama', 'like', '%' . $this->search . '%'));
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.laporan-aktivitas.index', compact('aktivitas'));
    }
}