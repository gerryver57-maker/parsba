<?php

namespace App\Livewire\Admin\Pengguna;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    // Properti untuk form
    public $userId;
    public $NIK;
    public $nama;
    public $ttl;
    public $alamat;
    public $nohp;
    public $role = 'petani';
    public $password;
    public $password_confirmation;

    // Properti untuk search
    public $search = '';
    public $perPage = 10;

    // Properti untuk modal
    public $isOpen = false;
    public $isDeleteModalOpen = false;
    public $deleteId;

    protected $rules = [
        'NIK' => 'required|string|min:16|max:20|unique:users,NIK',
        'nama' => 'required|string|max:100',
        'ttl' => 'nullable|date',
        'alamat' => 'nullable|string',
        'nohp' => 'nullable|string|max:15',
        'password' => 'required|min:6|confirmed',
    ];

    protected $messages = [
        'NIK.required' => 'NIK wajib diisi',
        'NIK.unique' => 'NIK sudah terdaftar',
        'NIK.min' => 'NIK minimal 16 digit',
        'nama.required' => 'Nama wajib diisi',
        'password.required' => 'Password wajib diisi',
        'password.min' => 'Password minimal 6 karakter',
        'password.confirmed' => 'Konfirmasi password tidak sesuai',
    ];

    public function mount()
    {
        $this->role = 'petani';
    }

    public function render()
    {
        $users = User::where('role', 'petani')
            ->when($this->search, function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('NIK', 'like', '%' . $this->search . '%')
                    ->orWhere('nohp', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.admin.pengguna.index', [
            'users' => $users,
        ]);
    }

    public function resetForm()
    {
        $this->userId = null;
        $this->NIK = '';
        $this->nama = '';
        $this->ttl = '';
        $this->alamat = '';
        $this->nohp = '';
        $this->role = 'petani';
        $this->password = '';
        $this->password_confirmation = '';
        $this->resetValidation();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isOpen = true;
        $this->dispatch('open-modal');
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetForm();
        $this->dispatch('close-modal');
    }

    public function openDeleteModal($id)
    {
        $this->deleteId = $id;
        $this->isDeleteModalOpen = true;
        $this->dispatch('open-delete-modal');
    }

    public function closeDeleteModal()
    {
        $this->isDeleteModalOpen = false;
        $this->deleteId = null;
        $this->dispatch('close-delete-modal');
    }

    public function store()
    {
        $rules = $this->rules;
        
        if ($this->userId) {
            $rules['NIK'] = 'required|string|min:16|max:20|unique:users,NIK,' . $this->userId;
            $rules['password'] = 'nullable|min:6|confirmed';
        }

        $this->validate($rules);

        $data = [
            'NIK' => $this->NIK,
            'nama' => $this->nama,
            'ttl' => $this->ttl ? Carbon::parse($this->ttl)->format('Y-m-d') : null,
            'alamat' => $this->alamat,
            'nohp' => $this->nohp,
            'role' => 'petani',
        ];

        if ($this->password || !$this->userId) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->userId) {
            User::find($this->userId)->update($data);
            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'Data petani berhasil diupdate!',
                'icon' => 'success',
            ]);
        } else {
            User::create($data);
            $this->dispatch('swal', [
                'title' => 'Berhasil!',
                'text' => 'Data petani berhasil ditambahkan!',
                'icon' => 'success',
            ]);
        }

        $this->closeModal();
        $this->resetForm();
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        
        $this->userId = $user->id;
        $this->NIK = $user->NIK;
        $this->nama = $user->nama;
        
        // Pastikan format tanggal lahir sesuai dengan input date (Y-m-d)
        if ($user->ttl) {
            $this->ttl = Carbon::parse($user->ttl)->format('Y-m-d');
        } else {
            $this->ttl = '';
        }
        
        $this->alamat = $user->alamat;
        $this->nohp = $user->nohp;
        $this->role = $user->role;
        $this->password = '';
        $this->password_confirmation = '';

        $this->isOpen = true;
        $this->dispatch('open-modal');
    }

    public function delete()
    {
        $user = User::find($this->deleteId);
        
        if ($user->id == auth()->id()) {
            $this->dispatch('swal', [
                'title' => 'Gagal!',
                'text' => 'Anda tidak dapat menghapus akun sendiri!',
                'icon' => 'error',
            ]);
            $this->closeDeleteModal();
            return;
        }

        $namaUser = $user->nama;
        $user->delete();
        
        $this->dispatch('swal', [
            'title' => 'Dihapus!',
            'text' => "Data petani {$namaUser} berhasil dihapus!",
            'icon' => 'success',
        ]);
        
        $this->closeDeleteModal();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }
}