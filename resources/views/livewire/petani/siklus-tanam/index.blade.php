<div>
    <div class="container-fluid">
        
        {{-- HEADER --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><i class="ti ti-arrow-loop me-2"></i>Siklus Tanam</h4>
                <p class="text-muted mb-0">Kelola siklus tanam padi Anda</p>
            </div>
            <button wire:click="openCreateModal" class="btn btn-success">
                <i class="ti ti-plus me-1"></i> Buat Siklus Baru
            </button>
        </div>

        {{-- STATISTIK --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Siklus Aktif</small>
                        <h3 class="text-success">{{ $totalAktif ?? 0 }} </h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Siklus Selesai</small>
                        <h3 class="text-primary">{{ $totalSelesai ?? 0 }}</h3>
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
                                <th>Lahan</th>
                                <th>Varietas</th>
                                <th>Tanggal Tanam</th>
                                <th>Perkiraan Panen</th>
                                <th>Sisa Hari</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($daftarSiklus as $index => $item)
                            <tr>
                                <td>{{ $daftarSiklus->firstItem() + $index  }}</td>
                                <td>
                                    <i class="ti ti-map-pin text-success me-1"></i>
                                    <strong>{{ $item->lahan->nama ?? '-' }}</strong>
                                    <br><small class="text-muted">{{ $item->lahan->luas ?? 0 }} Ha</small>
                                </td>
                                <td>
                                    <span class="badge bg-success bg-opacity-10 text-success">
                                        {{ $item->varietasPadi->nama ?? '-' }}
                                    </span>
                                    <br><small class="text-muted">{{ $item->varietasPadi->umur_panen ?? 0 }} hari</small>
                                </td>
                                <td>{{ Carbon\Carbon::parse($item->tanggal_tanam)->format('d M Y') }}</td>
                                <td>{{ Carbon\Carbon::parse($item->perkiraan_panen)->format('d M Y') }}</td>
                                <td>
                                    @if($item->status == 'aktif')
                                        @php 
                                            $sisa = Carbon\Carbon::now()->diffInDays(Carbon\Carbon::parse($item->perkiraan_panen), false);
                                            $sisaBulat = ceil($sisa);
                                        @endphp
                                        @if($sisaBulat <= 0)
                                            <span class="badge bg-danger">Sudah Panen!</span>
                                        @elseif($sisaBulat <= 7)
                                            <span class="badge bg-warning">{{ $sisaBulat }} Hari</span>
                                        @elseif($sisaBulat <= 14)
                                            <span class="badge bg-info">{{ $sisaBulat }} Hari</span>
                                        @else
                                            <span class="badge bg-success">{{ $sisaBulat }} Hari</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status == 'aktif')
                                        <span class="badge bg-success rounded-pill">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a wire:navigate href="{{ url('petani/jadwal/index?siklus='.$item->id) }}" class="btn btn-sm btn-outline-info" title="Lihat Jadwal">
                                            <i class="ti ti-calendar"></i>
                                        </a>
                                        @if($item->status == 'aktif')
                                        <button wire:click="confirmSelesai({{ $item->id }})" class="btn btn-sm btn-outline-success" title="Selesaikan">
                                            <i class="ti ti-check"></i>
                                        </button>
                                        @endif
                                        <button wire:click="confirmDelete({{ $item->id }})" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="ti ti-plant-off fs-1"></i>
                                    <p class="mt-2">Belum ada siklus tanam.</p>
                                    <button wire:click="openCreateModal" class="btn btn-sm btn-success">
                                        <i class="ti ti-plus me-1"></i> Buat Siklus Baru
                                    </button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <small>Menampilkan {{ $daftarSiklus->firstItem()??0 }}-{{ $daftarSiklus->lastItem()??0 }} dari {{ $daftarSiklus->total() }} data</small>
                    {{ $daftarSiklus->links() }}
                </div>
            </div>
        </div>

        {{-- MODAL FORM --}}
        @if($this->showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="ti ti-arrow-loop me-1"></i> {{ $titleModal }}</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                    </div>
                    <form wire:submit.prevent="save">
                        <div class="modal-body">
                            
                            <div class="mb-3">
                                <label class="form-label">Pilih Lahan <span class="text-danger">*</span></label>
                                <select wire:model="lahan_id" class="form-select @error('lahan_id') is-invalid @enderror">
                                    <option value="">-- Pilih Lahan --</option>
                                    @foreach($this->listLahan as $lahan)
                                        <option value="{{ $lahan->id }}">{{ $lahan->nama }} ({{ $lahan->luas }} Ha)</option>
                                    @endforeach
                                </select>
                                @error('lahan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                @if(is_countable($this->listLahan) && count($this->listLahan) == 0)
                                    <small class="text-warning">Belum ada lahan. <a wire:navigate href="{{ url('petani/lahan') }}">Tambah lahan dulu</a></small>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Pilih Varietas Padi <span class="text-danger">*</span></label>
                                <select wire:model.live="varietas_padi_id" class="form-select @error('varietas_padi_id') is-invalid @enderror">
                                    <option value="">-- Pilih Varietas --</option>
                                    @foreach($this->listVarietas as $v)
                                        <option value="{{ $v->id }}">{{ $v->nama }} ({{ $v->umur_panen }} hari)</option>
                                    @endforeach
                                </select>
                                @error('varietas_padi_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                
                                @if($this->varietasDetail)
                                <div class="alert alert-info mt-2 small mb-0">
                                    <strong>{{ $this->varietasDetail->nama }}</strong><br>
                                    Umur panen: {{ $this->varietasDetail->umur_panen }} hari<br>
                                    Potensi hasil: {{ $this->varietasDetail->potensi_hasil ?? '-' }} Ton/Ha
                                </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Tanggal Tanam <span class="text-danger">*</span></label>
                                <input type="date" wire:model="tanggal_tanam" class="form-control @error('tanggal_tanam') is-invalid @enderror">
                                @error('tanggal_tanam') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                
                                @if($tanggal_tanam && $this->varietasDetail)
                                <small class="text-success">
                                    <i class="ti ti-calendar-check me-1"></i> 
                                    Perkiraan panen: {{ Carbon\Carbon::parse($tanggal_tanam)->addDays($this->varietasDetail->umur_panen)->format('d M Y') }}
                                </small>
                                @endif
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
            html: `<p>Apakah Anda yakin ingin menghapus siklus:</p><strong class="fs-5 text-danger">${data[0].nama}</strong>`,
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

    $wire.on('tampilKonfirmasiSelesai', (data) => {
        Swal.fire({
            title: 'Selesaikan Siklus?',
            html: `<p>Siklus <strong>${data[0].nama}</strong> akan ditandai selesai.</p>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="ti ti-check me-1"></i> Selesaikan',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) $wire.dispatch('konfirmasiSelesai');
        });
    });
</script>
@endscript