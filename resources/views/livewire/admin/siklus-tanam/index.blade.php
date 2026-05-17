<div>
    <div class="container-fluid" wire:ignore.self>
        
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><i class="ti ti-arrow-loop me-2"></i>Siklus Tanam Petani</h4>
                <p class="text-muted mb-0">Pantau semua siklus tanam padi dari seluruh petani</p>
            </div>
        </div>

        {{-- KARTU STATISTIK --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                                <i class="ti ti-arrow-loop fs-4 text-success"></i>
                            </div>
                            <div>
                                <small class="text-muted">Siklus Aktif</small>
                                <h4 class="mb-0 fw-bold text-success">{{ $totalAktif }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                                <i class="ti ti-check fs-4 text-primary"></i>
                            </div>
                            <div>
                                <small class="text-muted">Siklus Selesai</small>
                                <h4 class="mb-0 fw-bold text-primary">{{ $totalSelesai }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                                <i class="ti ti-basket fs-4 text-warning"></i>
                            </div>
                            <div>
                                <small class="text-muted">Total Hasil Panen</small>
                                <h4 class="mb-0 fw-bold text-warning">{{ number_format($totalHasilPanen, 1) }} Ton</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABEL --}}
        <div class="card">
            <div class="card-body p-0">
                <div class="d-flex flex-wrap justify-content-between align-items-center p-3 border-bottom gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted">Show</span>
                        <select wire:model.live="jumlahData" class="form-select form-select-sm w-auto">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                        </select>
                        <span class="text-muted">entries</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <select wire:model.live="filterStatus" class="form-select form-select-sm" style="width: 150px;">
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="selesai">Selesai</option>
                        </select>
                        <input type="text" wire:model.live.debounce.300ms="cari" class="form-control form-control-sm" placeholder="Cari petani, lahan..." style="width: 200px;">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th>Petani</th>
                                <th>Lahan</th>
                                <th>Varietas</th>
                                <th wire:click="urutkan('tanggal_tanam')" style="cursor:pointer;">
                                    Tanam
                                    @if($kolomUrut === 'tanggal_tanam')
                                        <i class="ti ti-arrow-{{ $arahUrut === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </th>
                                <th>Perkiraan Panen</th>
                                <th>Hasil Panen</th>
                                <th wire:click="urutkan('status')" style="cursor:pointer;">
                                    Status
                                    @if($kolomUrut === 'status')
                                        <i class="ti ti-arrow-{{ $arahUrut === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </th>
                                <th width="8%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataSiklus as $index => $item)
                            <tr>
                                <td>{{ $dataSiklus->firstItem() + $index }}</td>
                                <td>
                                    <i class="ti ti-user-circle text-muted me-1"></i>
                                    {{ $item->petani->nama ?? '-' }}
                                </td>
                                <td>
                                    <i class="ti ti-map-pin text-success me-1"></i>
                                    {{ $item->lahan->nama ?? '-' }}
                                    <br><small class="text-muted">{{ $item->lahan->luas ?? '0' }} Ha</small>
                                </td>
                                <td>
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        {{ $item->varietasPadi->nama ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($item->tanggal_tanam)->format('d/m/Y') }}
                                </td>
                                <td>
                                    @if($item->status === 'selesai')
                                        <span class="text-muted">{{ \Carbon\Carbon::parse($item->perkiraan_panen)->format('d/m/Y') }}</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                            {{ \Carbon\Carbon::parse($item->perkiraan_panen)->format('d/m/Y') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status === 'selesai' && $item->hasil_panen)
                                        <strong class="text-primary">{{ number_format($item->hasil_panen, 1) }} Ton</strong>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status === 'aktif')
                                        <span class="badge bg-success rounded-pill">
                                            <i class="ti ti-plant me-1"></i> Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-primary rounded-pill">
                                            <i class="ti ti-check me-1"></i> Selesai
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <button wire:click="lihatDetail({{ $item->id }})" class="btn btn-sm btn-outline-info" title="Detail">
                                        <i class="ti ti-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="ti ti-plant-off fs-1 text-muted"></i>
                                    <p class="mt-2 text-muted">Tidak ada data siklus tanam.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <small class="text-muted">
                        Menampilkan {{ $dataSiklus->firstItem() ?? 0 }} - {{ $dataSiklus->lastItem() ?? 0 }} 
                        dari {{ $dataSiklus->total() }} data
                    </small>
                    {{ $dataSiklus->links() }}
                </div>
            </div>
        </div>

        {{-- MODAL DETAIL --}}
        @if($tampilDetail && $detailSiklus)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="ti ti-eye me-1"></i> Detail Siklus Tanam
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="tutupDetail"></button>
                    </div>
                    <div class="modal-body">
                        
                        {{-- INFO SIKLUS --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-3">
                                <div class="card border">
                                    <div class="card-body text-center p-3">
                                        <i class="ti ti-user-circle fs-2 text-muted"></i>
                                        <h6 class="mb-0 mt-1">{{ $detailSiklus->petani->nama ?? '-' }}</h6>
                                        <small class="text-muted">Petani</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border">
                                    <div class="card-body text-center p-3">
                                        <i class="ti ti-map-pin fs-2 text-success"></i>
                                        <h6 class="mb-0 mt-1">{{ $detailSiklus->lahan->nama ?? '-' }}</h6>
                                        <small class="text-muted">{{ $detailSiklus->lahan->luas ?? '0' }} Ha</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border">
                                    <div class="card-body text-center p-3">
                                        <i class="ti ti-plant fs-2 text-success"></i>
                                        <h6 class="mb-0 mt-1">{{ $detailSiklus->varietasPadi->nama ?? '-' }}</h6>
                                        <small class="text-muted">{{ $detailSiklus->varietasPadi->umur_panen ?? '-' }} HST</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card border">
                                    <div class="card-body text-center p-3">
                                        <i class="ti ti-calendar fs-2 text-primary"></i>
                                        <h6 class="mb-0 mt-1">{{ \Carbon\Carbon::parse($detailSiklus->tanggal_tanam)->format('d M Y') }}</h6>
                                        <small class="text-muted">Tanggal Tanam</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- INFO PANEN --}}
                        @if($detailSiklus->status === 'selesai' && $detailSiklus->panen)
                        <div class="alert alert-success">
                            <div class="row text-center">
                                <div class="col-md-4">
                                    <strong>Tanggal Panen:</strong><br>
                                    {{ \Carbon\Carbon::parse($detailSiklus->tanggal_panen_aktual)->format('d M Y') }}
                                </div>
                                <div class="col-md-4">
                                    <strong>Hasil Panen:</strong><br>
                                    {{ number_format($detailSiklus->hasil_panen, 1) }} Ton
                                </div>
                                <div class="col-md-4">
                                    <strong>Kualitas:</strong><br>
                                    <span class="badge bg-{{ $detailSiklus->panen->kualitas === 'baik' ? 'success' : ($detailSiklus->panen->kualitas === 'sedang' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($detailSiklus->panen->kualitas) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- JADWAL OTOMATIS --}}
                        <h6 class="fw-bold mb-3">
                            <i class="ti ti-calendar-week me-1 text-primary"></i> Jadwal Aktivitas
                            <span class="badge bg-primary ms-2">{{ $detailJadwal->count() }} Jadwal</span>
                        </h6>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Fase</th>
                                        <th>Tanggal Rekomendasi</th>
                                        <th>Pupuk</th>
                                        <th>Dosis</th>
                                        <th>Pestisida</th>
                                        <th>Deskripsi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($detailJadwal as $index => $jadwal)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $jadwal->nama_fase }}</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($jadwal->tanggal_rekomendasi)->format('d/m/Y') }}</td>
                                        <td>
                                            @if($jadwal->pupuk)
                                                <span class="badge bg-info bg-opacity-10 text-info">{{ $jadwal->pupuk->nama }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($jadwal->dosis_dihitung)
                                                {{ number_format($jadwal->dosis_dihitung, 1) }} {{ $jadwal->pupuk->satuan ?? '' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($jadwal->pestisida)
                                                <span class="badge bg-danger bg-opacity-10 text-danger">{{ $jadwal->pestisida->nama }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td><small>{{ Str::limit($jadwal->deskripsi_aktivitas, 40) ?: '-' }}</small></td>
                                        <td>
                                            @if($jadwal->sudah_dikonfirmasi)
                                                <span class="badge bg-success">
                                                    <i class="ti ti-check me-1"></i> Selesai
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark">
                                                    <i class="ti ti-clock me-1"></i> Pending
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Belum ada jadwal</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="tutupDetail">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>