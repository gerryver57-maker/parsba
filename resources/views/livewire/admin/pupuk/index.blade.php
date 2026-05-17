<div>
    <div class="container-fluid" wire:ignore.self>
        
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><i class="ti ti-droplet me-2"></i>Data Pupuk</h4>
                <p class="text-muted mb-0">Kelola data pupuk dan dosis standar per hektar</p>
            </div>
            <button wire:click="bukaModalTambah" class="btn btn-success">
                <i class="ti ti-plus me-1"></i> Tambah Pupuk
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
                    <input type="text" wire:model.live.debounce.300ms="cari" class="form-control" placeholder="Cari pupuk..." style="width: 250px;">
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th wire:click="urutkan('nama')" style="cursor:pointer;">
                                    Nama Pupuk
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
                                <th>Dosis Standar (/Ha)</th>
                                <th>Satuan</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataPupuk as $index => $item)
                            <tr>
                                <td>{{ $dataPupuk->firstItem() + $index }}</td>
                                <td>
                                    <strong class="text-primary">{{ $item->nama }}</strong>
                                </td>
                                <td>
                                    @if($item->jenis)
                                        <span class="badge bg-success bg-opacity-10 text-success">{{ $item->jenis }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ number_format($item->dosis_standar_ha, 1) }}</span>
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
                                    <i class="ti ti-droplet-off fs-1 text-muted"></i>
                                    <p class="mt-2 text-muted">Tidak ada data pupuk.</p>
                                    <button wire:click="bukaModalTambah" class="btn btn-sm btn-success">
                                        <i class="ti ti-plus me-1"></i> Tambah Pupuk
                                    </button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <small class="text-muted">
                        Menampilkan {{ $dataPupuk->firstItem() ?? 0 }} - {{ $dataPupuk->lastItem() ?? 0 }} 
                        dari {{ $dataPupuk->total() }} data
                    </small>
                    {{ $dataPupuk->links() }}
                </div>
            </div>
        </div>

        {{-- MODAL FORM --}}
        @if($tampilModal)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="ti ti-droplet me-1"></i> {{ $judulModal }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="tutupModal"></button>
                    </div>
                    <form wire:submit.prevent="simpan">
                        <div class="modal-body">
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Pupuk <span class="text-danger">*</span></label>
                                <input type="text" wire:model="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Contoh: Urea, NPK Phonska">
                                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jenis Pupuk</label>
                                <select wire:model="jenis" class="form-select @error('jenis') is-invalid @enderror">
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="Tunggal">Tunggal</option>
                                    <option value="Majemuk">Majemuk</option>
                                    <option value="Organik">Organik</option>
                                    <option value="Hayati">Hayati</option>
                                    <option value="Daun">Daun</option>
                                </select>
                                @error('jenis') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Dosis Standar (/Ha) <span class="text-danger">*</span></label>
                                        <input type="number" step="0.1" wire:model="dosis_standar_ha" class="form-control @error('dosis_standar_ha') is-invalid @enderror" placeholder="Contoh: 200">
                                        @error('dosis_standar_ha') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Satuan <span class="text-danger">*</span></label>
                                        <select wire:model="satuan" class="form-select @error('satuan') is-invalid @enderror">
                                            <option value="">-- Pilih Satuan --</option>
                                            <option value="Kg">Kg</option>
                                            <option value="Liter">Liter</option>
                                            <option value="Gram">Gram</option>
                                            <option value="ml">ml</option>
                                            <option value="Ton">Ton</option>
                                        </select>
                                        @error('satuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="tutupModal">Batal</button>
                            <button type="submit" class="btn btn-primary">{{ $sedangEdit ? 'Update' : 'Simpan' }}</button>
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
                <p>Apakah Anda yakin ingin menghapus pupuk:</p>
                <strong class="fs-5 text-primary">${data[0].nama}</strong>
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