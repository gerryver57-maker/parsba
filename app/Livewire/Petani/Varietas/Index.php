<?php

namespace App\Livewire\Petani\Varietas;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\VarietasPadi;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 12;
    public $sortBy = 'nama';
    public $sortDirection = 'asc';

    // Detail modal
    public $varietasDetail = null;
    public $showDetail = false;

    public function sortData($column)
    {
        $this->sortDirection = ($this->sortBy === $column && $this->sortDirection === 'asc') ? 'desc' : 'asc';
        $this->sortBy = $column;
    }

    public function lihatDetail($id)
    {
        $this->varietasDetail = VarietasPadi::with(['faseTumbuh.pupuk', 'faseTumbuh.pestisida'])->findOrFail($id);
        $this->showDetail = true;
    }

    public function tutupDetail()
    {
        $this->varietasDetail = null;
        $this->showDetail = false;
    }

    public function render()
    {
        $varietas = VarietasPadi::query()
            ->when($this->search, fn($q) => $q->where('nama', 'like', '%'.$this->search.'%')
                ->orWhere('deskripsi', 'like', '%'.$this->search.'%'))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.petani.varietas.index', compact('varietas'));
    }
}