<div>
    <div class="container-fluid">
        
        {{-- HEADER --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><i class="ti ti-calendar-week me-2"></i>Jadwal Aktivitas</h4>
                <p class="text-muted mb-0">Pantau dan konfirmasi aktivitas pertanian semua petani</p>
            </div>
            <div class="d-flex gap-2">
                <button wire:click="resetFilter" class="btn btn-outline-secondary">
                    <i class="ti ti-refresh me-1"></i> Reset
                </button>
            </div>
        </div>

        {{-- STATISTIK --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Hari Ini</small>
                        <h3>{{ $totalHariIni }}</h3>
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
                        <small class="text-muted">Minggu Ini</small>
                        <h3>{{ $totalMingguIni }}</h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- FILTER & TABEL --}}
        <div class="card">
            <div class="card-body p-0">
                <div class="row p-3 border-bottom g-2 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small">Tanggal</label>
                        <input type="date" wire:model.live="filterTanggal" class="form-control form-control-sm">
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
                        <input type="text" wire:model.live.debounce.300ms="cari" class="form-control form-control-sm" placeholder="Cari petani, lahan, fase...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small">Tampil</label>
                        <select wire:model.live="jumlahData" class="form-select form-select-sm">
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
                                <th wire:click="urutkan('tanggal_rekomendasi')" style="cursor:pointer;">
                                    Tanggal @if($kolomUrut==='tanggal_rekomendasi')<i class="ti ti-arrow-{{ $arahUrut==='asc'?'up':'down' }} ms-1"></i>@endif
                                </th>
                                <th>Petani</th>
                                <th>Lahan</th>
                                <th>Fase</th>
                                <th>Jenis</th>
                                <th>Detail</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataJadwal as $index => $item)
                            <tr class="{{ $item->sudah_dikonfirmasi ? '' : 'table-warning' }}">
                                <td>{{ $dataJadwal->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ Carbon\Carbon::parse($item->tanggal_rekomendasi)->format('d M Y') }}</strong>
                                </td>
                                <td>
                                    <i class="ti ti-user me-1 text-muted"></i>
                                    {{ $item->siklusTanam->petani->nama ?? '-' }}
                                </td>
                                <td>
                                    <i class="ti ti-map-pin me-1 text-muted"></i>
                                    {{ $item->siklusTanam->lahan->nama ?? '-' }}
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary">{{ $item->nama_fase }}</span>
                                </td>
                                <td>
                                    @if($item->pupuk_id)
                                        <span class="badge bg-success"><i class="ti ti-droplet me-1"></i>Pemupukan</span>
                                    @elseif($item->pestisida_id)
                                        <span class="badge bg-warning"><i class="ti ti-shield me-1"></i>Penyemprotan</span>
                                    @else
                                        <span class="badge bg-secondary">Lainnya</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->pupuk)
                                        <small>{{ $item->pupuk->nama }} ({{ $item->dosis_dihitung }} {{ $item->pupuk->satuan }})</small>
                                    @elseif($item->pestisida)
                                        <small>{{ $item->pestisida->nama }}</small>
                                    @else
                                        <small>{{ Str::limit($item->deskripsi_aktivitas, 40) ?: '-' }}</small>
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
                                            <i class="ti ti-check me-1"></i>
                                        </button>
                                    @else
                                        <button wire:click="batalKonfirmasi({{ $item->id }})" class="btn btn-sm btn-outline-secondary" title="Batalkan">
                                            <i class="ti ti-arrow-back"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="ti ti-calendar-off fs-1"></i>
                                    <p class="mt-2">Tidak ada jadwal untuk tanggal ini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <small>Menampilkan {{ $dataJadwal->firstItem()??0 }}-{{ $dataJadwal->lastItem()??0 }} dari {{ $dataJadwal->total() }} data</small>
                    {{ $dataJadwal->links() }}
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

    $wire.on('tampilKonfirmasi', (data) => {
        Swal.fire({
            title: 'Konfirmasi Aktivitas',
            html: `
                <p>Konfirmasi bahwa aktivitas sudah dilakukan:</p>
                <strong class="fs-5 text-success">${data[0].nama}</strong>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="ti ti-check me-1"></i> Konfirmasi',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                $wire.call('prosesKonfirmasi', data[0].id);
            }
        });
    });
</script>
@endscript