<div>
    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><i class="ti ti-file-report me-2"></i>Laporan Hasil Panen</h4>
                <p class="text-muted mb-0">Rekap data hasil panen padi seluruh petani</p>
            </div>
            <div class="d-flex gap-2">
                <button wire:click="downloadPDF" class="btn btn-danger">
                    <i class="ti ti-file-pdf me-1"></i> Download PDF
                </button>
                <button wire:click="loadStatistik" class="btn btn-outline-primary">
                    <i class="ti ti-refresh me-1"></i> Refresh
                </button>
            </div>
        </div>

        {{-- STATISTIK --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <small class="text-muted">Total Panen</small>
                        <h3>{{ number_format($totalPanen) }} <small class="text-muted fs-6">kali</small></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <small class="text-muted">Total Hasil</small>
                        <h3>{{ number_format($totalJumlah, 1) }} <small class="text-muted fs-6">Ton</small></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <small class="text-muted">Rata-rata</small>
                        <h3>{{ number_format($rataHasil, 1) }} <small class="text-muted fs-6">Ton/panen</small></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <small class="text-muted">Panen Terbaik</small>
                        <h3>{{ number_format($panenTerbaik, 1) }} <small class="text-muted fs-6">Ton</small></h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- GRAFIK --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="ti ti-chart-bar me-1"></i> Grafik {{ $filterTahun }}</h5>
                    <select wire:model.live="filterTahun" class="form-select form-select-sm w-auto">
                        @for($i = now()->year; $i >= 2020; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <canvas id="grafikPanen" height="80"></canvas>
            </div>
        </div>

        {{-- FILTER & TABEL --}}
        <div class="card">
            <div class="card-body p-0">
                <div class="row p-3 border-bottom g-2">
                    <div class="col-md-2">
                        <select wire:model.live="filterBulan" class="form-select form-select-sm">
                            <option value="">Semua Bulan</option>
                            @foreach(['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'] as $i => $b)
                                <option value="{{ $i+1 }}">{{ $b }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="filterKualitas" class="form-select form-select-sm">
                            <option value="">Semua Kualitas</option>
                            <option value="baik">Baik</option><option value="sedang">Sedang</option><option value="buruk">Buruk</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="filterVarietas" class="form-select form-select-sm">
                            <option value="">Semua Varietas</option>
                            @foreach($listVarietas as $v)
                                <option value="{{ $v->id }}">{{ $v->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" wire:model.live.debounce.400ms="search" class="form-control form-control-sm" placeholder="Cari...">
                    </div>
                    <div class="col-md-2">
                        <select wire:model.live="perPage" class="form-select form-select-sm">
                            <option value="10">10</option><option value="25">25</option><option value="50">50</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th><th>Petani</th><th>Lahan</th><th>Varietas</th>
                                <th>Jumlah</th><th>Kualitas</th><th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($panen as $i => $item)
                            <tr>
                                <td>{{ $panen->firstItem() + $i }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal_panen)->format('d M Y') }}</td>
                                <td>{{ $item->siklusTanam->petani->nama ?? '-' }}</td>
                                <td>{{ $item->siklusTanam->lahan->nama ?? '-' }}</td>
                                <td><span class="badge bg-success-subtle text-success">{{ $item->siklusTanam->varietasPadi->nama ?? '-' }}</span></td>
                                <td class="fw-bold">{{ number_format($item->jumlah, 1) }} Ton</td>
                                <td>
                                    <span class="badge @if($item->kualitas=='baik') bg-success @elseif($item->kualitas=='sedang') bg-warning @else bg-danger @endif">
                                        {{ ucfirst($item->kualitas) }}
                                    </span>
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($item->catatan, 30) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="text-center py-5 text-muted">Tidak ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between p-3 border-top">
                    <small>{{ $panen->firstItem()??0 }}-{{ $panen->lastItem()??0 }} dari {{ $panen->total() }}</small>
                    {{ $panen->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    let chartPanen = null;
    function renderChartPanen(labels, panen, target) {
        const canvas = document.getElementById('grafikPanen');
        if (!canvas) return;
        if (chartPanen) { chartPanen.destroy(); chartPanen = null; }
        chartPanen = new Chart(canvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    { label: 'Hasil Panen (Ton)', data: panen, backgroundColor: 'rgba(46,125,50,0.7)', borderColor: '#2e7d32', borderWidth: 2, borderRadius: 5 },
                    { label: 'Target (Ton)', data: target, type: 'line', borderColor: '#ffc107', borderWidth: 3, borderDash: [5,5], pointRadius: 5 }
                ]
            },
            options: { responsive: true, plugins: { legend: { position: 'top' } }, scales: { y: { beginAtZero: true } } }
        });
    }
    renderChartPanen(@json($grafikLabels), @json($grafikData), @json($targetPerBulan));
    $wire.on('updateChartPanen', (data) => { renderChartPanen(data[0].labels, data[0].panen, data[0].target); });
</script>
@endscript