<?php

namespace App\Livewire\Admin\Pupuk;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pupuk;

class Index extends Component
{
    use WithPagination;

    public $cari = '';
    public $jumlahData = 10;
    public $kolomUrut = 'created_at';
    public $arahUrut = 'desc';
    public $pupuk_id;
    public $nama;
    public $jenis;
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
            'jenis' => 'nullable|string|max:50',
            'dosis_standar_ha' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:20',
        ];
    }

    protected $messages = [
        'nama.required' => 'Nama pupuk wajib diisi.',
        'nama.max' => 'Nama pupuk maksimal 100 karakter.',
        'dosis_standar_ha.required' => 'Dosis standar wajib diisi.',
        'dosis_standar_ha.numeric' => 'Dosis standar harus berupa angka.',
        'dosis_standar_ha.min' => 'Dosis standar minimal 0.',
        'satuan.required' => 'Satuan wajib diisi.',
    ];

    public function resetForm()
    {
        $this->reset(['pupuk_id', 'nama', 'jenis', 'dosis_standar_ha', 'satuan', 'tampilModal', 'sedangEdit']);
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
        $this->judulModal = 'Tambah Data Pupuk';
        $this->tampilModal = true;
    }

    public function bukaModalEdit($id)
    {
        $this->resetForm();
        $this->sedangEdit = true;
        $this->judulModal = 'Edit Data Pupuk';
        $pupuk = Pupuk::findOrFail($id);
        $this->pupuk_id = $pupuk->id;
        $this->nama = $pupuk->nama;
        $this->jenis = $pupuk->jenis;
        $this->dosis_standar_ha = $pupuk->dosis_standar_ha;
        $this->satuan = $pupuk->satuan;
        $this->tampilModal = true;
    }

    public function tutupModal()
    {
        $this->resetForm();
    }

    public function konfirmasiHapus($id)
    {
        $pupuk = Pupuk::findOrFail($id);
        $this->pupuk_id = $pupuk->id;
        $this->dispatch('tampilKonfirmasiHapus', [
            'id' => $pupuk->id,
            'nama' => $pupuk->nama,
        ]);
    }

    public function simpan()
    {
        $this->validate();

        try {
            if ($this->sedangEdit) {
                Pupuk::findOrFail($this->pupuk_id)->update([
                    'nama' => $this->nama,
                    'jenis' => $this->jenis,
                    'dosis_standar_ha' => $this->dosis_standar_ha,
                    'satuan' => $this->satuan,
                ]);
                $pesan = 'Data pupuk berhasil diperbarui.';
            } else {
                Pupuk::create([
                    'nama' => $this->nama,
                    'jenis' => $this->jenis,
                    'dosis_standar_ha' => $this->dosis_standar_ha,
                    'satuan' => $this->satuan,
                ]);
                $pesan = 'Data pupuk berhasil ditambahkan.';
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
        $pupuk = Pupuk::findOrFail($this->pupuk_id);
        
        if ($pupuk->faseTumbuh()->exists()) {
            $this->dispatch('tampilPesan', ['tipe' => 'error', 'judul' => 'Gagal!', 'teks' => 'Pupuk ini digunakan dalam data fase tumbuh.']);
            return;
        }
        if ($pupuk->jadwalOtomatis()->exists()) {
            $this->dispatch('tampilPesan', ['tipe' => 'error', 'judul' => 'Gagal!', 'teks' => 'Pupuk ini digunakan dalam jadwal aktivitas.']);
            return;
        }

        $namaPupuk = $pupuk->nama;
        $pupuk->delete();
        $this->dispatch('tampilPesan', ['tipe' => 'success', 'judul' => 'Terhapus!', 'teks' => "Pupuk \"{$namaPupuk}\" berhasil dihapus."]);
        $this->resetForm();
        $this->dispatch('refreshComponent');
    }

    public function render()
    {
        return view('livewire.admin.pupuk.index', [
            'dataPupuk' => Pupuk::query()
                ->when($this->cari, fn($q) => $q->where('nama', 'like', '%' . $this->cari . '%')
                    ->orWhere('jenis', 'like', '%' . $this->cari . '%'))
                ->orderBy($this->kolomUrut, $this->arahUrut)
                ->paginate($this->jumlahData),
        ])->layout('layouts.admin');
    }
}