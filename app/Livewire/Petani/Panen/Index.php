<?php

namespace App\Livewire\Petani\Panen;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Panen;
use App\Models\SiklusTanam;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'tanggal_panen';
    public $sortDirection = 'desc';

    // Form
    public $panen_id;
    public $siklus_tanam_id;
    public $tanggal_panen;
    public $jumlah;
    public $kualitas = 'baik';
    public $catatan;

    // Data pendukung
    public $listSiklusAktif = [];
    public $siklusDetail = null;

    // Modal
    public $showModal = false;
    public $isEdit = false;
    public $titleModal = '';

    // Statistik
    public $totalPanen = 0;
    public $totalJumlah = 0;
    public $rataHasil = 0;

    protected function rules()
    {
        return [
            'siklus_tanam_id' => 'required|exists:siklus_tanam,id',
            'tanggal_panen' => 'required|date',
            'jumlah' => 'required|numeric|min:0.01',
            'kualitas' => 'required|in:baik,sedang,buruk',
            'catatan' => 'nullable|string|max:500',
        ];
    }

    protected $messages = [
        'siklus_tanam_id.required' => 'Siklus tanam wajib dipilih.',
        'siklus_tanam_id.exists' => 'Siklus tanam tidak valid.',
        'tanggal_panen.required' => 'Tanggal panen wajib diisi.',
        'tanggal_panen.date' => 'Format tanggal tidak valid.',
        'jumlah.required' => 'Jumlah panen wajib diisi.',
        'jumlah.numeric' => 'Jumlah panen harus berupa angka.',
        'jumlah.min' => 'Jumlah panen minimal 0.01 Ton.',
        'kualitas.required' => 'Kualitas wajib dipilih.',
        'kualitas.in' => 'Kualitas tidak valid.',
    ];

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'deleteConfirmed' => 'delete',
    ];

    public function mount()
    {
        $this->listSiklusAktif = SiklusTanam::with(['lahan', 'varietasPadi'])
            ->where('user_id', Auth::id())
            ->where('status', 'aktif')
            ->orderBy('tanggal_tanam', 'desc')
            ->get();

        $this->loadStatistik();
    }

    public function updatedSiklusTanamId($value)
    {
        if ($value) {
            $this->siklusDetail = SiklusTanam::with(['lahan', 'varietasPadi'])
                ->where('user_id', Auth::id())
                ->find($value);
        } else {
            $this->siklusDetail = null;
        }
    }

    public function resetForm()
    {
        $this->reset(['panen_id', 'siklus_tanam_id', 'tanggal_panen', 'jumlah', 'kualitas', 'catatan', 'showModal', 'isEdit', 'siklusDetail']);
        $this->resetValidation();
        $this->kualitas = 'baik';
    }

    public function sortData($column)
    {
        $this->sortDirection = ($this->sortBy === $column && $this->sortDirection === 'asc') ? 'desc' : 'asc';
        $this->sortBy = $column;
    }

    public function loadStatistik()
    {
        $query = Panen::whereHas('siklusTanam', fn($q) => $q->where('user_id', Auth::id()));
        $this->totalPanen = $query->count();
        $this->totalJumlah = $query->sum('jumlah');
        $this->rataHasil = $this->totalPanen > 0 ? round($this->totalJumlah / $this->totalPanen, 2) : 0;
    }

    // ========== MODAL ==========
    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->titleModal = 'Catat Hasil Panen';
        $this->tanggal_panen = Carbon::now()->format('Y-m-d');
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetForm();
        $this->isEdit = true;
        $this->titleModal = 'Edit Data Panen';

        $panen = Panen::whereHas('siklusTanam', fn($q) => $q->where('user_id', Auth::id()))->findOrFail($id);
        $this->panen_id = $panen->id;
        $this->siklus_tanam_id = $panen->siklus_tanam_id;
        $this->tanggal_panen = $panen->tanggal_panen->format('Y-m-d');
        $this->jumlah = $panen->jumlah;
        $this->kualitas = $panen->kualitas;
        $this->catatan = $panen->catatan;

        $this->updatedSiklusTanamId($this->siklus_tanam_id);
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
                $panen = Panen::whereHas('siklusTanam', fn($q) => $q->where('user_id', Auth::id()))->findOrFail($this->panen_id);
                $panen->update([
                    'siklus_tanam_id' => $this->siklus_tanam_id,
                    'tanggal_panen' => $this->tanggal_panen,
                    'jumlah' => $this->jumlah,
                    'kualitas' => $this->kualitas,
                    'catatan' => $this->catatan,
                ]);
                $msg = 'Data panen berhasil diperbarui.';
            } else {
                Panen::create([
                    'siklus_tanam_id' => $this->siklus_tanam_id,
                    'tanggal_panen' => $this->tanggal_panen,
                    'jumlah' => $this->jumlah,
                    'kualitas' => $this->kualitas,
                    'catatan' => $this->catatan,
                ]);

                // Update siklus jadi selesai
                $siklus = SiklusTanam::where('user_id', Auth::id())->findOrFail($this->siklus_tanam_id);
                $siklus->update([
                    'status' => 'selesai',
                    'tanggal_panen_aktual' => $this->tanggal_panen,
                    'hasil_panen' => $this->jumlah,
                ]);

                // Refresh list siklus aktif
                $this->listSiklusAktif = SiklusTanam::with(['lahan', 'varietasPadi'])
                    ->where('user_id', Auth::id())
                    ->where('status', 'aktif')
                    ->orderBy('tanggal_tanam', 'desc')
                    ->get();

                $msg = 'Hasil panen berhasil dicatat. Siklus tanam ditandai selesai.';
            }

            $this->loadStatistik();
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
        $panen = Panen::whereHas('siklusTanam', fn($q) => $q->where('user_id', Auth::id()))->findOrFail($id);
        $this->panen_id = $panen->id;

        $this->dispatch('tampilKonfirmasiHapus', [
            'nama' => $panen->siklusTanam->lahan->nama . ' - ' . number_format($panen->jumlah, 1) . ' Ton'
        ]);
    }

    // ========== DELETE ==========
    public function delete()
    {
        try {
            $panen = Panen::whereHas('siklusTanam', fn($q) => $q->where('user_id', Auth::id()))->findOrFail($this->panen_id);
            $panen->delete();

            $this->loadStatistik();
            $this->dispatch('tampilPesan', ['tipe' => 'success', 'judul' => 'Terhapus!', 'teks' => 'Data panen berhasil dihapus.']);
            $this->resetForm();
            $this->dispatch('refreshComponent');

        } catch (\Exception $e) {
            $this->dispatch('tampilPesan', ['tipe' => 'error', 'judul' => 'Gagal!', 'teks' => $e->getMessage()]);
        }
    }

    public function render()
    {
        $panen = Panen::with(['siklusTanam.lahan', 'siklusTanam.varietasPadi'])
            ->whereHas('siklusTanam', fn($q) => $q->where('user_id', Auth::id()))
            ->when($this->search, fn($q) => $q->whereHas('siklusTanam.lahan', fn($s) => $s->where('nama', 'like', '%'.$this->search.'%'))
                ->orWhereHas('siklusTanam.varietasPadi', fn($s) => $s->where('nama', 'like', '%'.$this->search.'%')))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.petani.panen.index', compact('panen'));
    }
}