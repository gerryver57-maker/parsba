<?php

namespace App\Livewire\Petani\Profil;

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

    protected function rules()
    {
        $rules = [
            'nik' => 'required|min:16',
            'nama' => 'required',
            'ttl' => 'required',
            'alamat' => 'required',
            'nohp' => 'required',
            'passwordold' => 'required',
        ];

        if ($this->passwordnew || $this->passwordconfirm) {
            $rules['passwordnew'] = ['required', 'min:6'];
            $rules['passwordconfirm'] = ['required', 'min:6', 'same:passwordnew'];
            $this->changingPassword = true;
        } else {
            $this->changingPassword = false;
        }

        return $rules;
    }

    protected $messages = [
        'nik.required' => 'NIK tidak boleh kosong',
        'nik.min' => 'NIK Harus 16 angka',
        'nama.required' => 'Nama tidak boleh kosong',
        'ttl.required' => 'Tanggal Lahir tidak boleh kosong',
        'alamat.required' => 'Alamat tidak boleh kosong',
        'nohp.required' => 'Nomor HP tidak boleh kosong',
        'passwordold.required' => 'Password lama tidak boleh kosong',
        'passwordnew.required' => 'Password baru tidak boleh kosong',
        'passwordnew.min' => 'Password baru Minimal 6 Karakter',
        'passwordconfirm.required' => 'Konfirmasi password tidak boleh kosong',
        'passwordconfirm.same' => 'Konfirmasi Password Tidak Sesuai',
        'passwordconfirm.min' => 'Konfirmasi Password Minimal 6 Karakter',
    ];

    public function render()
    {
        return view('livewire.petani.profil.index')->layout('layouts.petani');
    }

    /**
     * Update profil
     */
    public function update()
    {
        $this->validate();

        try {
            $user = User::findOrFail($this->id);

            // Cek password lama
            if (!Hash::check($this->passwordold, Auth::user()->password)) {
                $this->addError('passwordold', 'Password lama yang anda masukkan salah');
                return;
            }

            // Update data
            $user->NIK = $this->nik;
            $user->nama = $this->nama;
            $user->ttl = $this->ttl;
            $user->alamat = $this->alamat;
            $user->nohp = $this->nohp;
            $user->role = 'petani';

            // Jika ganti password
            if ($this->changingPassword && $this->passwordnew) {
                $user->password = Hash::make($this->passwordnew);
            }

            $user->remember_token = Str::random(50);
            $user->save();

            // Reset form
            $this->resetValidation();
            $this->reset(['passwordold', 'passwordnew', 'passwordconfirm']);
            $this->changingPassword = false;

            // Reload data
            $this->mount();

            $this->dispatch('tampilPesan', [
                'tipe' => 'success',
                'judul' => 'Berhasil!',
                'teks' => 'Profil berhasil diperbarui.',
            ]);

        } catch (\Throwable $th) {
            $this->dispatch('tampilPesan', [
                'tipe' => 'error',
                'judul' => 'Gagal!',
                'teks' => 'Terjadi kesalahan: ' . $th->getMessage(),
            ]);
        }
    }
}