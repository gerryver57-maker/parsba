<div>
    <div class="container-fluid">
        
        {{-- HEADER --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><i class="ti ti-map me-2"></i>Lahan Saya</h4>
                <p class="text-muted mb-0">Kelola data lahan sawah Anda</p>
            </div>
            <button wire:click="openCreateModal" class="btn btn-success">
                <i class="ti ti-plus me-1"></i> Tambah Lahan
            </button>
        </div>

        {{-- TOTAL LAHAN, LUAS & KALKULATOR --}}
        @php
            $totalLahan = \App\Models\Lahan::where('user_id', Auth::id())->count();
            $totalLuas = \App\Models\Lahan::where('user_id', Auth::id())->sum('luas');
            $totalLungguk = $totalLuas * 6; // 1 Ha = 6 Lungguk
        @endphp
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Jumlah Lahan</small>
                        <h3>{{ $totalLahan }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Luas</small>
                        <h3>{{ number_format($totalLuas, 2) }} <small class="text-muted fs-6">Ha</small></h3>
                        <small class="text-muted">≈ {{ number_format($totalLungguk, 2) }} Lungguk</small>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h6 class="fw-bold mb-3"><i class="ti ti-calculator me-1"></i> Kalkulator Konversi Lungguk</h6>
            <p class="text-muted small mb-2">1 Lungguk = 1/6 Hektar | 1 Hektar = 6 Lungguk</p>
            <div class="row g-2 align-items-end">
                <div class="col-5">
                    <label class="form-label small">Lungguk</label>
                    <input type="number" 
                           id="inputLungguk" 
                           class="form-control form-control-sm" 
                           placeholder="Jumlah lungguk" 
                           min="0" 
                           step="0.5" 
                           oninput="konversiDariLungguk()">
                </div>
                <div class="col-2 text-center">
                    <div class="mt-3">
                        <i class="ti ti-arrows-left-right fs-5 text-success"></i>
                    </div>
                </div>
                <div class="col-5">
                    <label class="form-label small">Hektar (Ha)</label>
                    <input type="number" 
                           id="inputHektar" 
                           class="form-control form-control-sm" 
                           placeholder="Luas hektar" 
                           min="0" 
                           step="0.01" 
                           oninput="konversiDariHektar()">
                </div>
            </div>
            <div class="mt-2 p-2 bg-light rounded text-center" id="hasilKonversi" style="display:none;">
                <small class="text-muted">Hasil Konversi:</small><br>
                <strong id="teksKonversi" class="text-success"></strong>
            </div>
            <button onclick="resetKalkulator()" class="btn btn-sm btn-outline-secondary w-100 mt-2">
                <i class="ti ti-refresh me-1"></i> Reset
            </button>
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
                        <span class="text-muted">entries</span>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" class="form-control form-control-sm" placeholder="Cari lahan..." style="width: 250px;">
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th wire:click="sortData('nama')" style="cursor:pointer;">
                                    Nama Lahan @if($sortBy==='nama')<i class="ti ti-arrow-{{ $sortDirection==='asc'?'up':'down' }} ms-1"></i>@endif
                                </th>
                                <th>Luas (Ha)</th>
                                <th>Lungguk</th>
                                <th>Jenis Irigasi</th>
                                <th>Catatan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lahan as $index => $item)
                            <tr>
                                <td>{{ $lahan->firstItem() + $index }}</td>
                                <td>
                                    <i class="ti ti-map-pin text-success me-1"></i>
                                    <strong>{{ $item->nama }}</strong>
                                </td>
                                <td>{{ number_format($item->luas, 2) }} Ha</td>
                                <td>
                                    <span class="badge bg-info bg-opacity-10 text-info">
                                        {{ number_format($item->luas * 6, 0) }} Lungguk
                                    </span>
                                </td>
                                <td>
                                    @if($item->jenis_irigasi == 'irigasi')
                                        <span class="badge bg-primary">Irigasi</span>
                                    @elseif($item->jenis_irigasi == 'tadah_hujan')
                                        <span class="badge bg-info">Tadah Hujan</span>
                                    @else
                                        <span class="badge bg-secondary">Rawa</span>
                                    @endif
                                </td>
                                <td><small>{{ Str::limit($item->catatan, 50) ?: '-' }}</small></td>
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
                                    <i class="ti ti-map-off fs-1"></i>
                                    <p class="mt-2">Belum ada data lahan.</p>
                                    <button wire:click="openCreateModal" class="btn btn-sm btn-success">
                                        <i class="ti ti-plus me-1"></i> Tambah Lahan
                                    </button>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <small>Menampilkan {{ $lahan->firstItem()??0 }}-{{ $lahan->lastItem()??0 }} dari {{ $lahan->total() }} data</small>
                    {{ $lahan->links() }}
                </div>
            </div>
        </div>

        {{-- MODAL FORM --}}
        @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title"><i class="ti ti-map me-1"></i> {{ $titleModal }}</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                    </div>
                    <form wire:submit.prevent="save">
                        <div class="modal-body">
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Lahan <span class="text-danger">*</span></label>
                                <input type="text" wire:model="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Contoh: Sawah Belakang">
                                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Luas (Hektar) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" wire:model="luas" id="modalLuas" class="form-control @error('luas') is-invalid @enderror" placeholder="Contoh: 0.5">
                                @error('luas') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                <small class="text-success" id="konversiModal" style="display:none;"></small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jenis Irigasi <span class="text-danger">*</span></label>
                                <select wire:model="jenis_irigasi" class="form-select @error('jenis_irigasi') is-invalid @enderror">
                                    <option value="">-- Pilih --</option>
                                    <option value="irigasi">Irigasi</option>
                                    <option value="tadah_hujan">Tadah Hujan</option>
                                    <option value="rawa">Rawa</option>
                                </select>
                                @error('jenis_irigasi') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

@push('scripts')
<script>
    // ========== KALKULATOR LUNGGUK ==========
    function konversiDariLungguk() {
        let lungguk = parseFloat(document.getElementById('inputLungguk').value) || 0;
        let hektar = lungguk / 6;
        document.getElementById('inputHektar').value = hektar.toFixed(2);
        tampilkanHasil(lungguk, hektar);
    }

    function konversiDariHektar() {
        let hektar = parseFloat(document.getElementById('inputHektar').value) || 0;
        let lungguk = hektar * 6;
        document.getElementById('inputLungguk').value = lungguk.toFixed(0);
        tampilkanHasil(lungguk, hektar);
    }

    function tampilkanHasil(lungguk, hektar) {
        let hasilEl = document.getElementById('hasilKonversi');
        let teksEl = document.getElementById('teksKonversi');
        if (lungguk > 0 || hektar > 0) {
            hasilEl.style.display = 'block';
            teksEl.innerHTML = lungguk.toFixed(0) + ' Lungguk = ' + hektar.toFixed(2) + ' Hektar';
        }
    }

    function resetKalkulator() {
        document.getElementById('inputLungguk').value = '';
        document.getElementById('inputHektar').value = '';
        document.getElementById('hasilKonversi').style.display = 'none';
    }

    // ========== KONVERSI DI MODAL ==========
    document.addEventListener('DOMContentLoaded', function() {
        let modalLuas = document.getElementById('modalLuas');
        if (modalLuas) {
            modalLuas.addEventListener('input', function() {
                let ha = parseFloat(this.value) || 0;
                let lungguk = ha * 6;
                let el = document.getElementById('konversiModal');
                if (el) {
                    if (ha > 0) {
                        el.style.display = 'block';
                        el.innerHTML = '≈ ' + lungguk.toFixed(0) + ' Lungguk (1 Lungguk = 1/6 Ha)';
                    } else {
                        el.style.display = 'none';
                    }
                }
            });
        }
    });

    // ========== SWEETALERT2 ==========
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
                <p>Apakah Anda yakin ingin menghapus lahan:</p>
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
                $wire.dispatch('deleteConfirmed');
            }
        });
    });
</script>
@endpush