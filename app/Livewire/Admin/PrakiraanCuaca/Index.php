<?php

namespace App\Livewire\Admin\Prakiraancuaca;

use Livewire\Component;
use App\Models\Lokasi;
use App\Models\PrakiraanCuaca;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    public $kodeDesa = '13.08.17.2004';
    public $apiUrl = 'https://api.bmkg.go.id/publik/prakiraan-cuaca';

    public $lokasi;
    public $cuacaPerHari = [];
    public $totalData = 0;
    public $lastSync = null;
    public $isLoading = false;

    protected $daftarHari = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
    ];

    public function mount()
    {
        $this->loadDariDatabase();
    }

    public function loadDariDatabase()
    {
        $this->lokasi = Lokasi::where('kode_desa', $this->kodeDesa)->first();

        if (!$this->lokasi) {
            $this->cuacaPerHari = [];
            $this->totalData = 0;
            $this->lastSync = null;
            return;
        }

        $semuaCuaca = PrakiraanCuaca::where('lokasi_id', $this->lokasi->id)
            ->orderBy('waktu_lokal')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->waktu_lokal)->format('Y-m-d');
            });

        $this->cuacaPerHari = [];
        $hariKe = 1;

        foreach ($semuaCuaca as $tanggal => $cuacaList) {
            $dataCuaca = [];
            foreach ($cuacaList as $cuaca) {
                $waktuLokal = Carbon::parse($cuaca->waktu_lokal);
                $hariInggris = $waktuLokal->format('l');
                
                $dataCuaca[] = [
                    'hari' => $this->daftarHari[$hariInggris] ?? $hariInggris,
                    'jam' => $waktuLokal->format('H:i'),
                    'tanggal' => $waktuLokal->format('Y-m-d'),
                    'deskripsi' => $cuaca->deskripsi_cuaca ?? 'N/A',
                    'icon_url' => $cuaca->Gambar ?? null,
                    'suhu' => $cuaca->suhu ?? 'N/A',
                    'kelembapan' => $cuaca->kelembapan ?? 'N/A',
                    'kecepatan_angin' => $cuaca->kecepatan_angin ?? 'N/A',
                    'arah_angin' => $cuaca->arah_angin ?? 'N/A',
                    'curah_hujan' => $cuaca->curah_hujan ?? 0,
                ];
            }
            
            $this->cuacaPerHari[] = [
                'hari_ke' => $hariKe,
                'tanggal' => $tanggal,
                'data' => $dataCuaca,
            ];
            $hariKe++;
        }

        $this->totalData = PrakiraanCuaca::where('lokasi_id', $this->lokasi->id)->count();
        
        $last = PrakiraanCuaca::where('lokasi_id', $this->lokasi->id)
            ->latest('created_at')
            ->first();
        $this->lastSync = $last ? Carbon::parse($last->created_at) : null;
    }

    public function sinkronisasi()
    {
        $this->isLoading = true;

        try {
            $response = Http::timeout(30)->get($this->apiUrl, ['adm4' => $this->kodeDesa]);

            if ($response->failed()) {
                throw new \Exception('Gagal mengambil data dari API BMKG. Status: ' . $response->status());
            }

            $data = $response->json();

            $this->lokasi = Lokasi::updateOrCreate(
                ['kode_desa' => $data['lokasi']['adm4']],
                [
                    'provinsi' => $data['lokasi']['provinsi'],
                    'kabupaten' => $data['lokasi']['kotkab'],
                    'kecamatan' => $data['lokasi']['kecamatan'],
                    'desa' => $data['lokasi']['desa'],
                    'bujur' => $data['lokasi']['lon'],
                    'lintang' => $data['lokasi']['lat'],
                    'zona_waktu' => $data['lokasi']['timezone'],
                ]
            );

            $totalSaved = 0;
            $totalDays = 0;

            foreach ($data['data'] as $periodeData) {
                if (!isset($periodeData['cuaca'])) continue;
                $totalDays = count($periodeData['cuaca']);
                
                foreach ($periodeData['cuaca'] as $hari) {
                    foreach ($hari as $item) {
                        if (!isset($item['local_datetime'])) continue;

                        try {
                            PrakiraanCuaca::updateOrCreate(
                                [
                                    'lokasi_id' => $this->lokasi->id,
                                    'waktu_lokal' => Carbon::parse($item['local_datetime']),
                                    'tanggal_analisis' => Carbon::parse($item['analysis_date'])->format('Y-m-d'),
                                ],
                                [
                                    'waktu_utc' => Carbon::parse($item['utc_datetime']),
                                    'suhu' => $item['t'] ?? null,
                                    'kelembapan' => $item['hu'] ?? null,
                                    'curah_hujan' => $item['tp'] ?? null,
                                    'deskripsi_cuaca' => $item['weather_desc'] ?? null,
                                    'Gambar' => $item['image'] ?? null,
                                    'arah_angin' => $item['wd'] ?? null,
                                    'kecepatan_angin' => $item['ws'] ?? null,
                                ]
                            );
                            $totalSaved++;
                        } catch (\Exception $e) {
                            Log::error('Gagal simpan item cuaca: ' . $e->getMessage());
                            continue;
                        }
                    }
                }
            }

            $this->loadDariDatabase();

            $this->dispatch('tampilPesan', [
                'tipe' => 'success',
                'judul' => 'Berhasil!',
                'teks' => "{$totalSaved} data cuaca ({$totalDays} hari) berhasil disimpan ke database.",
            ]);

        } catch (\Exception $e) {
            $this->dispatch('tampilPesan', [
                'tipe' => 'error',
                'judul' => 'Gagal!',
                'teks' => $e->getMessage(),
            ]);
        }

        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.admin.prakiraan-cuaca.index')->layout('layouts.admin');
    }
}