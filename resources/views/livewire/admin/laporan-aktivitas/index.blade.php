<div>
    <div class="container-fluid" wire:ignore.self>

        {{-- HEADER --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h4 class="fw-bold mb-1">
                    <i class="ti ti-clipboard-data me-2"></i>Laporan Aktivitas
                </h4>
                <p class="text-muted mb-0">Rekap aktivitas pertanian</p>
            </div>

            <div class="d-flex gap-2">
                <button wire:click="downloadPDF"
                        wire:loading.attr="disabled"
                        class="btn btn-danger shadow-sm">
                    <span wire:loading.remove>
                        <i class="ti ti-file-pdf me-1"></i> Download PDF
                    </span>
                    <span wire:loading>
                        <span class="spinner-border spinner-border-sm"></span> Proses...
                    </span>
                </button>

                <button wire:click="loadStatistik"
                        wire:loading.attr="disabled"
                        class="btn btn-outline-primary shadow-sm">
                    <i class="ti ti-refresh me-1"></i> Refresh
                </button>
            </div>
        </div>

        {{-- STATISTIK --}}
        <div class="row g-3 mb-4">
            @foreach([
                ['Total Aktivitas', $totalAktivitas, ''],
                ['Selesai', $totalSelesai, 'text-success'],
                ['Pending', $totalPending, 'text-warning'],
                ['Progress', $persentaseSelesai, '']
            ] as $stat)

            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <small class="text-muted">{{ $stat[0] }}</small>

                        <h3 class="mb-0 {{ $stat[2] }}">
                            @if($stat[0] === 'Progress')
                                {{ (int) $stat[1] }}%
                            @else
                                {{ number_format((float) $stat[1]) }}
                            @endif
                        </h3>
                    </div>
                </div>
            </div>

            @endforeach
        </div>

        {{-- PROGRESS --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span>Progress</span>
                    <strong>{{ $persentaseSelesai }}%</strong>
                </div>

                <div class="progress" style="height: 18px;">
                    <div class="progress-bar bg-success"
                         style="width: {{ $persentaseSelesai }}%">
                        {{ $totalSelesai }} / {{ $totalAktivitas }}
                    </div>
                </div>
            </div>
        </div>

        {{-- GRAFIK --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                    <h5 class="mb-0">
                        <i class="ti ti-chart-bar me-1"></i>
                        Grafik Aktivitas {{ $filterTahun }}
                    </h5>

                    <select wire:model.live="filterTahun"
                            class="form-select form-select-sm w-auto">
                        @for($i = now()->year; $i >= 2020; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <canvas id="grafikAktivitas" height="80"></canvas>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">

                {{-- FILTER --}}
                <div class="row p-3 border-bottom g-3 align-items-end">
                    <div class="col-md-2">
                        <select wire:model.live="filterBulan" class="form-select form-select-sm">
                            <option value="">Semua Bulan</option>
                            @foreach(['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'] as $i => $b)
                                <option value="{{ $i+1 }}">{{ $b }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select wire:model.live="filterStatus" class="form-select form-select-sm">
                            <option value="">Semua Status</option>
                            <option value="1">Selesai</option>
                            <option value="0">Pending</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select wire:model.live="filterJenis" class="form-select form-select-sm">
                            <option value="">Semua Jenis</option>
                            <option value="pemupukan">Pemupukan</option>
                            <option value="penyemprotan">Penyemprotan</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <input type="text"
                               wire:model.live.debounce.400ms="search"
                               class="form-control form-control-sm shadow-sm"
                               placeholder="Cari aktivitas...">
                    </div>

                    <div class="col-md-2">
                        <select wire:model.live="perPage" class="form-select form-select-sm">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>

                {{-- TABLE --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Petani</th>
                            <th>Lahan</th>
                            <th>Fase</th>
                            <th>Jenis</th>
                            <th>Detail</th>
                            <th>Status</th>
                            <th>Konfirmasi</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($aktivitas as $i => $item)
                            <tr>
                                <td>{{ $aktivitas->firstItem() + $i }}</td>

                                <td>
                                    {{ \Carbon\Carbon::parse($item->tanggal_rekomendasi)->format('d M Y') }}
                                    <br>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($item->tanggal_rekomendasi)->format('H:i') }}
                                    </small>
                                </td>

                                <td>{{ $item->siklusTanam->petani->nama ?? '-' }}</td>
                                <td>{{ $item->siklusTanam->lahan->nama ?? '-' }}</td>

                                <td>
                                    <span class="badge bg-primary-subtle text-primary">
                                        {{ $item->nama_fase }}
                                    </span>
                                </td>

                                <td>
                                    @if($item->pupuk_id)
                                        <span class="badge bg-success">Pemupukan</span>
                                    @elseif($item->pestisida_id)
                                        <span class="badge bg-warning">Penyemprotan</span>
                                    @else
                                        <span class="badge bg-secondary">Lainnya</span>
                                    @endif
                                </td>

                                <td>
                                    @if($item->pupuk)
                                        <small>{{ $item->pupuk->nama }}</small>
                                    @elseif($item->pestisida)
                                        <small>{{ $item->pestisida->nama }}</small>
                                    @else
                                        <small>{{ \Illuminate\Support\Str::limit($item->deskripsi_aktivitas, 40) }}</small>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge {{ $item->sudah_dikonfirmasi ? 'bg-success' : 'bg-warning' }}">
                                        {{ $item->sudah_dikonfirmasi ? 'Selesai' : 'Pending' }}
                                    </span>
                                </td>

                                <td>
                                    <small>
                                        {{ $item->tanggal_konfirmasi
                                            ? \Carbon\Carbon::parse($item->tanggal_konfirmasi)->format('d M Y H:i')
                                            : '-' }}
                                    </small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    Tidak ada data
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION (FIX UTAMA) --}}
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center p-3 border-top gap-2">

                    <small class="text-muted text-center text-md-start">
                        {{ $aktivitas->firstItem() ?? 0 }} -
                        {{ $aktivitas->lastItem() ?? 0 }}
                        dari {{ $aktivitas->total() }} data
                    </small>

                    <div class="w-100 d-flex justify-content-center justify-content-md-end">
                        {{ $aktivitas->links('pagination::bootstrap-5') }}
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

{{-- CHART --}}
@script
<script>
    let chartInstance = null;

    function renderChart() {
        const canvas = document.getElementById('grafikAktivitas');
        if (!canvas) {
            console.log('Canvas tidak ditemukan');
            return;
        }

        if (chartInstance) {
            chartInstance.destroy();
        }

        chartInstance = new Chart(canvas, {
            type: 'bar',
            data: {
                labels: @json($grafikLabels),
                datasets: [
                    { label: 'Selesai', data: @json($grafikSelesai), backgroundColor: '#2e7d32', borderRadius: 4 },
                    { label: 'Pending', data: @json($grafikPending), backgroundColor: '#ffc107', borderRadius: 4 }
                ]
            },
            options: {
                responsive: true,
                scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } }
            }
        });
    }

    // Langsung render
    renderChart();

    // Render ulang saat Livewire update
    Livewire.on('updateChart', (data) => {
        if (chartInstance) chartInstance.destroy();
        chartInstance = new Chart(document.getElementById('grafikAktivitas'), {
            type: 'bar',
            data: {
                labels: data[0].labels,
                datasets: [
                    { label: 'Selesai', data: data[0].selesai, backgroundColor: '#2e7d32', borderRadius: 4 },
                    { label: 'Pending', data: data[0].pending, backgroundColor: '#ffc107', borderRadius: 4 }
                ]
            },
            options: {
                responsive: true,
                scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } }
            }
        });
    });
</script>
@endscript