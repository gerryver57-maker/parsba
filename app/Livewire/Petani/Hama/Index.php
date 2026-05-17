<?php

namespace App\Livewire\Petani\Hama;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\HamaPenyakit;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 12;
    public $filterJenis = '';

    // Detail modal
    public $hamaDetail = null;
    public $showDetail = false;

    public function lihatDetail($id)
    {
        $this->hamaDetail = HamaPenyakit::findOrFail($id);
        $this->showDetail = true;
    }

    public function tutupDetail()
    {
        $this->hamaDetail = null;
        $this->showDetail = false;
    }

    public function render()
    {
        $hama = HamaPenyakit::query()
            ->when($this->search, fn($q) => $q->where('nama', 'like', '%'.$this->search.'%')
                ->orWhere('gejala', 'like', '%'.$this->search.'%')
                ->orWhere('rekomendasi', 'like', '%'.$this->search.'%'))
            ->when($this->filterJenis, fn($q) => $q->where('jenis', $this->filterJenis))
            ->orderBy('jenis')
            ->orderBy('nama')
            ->paginate($this->perPage);

        return view('livewire.petani.hama.index', compact('hama'));
    }
}