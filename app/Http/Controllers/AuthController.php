<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function registration()
    {
        return view('auth.registration');
    }

    public function registration_post(Request $request)
    {
        $validatedData = $request->validate([
            'nik' => 'required|unique:users,nik',
            'nama' => 'required',
            'ttl' => 'required|date',
            'alamat' => 'required',
            'nohp' => 'required',
            'password' => 'required|min:6',
            'confirmpassword' => 'required|same:password|min:6',
        ]);

        $user = new User;
        $user->NIK = trim($request->nik);
        $user->nama = trim($request->nama);
        $user->ttl = trim($request->ttl);
        $user->alamat = trim($request->alamat);
        $user->nohp = trim($request->nohp);
        $user->password = Hash::make(trim($request->password)); // enkripsi password
        $user->remember_token = Str::random(50);
        $user->save();

        return redirect('/loginsisfoparsba')->with('success', 'Berhasil Mendaftar !!!');
    }

    public function daftaradmin()
    {
        return view('auth.daftaradmin');
    }

    public function daftaradmin_post(Request $request)
    {
        $validatedData = $request->validate([
            'nik' => 'required|min:16|unique:users,nik',
            'nama' => 'required',
            'ttl' => 'required|date',
            'alamat' => 'required',
            'nohp' => 'required',
            'password' => 'required|min:6',
            'confirmpassword' => 'required|same:password|min:6',
        ], [
                'nik.required' => 'NIK tidak boleh kosong',
                'nik.min' => 'NIK Harus 16 angka',
                'nik.unique' => 'NIK sudah terdaftar',
                'nama.required' => 'Nama tidak boleh kosong',
                'ttl.required' => 'Tanggal Lahir tidak boleh kosong',
                'alamat.required' => 'Alamat tidak boleh kosong',
                'nohp.required' => 'Nomor HP tidak boleh kosong',
                'password.required' => 'Password tidak boleh kosong',
                'password.min' => 'Passord Minimal 6 Karakter',
                'passwordconfirm.required' => 'Ulangi Password tidak boleh kosong',
                'passwordconfirm.same' => 'Konfirmasi Password Tidak Sesuai, Pastikan kedua password sama',
                'passwordconfirm.min' => 'Password Minimal 6 Karakter',
            ]);

        $user = new User;
        $user->NIK = trim($request->nik);
        $user->nama = trim($request->nama);
        $user->ttl = trim($request->ttl);
        $user->alamat = trim($request->alamat);
        $user->nohp = trim($request->nohp);
        $user->role = "Admin";
        $user->password = Hash::make(trim($request->password)); // enkripsi password
        $user->remember_token = Str::random(50);
        $user->save();

        return redirect('/loginsisfoparsba')->with('success', 'Berhasil Mendaftar !!!');
    }

    public function login_post(Request $request)
    {
        $validatedData = $request->validate([
            'nik1' => 'required|min:16',
            'password1' => 'required|min:6',
        ], [
                'nik1.required' => 'NIK tidak boleh kosong',
                'nik1.min' => 'NIK Harus 16 angka',
                'password1.required' => 'Kata sandi tidak boleh kosong',
                'password1.min' => 'Kata sandi Minimal 6 Karakter',
            ]);

        if(Auth::attempt(['nik'=>$request->nik1,'password'=>$request->password1],true)){
             $user = Auth::user();
            
            // Update last login dengan waktu WIB
            $user->updateLastLogin();
            if (Auth::User()->role=='admin') {
                return redirect()->intended('admin/dashboard');
            }else if(Auth::User()->role=='petani') {
                return redirect()->intended('petani/dashboard');
            }else{
                return redirect('/loginsisfoparsba')->with('error','Cek kembali nik dan password anda !!!');
            }
        }else{
            return redirect('/loginsisfoparsba')->with('error','Cek kembali nik dan password anda !!!');
        }
    }

    public function login()
    {
        return view('auth.login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/loginsisfoparsba')->with('success', 'Anda telah berhasil logout');
    }
    protected function authenticated(Request $request, $user)
    {
        // Update last login ketika user berhasil login
        $user->updateLastLogin();
        
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        return redirect()->route('petani.dashboard');
    }
}
