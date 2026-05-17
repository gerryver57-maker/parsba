<div>
    <div class="container-fluid">

        {{-- HEADER DENGAN JAM DIGITAL --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4"
             x-data="{ jamDigital: '', tanggal: '' }" 
             x-init="setInterval(() => { 
                let now = new Date();
                let hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                let bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                jamDigital = now.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit', second:'2-digit'});
                tanggal = hari[now.getDay()] + ', ' + now.getDate() + ' ' + bulan[now.getMonth()] + ' ' + now.getFullYear();
            }, 1000)">
            <div>
                <h4 class="fw-bold mb-1">
                    <i class="ti ti-home me-2"></i>
                    Selamat Datang, {{ Auth::user()->nama }}!
                </h4>
                <p class="text-muted mb-0">
                    Pantau metrik utama pertanian padi di Nagari Bahagia Padang Gelugua
                </p>
            </div>

            <div class="d-flex align-items-center gap-3 mt-2 mt-sm-0">
                {{-- 🆕 JAM DIGITAL --}}
                <div style="background: rgba(46,125,50,0.08); padding: 8px 16px; border-radius: 10px; min-width: 160px; text-align: center;">
                    <div style="font-size: 1.5rem; font-weight: 300; font-family: 'Courier New', monospace; letter-spacing: 2px; color: #2e7d32;" x-text="jamDigital"></div>
                    <small class="text-muted" x-text="tanggal" style="font-size: 0.7rem;"></small>
                </div>

                <button wire:click="refreshData" class="btn btn-sm btn-outline-primary">
                    <i class="ti ti-refresh me-1"></i> Refresh
                </button>
            </div>
        </div>

        {{-- STATISTIK --}}
        <div class="row g-3 mb-4">
            @foreach([
                ['ti ti-users','success',$totalPetani,'Petani'],
                ['ti ti-map','primary',$totalLahan,'Lahan'],
                ['ti ti-plant','info',$totalSiklusAktif,'Siklus Aktif'],
                ['ti ti-basket','warning',$totalPanen,'Total Panen'],
                ['ti ti-weight','danger',$totalJumlahPanen,'Ton Padi'],
                ['ti ti-clock','secondary',$totalAktivitasPending,'Pending'],
            ] as $card)
            <div class="col-6 col-md-4 col-lg-2">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-{{ $card[1] }} bg-opacity-10 p-3 d-inline-flex mb-2">
                            <i class="{{ $card[0] }} fs-4 text-{{ $card[1] }}"></i>
                        </div>
                        <h3 class="fw-bold mb-0">{{ number_format($card[2]) }}</h3>
                        <small class="text-muted">{{ $card[3] }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row g-4">

            {{-- KIRI --}}
            <div class="col-lg-8">

                {{-- GRAFIK --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="ti ti-chart-bar me-1 text-success"></i>
                            Hasil Panen Tahun {{ now()->year }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="grafikDashboard" height="100"></canvas>
                    </div>
                </div>

                {{-- SIKLUS PANEN --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="ti ti-calendar-check me-1 text-warning"></i>
                            Siklus Mendekati Panen (14 Hari)
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if($siklusPanen->count())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Petani</th><th>Lahan</th><th>Varietas</th>
                                        <th>Tanam</th><th>Panen</th><th>Sisa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($siklusPanen as $siklus)
                                    @php
                                        $sisa = \Carbon\Carbon::now()->diffInDays($siklus->perkiraan_panen, false);
                                        $sisaBulat = (int) ceil($sisa);
                                    @endphp
                                    <tr>
                                        <td>{{ $siklus->petani->nama ?? '-' }}</td>
                                        <td>{{ $siklus->lahan->nama ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                {{ $siklus->varietasPadi->nama ?? '-' }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($siklus->tanggal_tanam)->format('d M Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($siklus->perkiraan_panen)->format('d M Y') }}</td>
                                        <td>
                                            @if($sisaBulat <= 0)<span class="badge bg-danger">Panen!</span>
                                            @elseif($sisaBulat <= 7)<span class="badge bg-warning">{{ $sisaBulat }} Hari</span>
                                            @else<span class="badge bg-info">{{ $sisaBulat }} Hari</span>@endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4 text-muted">
                            <i class="ti ti-calendar-off fs-1"></i>
                            <p class="mt-2">Tidak ada siklus mendekati panen.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- KANAN --}}
            <div class="col-lg-4">

                {{-- 🆕 CUACA DENGAN BACKGROUND WAKTU & ICON BMKG --}}
                <div class="card border-0 shadow-sm mb-4">
                    @if($cuacaSekarang)
                    @php
                        $jam = \Carbon\Carbon::parse($cuacaSekarang->waktu_lokal)->hour;
                        if ($jam >= 5 && $jam <= 10) { $bg1 = '#FF8008'; $bg2 = '#FFC837'; $waktuText = 'Pagi'; }
                        elseif ($jam >= 11 && $jam <= 15) { $bg1 = '#2196F3'; $bg2 = '#64B5F6'; $waktuText = 'Siang'; }
                        elseif ($jam >= 16 && $jam <= 18) { $bg1 = '#FF6B35'; $bg2 = '#F7C948'; $waktuText = 'Sore'; }
                        else { $bg1 = '#0F2027'; $bg2 = '#2C5364'; $waktuText = 'Malam'; }
                        $iconUrl = $cuacaSekarang->Gambar ?? null;
                        $adaHujan = $cuacaSekarang->curah_hujan > 0;
                        $hujanLebat = $cuacaSekarang->curah_hujan > 5;
                    @endphp
                    
                    {{-- CARD CUACA --}}
                    <div class="card-body text-center py-4 text-white" style="background: linear-gradient(135deg, {{ $bg1 }} 0%, {{ $bg2 }} 100%); border-radius: 0.5rem 0.5rem 0 0;">
                        <h6 class="text-white mb-2" style="opacity: 0.9;">Cuaca · {{ $waktuText }}</h6>
                        @if($iconUrl)
                            <img src="{{ $iconUrl }}" alt="icon" style="width:80px;height:80px;" class="mb-2">
                        @else
                            <i class="ti ti-cloud fs-1 text-white mb-2"></i>
                        @endif
                        <h1 class="text-white fw-bold display-5 mb-0">{{ $cuacaSekarang->suhu }}°C</h1>
                        <h5 class="text-white mb-1">{{ $cuacaSekarang->deskripsi_cuaca }}</h5>
                        <hr class="border-white my-3" style="opacity: 0.3;">
                        <div class="row text-white">
                            <div class="col-4"><small style="opacity:0.8;">Kelembapan</small><h6 class="mb-0 text-white">{{ $cuacaSekarang->kelembapan }}%</h6></div>
                            <div class="col-4"><small style="opacity:0.8;">Hujan</small><h6 class="mb-0 text-white">{{ $cuacaSekarang->curah_hujan }} mm</h6></div>
                            <div class="col-4"><small style="opacity:0.8;">Angin</small><h6 class="mb-0 text-white">{{ $cuacaSekarang->kecepatan_angin }} km/j</h6></div>
                        </div>
                    </div>
                    
                    {{-- INDIKATOR --}}
                    @if($adaHujan)
                    <div style="background: {{ $hujanLebat ? '#FFF3E0' : '#E3F2FD' }}; padding: 10px 15px; text-align:left; border-left:3px solid {{ $hujanLebat ? '#E65100' : '#1565C0' }};">
                        <strong style="color:{{ $hujanLebat ? '#E65100' : '#1565C0' }};font-size:0.8rem;">
                            {{ $hujanLebat ? '⚠️ WASPADA HUJAN LEBAT!' : '🌧️ AKAN TURUN HUJAN' }}
                        </strong>
                        <p style="color:{{ $hujanLebat ? '#BF360C' : '#0D47A1' }};font-size:0.7rem;margin:3px 0 0 0;">
                            {{ $hujanLebat ? 'Tunda aktivitas di lahan!' : 'Tunda pemupukan & penyemprotan.' }}
                        </p>
                    </div>
                    @else
                    <div style="background: #E8F5E9; padding: 10px 15px; text-align:left; border-left:3px solid #2E7D32;">
                        <strong style="color:#2E7D32;font-size:0.8rem;">☀️ KONDISI AMAN</strong>
                        <p style="color:#1B5E20;font-size:0.7rem;margin:3px 0 0 0;">Kondisi baik untuk pertanian.</p>
                    </div>
                    @endif
                    
                    <div class="text-center py-1" style="background:#f8f9fa;border-radius:0 0 0.5rem 0.5rem;">
                        <small style="opacity:0.5;font-size:0.6rem;"><i class="ti ti-info-circle me-1"></i>BMKG</small>
                    </div>
                    @else
                    <div class="card-body text-center py-4">
                        <i class="ti ti-cloud-off fs-1 text-muted"></i>
                        <p class="mt-2 mb-3">Data cuaca belum tersedia</p>
                        <a wire:navigate href="{{ url('admin/cuaca') }}" class="btn btn-sm btn-primary">
                            <i class="ti ti-cloud-upload me-1"></i> Sinkronisasi
                        </a>
                    </div>
                    @endif
                </div>

                {{-- AKTIVITAS --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ti ti-activity me-1 text-success"></i>
                            Aktivitas Terbaru
                        </h5>
                        <a wire:navigate href="{{ url('admin/laporanaktivitas/index') }}" class="btn btn-sm btn-outline-success">Semua</a>
                    </div>
                    <div class="card-body p-0">
                        @if($aktivitasTerbaru->count())
                        <div class="list-group list-group-flush">
                            @foreach($aktivitasTerbaru as $aktivitas)
                            @php
                                $icon = 'ti ti-check'; $color = 'primary';
                                if ($aktivitas->pupuk_id) { $icon = 'ti ti-droplet'; $color = 'success'; }
                                elseif ($aktivitas->pestisida_id) { $icon = 'ti ti-shield'; $color = 'warning'; }
                            @endphp
                            <div class="list-group-item border-0 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-{{ $color }} bg-opacity-10 p-2">
                                        <i class="{{ $icon }} text-{{ $color }}"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <strong>{{ $aktivitas->nama_fase }}</strong>
                                        <div class="small text-muted">
                                            {{ $aktivitas->siklusTanam->petani->nama ?? '-' }} | 
                                            {{ $aktivitas->siklusTanam->lahan->nama ?? '-' }}
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($aktivitas->tanggal_konfirmasi)->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-4 text-muted">
                            <i class="ti ti-activity-off fs-1"></i>
                            <p class="mt-2">Belum ada aktivitas.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@script
<script>
    let chartDashboard = null;

    function renderDashboardChart() {
        const canvas = document.getElementById('grafikDashboard');
        if (!canvas) return;

        if (chartDashboard) { chartDashboard.destroy(); chartDashboard = null; }

        chartDashboard = new Chart(canvas, {
            type: 'bar',
            data: {
                labels: @json($grafikLabels),
                datasets: [{ label: 'Hasil Panen (Ton)', data: @json($grafikData), backgroundColor: 'rgba(46,125,50,0.7)', borderColor: '#2e7d32', borderWidth: 2, borderRadius: 5 }]
            },
            options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
        });
    }

    if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', renderDashboardChart); }
    else { renderDashboardChart(); }
</script>
@endscript