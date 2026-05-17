@extends('layouts.login.app')

@section('title','Parsba | Register')

@section('content')
<div class="container">
    <div class="row align-items-center justify-content-center">
        <div class="col-md-7">
            <h3>Halaman Pendaftaran</h3><br><br>
            <form action="{{ url('registration_post') }}" method="post">
                {{ csrf_field() }}
                <div class="form-group first">
                    <label for="nik">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik') }}" class="form-control @error('nik') is-invalid @enderror" placeholder="Masukkan NIK" id="nik">
                    @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group last mb-3">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" placeholder="Nama Lengkap" id="namalengkap">
                    @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group last mb-3">
                    <label for="ttl">Tanggal Lahir</label>
                    <input type="date" name="ttl" value="{{ old('ttl') }}" min="1960-01-01" max="2000-01-01" class="form-control @error('ttl') is-invalid @enderror" id="ttl">
                    @error('ttl')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group last mb-3">
                    <label for="alamat">Alamat</label>
                    <input type="text" name="alamat" value="{{ old('alamat') }}" class="form-control @error('alamat') is-invalid @enderror" placeholder="Masukkan Alamat" id="alamat">
                    @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group last mb-3">
                    <label for="nohp">Nomor HP</label>
                    <input type="text" name="nohp" value="{{ old('nohp') }}" class="form-control @error('nohp') is-invalid @enderror" placeholder="Masukkan Nomor HP" id="nohp">
                    @error('nohp')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group last mb-3">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Masukkan Password" id="password">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group last mb-3">
                    <label for="confirmpassword">Ulangi Password</label>
                    <input type="password" name="confirmpassword" class="form-control @error('confirmpassword') is-invalid @enderror" placeholder="Masukkan Ulang Password" id="confirmpassword">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <input type="submit" value="DAFTAR" class="btn btn-block btn-primary">
            </form>
        </div>
    </div>
</div>
@endsection
