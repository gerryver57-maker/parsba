<div>
    <div class="container-fluid">
        
        {{-- HEADER --}}
        <div class="mb-4">
            <h4 class="fw-bold mb-1"><i class="ti ti-plant me-2"></i>Info Varietas Padi</h4>
            <p class="text-muted mb-0">Informasi lengkap varietas padi yang tersedia</p>
        </div>

        {{-- SEARCH & FILTER --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted">Tampil</span>
                <select wire:model.live="perPage" class="form-select form-select-sm w-auto">
                    <option value="8">8</option>
                    <option value="12">12</option>
                    <option value="16">16</option>
                </select>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Cari varietas..." style="width: 300px;">
        </div>

        {{-- CARD GRID --}}
        <div class="row g-4">
            @forelse($varietas as $item)
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        {{-- Icon --}}
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-flex mb-3">
                            <i class="ti ti-plant fs-2 text-success"></i>
                        </div>
                        
                        {{-- Nama --}}
                        <h5 class="fw-bold mb-1">{{ $item->nama }}</h5>
                        
                        {{-- Umur Panen --}}
                        <div class="mb-2">
                            <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                <i class="ti ti-calendar me-1"></i> {{ $item->umur_panen }} Hari (HST)
                            </span>
                        </div>
                        
                        {{-- Potensi Hasil --}}
                        <p class="mb-2">
                            <i class="ti ti-weight text-muted me-1"></i>
                            <strong>{{ $item->potensi_hasil ? number_format($item->potensi_hasil, 1) : '-' }}</strong> Ton/Ha
                        </p>
                        
                        {{-- Deskripsi --}}
                        <p class="text-muted small mb-3">
                            {{ Str::limit($item->deskripsi, 80) ?: 'Tidak ada deskripsi.' }}
                        </p>
                        
                        {{-- Jumlah Fase --}}
                        <div class="mb-3">
                            <span class="badge bg-info bg-opacity-10 text-info">
                                <i class="ti ti-list-check me-1"></i> {{ $item->faseTumbuh->count() }} Fase Tumbuh
                            </span>
                        </div>
                        
                        {{-- Tombol Detail --}}
                        <button wire:click="lihatDetail({{ $item->id }})" class="btn btn-sm btn-outline-success w-100">
                            <i class="ti ti-eye me-1"></i> Lihat Detail
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="ti ti-plant-off fs-1"></i>
                <p class="mt-2">Tidak ada data varietas padi.</p>
            </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $varietas->links() }}
        </div>

        {{-- MODAL DETAIL --}}
        @if($showDetail && $varietasDetail)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="ti ti-plant me-1"></i> {{ $varietasDetail->nama }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="tutupDetail"></button>
                    </div>
                    <div class="modal-body">
                        
                        {{-- INFO DASAR --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <table class="table table-sm">
                                    <tr>
                                        <td width="40%"><strong>Umur Panen</strong></td>
                                        <td>: {{ $varietasDetail->umur_panen }} Hari (HST)</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Potensi Hasil</strong></td>
                                        <td>: {{ $varietasDetail->potensi_hasil ? number_format($varietasDetail->potensi_hasil, 1) . ' Ton/Ha' : '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-light border">
                                    <strong>Deskripsi:</strong><br>
                                    {{ $varietasDetail->deskripsi ?: 'Tidak ada deskripsi.' }}
                                </div>
                            </div>
                        </div>

                        {{-- FASE TUMBUH --}}
                        <h6 class="fw-bold mb-3"><i class="ti ti-list-check me-1"></i> Fase Tumbuh & Rekomendasi</h6>
                        
                        @if($varietasDetail->faseTumbuh->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center">HST</th>
                                        <th>Fase</th>
                                        <th>Pupuk</th>
                                        <th>Pestisida</th>
                                        <th>Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($varietasDetail->faseTumbuh as $fase)
                                    <tr>
                                        <td class="text-center fw-bold">{{ $fase->hari_setelah_tanam }}</td>
                                        <td>{{ $fase->nama_fase }}</td>
                                        <td>
                                            @if($fase->pupuk)
                                                <span class="badge bg-success">
                                                    {{ $fase->pupuk->nama }} ({{ $fase->pupuk->dosis_standar_ha }} {{ $fase->pupuk->satuan }}/Ha)
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($fase->pestisida)
                                                <span class="badge bg-warning">{{ $fase->pestisida->nama }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td><small>{{ Str::limit($fase->deskripsi, 60) ?: '-' }}</small></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-3 text-muted">
                            <i class="ti ti-info-circle fs-1"></i>
                            <p class="mt-2">Belum ada data fase tumbuh untuk varietas ini.</p>
                        </div>
                        @endif

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="tutupDetail">
                            <i class="ti ti-x me-1"></i> Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>