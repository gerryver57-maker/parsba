<?php

namespace App\Livewire\Admin\Sinkronisasi;

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
    
    public $status = 'idle'; // idle, syncing, success, error
    public $lastSync = null;
    public $totalData = 0;
    public $logSinkronisasi = [];

    public function mount()
    {
        $this->loadStatus();
    }

    public function loadStatus()
    {
        $lokasi = Lokasi::where('kode_desa', $this->kodeDesa)->first();
        
        if ($lokasi) {
            $this->totalData = PrakiraanCuaca::where('lokasi_id', $lokasi->id)->count();
            
            $last = PrakiraanCuaca::where('lokasi_id', $lokasi->id)
                ->latest('created_at')
                ->first();
            $this->lastSync = $last ? Carbon::parse($last->created_at) : null;
        }

        // Load log dari file
        $this->loadLog();
    }

    public function loadLog()
    {
        $logFile = storage_path('logs/bmkg-sync.log');
        
        if (file_exists($logFile)) {
            $lines = file($logFile);
            $this->logSinkronisasi = array_slice(array_reverse($lines), 0, 20);
        } else {
            $this->logSinkronisasi = [];
        }
    }

    /**
     * Tulis log ke file
     */
    private function writeLog($message, $type = 'INFO')
    {
        $timestamp = Carbon::now()->format('Y-m-d H:i:s');
        $log = "[{$timestamp}] [{$type}] {$message}\n";
        
        file_put_contents(
            storage_path('logs/bmkg-sync.log'),
            $log,
            FILE_APPEND
        );
    }

    /**
     * Sinkronisasi manual
     */
    public function sinkronisasiSekarang()
    {
        $this->status = 'syncing';
        $this->writeLog('Memulai sinkronisasi manual...', 'INFO');

        try {
            // 1. Fetch API
            $this->writeLog('Fetch data dari API BMKG...', 'INFO');
            $response = Http::timeout(30)->get($this->apiUrl, [
                'adm4' => $this->kodeDesa,
            ]);

            if ($response->failed()) {
                throw new \Exception('Gagal fetch API. Status: ' . $response->status());
            }

            $data = $response->json();
            $this->writeLog('Data API berhasil diambil.', 'SUCCESS');

            // 2. Simpan Lokasi
            $this->writeLog('Menyimpan data lokasi...', 'INFO');
            $lokasi = Lokasi::updateOrCreate(
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
            $this->writeLog("Lokasi tersimpan: {$lokasi->desa}", 'SUCCESS');

            // 3. Simpan Cuaca
            $this->writeLog('Menyimpan data cuaca...', 'INFO');
            $totalSaved = 0;
            $totalDays = 0;
            $totalError = 0;

            foreach ($data['data'] as $periodeData) {
                if (!isset($periodeData['cuaca'])) continue;
                
                $totalDays = count($periodeData['cuaca']);
                
                foreach ($periodeData['cuaca'] as $hari) {
                    foreach ($hari as $item) {
                        if (!isset($item['local_datetime'])) continue;

                        try {
                            PrakiraanCuaca::updateOrCreate(
                                [
                                    'lokasi_id' => $lokasi->id,
                                    'waktu_lokal' => Carbon::parse($item['local_datetime']),
                                    'tanggal_analisis' => Carbon::parse($item['analysis_date'])->format('Y-m-d'),
                                ],
                                [
                                    'waktu_utc' => Carbon::parse($item['utc_datetime']),
                                    'suhu' => $item['t'] ?? null,
                                    'kelembapan' => $item['hu'] ?? null,
                                    'curah_hujan' => $item['tp'] ?? null,
                                    'deskripsi_cuaca' => $item['weather_desc'] ?? null,
                                    'arah_angin' => $item['wd'] ?? null,
                                    'kecepatan_angin' => $item['ws'] ?? null,
                                ]
                            );
                            $totalSaved++;
                        } catch (\Exception $e) {
                            $totalError++;
                            $this->writeLog("Error: {$e->getMessage()}", 'ERROR');
                            continue;
                        }
                    }
                }
            }

            $this->writeLog("Sinkronisasi selesai. {$totalSaved} data tersimpan, {$totalError} error.", 'SUCCESS');

            // 4. Reload status
            $this->loadStatus();
            $this->status = 'success';

            // 5. Notifikasi
            $this->dispatch('tampilPesan', [
                'tipe' => 'success',
                'judul' => 'Sinkronisasi Berhasil!',
                'teks' => "{$totalSaved} data cuaca ({$totalDays} hari) berhasil disimpan.",
            ]);

        } catch (\Exception $e) {
            $this->writeLog("Gagal: {$e->getMessage()}", 'ERROR');
            $this->status = 'error';
            $this->loadLog();

            $this->dispatch('tampilPesan', [
                'tipe' => 'error',
                'judul' => 'Sinkronisasi Gagal!',
                'teks' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Hapus log
     */
    public function hapusLog()
    {
        $logFile = storage_path('logs/bmkg-sync.log');
        if (file_exists($logFile)) {
            unlink($logFile);
        }
        $this->logSinkronisasi = [];
        
        $this->dispatch('tampilPesan', [
            'tipe' => 'success',
            'judul' => 'Log Terhapus!',
            'teks' => 'Riwayat log sinkronisasi berhasil dibersihkan.',
        ]);
    }

    public function render()
    {
        return view('livewire.admin.sinkronisasi.index')->layout('layouts.admin');
    }
}