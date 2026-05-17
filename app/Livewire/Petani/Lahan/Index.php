<?php

namespace App\Livewire\Petani\Lahan;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Lahan;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Form
    public $lahan_id;
    public $nama;
    public $luas;
    public $jenis_irigasi;
    public $catatan;

    // Modal
    public $showModal = false;
    public $isEdit = false;
    public $titleModal = '';

    protected function rules()
    {
        return [
            'nama' => 'required|string|max:100',
            'luas' => 'required|numeric|min:0.01|max:100',
            'jenis_irigasi' => 'required|in:irigasi,tadah_hujan,rawa',
            'catatan' => 'nullable|string|max:500',
        ];
    }

    protected $messages = [
        'nama.required' => 'Nama lahan wajib diisi.',
        'nama.max' => 'Nama lahan maksimal 100 karakter.',
        'luas.required' => 'Luas lahan wajib diisi.',
        'luas.numeric' => 'Luas lahan harus berupa angka.',
        'luas.min' => 'Luas lahan minimal 0.01 Hektar.',
        'luas.max' => 'Luas lahan maksimal 100 Hektar.',
        'jenis_irigasi.required' => 'Jenis irigasi wajib dipilih.',
        'jenis_irigasi.in' => 'Jenis irigasi tidak valid.',
    ];

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'deleteConfirmed' => 'delete',
    ];

    public function resetForm()
    {
        $this->reset(['lahan_id', 'nama', 'luas', 'jenis_irigasi', 'catatan', 'showModal', 'isEdit']);
        $this->resetValidation();
    }

    public function sortData($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    // ========== MODAL ==========
    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->titleModal = 'Tambah Lahan';
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetForm();
        $this->isEdit = true;
        $this->titleModal = 'Edit Lahan';

        $lahan = Lahan::where('user_id', Auth::id())->findOrFail($id);
        $this->lahan_id = $lahan->id;
        $this->nama = $lahan->nama;
        $this->luas = $lahan->luas;
        $this->jenis_irigasi = $lahan->jenis_irigasi;
        $this->catatan = $lahan->catatan;

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->resetForm();
    }

    // ========== SAVE ==========
    public function save()
    {
        $this->validate();

        try {
            if ($this->isEdit) {
                $lahan = Lahan::where('user_id', Auth::id())->findOrFail($this->lahan_id);
                $lahan->update([
                    'nama' => $this->nama,
                    'luas' => $this->luas,
                    'jenis_irigasi' => $this->jenis_irigasi,
                    'catatan' => $this->catatan,
                ]);
                $msg = 'Lahan berhasil diperbarui.';
            } else {
                Lahan::create([
                    'user_id' => Auth::id(),
                    'nama' => $this->nama,
                    'luas' => $this->luas,
                    'jenis_irigasi' => $this->jenis_irigasi,
                    'catatan' => $this->catatan,
                ]);
                $msg = 'Lahan berhasil ditambahkan.';
            }

            $this->dispatch('tampilPesan', ['tipe' => 'success', 'judul' => 'Berhasil!', 'teks' => $msg]);
            $this->resetForm();
            $this->dispatch('refreshComponent');

        } catch (\Exception $e) {
            $this->dispatch('tampilPesan', ['tipe' => 'error', 'judul' => 'Gagal!', 'teks' => $e->getMessage()]);
        }
    }

    // ========== KONFIRMASI HAPUS ==========
    public function confirmDelete($id)
    {
        $lahan = Lahan::where('user_id', Auth::id())->findOrFail($id);
        $this->lahan_id = $lahan->id;

        $this->dispatch('tampilKonfirmasiHapus', ['nama' => $lahan->nama]);
    }

    // ========== DELETE ==========
    public function delete()
    {
        try {
            $lahan = Lahan::where('user_id', Auth::id())->findOrFail($this->lahan_id);

            // Cek apakah lahan punya siklus tanam
            if ($lahan->siklusTanam()->exists()) {
                $this->dispatch('tampilPesan', [
                    'tipe' => 'error',
                    'judul' => 'Gagal!',
                    'teks' => 'Lahan tidak dapat dihapus karena sudah memiliki riwayat siklus tanam.',
                ]);
                return;
            }

            $nama = $lahan->nama;
            $lahan->delete();

            $this->dispatch('tampilPesan', ['tipe' => 'success', 'judul' => 'Terhapus!', 'teks' => "Lahan \"{$nama}\" berhasil dihapus."]);
            $this->resetForm();
            $this->dispatch('refreshComponent');

        } catch (\Exception $e) {
            $this->dispatch('tampilPesan', ['tipe' => 'error', 'judul' => 'Gagal!', 'teks' => $e->getMessage()]);
        }
    }

    public function render()
    {
        $lahan = Lahan::query()
            ->where('user_id', Auth::id())
            ->when($this->search, fn($q) => $q->where('nama', 'like', '%' . $this->search . '%'))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.petani.lahan.index', compact('lahan'));
    }
}