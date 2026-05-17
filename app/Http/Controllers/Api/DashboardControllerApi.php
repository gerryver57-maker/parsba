<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lahan;
use App\Models\SiklusTanam;
use App\Models\Panen;
use App\Models\JadwalOtomatis;
use Illuminate\Http\Request;

class DashboardControllerApi extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        return response()->json([
            'success' => true,
            'totalLahan' => Lahan::where('user_id', $userId)->count(),
            'totalSiklusAktif' => SiklusTanam::where('user_id', $userId)->where('status', 'aktif')->count(),
            'totalPanen' => Panen::whereHas('siklusTanam', fn($q) => $q->where('user_id', $userId))->count(),
            'totalJumlahPanen' => Panen::whereHas('siklusTanam', fn($q) => $q->where('user_id', $userId))->sum('jumlah'),
            'totalAktivitasPending' => JadwalOtomatis::whereHas('siklusTanam', fn($q) => $q->where('user_id', $userId))->where('sudah_dikonfirmasi', false)->count(),
        ]);
    }
}