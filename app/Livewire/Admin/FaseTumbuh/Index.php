<?php

namespace App\Livewire\Admin\FaseTumbuh;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\FaseTumbuh;
use App\Models\VarietasPadi;
use App\Models\Pupuk;
use App\Models\Pestisida;

class Index extends Component
{
    use WithPagination;

    public $cari = '';
    public $jumlahData = 10;
    public $kolomUrut = 'created_at';
    public $arahUrut = 'desc';
    public $filterVarietas = '';

    public $fase_id;
    public $varietas_padi_id;
    public $nama_fase;
    public $hari_setelah_tanam;
    public $pupuk_id;
    public $pestisida_id;
    public $deskripsi;

    public $tampilModal = false;
    public $tampilDetail = false;
    public $sedangEdit = false;
    public $judulModal = '';
    public $detailFase;

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'hapusDikonfirmasi' => 'hapus',
    ];

    protected function rules()
    {
        return [
            'varietas_padi_id' => 'required|exists:varietas_padi,id',
            'nama_fase' => 'required|string|max:100',
            'hari_setelah_tanam' => 'required|integer|min:0|max:300',
            'pupuk_id' => 'nullable|exists:pupuk,id',
            'pestisida_id' => 'nullable|exists:pestisida,id',
            'deskripsi' => 'nullable|string|max:500',
        ];
    }

    public function resetForm()
    {
        $this->reset([
            'fase_id',
            'varietas_padi_id',
            'nama_fase',
            'hari_setelah_tanam',
            'pupuk_id',
            'pestisida_id',
            'deskripsi',
            'tampilModal',
            'sedangEdit'
        ]);

        $this->resetValidation();
    }

    public function bukaModalTambah()
    {
        $this->resetForm();
        $this->sedangEdit = false;
        $this->judulModal = 'Tambah Fase Tumbuh';
        $this->tampilModal = true;
    }

    public function bukaModalEdit($id)
    {
        $this->resetForm();

        $this->sedangEdit = true;
        $this->judulModal = 'Edit Fase Tumbuh';

        $fase = FaseTumbuh::findOrFail($id);

        $this->fase_id = $fase->id;
        $this->varietas_padi_id = $fase->varietas_padi_id;
        $this->nama_fase = $fase->nama_fase;
        $this->hari_setelah_tanam = $fase->hari_setelah_tanam;
        $this->pupuk_id = $fase->pupuk_id;
        $this->pestisida_id = $fase->pestisida_id;
        $this->deskripsi = $fase->deskripsi;

        $this->tampilModal = true;
    }

    public function tutupModal()
    {
        $this->resetForm();
    }

    public function lihatDetail($id)
    {
        $this->detailFase = FaseTumbuh::with(['varietasPadi', 'pupuk', 'pestisida'])
            ->findOrFail($id);

        $this->tampilDetail = true;
    }

    public function tutupDetail()
    {
        $this->tampilDetail = false;
        $this->detailFase = null;
    }

    public function konfirmasiHapus($id)
    {
        $fase = FaseTumbuh::findOrFail($id);

        $this->fase_id = $fase->id;

        $this->dispatch('tampilKonfirmasiHapus', [
            'id' => $fase->id,
            'nama' => $fase->nama_fase,
        ]);
    }

    public function simpan()
    {
        $this->validate();

        if ($this->sedangEdit) {
            FaseTumbuh::findOrFail($this->fase_id)->update([
                'varietas_padi_id' => $this->varietas_padi_id,
                'nama_fase' => $this->nama_fase,
                'hari_setelah_tanam' => $this->hari_setelah_tanam,
                'pupuk_id' => $this->pupuk_id ?: null,
                'pestisida_id' => $this->pestisida_id ?: null,
                'deskripsi' => $this->deskripsi,
            ]);

            $pesan = 'Fase berhasil diperbarui.';
        } else {
            FaseTumbuh::create([
                'varietas_padi_id' => $this->varietas_padi_id,
                'nama_fase' => $this->nama_fase,
                'hari_setelah_tanam' => $this->hari_setelah_tanam,
                'pupuk_id' => $this->pupuk_id ?: null,
                'pestisida_id' => $this->pestisida_id ?: null,
                'deskripsi' => $this->deskripsi,
            ]);

            $pesan = 'Fase berhasil ditambahkan.';
        }

        $this->dispatch('tampilPesan', [
            'tipe' => 'success',
            'judul' => 'Berhasil',
            'teks' => $pesan
        ]);

        $this->resetForm();
        $this->dispatch('refreshComponent');
    }

    public function hapus()
    {
        FaseTumbuh::findOrFail($this->fase_id)->delete();

        $this->dispatch('tampilPesan', [
            'tipe' => 'success',
            'judul' => 'Terhapus',
            'teks' => 'Data berhasil dihapus'
        ]);

        $this->resetForm();
        $this->dispatch('refreshComponent');
    }

    public function render()
    {
        $dataFase = FaseTumbuh::with(['varietasPadi', 'pupuk', 'pestisida'])
            ->when($this->filterVarietas, fn($q) =>
                $q->where('varietas_padi_id', $this->filterVarietas)
            )
            ->when($this->cari, fn($q) =>
                $q->where('nama_fase', 'like', "%{$this->cari}%")
                  ->orWhere('deskripsi', 'like', "%{$this->cari}%")
            )
            ->orderBy('varietas_padi_id')
            ->orderBy('hari_setelah_tanam')
            ->paginate($this->jumlahData);

        return view('livewire.admin.fase-tumbuh.index', [
            'dataFase' => $dataFase,
            'daftarVarietas' => VarietasPadi::orderBy('nama')->get(),
            'daftarPupuk' => Pupuk::orderBy('nama')->get(),
            'daftarPestisida' => Pestisida::orderBy('nama')->get(),
        ])->layout('layouts.admin');
    }
}