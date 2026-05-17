<?php

namespace App\Livewire\Petani\SiklusTanam;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SiklusTanam;
use App\Models\Lahan;
use App\Models\VarietasPadi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Form
    public $siklus_id;
    public $lahan_id;
    public $varietas_padi_id;
    public $tanggal_tanam;
    public $catatan;

    // Data pendukung
    public $listLahan = [];
    public $listVarietas = [];
    public $varietasDetail = null;

    // Modal
    public $showModal = false;
    public $isEdit = false;
    public $titleModal = '';
    public $siklus;

    protected function rules()
    {
        return [
            'lahan_id' => 'required|exists:lahan,id',
            'varietas_padi_id' => 'required|exists:varietas_padi,id',
            'tanggal_tanam' => 'required|date',
            'catatan' => 'nullable|string|max:500',
        ];
    }

    protected $messages = [
        'lahan_id.required' => 'Lahan wajib dipilih.',
        'lahan_id.exists' => 'Lahan tidak valid.',
        'varietas_padi_id.required' => 'Varietas padi wajib dipilih.',
        'varietas_padi_id.exists' => 'Varietas tidak valid.',
        'tanggal_tanam.required' => 'Tanggal tanam wajib diisi.',
        'tanggal_tanam.date' => 'Format tanggal tidak valid.',
    ];

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'deleteConfirmed' => 'delete',
        'konfirmasiSelesai' => 'selesaikanSiklus',
    ];

    public function mount()
    {
        $this->listLahan = Lahan::where('user_id', Auth::id())->orderBy('nama')->get();
        $this->listVarietas = VarietasPadi::orderBy('nama')->get();
    }

    public function updatedVarietasPadiId($value)
    {
        if ($value) {
            $this->varietasDetail = VarietasPadi::find($value);
        } else {
            $this->varietasDetail = null;
        }
    }

    public function resetForm()
    {
        $this->reset(['siklus_id', 'lahan_id', 'varietas_padi_id', 'tanggal_tanam', 'catatan', 'showModal', 'isEdit', 'varietasDetail']);
        $this->resetValidation();
    }

    public function sortData($column)
    {
        $this->sortDirection = ($this->sortBy === $column && $this->sortDirection === 'asc') ? 'desc' : 'asc';
        $this->sortBy = $column;
    }

    // ========== MODAL ==========
    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEdit = false;
        $this->titleModal = 'Buat Siklus Tanam Baru';
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
            $varietas = VarietasPadi::findOrFail($this->varietas_padi_id);
            $perkiraanPanen = Carbon::parse($this->tanggal_tanam)->addDays($varietas->umur_panen);

            if ($this->isEdit) {
                $siklus = SiklusTanam::where('user_id', Auth::id())->findOrFail($this->siklus_id);
                $siklus->update([
                    'lahan_id' => $this->lahan_id,
                    'varietas_padi_id' => $this->varietas_padi_id,
                    'tanggal_tanam' => $this->tanggal_tanam,
                    'perkiraan_panen' => $perkiraanPanen,
                    'catatan' => $this->catatan,
                ]);
                $msg = 'Siklus tanam berhasil diperbarui.';
            } else {
                $siklus = SiklusTanam::create([
                    'user_id' => Auth::id(),
                    'lahan_id' => $this->lahan_id,
                    'varietas_padi_id' => $this->varietas_padi_id,
                    'tanggal_tanam' => $this->tanggal_tanam,
                    'perkiraan_panen' => $perkiraanPanen,
                    'status' => 'aktif',
                    'catatan' => $this->catatan,
                ]);

                // Generate jadwal otomatis
                $siklus->generateJadwal();
                $msg = 'Siklus tanam berhasil dibuat. Jadwal kegiatan otomatis sudah dibuat.';
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
        $siklus = SiklusTanam::where('user_id', Auth::id())->findOrFail($id);
        $this->siklus_id = $siklus->id;

        $this->dispatch('tampilKonfirmasiHapus', ['nama' => $siklus->lahan->nama . ' - ' . $siklus->varietasPadi->nama]);
    }

    // ========== DELETE ==========
    public function delete()
    {
        try {
            $siklus = SiklusTanam::where('user_id', Auth::id())->findOrFail($this->siklus_id);

            // Hapus jadwal dulu
            $siklus->jadwalOtomatis()->delete();
            $siklus->delete();

            $this->dispatch('tampilPesan', ['tipe' => 'success', 'judul' => 'Terhapus!', 'teks' => 'Siklus tanam berhasil dihapus.']);
            $this->resetForm();
            $this->dispatch('refreshComponent');

        } catch (\Exception $e) {
            $this->dispatch('tampilPesan', ['tipe' => 'error', 'judul' => 'Gagal!', 'teks' => $e->getMessage()]);
        }
    }

    // ========== KONFIRMASI SELESAIKAN SIKLUS ==========
    public function confirmSelesai($id)
    {
        $siklus = SiklusTanam::where('user_id', Auth::id())->findOrFail($id);
        $this->siklus_id = $siklus->id;

        $this->dispatch('tampilKonfirmasiSelesai', [
            'nama' => $siklus->lahan->nama . ' - ' . $siklus->varietasPadi->nama
        ]);
    }

    // ========== SELESAIKAN SIKLUS ==========
    public function selesaikanSiklus()
    {
        try {
            $siklus = SiklusTanam::where('user_id', Auth::id())->findOrFail($this->siklus_id);
            $siklus->update(['status' => 'selesai']);

            $this->dispatch('tampilPesan', ['tipe' => 'success', 'judul' => 'Berhasil!', 'teks' => 'Siklus tanam diselesaikan.']);
            $this->dispatch('refreshComponent');

        } catch (\Exception $e) {
            $this->dispatch('tampilPesan', ['tipe' => 'error', 'judul' => 'Gagal!', 'teks' => $e->getMessage()]);
        }
    }

        public function render()
        {
            $daftarSiklus = SiklusTanam::with(['lahan', 'varietasPadi'])
                ->where('user_id', Auth::id())
                ->when($this->search, function ($query) {
                    $query->whereHas('lahan', fn($q) => $q->where('nama', 'like', '%' . $this->search . '%'))
                          ->orWhereHas('varietasPadi', fn($q) => $q->where('nama', 'like', '%' . $this->search . '%'));
                })
                ->orderBy($this->sortBy, $this->sortDirection)
                ->paginate($this->perPage);

            $totalAktif = SiklusTanam::where('user_id', Auth::id())->where('status', 'aktif')->count();
            $totalSelesai = SiklusTanam::where('user_id', Auth::id())->where('status', 'selesai')->count();

            return view('livewire.petani.siklus-tanam.index', compact('daftarSiklus', 'totalAktif', 'totalSelesai'));
        }
}