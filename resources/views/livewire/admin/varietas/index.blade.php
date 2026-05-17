<div>
    <div class="container-fluid" wire:ignore.self>
        
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><i class="ti ti-plant me-2"></i>Data Varietas Padi</h4>
                <p class="text-muted mb-0">Kelola data varietas padi</p>
            </div>
            <button wire:click="openCreateModal" class="btn btn-success">
                <i class="ti ti-plus me-1"></i> Tambah Varietas
            </button>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="d-flex flex-wrap justify-content-between align-items-center p-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted">Show</span>
                        <select wire:model.live="perPage" class="form-select form-select-sm w-auto">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                        </select>
                        <span class="text-muted">entries</span>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Cari..." style="width: 250px;">
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th wire:click="sortData('nama')" style="cursor:pointer;">
                                    Nama Varietas
                                    @if($sortBy === 'nama')
                                        <i class="ti ti-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </th>
                                <th wire:click="sortData('umur_panen')" style="cursor:pointer;">
                                    Umur Panen (HST)
                                    @if($sortBy === 'umur_panen')
                                        <i class="ti ti-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </th>
                                <th>Potensi Hasil (Ton/Ha)</th>
                                <th>Deskripsi</th>
                                <th>Fase Tumbuh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($varietas as $index => $item)
                            <tr>
                                <td>{{ $varietas->firstItem() + $index }}</td>
                                <td><strong class="text-success">{{ $item->nama }}</strong></td>
                                <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $item->umur_panen }} Hari</span></td>
                                <td>{{ $item->potensi_hasil ? number_format($item->potensi_hasil, 1) . ' Ton/Ha' : '-' }}</td>
                                <td><small>{{ Str::limit($item->deskripsi, 50) ?: '-' }}</small></td>
                                <td><span class="badge bg-info bg-opacity-10 text-info">{{ $item->faseTumbuh->count() }} Fase</span></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button wire:click="openEditModal({{ $item->id }})" class="btn btn-sm btn-outline-warning">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $item->id }})" class="btn btn-sm btn-outline-danger">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="ti ti-plant-off fs-1 text-muted"></i>
                                    <p class="mt-2 text-muted">Tidak ada data varietas padi.</p>
                                    <button wire:click="openCreateModal" class="btn btn-sm btn-success">
                                        <i class="ti ti-plus me-1"></i> Tambah Varietas
                                    </button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <small class="text-muted">
                        Menampilkan {{ $varietas->firstItem() ?? 0 }} - {{ $varietas->lastItem() ?? 0 }} 
                        dari {{ $varietas->total() }} data
                    </small>
                    {{ $varietas->links() }}
                </div>
            </div>
        </div>

        {{-- MODAL FORM --}}
        @if($showModal)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">
                            <i class="ti ti-plant me-1"></i> {{ $titleModal }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                    </div>
                    <form wire:submit.prevent="save">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Varietas <span class="text-danger">*</span></label>
                                <input type="text" wire:model="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Contoh: Ciherang, Inpari 32">
                                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Umur Panen (HST) <span class="text-danger">*</span></label>
                                <input type="number" wire:model="umur_panen" class="form-control @error('umur_panen') is-invalid @enderror" placeholder="Contoh: 110">
                                @error('umur_panen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <small class="text-muted">Hari Setelah Tanam</small>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Potensi Hasil (Ton/Ha)</label>
                                <input type="number" step="0.1" wire:model="potensi_hasil" class="form-control @error('potensi_hasil') is-invalid @enderror" placeholder="Contoh: 6.5">
                                @error('potensi_hasil') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea wire:model="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3" placeholder="Karakteristik varietas..."></textarea>
                                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">
                                <i class="ti ti-x me-1"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-check me-1"></i> {{ $isEdit ? 'Update' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

 @script
    <script>
            $wire.on('showAlert', (data) => {
                Swal.fire({
                    icon: data[0].type,
                    title: data[0].title,
                    text: data[0].text,
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: data[0].type !== 'success'
                });
            });

            $wire.on('showDeleteConfirmation', (data) => {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    html: `
                        <p>Apakah Anda yakin ingin menghapus varietas:</p>
                        <strong class="fs-5 text-success">${data[0].nama}</strong>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('deleteConfirmed');
                    }
                });
            });
    </script>
@endscript
