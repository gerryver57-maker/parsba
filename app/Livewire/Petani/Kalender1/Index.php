<?php

namespace App\Livewire\Petani\Kalender1;

use Livewire\Component;
use App\Models\SiklusTanam;
use App\Models\JadwalOtomatis;
use App\Models\PrakiraanCuaca;
use App\Models\Lokasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $events = [];
    public $showCuaca = true;

    public function mount()
    {
        $this->loadEvents();
    }

    public function loadEvents()
    {
        $this->events = [];
        $userId = Auth::id();

        // Cuaca
        if ($this->showCuaca) $this->loadCuacaEvents();

        // Siklus Tanam
        $siklusQuery = SiklusTanam::with(['lahan', 'varietasPadi'])->where('user_id', $userId);
        foreach ($siklusQuery->get() as $s) {
            $lahan = $s->lahan->nama ?? 'Lahan';
            $var = $s->varietasPadi->nama ?? 'Padi';

            $this->events[] = [
                'title' => '🌱 Tanam '.$var,
                'start' => Carbon::parse($s->tanggal_tanam)->format('Y-m-d'),
                'backgroundColor' => '#4caf50', 'borderColor' => '#388e3c', 'textColor' => '#fff',
                'imageUrl' => asset('dashboard/images/padi/tanampadi.png'),
                'extendedProps' => ['lahan'=>$lahan,'jenis'=>'Tanam','detail'=>"Tanam {$var} di {$lahan}"]
            ];

            if ($s->perkiraan_panen) {
                $this->events[] = [
                    'title' => '🌾 Panen '.$var,
                    'start' => Carbon::parse($s->perkiraan_panen)->format('Y-m-d'),
                    'backgroundColor' => '#ffc107', 'borderColor' => '#ffb300', 'textColor' => '#333',
                    'imageUrl' => asset('dashboard/images/padi/panenpadi.png'),
                    'extendedProps' => ['lahan'=>$lahan,'jenis'=>'Panen','detail'=>"Panen {$var} di {$lahan}"]
                ];
            }
        }

        // Jadwal
        $jadwalQuery = JadwalOtomatis::with(['siklusTanam.lahan','pupuk','pestisida'])
            ->whereHas('siklusTanam', fn($q) => $q->where('user_id', $userId));

        foreach ($jadwalQuery->get() as $j) {
            $lahan = $j->siklusTanam->lahan->nama ?? 'Lahan';
            $done = $j->sudah_dikonfirmasi;
            $imageUrl = null; $color = '#2196f3'; $jenis = 'Aktivitas'; $title = ''; $detail = '';

            if ($j->pupuk_id) {
                $imageUrl = asset('dashboard/images/padi/memupukpadi.png');
                $color = '#4CAF50'; $jenis = 'Pemupukan';
                $title = "Pupuk {$j->pupuk->nama}";
                $detail = "{$j->pupuk->nama} ({$j->dosis_dihitung} {$j->pupuk->satuan})";
            } elseif ($j->pestisida_id) {
                $imageUrl = asset('dashboard/images/padi/menyemprotpadi.png');
                $color = '#FF9800'; $jenis = 'Penyemprotan';
                $title = "Semprot {$j->pestisida->nama}";
                $detail = "{$j->pestisida->nama}";
            } else {
                $title = $j->nama_fase;
                $detail = $j->nama_fase;
            }

            $title = ($done ? '✅' : '⏳') . ' ' . $title;

            $this->events[] = [
                'id' => 'jadwal-'.$j->id,
                'title' => $title,
                'start' => Carbon::parse($j->tanggal_rekomendasi)->format('Y-m-d'),
                'backgroundColor' => $done ? '#9e9e9e' : $color,
                'borderColor' => $done ? '#757575' : $color,
                'textColor' => '#fff',
                'imageUrl' => $imageUrl,
                'extendedProps' => [
                    'jadwal_id' => $j->id,
                    'lahan' => $lahan,
                    'jenis' => $jenis,
                    'detail' => $detail,
                    'status' => $done ? 'Selesai' : 'Pending',
                    'isPending' => !$done,
                    'imageUrl' => $imageUrl,
                ]
            ];
        }
    }

