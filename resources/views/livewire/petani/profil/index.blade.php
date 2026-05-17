<div>
    <div class="container-fluid">
        
        {{-- HEADER --}}
        <div class="mb-4">
            <h4 class="fw-bold mb-1"><i class="ti ti-user-circle me-2"></i>Profil Saya</h4>
            <p class="text-muted mb-0">Kelola informasi akun Anda</p>
        </div>

        <div class="row g-4">
            
            {{-- KIRI: INFORMASI AKUN --}}
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body py-4">
                        <div class="position-relative d-inline-block mb-3">

                            @php
                                $user = Auth::user();

                                // avatar online berdasarkan nama (gratis, otomatis unik)
                                $avatarUrl = 'https://ui-avatars.com/api/?name='
                                    . urlencode($user->nama)
                                    . '&background=198754&color=ffffff&size=128&bold=true';
                            @endphp

                            <img src="{{ $avatarUrl }}"
                                alt="Avatar Admin"
                                class="rounded-circle shadow"
                                width="90"
                                height="90"
                                style="object-fit: cover;">

                            {{-- status online dot --}}
                            <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle"
                                style="width: 18px; height: 18px;"></span>

                        </div>
                        <h5 class="fw-bold">{{ Auth::user()->nama }}</h5>
                        <span class="badge bg-info">Petani</span>
                        <hr>
                        <table class="table table-sm table-borderless text-start">
                            <tr>
                                <td width="40%"><small class="text-muted">NIK</small></td>
                                <td><small>: {{ Auth::user()->NIK }}</small></td>
                            </tr>
                            <tr>
                                <td><small class="text-muted">Tanggal Lahir</small></td>
                                <td><small>: {{ Auth::user()->ttl ? Auth::user()->ttl->format('d M Y') : '-' }}</small></td>
                            </tr>
                            <tr>
                                <td><small class="text-muted">Telepon</small></td>
                                <td><small>: {{ Auth::user()->nohp ?: '-' }}</small></td>
                            </tr>
                            <tr>
                                <td><small class="text-muted">Alamat</small></td>
                                <td><small>: {{ Auth::user()->alamat ?: '-' }}</small></td>
                            </tr>
                            <tr>
                                <td><small class="text-muted">Terdaftar</small></td>
                                <td><small>: {{ Auth::user()->created_at->translatedFormat('d F Y') }}</small></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            {{-- KANAN: FORM --}}
            <div class="col-md-8">
                
                {{-- 1. EDIT PROFIL --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="ti ti-user-edit me-1"></i> Edit Profil</h5>
                    </div>
                    <div class="card-body">
                        <form wire:submit.prevent="update">
                            
                            {{-- NIK --}}
                            <div class="mb-3">
                                <label class="form-label">NIK <span class="text-danger">*</span></label>
                                <input type="number" wire:model="nik" class="form-control @error('nik') is-invalid @enderror" placeholder="Masukkan NIK 16 digit">
                                @error('nik') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- NAMA --}}
                            <div class="mb-3">
                                <label class="form-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" wire:model="nama" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan nama lengkap">
                                @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- TANGGAL LAHIR --}}
                            <div class="mb-3">
                                <label class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <input type="date" wire:model="ttl" class="form-control @error('ttl') is-invalid @enderror">
                                @error('ttl') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- NO HP --}}
                            <div class="mb-3">
                                <label class="form-label">No. HP <span class="text-danger">*</span></label>
                                <input type="number" wire:model="nohp" class="form-control @error('nohp') is-invalid @enderror" placeholder="Masukkan nomor telepon">
                                @error('nohp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- ALAMAT --}}
                            <div class="mb-3">
                                <label class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea wire:model="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="2" placeholder="Masukkan alamat lengkap"></textarea>
                                @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <hr>
                            <h6 class="fw-bold mb-3"><i class="ti ti-lock me-1"></i> Verifikasi Password</h6>

                            {{-- PASSWORD LAMA --}}
                            <div class="mb-3">
                                <label class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                                <input type="password" wire:model="passwordold" class="form-control @error('passwordold') is-invalid @enderror" placeholder="Masukkan password untuk verifikasi">
                                @error('passwordold') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <button type="submit" class="btn btn-success">
                                <i class="ti ti-check me-1"></i> Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>

                {{-- 2. GANTI PASSWORD --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="ti ti-lock me-1"></i> Ganti Password (Opsional)</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Isi form di bawah hanya jika Anda ingin mengganti password. Jika tidak, kosongkan saja.</p>
                        
                        <form wire:submit.prevent="update">
                            
                            {{-- PASSWORD LAMA --}}
                            <div class="mb-3">
                                <label class="form-label">Password Saat Ini <span class="text-danger">*</span></label>
                                <input type="password" wire:model="passwordold" class="form-control @error('passwordold') is-invalid @enderror" placeholder="Masukkan password saat ini">
                                @error('passwordold') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- PASSWORD BARU --}}
                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password" wire:model="passwordnew" class="form-control @error('passwordnew') is-invalid @enderror" placeholder="Minimal 6 karakter">
                                @error('passwordnew') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            {{-- KONFIRMASI --}}
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" wire:model="passwordconfirm" class="form-control @error('passwordconfirm') is-invalid @enderror" placeholder="Ulangi password baru">
                                @error('passwordconfirm') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <button type="submit" class="btn btn-warning">
                                <i class="ti ti-key me-1"></i> Simpan & Ganti Password
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
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
</script>
@endscript