<?php

namespace App\Livewire\Admin\Hama;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\HamaPenyakit;

class Index extends Component
{
    use WithPagination;

    public $cari = '';
    public $jumlahData = 10;
    public $kolomUrut = 'created_at';
    public $arahUrut = 'desc';
    public $hama_id;
    public $nama;
    public $jenis;
    public $gejala;
    public $rekomendasi;
    public $tampilModal = false;
    public $sedangEdit = false;
    public $judulModal = '';

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'hapusDikonfirmasi' => 'hapus',
    ];

    protected function rules()
    {
        return [
            'nama' => 'required|string|max:100',
            'jenis' => 'required|in:hama,penyakit',
            'gejala' => 'nullable|string|max:1000',
            'rekomendasi' => 'nullable|string|max:1000',
        ];
    }

    protected $messages = [
        'nama.required' => 'Nama hama/penyakit wajib diisi.',
        'nama.max' => 'Nama maksimal 100 karakter.',
        'jenis.required' => 'Jenis wajib dipilih.',
        'jenis.in' => 'Jenis harus Hama atau Penyakit.',
        'gejala.max' => 'Gejala maksimal 1000 karakter.',
        'rekomendasi.max' => 'Rekomendasi maksimal 1000 karakter.',
    ];

    public function resetForm()
    {
        $this->reset(['hama_id', 'nama', 'jenis', 'gejala', 'rekomendasi', 'tampilModal', 'sedangEdit']);
        $this->resetValidation();
    }

    public function urutkan($kolom)
    {
        if ($this->kolomUrut === $kolom) {
            $this->arahUrut = $this->arahUrut === 'asc' ? 'desc' : 'asc';
        } else {
            $this->kolomUrut = $kolom;
            $this->arahUrut = 'asc';
        }
    }

    public function bukaModalTambah()
    {
        $this->resetForm();
        $this->sedangEdit = false;
        $this->judulModal = 'Tambah Data Hama/Penyakit';
        $this->tampilModal = true;
    }

    public function bukaModalEdit($id)
    {
        $this->resetForm();
        $this->sedangEdit = true;
        $this->judulModal = 'Edit Data Hama/Penyakit';
        $hama = HamaPenyakit::findOrFail($id);
        $this->hama_id = $hama->id;
        $this->nama = $hama->nama;
        $this->jenis = $hama->jenis;
        $this->gejala = $hama->gejala;
        $this->rekomendasi = $hama->rekomendasi;
        $this->tampilModal = true;
    }

    public function tutupModal()
    {
        $this->resetForm();
    }

    public function konfirmasiHapus($id)
    {
        $hama = HamaPenyakit::findOrFail($id);
        $this->hama_id = $hama->id;
        $this->dispatch('tampilKonfirmasiHapus', [
            'id' => $hama->id,
            'nama' => $hama->nama,
        ]);
    }

    public function simpan()
    {
        $this->validate();

        try {
            if ($this->sedangEdit) {
                HamaPenyakit::findOrFail($this->hama_id)->update([
                    'nama' => $this->nama,
                    'jenis' => $this->jenis,
                    'gejala' => $this->gejala,
                    'rekomendasi' => $this->rekomendasi,
                ]);
                $pesan = 'Data hama/penyakit berhasil diperbarui.';
            } else {
                HamaPenyakit::create([
                    'nama' => $this->nama,
                    'jenis' => $this->jenis,
                    'gejala' => $this->gejala,
                    'rekomendasi' => $this->rekomendasi,
                ]);
                $pesan = 'Data hama/penyakit berhasil ditambahkan.';
            }

            $this->dispatch('tampilPesan', ['tipe' => 'success', 'judul' => 'Berhasil!', 'teks' => $pesan]);
            $this->resetForm();
            $this->dispatch('refreshComponent');
        } catch (\Exception $e) {
            $this->dispatch('tampilPesan', ['tipe' => 'error', 'judul' => 'Gagal!', 'teks' => $e->getMessage()]);
        }
    }

    public function hapus()
    {
        $hama = HamaPenyakit::findOrFail($this->hama_id);
        $namaHama = $hama->nama;
        $hama->delete();
        
        $this->dispatch('tampilPesan', ['tipe' => 'success', 'judul' => 'Terhapus!', 'teks' => "\"{$namaHama}\" berhasil dihapus."]);
        $this->resetForm();
        $this->dispatch('refreshComponent');
    }

    public function render()
    {
        return view('livewire.admin.hama.index', [
            'dataHama' => HamaPenyakit::query()
                ->when($this->cari, fn($q) => $q->where('nama', 'like', '%' . $this->cari . '%')
                    ->orWhere('gejala', 'like', '%' . $this->cari . '%')
                    ->orWhere('rekomendasi', 'like', '%' . $this->cari . '%'))
                ->orderBy($this->kolomUrut, $this->arahUrut)
                ->paginate($this->jumlahData),
        ])->layout('layouts.admin');
    }
}