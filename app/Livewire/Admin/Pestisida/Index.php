<?php

namespace App\Livewire\Admin\Pestisida;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pestisida;

class Index extends Component
{
    use WithPagination;

    public $cari = '';
    public $jumlahData = 10;
    public $kolomUrut = 'created_at';
    public $arahUrut = 'desc';
    public $pestisida_id;
    public $nama;
    public $hama_target;
    public $dosis_standar_ha;
    public $satuan;
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
            'hama_target' => 'nullable|string|max:100',
            'dosis_standar_ha' => 'nullable|numeric|min:0',
            'satuan' => 'required|string|max:20',
        ];
    }

    protected $messages = [
        'nama.required' => 'Nama pestisida wajib diisi.',
        'nama.max' => 'Nama pestisida maksimal 100 karakter.',
        'dosis_standar_ha.numeric' => 'Dosis standar harus berupa angka.',
        'dosis_standar_ha.min' => 'Dosis standar minimal 0.',
        'satuan.required' => 'Satuan wajib diisi.',
    ];

    public function resetForm()
    {
        $this->reset(['pestisida_id', 'nama', 'hama_target', 'dosis_standar_ha', 'satuan', 'tampilModal', 'sedangEdit']);
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
        $this->judulModal = 'Tambah Data Pestisida';
        $this->tampilModal = true;
    }

    public function bukaModalEdit($id)
    {
        $this->resetForm();
        $this->sedangEdit = true;
        $this->judulModal = 'Edit Data Pestisida';
        $pestisida = Pestisida::findOrFail($id);
        $this->pestisida_id = $pestisida->id;
        $this->nama = $pestisida->nama;
        $this->hama_target = $pestisida->hama_target;
        $this->dosis_standar_ha = $pestisida->dosis_standar_ha;
        $this->satuan = $pestisida->satuan;
        $this->tampilModal = true;
    }

    public function tutupModal()
    {
        $this->resetForm();
    }

    public function konfirmasiHapus($id)
    {
        $pestisida = Pestisida::findOrFail($id);
        $this->pestisida_id = $pestisida->id;
        $this->dispatch('tampilKonfirmasiHapus', [
            'id' => $pestisida->id,
            'nama' => $pestisida->nama,
        ]);
    }

    public function simpan()
    {
        $this->validate();

        try {
            if ($this->sedangEdit) {
                Pestisida::findOrFail($this->pestisida_id)->update([
                    'nama' => $this->nama,
                    'hama_target' => $this->hama_target,
                    'dosis_standar_ha' => $this->dosis_standar_ha,
                    'satuan' => $this->satuan,
                ]);
                $pesan = 'Data pestisida berhasil diperbarui.';
            } else {
                Pestisida::create([
                    'nama' => $this->nama,
                    'hama_target' => $this->hama_target,
                    'dosis_standar_ha' => $this->dosis_standar_ha,
                    'satuan' => $this->satuan,
                ]);
                $pesan = 'Data pestisida berhasil ditambahkan.';
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
        $pestisida = Pestisida::findOrFail($this->pestisida_id);
        
        if ($pestisida->faseTumbuh()->exists()) {
            $this->dispatch('tampilPesan', ['tipe' => 'error', 'judul' => 'Gagal!', 'teks' => 'Pestisida ini digunakan dalam data fase tumbuh.']);
            return;
        }
        if ($pestisida->jadwalOtomatis()->exists()) {
            $this->dispatch('tampilPesan', ['tipe' => 'error', 'judul' => 'Gagal!', 'teks' => 'Pestisida ini digunakan dalam jadwal aktivitas.']);
            return;
        }

        $namaPestisida = $pestisida->nama;
        $pestisida->delete();
        $this->dispatch('tampilPesan', ['tipe' => 'success', 'judul' => 'Terhapus!', 'teks' => "Pestisida \"{$namaPestisida}\" berhasil dihapus."]);
        $this->resetForm();
        $this->dispatch('refreshComponent');
    }

    public function render()
    {
        return view('livewire.admin.pestisida.index', [
            'dataPestisida' => Pestisida::query()
                ->when($this->cari, fn($q) => $q->where('nama', 'like', '%' . $this->cari . '%')
                    ->orWhere('hama_target', 'like', '%' . $this->cari . '%'))
                ->orderBy($this->kolomUrut, $this->arahUrut)
                ->paginate($this->jumlahData),
        ])->layout('layouts.admin');
    }
}