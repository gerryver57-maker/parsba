<div>
    <div class="container-fluid" wire:ignore.self>
        
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><i class="ti ti-calendar me-2"></i>Fase Tumbuh Padi</h4>
                <p class="text-muted mb-0">Atur jadwal pemupukan & penyemprotan berdasarkan Hari Setelah Tanam (HST)</p>
            </div>
            <button wire:click="bukaModalTambah" class="btn btn-success">
                <i class="ti ti-plus me-1"></i> Tambah Fase
            </button>
        </div>

        <div class="card">
            <div class="card-body p-0">
                {{-- TOOLBAR --}}
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
                        <select wire:model.live="filterVarietas" class="form-select form-select-sm" style="width: 200px;">
                            <option value="">Semua Varietas</option>
                            @foreach($daftarVarietas as $v)
                                <option value="{{ $v->id }}">{{ $v->nama }}</option>
                            @endforeach
                        </select>
                        <input type="text" wire:model.live.debounce.300ms="cari" class="form-control form-control-sm" placeholder="Cari fase..." style="width: 200px;">
                    </div>
                </div>

                {{-- TABEL --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th>Varietas</th>
                                <th>Nama Fase</th>
                                <th width="10%">HST</th>
                                <th>Pupuk</th>
                                <th>Pestisida</th>
                                <th>Deskripsi</th>
                                <th width="12%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataFase as $index => $item)
                            <tr>
                                <td>{{ $dataFase->firstItem() + $index }}</td>
                                <td>
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        <i class="ti ti-plant me-1"></i> {{ $item->varietasPadi->nama ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <strong>{{ $item->nama_fase }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary fs-6">
                                        {{ $item->hari_setelah_tanam }} HST
                                    </span>
                                </td>
                                <td>
                                    @if($item->pupuk)
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            <i class="ti ti-droplet me-1"></i> 
                                            {{ $item->pupuk->nama }} 
                                            ({{ number_format($item->pupuk->dosis_standar_ha, 1) }} {{ $item->pupuk->satuan }}/Ha)
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->pestisida)
                                        <span class="badge bg-danger bg-opacity-10 text-danger">
                                            <i class="ti ti-shield me-1"></i> {{ $item->pestisida->nama }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ Str::limit($item->deskripsi, 40) ?: '-' }}</small>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <button wire:click="lihatDetail({{ $item->id }})" class="btn btn-sm btn-outline-info" title="Detail">
                                            <i class="ti ti-eye"></i>
                                        </button>
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
                                <td colspan="8" class="text-center py-5">
                                    <i class="ti ti-calendar-off fs-1 text-muted"></i>
                                    <p class="mt-2 text-muted">Tidak ada data fase tumbuh.</p>
                                    <button wire:click="bukaModalTambah" class="btn btn-sm btn-success">
                                        <i class="ti ti-plus me-1"></i> Tambah Fase
                                    </button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <small class="text-muted">
                        Menampilkan {{ $dataFase->firstItem() ?? 0 }} - {{ $dataFase->lastItem() ?? 0 }} 
                        dari {{ $dataFase->total() }} data
                    </small>
                    {{ $dataFase->links() }}
                </div>
            </div>
        </div>

        {{-- MODAL FORM --}}
        @if($tampilModal)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="ti ti-calendar me-1"></i> {{ $judulModal }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="tutupModal"></button>
                    </div>
                    <form wire:submit.prevent="simpan">
                        <div class="modal-body">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Varietas Padi <span class="text-danger">*</span></label>
                                        <select wire:model="varietas_padi_id" class="form-select @error('varietas_padi_id') is-invalid @enderror">
                                            <option value="">-- Pilih Varietas --</option>
                                            @foreach($daftarVarietas as $v)
                                                <option value="{{ $v->id }}">{{ $v->nama }} ({{ $v->umur_panen }} HST)</option>
                                            @endforeach
                                        </select>
                                        @error('varietas_padi_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Hari Setelah Tanam (HST) <span class="text-danger">*</span></label>
                                        <input type="number" wire:model="hari_setelah_tanam" class="form-control @error('hari_setelah_tanam') is-invalid @enderror" placeholder="Contoh: 7">
                                        @error('hari_setelah_tanam') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Fase <span class="text-danger">*</span></label>
                                <input type="text" wire:model="nama_fase" class="form-control @error('nama_fase') is-invalid @enderror" placeholder="Contoh: Pemupukan Dasar, Anakan Aktif, Primordia">
                                @error('nama_fase') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Rekomendasi Pupuk</label>
                                        <select wire:model="pupuk_id" class="form-select @error('pupuk_id') is-invalid @enderror">
                                            <option value="">-- Tidak Ada --</option>
                                            @foreach($daftarPupuk as $p)
                                                <option value="{{ $p->id }}">{{ $p->nama }} ({{ $p->dosis_standar_ha }} {{ $p->satuan }}/Ha)</option>
                                            @endforeach
                                        </select>
                                        @error('pupuk_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Rekomendasi Pestisida</label>
                                        <select wire:model="pestisida_id" class="form-select @error('pestisida_id') is-invalid @enderror">
                                            <option value="">-- Tidak Ada --</option>
                                            @foreach($daftarPestisida as $ps)
                                                <option value="{{ $ps->id }}">{{ $ps->nama }} ({{ $ps->hama_target }})</option>
                                            @endforeach
                                        </select>
                                        @error('pestisida_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi Aktivitas</label>
                                <textarea wire:model="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="2" placeholder="Detail aktivitas yang dilakukan..."></textarea>
                                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

        {{-- MODAL DETAIL --}}
        @if($tampilDetail && $detailFase)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">
                            <i class="ti ti-eye me-1"></i> Detail Fase Tumbuh
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="tutupDetail"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-sm table-borderless">
                            <tr><td width="40%"><strong>Varietas</strong></td><td>: {{ $detailFase->varietasPadi->nama ?? '-' }} ({{ $detailFase->varietasPadi->umur_panen ?? '-' }} HST)</td></tr>
                            <tr><td><strong>Nama Fase</strong></td><td>: {{ $detailFase->nama_fase }}</td></tr>
                            <tr><td><strong>HST</strong></td><td>: {{ $detailFase->hari_setelah_tanam }} hari</td></tr>
                            <tr><td><strong>Pupuk</strong></td><td>: {{ $detailFase->pupuk->nama ?? 'Tidak ada' }}</td></tr>
                            <tr><td><strong>Pestisida</strong></td><td>: {{ $detailFase->pestisida->nama ?? 'Tidak ada' }}</td></tr>
                            <tr><td><strong>Deskripsi</strong></td><td>: {{ $detailFase->deskripsi ?: '-' }}</td></tr>
                        </table>
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
                <p>Apakah Anda yakin ingin menghapus fase:</p>
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