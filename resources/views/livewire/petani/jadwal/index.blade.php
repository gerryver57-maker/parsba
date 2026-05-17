<div>
    <div class="container-fluid">
        
        {{-- HEADER --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><i class="ti ti-calendar-week me-2"></i>Jadwal Kegiatan</h4>
                <p class="text-muted mb-0">Konfirmasi aktivitas pertanian sesuai jadwal</p>
            </div>
        </div>

        {{-- STATISTIK --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Jadwal</small>
                        <h3>{{ $totalJadwal }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Selesai</small>
                        <h3 class="text-success">{{ $totalSelesai }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Pending</small>
                        <h3 class="text-warning">{{ $totalPending }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Progress</small>
                        <h3>{{ $totalJadwal > 0 ? round(($totalSelesai/$totalJadwal)*100) : 0 }}%</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- FILTER & TABEL --}}
        <div class="card">
            <div class="card-body p-0">
                <div class="row p-3 border-bottom g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small">Filter Siklus</label>
                        <select wire:model.live="filterSiklus" class="form-select form-select-sm">
                            <option value="">Semua Siklus Aktif</option>
                            @foreach($listSiklus as $s)
                                <option value="{{ $s->id }}">
                                    {{ $s->lahan->nama ?? '-' }} - {{ $s->varietasPadi->nama ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Status</label>
                        <select wire:model.live="filterStatus" class="form-select form-select-sm">
                            <option value="">Semua</option>
                            <option value="0">⏳ Pending</option>
                            <option value="1">✅ Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small">Cari</label>
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Cari fase, lahan...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Tampil</label>
                        <select wire:model.live="perPage" class="form-select form-select-sm">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th wire:click="sortData('tanggal_rekomendasi')" style="cursor:pointer;">
                                    Tanggal @if($sortBy==='tanggal_rekomendasi')<i class="ti ti-arrow-{{ $sortDirection==='asc'?'up':'down' }} ms-1"></i>@endif
                                </th>
                                <th>Fase</th>
                                <th>Lahan</th>
                                <th>Jenis</th>
                                <th>Detail</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwal as $index => $item)
                            <tr class="{{ $item->sudah_dikonfirmasi ? '' : 'table-warning' }}">
                                <td>{{ $jadwal->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ Carbon\Carbon::parse($item->tanggal_rekomendasi)->format('d M Y') }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ $item->nama_fase }}</span>
                                </td>
                                <td>{{ $item->siklusTanam->lahan->nama ?? '-' }}</td>
                                <td>
                                    @if($item->pupuk_id)
                                        <span class="badge bg-success"><i class="ti ti-droplet me-1"></i>Pupuk</span>
                                    @elseif($item->pestisida_id)
                                        <span class="badge bg-warning"><i class="ti ti-shield me-1"></i>Pestisida</span>
                                    @else
                                        <span class="badge bg-secondary">Lainnya</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->pupuk)
                                        <small>{{ $item->pupuk->nama }} <strong>({{ $item->dosis_dihitung }} {{ $item->pupuk->satuan }})</strong></small>
                                    @elseif($item->pestisida)
                                        <small>{{ $item->pestisida->nama }}</small>
                                    @else
                                        <small>{{ Str::limit($item->deskripsi_aktivitas, 50) ?: '-' }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($item->sudah_dikonfirmasi)
                                        <span class="badge bg-success rounded-pill"><i class="ti ti-check me-1"></i>Selesai</span>
                                    @else
                                        <span class="badge bg-warning rounded-pill"><i class="ti ti-clock me-1"></i>Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$item->sudah_dikonfirmasi)
                                        <button wire:click="konfirmasi({{ $item->id }})" class="btn btn-sm btn-success">
                                            <i class="ti ti-check me-1"></i> Konfirmasi
                                        </button>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="ti ti-calendar-off fs-1"></i>
                                    <p class="mt-2">Tidak ada jadwal kegiatan.</p>
                                    @if(count($listSiklus) == 0)
                                        <a wire:navigate href="{{ url('petani/siklus') }}" class="btn btn-sm btn-success">
                                            <i class="ti ti-plus me-1"></i> Buat Siklus Tanam
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <small>Menampilkan {{ $jadwal->firstItem()??0 }}-{{ $jadwal->lastItem()??0 }} dari {{ $jadwal->total() }} data</small>
                    {{ $jadwal->links() }}
                </div>
            </div>
        </div>

    </div>
</div>

@script
<script>
    $wire.on('tampilPesan', (data) => {
        Swal.fire({
            icon: data[0].tipe,
            title: data[0].judul,
            text: data[0].teks,
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: data[0].tipe !== 'success'
        });
    });

    // 🆕 PERINGATAN URUTAN
    $wire.on('tampilPeringatanUrutan', (data) => {
        Swal.fire({
            title: '⚠️ Konfirmasi Harus Berurutan!',
            html: `
                <p>Anda belum mengkonfirmasi jadwal sebelumnya:</p>
                <strong class="fs-5 text-warning">${data[0].nama}</strong>
                <p class="text-muted small">Tanggal: ${data[0].tanggal}</p>
                <hr>
                <p class="text-muted small">Silakan konfirmasi jadwal yang lebih dulu sebelum melanjutkan ke jadwal ini.</p>
            `,
            icon: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Mengerti',
        });
    });

    $wire.on('tampilKonfirmasi', (data) => {
        Swal.fire({
            title: 'Konfirmasi Aktivitas',
            html: `
                <p>Apakah Anda sudah melakukan aktivitas:</p>
                <strong class="fs-5 text-success">${data[0].nama}</strong>
                <p class="text-muted mt-2 small">Klik "Ya" untuk menandai selesai.</p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="ti ti-check me-1"></i> Ya, Sudah!',
            cancelButtonText: '<i class="ti ti-x me-1"></i> Belum',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $wire.call('prosesKonfirmasi', data[0].id);
            }
        });
    });
</script>
@endscript