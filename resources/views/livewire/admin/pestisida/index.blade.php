<div>
    <div class="container-fluid" wire:ignore.self>
        
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><i class="ti ti-shield me-2"></i>Data Pestisida</h4>
                <p class="text-muted mb-0">Kelola data pestisida, target hama, dan dosis standar</p>
            </div>
            <button wire:click="bukaModalTambah" class="btn btn-success">
                <i class="ti ti-plus me-1"></i> Tambah Pestisida
            </button>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <div class="d-flex flex-wrap justify-content-between align-items-center p-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted">Show</span>
                        <select wire:model.live="jumlahData" class="form-select form-select-sm w-auto">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                        </select>
                        <span class="text-muted">entries</span>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="cari" class="form-control" placeholder="Cari pestisida atau hama..." style="width: 250px;">
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th wire:click="urutkan('nama')" style="cursor:pointer;">
                                    Nama Pestisida
                                    @if($kolomUrut === 'nama')
                                        <i class="ti ti-arrow-{{ $arahUrut === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </th>
                                <th wire:click="urutkan('hama_target')" style="cursor:pointer;">
                                    Target Hama
                                    @if($kolomUrut === 'hama_target')
                                        <i class="ti ti-arrow-{{ $arahUrut === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </th>
                                <th>Dosis Standar (/Ha)</th>
                                <th>Satuan</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataPestisida as $index => $item)
                            <tr>
                                <td>{{ $dataPestisida->firstItem() + $index }}</td>
                                <td>
                                    <strong class="text-danger">{{ $item->nama }}</strong>
                                </td>
                                <td>
                                    @if($item->hama_target)
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                            <i class="ti ti-bug me-1"></i> {{ $item->hama_target }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->dosis_standar_ha)
                                        <span class="fw-semibold">{{ number_format($item->dosis_standar_ha, 1) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $item->satuan }}</span>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button wire:click="bukaModalEdit({{ $item->id }})" class="btn btn-sm btn-outline-warning" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button wire:click="konfirmasiHapus({{ $item->id }})" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="ti ti-shield-off fs-1 text-muted"></i>
                                    <p class="mt-2 text-muted">Tidak ada data pestisida.</p>
                                    <button wire:click="bukaModalTambah" class="btn btn-sm btn-success">
                                        <i class="ti ti-plus me-1"></i> Tambah Pestisida
                                    </button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <small class="text-muted">
                        Menampilkan {{ $dataPestisida->firstItem() ?? 0 }} - {{ $dataPestisida->lastItem() ?? 0 }} 
                        dari {{ $dataPestisida->total() }} data
                    </small>
                    {{ $dataPestisida->links() }}
                </div>
            </div>
        </div>

        {{-- MODAL FORM --}}
        @if($tampilModal)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="ti ti-shield me-1"></i> {{ $judulModal }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="tutupModal"></button>
                    </div>
                    <form wire:submit.prevent="simpan">
                        <div class="modal-body">
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Pestisida <span class="text-danger">*</span></label>
                                <input type="text" wire:model="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Contoh: Regent, Dithane">
                                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Target Hama</label>
                                <input type="text" wire:model="hama_target" class="form-control @error('hama_target') is-invalid @enderror" placeholder="Contoh: Wereng Coklat, Tikus">
                                @error('hama_target') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <small class="text-muted">Hama atau penyakit yang menjadi target pestisida ini</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Dosis Standar (/Ha)</label>
                                        <input type="number" step="0.1" wire:model="dosis_standar_ha" class="form-control @error('dosis_standar_ha') is-invalid @enderror" placeholder="Contoh: 2">
                                        @error('dosis_standar_ha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Satuan <span class="text-danger">*</span></label>
                                        <select wire:model="satuan" class="form-select @error('satuan') is-invalid @enderror">
                                            <option value="">-- Pilih Satuan --</option>
                                            <option value="ml">ml</option>
                                            <option value="Liter">Liter</option>
                                            <option value="Gram">Gram</option>
                                            <option value="Kg">Kg</option>
                                            <option value="cc">cc</option>
                                        </select>
                                        @error('satuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="tutupModal">Batal</button>
                            <button type="submit" class="btn btn-danger">{{ $sedangEdit ? 'Update' : 'Simpan' }}</button>
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
            html: `
                <p>Apakah Anda yakin ingin menghapus pestisida:</p>
                <strong class="fs-5 text-danger">${data[0].nama}</strong>
                <p class="text-muted mt-2 small">Data yang sudah dihapus tidak dapat dikembalikan.</p>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: '<i class="ti ti-trash me-1"></i> Ya, Hapus!',
            cancelButtonText: '<i class="ti ti-x me-1"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $wire.dispatch('hapusDikonfirmasi');
            }
        });
    });
</script>
@endscript