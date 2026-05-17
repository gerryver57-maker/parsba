<!-- resources/views/livewire/admin/data-user.blade.php -->

<div>
    <div class="page-wrapper">
        <div class="container-fluid" wire:ignore.self>
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title mb-1">
                                <i class="ti ti-users me-2 text-success"></i> Data Petani
                            </h4>
                            <p class="text-muted mb-0">Kelola data petani yang terdaftar di sistem PARSBA</p>
                        </div>
                        <button wire:click="openModal" class="btn btn-success">
                            <i class="ti ti-plus me-1"></i> Tambah Petani
                        </button>
                    </div>
                </div>
            </div>

            <!-- Filter dan Search -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="ti ti-search text-muted"></i>
                        </span>
                        <input type="text" wire:model.live.debounce.300ms="search" 
                               class="form-control border-start-0 ps-0" 
                               placeholder="Cari NIK, Nama, atau No HP...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select wire:model.live="perPage" class="form-select">
                        <option value="10">10 data</option>
                        <option value="25">25 data</option>
                        <option value="50">50 data</option>
                        <option value="100">100 data</option>
                    </select>
                </div>
                <div class="col-md-5 text-end">
                    <span class="text-muted">
                        <i class="ti ti-users me-1"></i> Total Petani: {{ $users->total() }}
                    </span>
                </div>
            </div>

            <!-- Loading State -->
            <div wire:loading class="text-center py-5">
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2 text-muted">Memuat data...</p>
            </div>

            <!-- Tabel Data Petani -->
            <div wire:loading.remove class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="40" class="ps-3">#</th>
                                            <th>NIK</th>
                                            <th width="250">Informasi Petani</th>
                                            <th>No HP</th>
                                            <th>Alamat</th>
                                            <th width="180">Terakhir Login</th>
                                            <th width="100" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $index => $user)
                                        <tr>
                                            <td class="ps-3">{{ $users->firstItem() + $index }}</td>
                                            <td>
                                                <span class="font-monospace">{{ $user->NIK }}</span>
                                            </td>
                                            <!-- Kolom Informasi Petani (Nama + Tanggal Lahir + Umur) -->
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm rounded-circle bg-light d-flex align-items-center justify-content-center me-3">
                                                        <i class="ti ti-user text-success fs-5"></i>
                                                    </div>
                                                    <div class="info-petani">
                                                        <div class="fw-bold text-dark mb-1">
                                                            {{ $user->nama }}
                                                        </div>
                                                        <div class="d-flex flex-wrap gap-2">
                                                            @if($user->ttl)
                                                                <span class="badge bg-light text-dark">
                                                                    <i class="ti ti-calendar me-1"></i>
                                                                    Lahir: {{ \Carbon\Carbon::parse($user->ttl)->translatedFormat('d M Y') }}
                                                                </span>
                                                                <span class="badge bg-success bg-opacity-10 text-success">
                                                                    <i class="ti ti-cake me-1"></i>
                                                                    Umur: {{ \Carbon\Carbon::parse($user->ttl)->age }} tahun
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                                    <i class="ti ti-calendar-off me-1"></i>
                                                                    Tanggal lahir belum diisi
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($user->nohp)
                                                    <span class="font-monospace">
                                                        <i class="ti ti-phone me-1 text-success"></i>
                                                        {{ $user->nohp }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->alamat)
                                                    <span title="{{ $user->alamat }}">
                                                        {{ Str::limit($user->alamat, 30) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->last_login_at)
                                                    <div class="d-flex flex-column">
                                                        <span class="fw-semibold text-success">
                                                            <i class="ti ti-clock me-1"></i>
                                                            {{ $user->last_login_human }}
                                                        </span>
                                                        <small class="text-muted">
                                                            {{ $user->last_login_formatted }}
                                                        </small>
                                                    </div>
                                                @else
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                        <i class="ti ti-eye-off me-1"></i> Belum pernah login
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button wire:click="edit({{ $user->id }})" 
                                                        class="btn btn-sm btn-outline-primary me-1" 
                                                        title="Edit">
                                                    <i class="ti ti-edit"></i>
                                                </button>
                                                <button wire:click="openDeleteModal({{ $user->id }})" 
                                                        class="btn btn-sm btn-outline-danger" 
                                                        title="Hapus">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <i class="ti ti-users-off fs-1 text-muted mb-2 d-block"></i>
                                                <p class="text-muted mb-0">Tidak ada data petani</p>
                                                <button wire:click="openModal" class="btn btn-sm btn-success mt-2">
                                                    <i class="ti ti-plus"></i> Tambah Petani
                                                </button>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted">
                                        Menampilkan {{ $users->firstItem() }} - {{ $users->lastItem() }} 
                                        dari {{ $users->total() }} data
                                    </small>
                                </div>
                                <div>
                                    {{ $users->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@if($isOpen)
<div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);" 
     x-data="{ open: true }" x-show="open" x-on:open-modal.window="open = true" 
     x-on:close-modal.window="open = false" wire:ignore.self>
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow-lg">
            <div class="modal-header bg-success text-white rounded-top-4">
                <h5 class="modal-title">
                    <i class="ti ti-{{ $userId ? 'edit' : 'user-plus' }} me-2"></i>
                    {{ $userId ? 'Edit Data Petani' : 'Tambah Petani Baru' }}
                </h5>
                <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
            </div>
            <div class="modal-body p-4">
                <form wire:submit.prevent="store">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                NIK <span class="text-danger">*</span>
                            </label>
                            <input type="number" wire:model="NIK" 
                                   class="form-control @error('NIK') is-invalid @enderror" 
                                   placeholder="Masukkan 16 digit NIK">
                            @error('NIK') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" wire:model="nama" 
                                   class="form-control @error('nama') is-invalid @enderror" 
                                   placeholder="Masukkan nama lengkap">
                            @error('nama') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Tanggal Lahir
                            </label>
                            <input type="date" wire:model="ttl" 
                                   class="form-control @error('ttl') is-invalid @enderror"
                                   value="{{ $ttl }}">
                            @error('ttl') <small class="text-danger">{{ $message }}</small> @enderror
                            <small class="text-muted">Format: YYYY-MM-DD</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nomor HP</label>
                            <input type="number" wire:model="nohp" 
                                   class="form-control @error('nohp') is-invalid @enderror" 
                                   placeholder="081234567890">
                            @error('nohp') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Alamat Lengkap</label>
                            <textarea wire:model="alamat" 
                                      class="form-control @error('alamat') is-invalid @enderror" 
                                      rows="2" placeholder="Masukkan alamat lengkap"></textarea>
                            @error('alamat') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Password @if(!$userId)<span class="text-danger">*</span>@endif
                            </label>
                            <input type="password" wire:model="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   placeholder="Minimal 6 karakter">
                            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                            @if($userId)
                            <small class="text-muted">Kosongkan jika tidak ingin mengubah password</small>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                Konfirmasi Password @if(!$userId)<span class="text-danger">*</span>@endif
                            </label>
                            <input type="password" wire:model="password_confirmation" 
                                   class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   placeholder="Ulangi password">
                            @error('password_confirmation') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="modal-footer px-0 pb-0 pt-4">
                        <button type="button" class="btn btn-secondary px-4" wire:click="closeModal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="ti ti-device-floppy me-1"></i> 
                            {{ $userId ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

    <!-- Modal Konfirmasi Hapus -->
    @if($isDeleteModalOpen)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);"
         x-data="{ deleteOpen: true }" x-show="deleteOpen" x-on:open-delete-modal.window="deleteOpen = true"
         x-on:close-delete-modal.window="deleteOpen = false" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header bg-danger text-white rounded-top-4">
                    <h5 class="modal-title">
                        <i class="ti ti-alert-triangle me-2"></i> Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="closeDeleteModal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="ti ti-trash fs-1 text-danger mb-3 d-block"></i>
                    <p class="mb-0 fw-semibold">Apakah Anda yakin ingin menghapus petani ini?</p>
                    <small class="text-muted">Data yang dihapus tidak dapat dikembalikan!</small>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <button type="button" class="btn btn-secondary px-4" wire:click="closeDeleteModal">
                        Batal
                    </button>
                    <button type="button" class="btn btn-danger px-4" wire:click="delete">
                        <i class="ti ti-trash me-1"></i> Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        .avatar-sm {
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #e8f5e9;
        }
        .font-monospace {
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            font-weight: 500;
        }
        .table th, .table td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }
        .card {
            transition: all 0.3s ease;
        }
        .info-petani {
            line-height: 1.4;
        }
        .badge {
            font-weight: 500;
            padding: 0.35rem 0.65rem;
        }
    </style>

    @script
    <script>
            $wire.on('swal', (data) => {
                Swal.fire({
                    title: data[0].title,
                    text: data[0].text,
                    icon: data[0].icon,
                    confirmButtonColor: '#2b5e2b',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: true,
                });
            });
    </script>
    @endscript
</div>