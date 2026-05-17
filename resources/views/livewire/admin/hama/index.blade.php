<div>
    <div class="container-fluid" wire:ignore.self>
        
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><i class="ti ti-bug me-2"></i>Data Hama & Penyakit</h4>
                <p class="text-muted mb-0">Kelola data hama, penyakit, gejala, dan rekomendasi pengendalian</p>
            </div>
            <button wire:click="bukaModalTambah" class="btn btn-success">
                <i class="ti ti-plus me-1"></i> Tambah Data
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
                    <input type="text" wire:model.live.debounce.300ms="cari" class="form-control" placeholder="Cari hama, gejala..." style="width: 250px;">
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th wire:click="urutkan('nama')" style="cursor:pointer;">
                                    Nama
                                    @if($kolomUrut === 'nama')
                                        <i class="ti ti-arrow-{{ $arahUrut === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </th>
                                <th wire:click="urutkan('jenis')" style="cursor:pointer;">
                                    Jenis
                                    @if($kolomUrut === 'jenis')
                                        <i class="ti ti-arrow-{{ $arahUrut === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </th>
                                <th>Gejala</th>
                                <th>Rekomendasi</th>
                                <th width="12%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataHama as $index => $item)
                            <tr>
                                <td>{{ $dataHama->firstItem() + $index }}</td>
                                <td>
                                    <strong class="{{ $item->jenis === 'hama' ? 'text-danger' : 'text-warning' }}">
                                        {{ $item->nama }}
                                    </strong>
                                </td>
                                <td>
                                    @if($item->jenis === 'hama')
                                        <span class="badge bg-danger bg-opacity-10 text-danger">
                                            <i class="ti ti-bug me-1"></i> Hama
                                        </span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning">
                                            <i class="ti ti-virus me-1"></i> Penyakit
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ Str::limit($item->gejala, 60) ?: '-' }}</small>
                                </td>
                                <td>
                                    <small>{{ Str::limit($item->rekomendasi, 60) ?: '-' }}</small>
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
                                    <i class="ti ti-bug-off fs-1 text-muted"></i>
                                    <p class="mt-2 text-muted">Tidak ada data hama & penyakit.</p>
                                    <button wire:click="bukaModalTambah" class="btn btn-sm btn-success">
                                        <i class="ti ti-plus me-1"></i> Tambah Data
                                    </button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <small class="text-muted">
                        Menampilkan {{ $dataHama->firstItem() ?? 0 }} - {{ $dataHama->lastItem() ?? 0 }} 
                        dari {{ $dataHama->total() }} data
                    </small>
                    {{ $dataHama->links() }}
                </div>
            </div>
        </div>

        {{-- MODAL FORM --}}
        @if($tampilModal)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">
                            <i class="ti ti-bug me-1"></i> {{ $judulModal }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="tutupModal"></button>
                    </div>
                    <form wire:submit.prevent="simpan">
                        <div class="modal-body">
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Hama/Penyakit <span class="text-danger">*</span></label>
                                        <input type="text" wire:model="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Contoh: Wereng Coklat, Blast">
                                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Jenis <span class="text-danger">*</span></label>
                                        <select wire:model="jenis" class="form-select @error('jenis') is-invalid @enderror">
                                            <option value="">-- Pilih --</option>
                                            <option value="hama">Hama</option>
                                            <option value="penyakit">Penyakit</option>
                                        </select>
                                        @error('jenis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Gejala</label>
                                <textarea wire:model="gejala" class="form-control @error('gejala') is-invalid @enderror" rows="3" placeholder="Deskripsikan gejala yang ditimbulkan..."></textarea>
                                @error('gejala') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <small class="text-muted">Maksimal 1000 karakter</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Rekomendasi Pengendalian</label>
                                <textarea wire:model="rekomendasi" class="form-control @error('rekomendasi') is-invalid @enderror" rows="3" placeholder="Cara pengendalian yang disarankan..."></textarea>
                                @error('rekomendasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <small class="text-muted">Maksimal 1000 karakter</small>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="tutupModal">Batal</button>
                            <button type="submit" class="btn btn-warning">{{ $sedangEdit ? 'Update' : 'Simpan' }}</button>
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
                <p>Apakah Anda yakin ingin menghapus:</p>
                <strong class="fs-5 text-warning">${data[0].nama}</strong>
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