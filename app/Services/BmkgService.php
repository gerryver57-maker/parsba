<?php

namespace App\Services;

use App\Models\Lokasi;
use App\Models\PrakiraanCuaca;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BmkgService
{
    protected $baseUrl = 'https://api.bmkg.go.id/publik/prakiraan-cuaca';
    protected $kodeDesa = '13.08.17.2004';

    public function fetchDariApi(): ?array
    {
        $response = Http::timeout(30)->get($this->baseUrl, [
            'adm4' => $this->kodeDesa,
        ]);

        if ($response->failed()) {
            throw new \Exception('Gagal mengambil data dari API BMKG. Status: ' . $response->status());
        }

        $data = $response->json();

        if (!$data) {
            throw new \Exception('Data bukan format JSON yang valid.');
        }

        return $data;
    }

    public function simpanLokasi(array $dataLokasi): Lokasi
    {
        return Lokasi::updateOrCreate(
            ['kode_desa' => $dataLokasi['adm4']],
            [
                'provinsi' => $dataLokasi['provinsi'],
                'kabupaten' => $dataLokasi['kotkab'],
                'kecamatan' => $dataLokasi['kecamatan'],
                'desa' => $dataLokasi['desa'],
                'bujur' => $dataLokasi['lon'],
                'lintang' => $dataLokasi['lat'],
                'zona_waktu' => $dataLokasi['timezone'],
            ]
        );
    }

    public function simpanCuaca(int $lokasiId, array $cuacaArray): int
    {
        $totalSaved = 0;

        foreach ($cuacaArray as $hari) {
            foreach ($hari as $item) {
                if (!isset($item['local_datetime'])) {
                    continue;
                }

                try {
                    // Parse analysis_date yang formatnya ISO 8601
                    $analysisDate = isset($item['analysis_date']) 
                        ? Carbon::parse($item['analysis_date'])->format('Y-m-d') 
                        : Carbon::now()->format('Y-m-d');

                    $result = PrakiraanCuaca::updateOrCreate(
                        [
                            'lokasi_id' => $lokasiId,
                            'waktu_lokal' => Carbon::parse($item['local_datetime']),
                            'tanggal_analisis' => $analysisDate,
                        ],
                        [
                            'waktu_utc' => Carbon::parse($item['utc_datetime'] ?? $item['datetime']),
                            'suhu' => $item['t'] ?? null,
                            'kelembapan' => $item['hu'] ?? null,
                            'curah_hujan' => $item['tp'] ?? null,
                            'deskripsi_cuaca' => $item['weather_desc'] ?? null,
                            'arah_angin' => $item['wd'] ?? null,
                            'kecepatan_angin' => $item['ws'] ?? null,
                        ]
                    );

                    if ($result) {
                        $totalSaved++;
                    }
                } catch (\Exception $e) {
                    Log::error('Gagal simpan cuaca:', [
                        'error' => $e->getMessage(),
                        'item' => $item
                    ]);
                    continue;
                }
            }
        }

        return $totalSaved;
    }

    public function getCuacaDariDb(): array
    {
        $lokasi = Lokasi::where('kode_desa', $this->kodeDesa)->first();

        if (!$lokasi) {
            return [
                'lokasi' => null,
                'cuacaPerHari' => [],
                'totalData' => 0,
                'lastSync' => null,
            ];
        }

        $daftarHari = [
            'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];

        $semuaCuaca = PrakiraanCuaca::where('lokasi_id', $lokasi->id)
            ->orderBy('waktu_lokal')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->waktu_lokal)->format('Y-m-d');
            });

        $cuacaPerHari = [];
        $hariKe = 1;

        foreach ($semuaCuaca as $tanggal => $cuacaList) {
            $dataCuaca = [];
            foreach ($cuacaList as $cuaca) {
                $waktuLokal = Carbon::parse($cuaca->waktu_lokal);
                $hariInggris = $waktuLokal->format('l');

                $dataCuaca[] = [
                    'hari' => $daftarHari[$hariInggris] ?? $hariInggris,
                    'jam' => $waktuLokal->format('H:i'),
                    'tanggal' => $waktuLokal->format('Y-m-d'),
                    'deskripsi' => $cuaca->deskripsi_cuaca ?? 'N/A',
                    'suhu' => $cuaca->suhu ?? 'N/A',
                    'kelembapan' => $cuaca->kelembapan ?? 'N/A',
                    'kecepatan_angin' => $cuaca->kecepatan_angin ?? 'N/A',
                    'arah_angin' => $cuaca->arah_angin ?? 'N/A',
                    'curah_hujan' => $cuaca->curah_hujan ?? 0,
                ];
            }

            $cuacaPerHari[] = [
                'hari_ke' => $hariKe,
                'tanggal' => $tanggal,
                'data' => $dataCuaca,
            ];
            $hariKe++;
        }

        $lastSync = PrakiraanCuaca::where('lokasi_id', $lokasi->id)
            ->latest('created_at')
            ->first();

        return [
            'lokasi' => $lokasi,
            'cuacaPerHari' => $cuacaPerHari,
            'totalData' => PrakiraanCuaca::where('lokasi_id', $lokasi->id)->count(),
            'lastSync' => $lastSync ? Carbon::parse($lastSync->created_at) : null,
        ];
    }

    public function sinkronisasi(): array
    {
        Log::info('Mulai sinkronisasi BMKG...');

        // Fetch dari API
        $dataApi = $this->fetchDariApi();

        // Simpan lokasi (dari root)
        $lokasi = $this->simpanLokasi($dataApi['lokasi']);
        Log::info('Lokasi tersimpan:', ['id' => $lokasi->id]);

        // Simpan juga lokasi dari data[0] jika berbeda
        if (isset($dataApi['data'][0]['lokasi'])) {
            $this->simpanLokasi($dataApi['data'][0]['lokasi']);
        }

        // Simpan data cuaca
        $totalSaved = 0;
        if (isset($dataApi['data'][0]['cuaca'])) {
            Log::info('Jumlah hari cuaca:', ['hari' => count($dataApi['data'][0]['cuaca'])]);
            $totalSaved = $this->simpanCuaca($lokasi->id, $dataApi['data'][0]['cuaca']);
        } else {
            Log::warning('Data cuaca tidak ditemukan di API');
        }

        Log::info('Sinkronisasi selesai:', ['totalSaved' => $totalSaved]);

        // Kembalikan data dari database
        $dataDb = $this->getCuacaDariDb();
        $dataDb['totalSaved'] = $totalSaved;

        return $dataDb;
    }
}