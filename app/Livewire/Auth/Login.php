<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Login extends Component
{
    public $nik, $nama, $ttl, $alamat, $nohp, $password, $passwordconfirm;
    public $isactive = false;

    public function render()
    {
        return view('livewire.auth.login');
    }
    public function store()
    {
        try {
            $this->validate([
                'nik' => 'required|min:16|unique:users,nik',
                'nama' => 'required',
                'ttl' => 'required',
                'alamat' => 'required',
                'nohp' => 'required',
                'password' => 'required|min:6',
                'passwordconfirm' => 'required|same:password|min:6',
            ], [
                'nik.required' => 'NIK tidak boleh kosong',
                'nik.min' => 'NIK Harus 16 angka',
                'nik.unique' => 'NIK sudah terdaftar',
                'nama.required' => 'Nama tidak boleh kosong',
                'ttl.required' => 'Tanggal Lahir tidak boleh kosong',
                'alamat.required' => 'Alamat tidak boleh kosong',
                'nohp.required' => 'Nomor HP tidak boleh kosong',
                'password.required' => 'Kata sandi tidak boleh kosong',
                'password.min' => 'Kata sandi Minimal 6 Karakter',
                'passwordconfirm.required' => 'Ulangi Kata sandi tidak boleh kosong',
                'passwordconfirm.same' => 'Konfirmasi Kata sandi Tidak Sesuai, Pastikan kedua password sama',
                'passwordconfirm.min' => 'Kata sandi Minimal 6 Karakter',
            ]);
            $login = new user;
            $login->nik = $this->nik;
            $login->nama = $this->nama;
            $login->ttl = $this->ttl;
            $login->alamat = $this->alamat;
            $login->nohp = $this->nohp;
            $login->role = 'petani';
            $login->password = Hash::make($this->password);
            $login->remember_token = Str::random(50);
            $login->save();

            $this->resetValidation();
            $this->reset();
            $this->dispatch('closeCreateLogin');
        } catch (\Throwable $th) {
            $this->isactive=true;
            throw $th;
        }
            
    }
    public function login_post(){

        if (Auth::attempt(['nik' => $this->nik1, 'password' => $this->password1], true)) {
            if (Auth::User()->role == 'admin') {
                return redirect()->intended('admin/dashboard');
            } else if (Auth::User()->role == 'petani') {
                return redirect()->intended('petani/dashboard');
            } else {
                return redirect('/')->with('error', 'Cek kembali nik dan password anda !!!');
            }
        } else {
            return redirect('/')->with('error', 'Cek kembali nik dan password anda !!!');
        }
    }
}
