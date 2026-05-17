@extends('layouts.login.app')

@section('title','Parsba | Login')

@section('content')
    <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-7">
            <h3>Masuk to <strong>PARSBA</strong></h3>
            <p class="mb-4">Membantuu Petani Padi Bahagia Padang Galugua, Merencanakan Kegiatan Bertani dari Tanam hingga Panen secara Lebih Mudah dan Efisien</p>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form action="{{url('login_post')}}" method="post">
              {{ csrf_field() }}
              <div class="form-group first">
                <label for="nik1">NIK</label>
                <input type="text"  name="nik1"  class="form-control @error('nik1') is-invalid @enderror" placeholder="Masukkan NIK" id="nik1">
                 @error('nik1')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="form-group last mb-3">
                <label for="password1">Password</label>
                <input type="password"  name="password1"  class="form-control @error('password1') is-invalid @enderror" placeholder="Masukkan Password" id="password1">
                 @error('password1')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              
              <div class="d-flex mb-5 align-items-center">
                    <span class="ml-2"><p>Belum punya akun ? <br> klik <a href="{{(url('/registration'))}}" class="forgot-pass">disini</p></a></span> 
                </div>

              <input type="submit" value="Log In" class="btn btn-block btn-primary">

            </form>
          </div>
        </div>
        </div>
@endsection