private function loadCuacaEvents()
{
    $lokasi = Lokasi::where('kode_desa', '13.08.17.2004')->first();
    if (!$lokasi) return;

    // 🆕 Ambil data hanya 3 hari (hari ini, besok, lusa)
    $cuacaList = PrakiraanCuaca::where('lokasi_id', $lokasi->id)
        ->whereBetween('waktu_lokal', [
            Carbon::now()->startOfDay(),
            Carbon::now()->addDays(2)->endOfDay()  // 🆕 Hanya 3 hari
        ])
        ->orderBy('waktu_lokal', 'asc')
        ->get()
        ->groupBy(fn($item) => Carbon::parse($item->waktu_lokal)->format('Y-m-d'));

    foreach ($cuacaList as $tanggal => $items) {
        // Hari ini: data <= jam sekarang, hari lain: data pertama
        if ($tanggal === Carbon::now()->format('Y-m-d')) {
            $c = $items->where('waktu_lokal', '<=', Carbon::now())->last() ?? $items->first();
        } else {
            $c = $items->first();
        }
        
        $color = '#87ceeb'; $jenisCuaca = 'Berawan';
        $desc = $c->deskripsi_cuaca ?? '';

        if (stripos($desc, 'cerah') !== false) { $color = '#ff9800'; $jenisCuaca = 'Cerah'; }
        elseif (stripos($desc, 'hujan') !== false) { $color = '#42a5f5'; $jenisCuaca = 'Hujan'; }
        elseif (stripos($desc, 'berawan') !== false) { $color = '#90a4ae'; $jenisCuaca = 'Berawan'; }

        $this->events[] = [
            'id' => 'cuaca-' . $tanggal,
            'title' => "{$c->suhu}°C {$jenisCuaca}",
            'start' => $tanggal, 'allDay' => true,
            'backgroundColor' => $color, 'borderColor' => $color,
            'textColor' => $jenisCuaca === 'Cerah' ? '#333' : '#fff',
            'imageUrl' => $c->Gambar ?? null,
            'extendedProps' => [
                'jenis' => 'Cuaca: ' . $jenisCuaca,
                'suhu' => $c->suhu,
                'curah_hujan' => $c->curah_hujan,
                'kelembapan' => $c->kelembapan,
                'isCuaca' => true,
                'imageUrl' => $c->Gambar ?? null,
            ]
        ];
    }
}
    // 🆕 KONFIRMASI DENGAN PENGECEKAN URUTAN
    public function konfirmasiJadwal($id)
    {
        $jadwal = JadwalOtomatis::whereHas('siklusTanam', fn($q) => $q->where('user_id', Auth::id()))
            ->findOrFail($id);
        
        // Cek apakah ada jadwal sebelumnya yang belum dikonfirmasi
        $jadwalSebelumnya = JadwalOtomatis::where('siklus_tanam_id', $jadwal->siklus_tanam_id)
            ->where('tanggal_rekomendasi', '<', $jadwal->tanggal_rekomendasi)
            ->where('sudah_dikonfirmasi', false)
            ->orderBy('tanggal_rekomendasi', 'desc')
            ->first();
        
        if ($jadwalSebelumnya) {
            $this->dispatch('tampilPeringatanUrutan', [
                'nama' => $jadwalSebelumnya->nama_fase,
                'tanggal' => $jadwalSebelumnya->tanggal_rekomendasi->format('d M Y'),
            ]);
            return;
        }
        
        // Konfirmasi
        $jadwal->update(['sudah_dikonfirmasi' => true, 'tanggal_konfirmasi' => Carbon::now()]);
        
        $this->loadEvents();
        $this->dispatch('updateCalendarEvents', events: $this->events);
        $this->dispatch('tampilPesan', ['tipe' => 'success', 'judul' => 'Berhasil!', 'teks' => 'Aktivitas dikonfirmasi.']);
    }

    public function render()
    {
        return view('livewire.petani.kalender1.index')->layout('layouts.petani');
    }
}