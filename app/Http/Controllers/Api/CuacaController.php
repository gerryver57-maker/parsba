<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrakiraanCuaca;
use Carbon\Carbon;

class CuacaController extends Controller
{
    public function terkini()
    {
        $cuaca = PrakiraanCuaca::with('lokasi')
            ->whereHas('lokasi', fn($q) => $q->where('kode_desa', '13.08.17.2004'))
            ->where('waktu_lokal', '<=', Carbon::now()->addHour())
            ->orderBy('waktu_lokal', 'desc')
            ->first();

        if (!$cuaca) {
            $cuaca = PrakiraanCuaca::with('lokasi')
                ->whereHas('lokasi', fn($q) => $q->where('kode_desa', '13.08.17.2004'))
                ->orderBy('waktu_lokal', 'asc')
                ->first();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'suhu' => $cuaca->suhu ?? 0,
                'kelembapan' => $cuaca->kelembapan ?? 0,
                'curah_hujan' => $cuaca->curah_hujan ?? 0,
                'kecepatan_angin' => $cuaca->kecepatan_angin ?? 0,
                'deskripsi_cuaca' => $cuaca->deskripsi_cuaca ?? 'N/A',
                'icon_url' => $cuaca->Gambar ?? null,
                'waktu_lokal' => $cuaca->waktu_lokal ?? null,
                'lokasi' => [
                    'desa' => $cuaca->lokasi->desa ?? 'Nagari Bahagia',
                ],
            ],
        ]);
    }
}