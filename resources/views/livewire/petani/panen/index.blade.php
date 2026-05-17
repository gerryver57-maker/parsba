<div>
    <div class="container-fluid">
        
        {{-- HEADER --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><i class="ti ti-basket me-2"></i>Panen Saya</h4>
                <p class="text-muted mb-0">Catat dan kelola hasil panen padi Anda</p>
            </div>
            @php
                $list = $this->listSiklusAktif ?? [];
                $siklusCount = is_countable($list) ? count($list) : 0;
            @endphp
            <button wire:click="openCreateModal" class="btn btn-success" {{ $siklusCount == 0 ? 'disabled' : '' }}>
                <i class="ti ti-plus me-1"></i> Catat Panen
            </button>
        </div>

        {{-- STATISTIK --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Panen</small>
                        <h3>{{ number_format((int)$this->totalPanen) }} <small class="text-muted fs-6">kali</small></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Hasil</small>
                        <h3>{{ number_format((float)$this->totalJumlah, 1) }} <small class="text-muted fs-6">Ton</small></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Rata-rata</small>
                        <h3>{{ number_format((float)$this->rataHasil, 1) }} <small class="text-muted fs-6">Ton/panen</small></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Siklus Aktif</small>
                        <h3>{{ $siklusCount }} <small class="text-muted fs-6">siap panen</small></h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABEL --}}
        <div class="card">
            <div class="card-body p-0">
                <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted">Show</span>
                        <select wire:model.live="perPage" class="form-select form-select-sm w-auto">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                        </select>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Cari..." style="width: 250px;">
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th wire:click="sortData('tanggal_panen')" style="cursor:pointer;">
                                    Tanggal Panen @if($this->sortBy==='tanggal_panen')<i class="ti ti-arrow-{{ $this->sortDirection==='asc'?'up':'down' }} ms-1"></i>@endif
                                </th>
                                <th>Lahan</th>
                                <th>Varietas</th>
                                <th wire:click="sortData('jumlah')" style="cursor:pointer;">
                                    Jumlah (Ton) @if($this->sortBy==='jumlah')<i class="ti ti-arrow-{{ $this->sortDirection==='asc'?'up':'down' }} ms-1"></i>@endif
                                </th>
                                <th>Kualitas</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($panen as $index => $item)
                            <tr>
                                <td>{{ $panen->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ Carbon\Carbon::parse($item->tanggal_panen)->format('d M Y') }}</strong>
                                </td>
                                <td>
                                    <i class="ti ti-map-pin text-success me-1"></i>
                                    {{ $item->siklusTanam->lahan->nama ?? '-' }}
                                </td>
                                <td>
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        {{ $item->siklusTanam->varietasPadi->nama ?? '-' }}
                                    </span>
                                </td>
                                <td class="fw-bold">{{ number_format($item->jumlah, 1) }} Ton</td>
                                <td>
                                    @if($item->kualitas == 'baik')
                                        <span class="badge bg-success">Baik</span>
                                    @elseif($item->kualitas == 'sedang')
                                        <span class="badge bg-warning">Sedang</span>
                                    @else
                                        <span class="badge bg-danger">Buruk</span>
                                    @endif
                                </td>
                                <td><small>{{ Str::limit($item->catatan, 30) ?: '-' }}</small></td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button wire:click="openEditModal({{ $item->id }})" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button wire:click="confirmDelete({{ $item->id }})" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="ti ti-basket-off fs-1"></i>
                                    <p class="mt-2">Belum ada data panen.</p>
                                    @if($siklusCount > 0)
                                        <button wire:click="openCreateModal" class="btn btn-sm btn-success">
                                            <i class="ti ti-plus me-1"></i> Catat Panen
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <small>Menampilkan {{ $panen->firstItem()??0 }}-{{ $panen->lastItem()??0 }} dari {{ $panen->total() }} data</small>
                    {{ $panen->links() }}
                </div>
            </div>
        </div>

        {{-- MODAL FORM --}}
        @if($this->showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="ti ti-basket me-1"></i> {{ $this->titleModal }}</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                    </div>
                    <form wire:submit.prevent="save">
                        <div class="modal-body">
                            
                            <div class="mb-3">
                                <label class="form-label">Pilih Siklus Tanam <span class="text-danger">*</span></label>
                                <select wire:model.live="siklus_tanam_id" class="form-select @error('siklus_tanam_id') is-invalid @enderror">
                                    <option value="">-- Pilih Siklus --</option>
                                    @foreach($list as $s)
                                        <option value="{{ $s->id }}">
                                            {{ $s->lahan->nama ?? '-' }} - {{ $s->varietasPadi->nama ?? '-' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('siklus_tanam_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                
                                @if($this->siklusDetail)
                                <div class="alert alert-info mt-2 small mb-0">
                                    <strong>{{ $this->siklusDetail->lahan->nama ?? '' }}</strong> | 
                                    {{ $this->siklusDetail->varietasPadi->nama ?? '' }} | 
                                    Tanam: {{ $this->siklusDetail->tanggal_tanam?->format('d M Y') }} |
                                    Perkiraan: {{ $this->siklusDetail->perkiraan_panen?->format('d M Y') }}
                                </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Panen <span class="text-danger">*</span></label>
                                <input type="date" wire:model="tanggal_panen" class="form-control @error('tanggal_panen') is-invalid @enderror">
                                @error('tanggal_panen') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jumlah (Ton) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" wire:model="jumlah" class="form-control @error('jumlah') is-invalid @enderror" placeholder="Contoh: 2.5">
                                @error('jumlah') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kualitas <span class="text-danger">*</span></label>
                                <select wire:model="kualitas" class="form-select @error('kualitas') is-invalid @enderror">
                                    <option value="baik">Baik</option>
                                    <option value="sedang">Sedang</option>
                                    <option value="buruk">Buruk</option>
                                </select>
                                @error('kualitas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea wire:model="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="2" placeholder="Opsional..."></textarea>
                                @error('catatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">
                                <i class="ti ti-x me-1"></i> Batal
                            </button>
                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-check me-1"></i> {{ $this->isEdit ? 'Update' : 'Simpan' }}
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

    $wire.on('tampilKonfirmasiHapus', (data) => {
        Swal.fire({
            title: 'Konfirmasi Hapus',
            html: `<p>Apakah Anda yakin ingin menghapus data panen:</p><strong class="fs-5 text-danger">${data[0].nama}</strong>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="ti ti-trash me-1"></i> Ya, Hapus!',
            cancelButtonText: '<i class="ti ti-x me-1"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) $wire.dispatch('deleteConfirmed');
        });
    });
</script>
@endscript