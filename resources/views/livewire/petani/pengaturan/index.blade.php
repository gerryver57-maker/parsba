<div>
    <div class="max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden md:max-w-2xl p-6">

    @if(session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

        <div class="card-body p-4">
            <h5 class="card-title mb-4">
                <i class="bi bi-person-lines-fill text-success"></i> Pengaturan Akun
            </h5>
            
            <!-- NIK -->
            <div class="detail-item row">
                <div class="col-sm-4">
                    <h6 class="mb-0 text-muted">NIK</h6>
                </div>
                <div class="col-sm-8">
                    <input wire:model="nik" type="number" class="form-control @error('nik') is-invalid @enderror" id="nik" value="{{$nik}}">
                    @error('nik')
                    <small class="text-danger">
                        {{$message}}
                    </small>
                    @enderror
                </div>
            </div>
            
            <!-- Nama Lengkap -->
            <div class="detail-item row">
                <div class="col-sm-4">
                    <h6 class="mb-0 text-muted">Nama Lengkap</h6>
                </div>
                <div class="col-sm-8">
                    <input wire:model="nama" type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" value="{{$nama}}">
                    @error('nama')
                    <small class="text-danger">
                        {{$message}}
                    </small>
                    @enderror
                </div>
            </div>
            
            <!-- Tanggal Lahir -->
            <div class="detail-item row">
                <div class="col-sm-4">
                    <h6 class="mb-0 text-muted">Tanggal Lahir</h6>
                </div>
                <div class="col-sm-8">
                    <input wire:model="ttl" type="date" class="form-control @error('ttl') is-invalid @enderror" id="ttl" value="{{$ttl}}">
                    @error('ttl')
                    <small class="text-danger">
                        {{$message}}
                    </small>
                    @enderror
                </div>
            </div>
            
            <!-- Alamat -->
            <div class="detail-item row">
                <div class="col-sm-4">
                    <h6 class="mb-0 text-muted">Alamat</h6>
                </div>
                <div class="col-sm-8">
                    <input wire:model="alamat" type="text" class="form-control @error('alamat') is-invalid @enderror" id="alamat" value="{{$alamat}}">
                    @error('alamat')
                    <small class="text-danger">
                        {{$message}}
                    </small>
                    @enderror
                </div>
            </div>
            
            <!-- Nomor HP -->
            <div class="detail-item row">
                <div class="col-sm-4">
                    <h6 class="mb-0 text-muted">Nomor HP</h6>
                </div>
                <div class="col-sm-8">
                    <input wire:model="nohp" type="number" class="form-control @error('nohp') is-invalid @enderror" id="nohp" value="{{$nohp}}">
                    @error('nohp')
                    <small class="text-danger">
                        {{$message}}
                    </small>
                    @enderror
                </div>
            </div>

            <!-- password saat ini -->
            <div class="detail-item row">
                <div class="col-sm-4">
                    <h6 class="mb-0 text-muted">Kata sandi saat ini</h6>
                </div>
                <div class="col-sm-8">
                    <input wire:model="passwordold" placeholder="kata sandi wajib diisi untuk mengubah data" type="password" class="form-control @error('passwordold') is-invalid @enderror" id="passwordold">
                    @error('passwordold')
                    <small class="text-danger">
                        {{$message}}
                    </small>
                    @enderror
                </div>
            </div>

            <!-- password saat ini -->
            <div class="detail-item row">
                <div class="col-sm-4">
                    <h6 class="mb-0 text-muted">Ganti Kata sandi</h6>
                </div>
                <div class="col-sm-8">
                    <input wire:model="passwordnew" placeholder="kata sandi tidak wajib diisi untuk mengubah data" type="password" class="form-control @error('passwordnew') is-invalid @enderror" id="passwordnew">
                    @error('passwordnew')
                    <small class="text-danger">
                        {{$message}}
                    </small>
                    @enderror
                </div>
            </div>

            <!-- password saat ini -->
            <div class="detail-item row">
                <div class="col-sm-4">
                    <h6 class="mb-0 text-muted">Ulangi kata sandi</h6>
                </div>
                <div class="col-sm-8">
                    <input wire:model="passwordconfirm" placeholder="kata sandi tidak wajib diisi untuk mengubah data" type="password" class="form-control @error('passwordconfirm') is-invalid @enderror" id="passwordconfirm" >
                    @error('passwordconfirm')
                    <small class="text-danger">
                        {{$message}}
                    </small>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-between">
            <button wire:click="update({{$id}})" type="submit" class="btn btn-mg btn-warning">
                Simpan Perubahan
            </button>
        </div>

        @script
        <script>
            $wire.on('closeEditPengaturan', () => {
                Swal.fire({
                    title: "Sukses",
                    text: "Data Berhasil di Ubah",
                    icon: "success"
                });
            });

             $wire.on('closeEditPengaturanError', () => {
                Swal.fire({
                    title: "error",
                    text: "Data Gagal di Ubah",
                    icon: "error"
                });
            });

        </script>

        @endscript
</div>
</div>
