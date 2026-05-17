<?php

namespace App\Livewire\Petani\Cuaca;

use Livewire\Component;
use App\Models\Lokasi;
use App\Models\PrakiraanCuaca;
use Carbon\Carbon;

class Index extends Component
{
    public $kodeDesa = '13.08.17.2004';
    public $lokasi;
    public $cuacaSekarang;
    public $hariIni = [];
    public $besok = [];
    public $lusa = [];
    public $selectedTab = 'hari-ini';
    public $totalData = 0;
    public $lastSync = null;
    public $rekomendasi = [];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->lokasi = Lokasi::where('kode_desa', $this->kodeDesa)->first();

        if (!$this->lokasi) {
            $this->hariIni = [];
            $this->besok = [];
            $this->lusa = [];
            $this->totalData = 0;
            $this->lastSync = null;
            return;
        }

        // Cuaca sekarang: hanya yang ada gambar
        $this->cuacaSekarang = PrakiraanCuaca::where('lokasi_id', $this->lokasi->id)
            ->where('waktu_lokal', '<=', Carbon::now()->addHour())
            ->whereNotNull('Gambar')
            ->where('Gambar', '!=', '')
            ->orderBy('waktu_lokal', 'desc')
            ->first();

        if (!$this->cuacaSekarang) {
            $this->cuacaSekarang = PrakiraanCuaca::where('lokasi_id', $this->lokasi->id)
                ->whereNotNull('Gambar')
                ->where('Gambar', '!=', '')
                ->orderBy('waktu_lokal', 'asc')
                ->first();
        }

        // Hari ini, besok, lusa
        $this->hariIni = $this->getCuacaPerTanggal(Carbon::now()->format('Y-m-d'));
        $this->besok = $this->getCuacaPerTanggal(Carbon::now()->addDay()->format('Y-m-d'));
        $this->lusa = $this->getCuacaPerTanggal(Carbon::now()->addDays(2)->format('Y-m-d'));

        $this->totalData = PrakiraanCuaca::where('lokasi_id', $this->lokasi->id)->count();

        $last = PrakiraanCuaca::where('lokasi_id', $this->lokasi->id)->latest('created_at')->first();
        $this->lastSync = $last ? Carbon::parse($last->created_at) : null;

        $this->generateRekomendasi();
    }

    /**
     * Ambil data cuaca per tanggal, HANYA yang ada gambar
     */
    private function getCuacaPerTanggal($tanggal)
    {
        if (!$this->lokasi) return collect();

        return PrakiraanCuaca::where('lokasi_id', $this->lokasi->id)
            ->whereDate('waktu_lokal', $tanggal)
            ->whereNotNull('Gambar')
            ->where('Gambar', '!=', '')
            ->orderBy('waktu_lokal')
            ->get()
            ->map(function ($item) {
                $item->jam = substr($item->waktu_lokal, 11, 5);
                return $item;
            });
    }

    public function setTab($tab)
    {
        $this->selectedTab = $tab;
        $this->generateRekomendasi();
    }

    private function generateRekomendasi()
    {
        $this->rekomendasi = [];

        $cuacaList = $this->selectedTab === 'hari-ini' ? $this->hariIni : 
                    ($this->selectedTab === 'besok' ? $this->besok : $this->lusa);

        if ($cuacaList->count() == 0) return;

        $adaHujan = $cuacaList->where('curah_hujan', '>', 0)->count();
        $suhuMax = $cuacaList->max('suhu');

        if ($adaHujan > 0) {
            $this->rekomendasi[] = ['icon' => 'ti-cloud-rain', 'color' => 'info', 'teks' => 'Terdeteksi potensi hujan. Sebaiknya tunda pemupukan dan penyemprotan.'];
        }
        if ($suhuMax > 30) {
            $this->rekomendasi[] = ['icon' => 'ti-temperature', 'color' => 'danger', 'teks' => 'Suhu tinggi (' . $suhuMax . '°C). Pastikan lahan memiliki cukup air.'];
        }
        if ($cuacaList->where('kelembapan', '>', 90)->count() > 0) {
            $this->rekomendasi[] = ['icon' => 'ti-droplet', 'color' => 'warning', 'teks' => 'Kelembapan tinggi. Waspadai serangan jamur.'];
        }
    }

    public function render()
    {
        return view('livewire.petani.cuaca.index')->layout('layouts.petani');
    }
}