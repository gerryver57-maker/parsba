<div>
    <!-- Background -->
    <div class="animated-bg"></div>

    <!-- Container utama -->
    <div class="container {{$isactive?'active':''}} {{$isactive ? 'show-register' : ''}}" id="container">
		<div class="mobile-toggle">
    <button type="button" id="btnLogin">Masuk</button>
    <button type="button" id="btnRegister">Daftar</button>
</div>
        <!-- Form Registrasi -->
        <div class="form-container sign-up">
            <div class="formw" wire:ignore.self>
                <h3>Buat Akun Baru</h3>
                <span>Gunakan NIK Anda untuk pendaftaran</span>

                <!-- NIK -->
                <input wire:model="nik" type="number" class="form-control @error('nik') is-invalid @enderror" placeholder="Nomor Induk Kependudukan">
                @error('nik') <small class="text-danger">{{ $message }}</small> @enderror

                <!-- Nama -->
                <input wire:model="nama" type="text" class="form-control @error('nama') is-invalid @enderror" placeholder="Nama Lengkap">
                @error('nama') <small class="text-danger">{{ $message }}</small> @enderror

                <!-- Tanggal Lahir -->
                <input wire:model="ttl" type="date" class="form-control @error('ttl') is-invalid @enderror">
                @error('ttl') <small class="text-danger">{{ $message }}</small> @enderror

                <!-- Alamat -->
                <input wire:model="alamat" type="text" class="form-control @error('alamat') is-invalid @enderror" placeholder="Alamat Lengkap">
                @error('alamat') <small class="text-danger">{{ $message }}</small> @enderror

                <!-- No HP -->
                <input wire:model="nohp" type="number" class="form-control @error('nohp') is-invalid @enderror" placeholder="Nomor HP">
                @error('nohp') <small class="text-danger">{{ $message }}</small> @enderror

                <!-- Password -->
                <input wire:model="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Kata Sandi">
                @error('password') <small class="text-danger">{{ $message }}</small> @enderror

                <!-- Konfirmasi Password -->
                <input wire:model="passwordconfirm" type="password" class="form-control @error('passwordconfirm') is-invalid @enderror" placeholder="Ulangi Kata Sandi">
                @error('passwordconfirm') <small class="text-danger">{{ $message }}</small> @enderror

                <!-- Tombol Daftar -->
                <button wire:click.stop="store" type="button" id="daftar">Daftar</button>
            </div>
        </div>

        <!-- Form Login -->
        <div class="form-container sign-in">
            <form action="{{url('login_post')}}" method="post" class="formw">
                {{ csrf_field() }}
                <h3>Masuk ke Akun</h3>
                <span>Selamat Datang di Sistem Informasi PARSBA</span>

                <!-- NIK -->
                <input wire:model="nik1" name="nik1" type="number" id="nik1" placeholder="Nomor Induk Kependudukan" class="form-control mt-2 @error('loginNik') is-invalid @enderror" />
                @error('nik1') <small class="text-danger">{{ $message }}</small> @enderror

                <!-- Password -->
                <input wire:model="password1" name="password1" type="password" id="password1" placeholder="Kata Sandi" class="form-control mt-2 @error('loginPassword') is-invalid @enderror" />
                @error('password1') <small class="text-danger">{{ $message }}</small> @enderror

                <!-- Tombol Login -->
                <input type="submit" class="butt" value="Masuk" style="background-color: #77ba57;">
            </form>
        </div>

        <!-- Panel Toggle -->
        <div class="toggle-container">
            <div class="toggle">
                <!-- Panel Kiri (Login) -->
                <div class="toggle-panel toggle-left">
                    <h3>Selamat Datang Kembali!</h3>
                    <p>Masuk untuk mengakses fitur lengkap PARSBA</p>
                    <button class="hidden" id="login">Masuk</button>
                </div>

                <!-- Panel Kanan (Register) -->
                <div class="toggle-panel toggle-right">
                    <h3>Halo, Sahabat Tani!</h3>
                    <h5>Selamat Datang di PARSBA</h5>
                    <p>
                        Sistem Informasi PARSBA hadir untuk membantu Petani Padi Bahagia Padang Galugua dalam merencanakan kegiatan pertanian mulai dari tanam hingga panen secara efisien, cerdas, dan terstruktur.
                    </p>
                    <button class="hidden" id="register">Buat Akun</button>
                </div>
            </div>
        </div>
        @script
        <script>
                    const container = document.getElementById("container");

document.getElementById("btnLogin").addEventListener("click", () => {
    container.classList.remove("show-register");
});

document.getElementById("btnRegister").addEventListener("click", () => {
    container.classList.add("show-register");
});
            window.addEventListener('validasigagal', () => {
                document.getElementById("container").classList.remove("container");
                document.getElementById("container").classList.add("active");
                document.getElementById("container").classList.add("show-register");
            });
            $wire.on('closeCreateLogin', () => {
                Swal.fire({
                    title: "Sukses",
                    text: "Data Berhasil di Daftarkan",
                    icon: "success",
                    confirmButtonColor: '#2b5e2b',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: true,
                });
            });

            @if(session('error'))
                Swal.fire({
                    title: "Gagal",
                    text: '{{session('error')}}',
                    icon: "error",
                    confirmButtonText:"Coba Lagi",
                    confirmButtonColor:"#d33",
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: true,

                });
            @endif
        </script>

        @endscript
    </div>

</div>
