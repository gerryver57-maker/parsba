<?php

namespace App\Livewire\Admin\Profil;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class Index extends Component
{
    public $id, $nik, $nama, $ttl, $alamat, $nohp;
    public $passwordold, $passwordnew, $passwordconfirm;
    public $changingPassword = false;

    protected $listeners = ['prosesGantiPassword' => 'prosesGantiPassword'];

    public function mount()
    {
        $user = Auth::user();
        $this->id = $user->id;
        $this->nik = $user->NIK;
        $this->nama = $user->nama;
        $this->ttl = $user->ttl ? $user->ttl->format('Y-m-d') : null;
        $this->alamat = $user->alamat;
        $this->nohp = $user->nohp;
    }

    // ========== RULES EDIT PROFIL ==========
    protected function rulesProfil()
    {
        return [
            'nik' => 'required|min:16',
            'nama' => 'required',
            'ttl' => 'required',
            'alamat' => 'required',
            'nohp' => 'required',
            'passwordold' => 'required',
        ];
    }

    protected $messagesProfil = [
        'nik.required' => 'NIK tidak boleh kosong',
        'nik.min' => 'NIK Harus 16 angka',
        'nama.required' => 'Nama tidak boleh kosong',
        'ttl.required' => 'Tanggal Lahir tidak boleh kosong',
        'alamat.required' => 'Alamat tidak boleh kosong',
        'nohp.required' => 'Nomor HP tidak boleh kosong',
        'passwordold.required' => 'Kata sandi lama tidak boleh kosong',
    ];

    // ========== RULES GANTI PASSWORD ==========
    protected function rulesPassword()
    {
        return [
            'passwordold' => 'required',
            'passwordnew' => 'required|min:6',
            'passwordconfirm' => 'required|min:6|same:passwordnew',
        ];
    }

    protected $messagesPassword = [
        'passwordold.required' => 'Kata sandi lama tidak boleh kosong',
        'passwordnew.required' => 'Kata sandi baru tidak boleh kosong',
        'passwordnew.min' => 'Kata sandi baru Minimal 6 Karakter',
        'passwordconfirm.required' => 'Konfirmasi kata sandi tidak boleh kosong',
        'passwordconfirm.same' => 'Konfirmasi Kata sandi Tidak Sesuai',
        'passwordconfirm.min' => 'Konfirmasi Kata sandi Minimal 6 Karakter',
    ];

    public function render()
    {
        return view('livewire.admin.profil.index')->layout('layouts.admin');
    }

    // ========== UPDATE PROFIL ==========
    public function update()
    {
        $this->validate($this->rulesProfil(), $this->messagesProfil);

        try {
            $user = User::findOrFail($this->id);

            if (!Hash::check($this->passwordold, $user->password)) {
                $this->addError('passwordold', 'Password lama yang anda masukkan salah');
                return;
            }

            $user->update([
                'NIK' => $this->nik,
                'nama' => $this->nama,
                'ttl' => $this->ttl,
                'alamat' => $this->alamat,
                'nohp' => $this->nohp,
                'remember_token' => Str::random(50),
            ]);

            $this->reset(['passwordold', 'passwordnew', 'passwordconfirm']);
            $this->resetValidation();

            $this->dispatch('tampilPesan', [
                'tipe' => 'success',
                'judul' => 'Berhasil!',
                'teks' => 'Profil berhasil diperbarui.',
            ]);

        } catch (\Throwable $th) {
            $this->dispatch('tampilPesan', [
                'tipe' => 'error',
                'judul' => 'Gagal!',
                'teks' => $th->getMessage(),
            ]);
        }
    }

    // ========== KONFIRMASI GANTI PASSWORD ==========
    public function gantiPassword()
    {
        $this->validate($this->rulesPassword(), $this->messagesPassword);

        // Cek password lama
        if (!Hash::check($this->passwordold, Auth::user()->password)) {
            $this->addError('passwordold', 'Password lama yang anda masukkan salah');
            return;
        }

        $this->dispatch('tampilKonfirmasiGantiPassword');
    }

    // ========== PROSES GANTI PASSWORD ==========
    public function prosesGantiPassword()
    {
        try {
            $user = User::findOrFail($this->id);
            $user->update([
                'password' => Hash::make($this->passwordnew),
                'remember_token' => Str::random(50),
            ]);

            $this->reset(['passwordold', 'passwordnew', 'passwordconfirm']);
            $this->resetValidation();

            $this->dispatch('tampilPesan', [
                'tipe' => 'success',
                'judul' => 'Berhasil!',
                'teks' => 'Password berhasil diganti.',
            ]);

        } catch (\Throwable $th) {
            $this->dispatch('tampilPesan', [
                'tipe' => 'error',
                'judul' => 'Gagal!',
                'teks' => $th->getMessage(),
            ]);
        }
    }
}