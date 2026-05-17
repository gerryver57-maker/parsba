<?php

namespace App\Livewire\Admin\Varietas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\VarietasPadi;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $varietas_id;
    public $nama;
    public $umur_panen;
    public $potensi_hasil;
    public $deskripsi;
    public $showModal = false;
    public $isEdit = false;
    public $titleModal = '';

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'deleteConfirmed' => 'delete',
    ];

    protected function rules()
    {
        return [
            'nama' => 'required|string|max:100',
            'umur_panen' => 'required|integer|min:60|max:200',
            'potensi_hasil' => 'nullable|numeric|min:0|max:20',
            'deskripsi' => 'nullable|string|max:500',
        ];
    }

    public function resetForm()
    {
        $this->reset(['varietas_id', 'nama', 'umur_panen', 'potensi_hasil', 'deskripsi', 'showModal', 'isEdit']);
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

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->titleModal = 'Tambah Varietas Padi';
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetForm();
        $this->isEdit = true;
        $this->titleModal = 'Edit Varietas Padi';
        $varietas = VarietasPadi::findOrFail($id);
        $this->varietas_id = $varietas->id;
        $this->nama = $varietas->nama;
        $this->umur_panen = $varietas->umur_panen;
        $this->potensi_hasil = $varietas->potensi_hasil;
        $this->deskripsi = $varietas->deskripsi;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->resetForm();
    }

    public function confirmDelete($id)
    {
        $varietas = VarietasPadi::findOrFail($id);
        $this->varietas_id = $varietas->id;
        $this->dispatch('showDeleteConfirmation', [
            'id' => $varietas->id,
            'nama' => $varietas->nama,
        ]);
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->isEdit) {
                VarietasPadi::findOrFail($this->varietas_id)->update([
                    'nama' => $this->nama,
                    'umur_panen' => $this->umur_panen,
                    'potensi_hasil' => $this->potensi_hasil,
                    'deskripsi' => $this->deskripsi,
                ]);
                $msg = 'Varietas padi berhasil diperbarui.';
            } else {
                VarietasPadi::create([
                    'nama' => $this->nama,
                    'umur_panen' => $this->umur_panen,
                    'potensi_hasil' => $this->potensi_hasil,
                    'deskripsi' => $this->deskripsi,
                    'dibuat_oleh' => Auth::id(),
                ]);
                $msg = 'Varietas padi berhasil ditambahkan.';
            }

            $this->dispatch('showAlert', ['type' => 'success', 'title' => 'Berhasil!', 'text' => $msg]);
            $this->resetForm();
            $this->dispatch('refreshComponent');
        } catch (\Exception $e) {
            $this->dispatch('showAlert', ['type' => 'error', 'title' => 'Gagal!', 'text' => $e->getMessage()]);
        }
    }

    public function delete()
    {
        $varietas = VarietasPadi::findOrFail($this->varietas_id);
        
        if ($varietas->siklusTanam()->exists()) {
            $this->dispatch('showAlert', ['type' => 'error', 'title' => 'Gagal!', 'text' => 'Varietas digunakan dalam siklus tanam.']);
            return;
        }
        if ($varietas->faseTumbuh()->exists()) {
            $this->dispatch('showAlert', ['type' => 'warning', 'title' => 'Gagal!', 'text' => 'Hapus fase tumbuh terlebih dahulu.']);
            return;
        }

        $nama = $varietas->nama;
        $varietas->delete();
        $this->dispatch('showAlert', ['type' => 'success', 'title' => 'Terhapus!', 'text' => "Varietas \"{$nama}\" berhasil dihapus."]);
        $this->resetForm();
        $this->dispatch('refreshComponent');
    }

    public function render()
    {
        return view('livewire.admin.varietas.index', [
            'varietas' => VarietasPadi::query()
                ->when($this->search, fn($q) => $q->where('nama', 'like', '%' . $this->search . '%'))
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage),
        ])->layout('layouts.admin');
    }
}