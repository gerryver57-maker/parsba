<div>
    <div class="container-fluid">
        
        {{-- HEADER --}}
        <div class="mb-4">
            <h4 class="fw-bold mb-1"><i class="ti ti-bug me-2"></i>Hama & Penyakit</h4>
            <p class="text-muted mb-0">Informasi hama dan penyakit tanaman padi serta cara pengendaliannya</p>
        </div>

        {{-- FILTER --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div class="d-flex align-items-center gap-2">
                <span class="text-muted">Filter</span>
                <select wire:model.live="filterJenis" class="form-select form-select-sm w-auto">
                    <option value="">Semua</option>
                    <option value="hama">🐛 Hama</option>
                    <option value="penyakit">🦠 Penyakit</option>
                </select>
                <span class="text-muted">| Tampil</span>
                <select wire:model.live="perPage" class="form-select form-select-sm w-auto">
                    <option value="8">8</option>
                    <option value="12">12</option>
                    <option value="16">16</option>
                </select>
            </div>
            <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Cari hama, gejala..." style="width: 300px;">
        </div>

        {{-- CARD GRID --}}
        <div class="row g-4">
            @forelse($hama as $item)
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        {{-- Icon --}}
                        <div class="rounded-circle bg-{{ $item->jenis == 'hama' ? 'danger' : 'warning' }} bg-opacity-10 p-3 d-inline-flex mb-3">
                            <i class="ti ti-{{ $item->jenis == 'hama' ? 'bug' : 'virus' }} fs-2 text-{{ $item->jenis == 'hama' ? 'danger' : 'warning' }}"></i>
                        </div>
                        
                        {{-- Nama --}}
                        <h5 class="fw-bold mb-1">{{ $item->nama }}</h5>
                        
                        {{-- Jenis --}}
                        <div class="mb-2">
                            <span class="badge bg-{{ $item->jenis == 'hama' ? 'danger' : 'warning' }} bg-opacity-10 text-{{ $item->jenis == 'hama' ? 'danger' : 'warning' }} px-3 py-2">
                                <i class="ti ti-{{ $item->jenis == 'hama' ? 'bug' : 'virus' }} me-1"></i>
                                {{ $item->jenis == 'hama' ? 'Hama' : 'Penyakit' }}
                            </span>
                        </div>
                        
                        {{-- Gejala --}}
                        <p class="text-muted small mb-3">
                            <i class="ti ti-zoom-exclamation me-1"></i>
                            {{ Str::limit($item->gejala, 80) ?: 'Belum ada data gejala.' }}
                        </p>
                        
                        {{-- Tombol Detail --}}
                        <button wire:click="lihatDetail({{ $item->id }})" class="btn btn-sm btn-outline-{{ $item->jenis == 'hama' ? 'danger' : 'warning' }} w-100">
                            <i class="ti ti-eye me-1"></i> Lihat Detail & Rekomendasi
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">
                <i class="ti ti-bug-off fs-1"></i>
                <p class="mt-2">Tidak ada data hama & penyakit.</p>
            </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $hama->links() }}
        </div>

        {{-- MODAL DETAIL --}}
        @if($showDetail && $hamaDetail)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-{{ $hamaDetail->jenis == 'hama' ? 'danger' : 'warning' }} text-white">
                        <h5 class="modal-title">
                            <i class="ti ti-{{ $hamaDetail->jenis == 'hama' ? 'bug' : 'virus' }} me-1"></i>
                            {{ $hamaDetail->nama }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="tutupDetail"></button>
                    </div>
                    <div class="modal-body">
                        
                        {{-- JENIS --}}
                        <div class="mb-4">
                            <span class="badge bg-{{ $hamaDetail->jenis == 'hama' ? 'danger' : 'warning' }} fs-6 px-3 py-2">
                                <i class="ti ti-{{ $hamaDetail->jenis == 'hama' ? 'bug' : 'virus' }} me-1"></i>
                                {{ $hamaDetail->jenis == 'hama' ? 'Hama' : 'Penyakit' }}
                            </span>
                        </div>

                        {{-- GEJALA --}}
                        <div class="card mb-3 border-danger bg-danger bg-opacity-10">
                            <div class="card-body">
                                <h6 class="fw-bold text-danger">
                                    <i class="ti ti-zoom-exclamation me-1"></i> Gejala
                                </h6>
                                <p class="mb-0">{{ $hamaDetail->gejala ?: 'Belum ada data gejala.' }}</p>
                            </div>
                        </div>

                        {{-- REKOMENDASI --}}
                        <div class="card border-success bg-success bg-opacity-10">
                            <div class="card-body">
                                <h6 class="fw-bold text-success">
                                    <i class="ti ti-shield-check me-1"></i> Rekomendasi Pengendalian
                                </h6>
                                <p class="mb-0">{{ $hamaDetail->rekomendasi ?: 'Belum ada data rekomendasi.' }}</p>
                            </div>
                        </div>

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