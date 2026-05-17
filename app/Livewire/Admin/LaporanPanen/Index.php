<?php

namespace App\Livewire\Admin\LaporanPanen;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Panen;
use App\Models\VarietasPadi;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'tanggal_panen';
    public $sortDirection = 'desc';

    public $filterTahun;
    public $filterBulan = '';
    public $filterKualitas = '';
    public $filterVarietas = '';

    public $totalPanen = 0;
    public $totalJumlah = 0;
    public $rataHasil = 0;
    public $panenTerbaik = 0;

    public $grafikLabels = [];
    public $grafikData = [];
    public $targetPerBulan = [];
    public $listVarietas = [];

    public function mount()
    {
        $this->filterTahun = Carbon::now()->year;
        $this->listVarietas = VarietasPadi::orderBy('nama')->get();
        $this->loadStatistik();
        $this->loadGrafik();
    }

    public function updatedFilterTahun()
    {
        $this->resetPage();
        $this->loadStatistik();
        $this->loadGrafik();
        $this->dispatch('updateChartPanen', [
            'labels' => $this->grafikLabels,
            'panen' => $this->grafikData,
            'target' => $this->targetPerBulan,
        ]);
    }

    public function updatedFilterBulan() { $this->resetPage(); $this->loadStatistik(); }
    public function updatedFilterKualitas() { $this->resetPage(); $this->loadStatistik(); }
    public function updatedFilterVarietas() { $this->resetPage(); $this->loadStatistik(); }

    public function sortData($column)
    {
        $this->sortDirection = ($this->sortBy === $column && $this->sortDirection === 'asc') ? 'desc' : 'asc';
        $this->sortBy = $column;
    }

    public function loadStatistik()
    {
        $query = Panen::query();
        if ($this->filterTahun) $query->whereYear('tanggal_panen', $this->filterTahun);
        if ($this->filterBulan) $query->whereMonth('tanggal_panen', $this->filterBulan);
        if ($this->filterKualitas) $query->where('kualitas', $this->filterKualitas);
        if ($this->filterVarietas) {
            $query->whereHas('siklusTanam', fn($q) => $q->where('varietas_padi_id', $this->filterVarietas));
        }

        $this->totalPanen = $query->count();
        $this->totalJumlah = $query->sum('jumlah') ?? 0;
        $this->rataHasil = $this->totalPanen > 0 ? round($this->totalJumlah / $this->totalPanen, 2) : 0;
        $this->panenTerbaik = $query->max('jumlah') ?? 0;
    }

    public function loadGrafik()
    {
        $tahun = $this->filterTahun ?? Carbon::now()->year;
        $this->grafikLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $this->grafikData = [];
        $this->targetPerBulan = [];

        for ($i = 1; $i <= 12; $i++) {
            $total = Panen::whereYear('tanggal_panen', $tahun)->whereMonth('tanggal_panen', $i)->sum('jumlah');
            $this->grafikData[] = (float) $total;
            $this->targetPerBulan[] = 708;
        }
    }

    // ========== DOWNLOAD PDF - DOMPDF LANGSUNG ==========
    public function downloadPDF()
    {
        try {
            $panen = Panen::with(['siklusTanam.lahan', 'siklusTanam.petani', 'siklusTanam.varietasPadi'])
                ->when($this->filterTahun, fn($q) => $q->whereYear('tanggal_panen', $this->filterTahun))
                ->when($this->filterBulan, fn($q) => $q->whereMonth('tanggal_panen', $this->filterBulan))
                ->when($this->filterKualitas, fn($q) => $q->where('kualitas', $this->filterKualitas))
                ->when($this->filterVarietas, fn($q) => $q->whereHas('siklusTanam', fn($s) => $s->where('varietas_padi_id', $this->filterVarietas)))
                ->orderBy('tanggal_panen', 'desc')
                ->get();

            $data = [
                'panen' => $panen,
                'filterTahun' => $this->filterTahun,
                'filterBulan' => $this->filterBulan,
                'filterKualitas' => $this->filterKualitas,
                'bulanText' => $this->filterBulan ? Carbon::create()->month($this->filterBulan)->translatedFormat('F') : 'Semua',
                'totalPanen' => $panen->count(),
                'totalJumlah' => $panen->sum('jumlah'),
                'rataHasil' => $panen->count() > 0 ? round($panen->sum('jumlah') / $panen->count(), 2) : 0,
                'panenTerbaik' => $panen->max('jumlah') ?? 0,
            ];

            $filename = 'Laporan_Hasil_Panen_' . $this->filterTahun . '.pdf';

            $html = view('livewire.admin.laporan-panen.pdf', $data)->render();

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
        $panen = Panen::with(['siklusTanam.lahan', 'siklusTanam.petani', 'siklusTanam.varietasPadi'])
            ->when($this->filterTahun, fn($q) => $q->whereYear('tanggal_panen', $this->filterTahun))
            ->when($this->filterBulan, fn($q) => $q->whereMonth('tanggal_panen', $this->filterBulan))
            ->when($this->filterKualitas, fn($q) => $q->where('kualitas', $this->filterKualitas))
            ->when($this->filterVarietas, fn($q) => $q->whereHas('siklusTanam', fn($s) => $s->where('varietas_padi_id', $this->filterVarietas)))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.laporan-panen.index', compact('panen'));
    }
}