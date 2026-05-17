@php
    use Carbon\Carbon;
@endphp

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
                <p class="text-muted mb-0">Pantau lahan dan aktivitas pertanian Anda</p>
            </div>
            <div class="text-end" style="background: rgba(46,125,50,0.08); padding: 10px 20px; border-radius: 12px; min-width: 180px;">
                <div style="font-size: 1.8rem; font-weight: 300; font-family: 'Courier New', monospace; letter-spacing: 2px; color: #2e7d32;" x-text="jamDigital"></div>
                <small class="text-muted" x-text="tanggal" style="font-size: 0.75rem;"></small>
            </div>
        </div>

        {{-- 🆕 PANDUAN PENGGUNAAN 3 BAHASA --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#panduan-id">
                            🇮🇩 Indonesia
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#panduan-bt">
                            🏔️ Batak Angkola
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#panduan-mn">
                            🏡 Minang
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body tab-content">
                
                {{-- BAHASA INDONESIA --}}
                <div class="tab-pane fade show active" id="panduan-id">
                    <h5 class="fw-bold text-success mb-3">📖 Panduan Penggunaan Aplikasi</h5>
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <div class="card border-success h-100">
                                <div class="card-body text-center p-3">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-flex mb-2">
                                        <span class="fs-4">1️⃣</span>
                                    </div>
                                    <h6 class="fw-bold">Lahan Saya</h6>
                                    <small class="text-muted">Daftarkan lahan sawah Anda terlebih dahulu. Isi nama, luas, dan jenis irigasi.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card border-success h-100">
                                <div class="card-body text-center p-3">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-flex mb-2">
                                        <span class="fs-4">2️⃣</span>
                                    </div>
                                    <h6 class="fw-bold">Siklus Tanam</h6>
                                    <small class="text-muted">Buat siklus tanam baru. Pilih lahan, varietas, dan tanggal tanam.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card border-success h-100">
                                <div class="card-body text-center p-3">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-flex mb-2">
                                        <span class="fs-4">3️⃣</span>
                                    </div>
                                    <h6 class="fw-bold">Jadwal Kegiatan</h6>
                                    <small class="text-muted">Ikuti jadwal pemupukan & penyemprotan. Konfirmasi setelah dilakukan.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card border-success h-100">
                                <div class="card-body text-center p-3">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-flex mb-2">
                                        <span class="fs-4">4️⃣</span>
                                    </div>
                                    <h6 class="fw-bold">Panen & Laporan</h6>
                                    <small class="text-muted">Catat hasil panen. Lihat grafik dan unduh laporan.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BAHASA BATAK ANGKOLA --}}
                <div class="tab-pane fade" id="panduan-bt">
                    <h5 class="fw-bold text-success mb-3">📖 Panduan Pamake Aplikasi</h5>
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <div class="card border-success h-100">
                                <div class="card-body text-center p-3">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-flex mb-2">
                                        <span class="fs-4">1️⃣</span>
                                    </div>
                                    <h6 class="fw-bold">Hauma Au</h6>
                                    <small class="text-muted">Daftarhon jolo hauma (sawah) mu. Isi goar, bidang, dohot jenis irigasi na.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card border-success h-100">
                                <div class="card-body text-center p-3">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-flex mb-2">
                                        <span class="fs-4">2️⃣</span>
                                    </div>
                                    <h6 class="fw-bold">Siklus Tanam</h6>
                                    <small class="text-muted">Bahen siklus tanam naimbaru. Pilih hauma, boni (varietas), dohot tanggal tanam.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card border-success h-100">
                                <div class="card-body text-center p-3">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-flex mb-2">
                                        <span class="fs-4">3️⃣</span>
                                    </div>
                                    <h6 class="fw-bold">Jadwal Karejo</h6>
                                    <small class="text-muted">Ihutkon jadwal pamupukon dohot panyemprotan. Konfirmasi anggo dung siap.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card border-success h-100">
                                <div class="card-body text-center p-3">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-flex mb-2">
                                        <span class="fs-4">4️⃣</span>
                                    </div>
                                    <h6 class="fw-bold">Gotil & Laporan</h6>
                                    <small class="text-muted">Catat hasil gotil (panen). Ligani grafik dohot unduh laporan.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BAHASA MINANG --}}
                <div class="tab-pane fade" id="panduan-mn">
                    <h5 class="fw-bold text-success mb-3">📖 Panduan Panggunoan Aplikasi</h5>
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <div class="card border-success h-100">
                                <div class="card-body text-center p-3">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-flex mb-2">
                                        <span class="fs-4">1️⃣</span>
                                    </div>
                                    <h6 class="fw-bold">Lahak Denai</h6>
                                    <small class="text-muted">Daftaran lahakan sawah sanak dulu. Isi namo, laweh, jo jenis irigasi.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card border-success h-100">
                                <div class="card-body text-center p-3">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-flex mb-2">
                                        <span class="fs-4">2️⃣</span>
                                    </div>
                                    <h6 class="fw-bold">Siklus Tanam</h6>
                                    <small class="text-muted">Buek siklus tanam baru. Piliah lahak, bibit (varietas), jo tanggal tanam.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card border-success h-100">
                                <div class="card-body text-center p-3">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-flex mb-2">
                                        <span class="fs-4">3️⃣</span>
                                    </div>
                                    <h6 class="fw-bold">Jadwal Karajo</h6>
                                    <small class="text-muted">Ikuti jadwal maagiah pupuk jo manyemprot. Konfirmasi kalau alah dilakukan.</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="card border-success h-100">
                                <div class="card-body text-center p-3">
                                    <div class="rounded-circle bg-success bg-opacity-10 p-3 d-inline-flex mb-2">
                                        <span class="fs-4">4️⃣</span>
                                    </div>
                                    <h6 class="fw-bold">Panen & Laporan</h6>
                                    <small class="text-muted">Catat hasil panen. Caliak grafik jo unduh laporan.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- STATISTIK --}}
        <div class="row g-3 mb-4">
            @php
                $cards = [
                    ['icon'=>'ti-map','color'=>'success','value'=>$totalLahan,'label'=>'Lahan'],
                    ['icon'=>'ti-plant','color'=>'info','value'=>$totalSiklusAktif,'label'=>'Siklus Aktif'],
                    ['icon'=>'ti-basket','color'=>'warning','value'=>$totalPanen,'label'=>'Total Panen'],
                    ['icon'=>'ti-weight','color'=>'danger','value'=>number_format((float)$totalJumlahPanen,1),'label'=>'Ton Padi'],
                    ['icon'=>'ti-clock','color'=>'secondary','value'=>$totalAktivitasPending,'label'=>'Pending'],
                ];
            @endphp

            @foreach($cards as $c)
            <div class="col-6 col-md-4 col-lg">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="rounded-circle bg-{{ $c['color'] }} bg-opacity-10 p-3 d-inline-flex mb-2">
                            <i class="ti {{ $c['icon'] }} fs-4 text-{{ $c['color'] }}"></i>
                        </div>
                        <h3 class="fw-bold mb-0">{{ is_numeric($c['value']) ? number_format($c['value']) : $c['value'] }}</h3>
                        <small class="text-muted">{{ $c['label'] }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="row g-4">
            {{-- KIRI --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="ti ti-chart-bar me-1 text-success"></i>Hasil Panen Tahun {{ Carbon::now()->year }}</h5>
                    </div>
                    <div class="card-body"><canvas id="grafikPetani" height="80"></canvas></div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="ti ti-calendar-check me-1 text-warning"></i>Siklus Mendekati Panen</h5>
                    </div>
                    <div class="card-body p-0">
                        @forelse($siklusPanen as $s)
                            @php
                                $sisa = Carbon::now()->diffInDays(Carbon::parse($s->perkiraan_panen), false);
                                $sisaBulat = (int) ceil($sisa);
                            @endphp
                        @if($loop->first)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light"><tr><th>Lahan</th><th>Varietas</th><th>Tanam</th><th>Perkiraan Panen</th><th>Sisa Hari</th></tr></thead>
                                <tbody>
                        @endif
                                    <tr>
                                        <td>{{ optional($s->lahan)->nama ?? '-' }}</td>
                                        <td><span class="badge bg-success bg-opacity-10 text-success">{{ optional($s->varietasPadi)->nama ?? '-' }}</span></td>
                                        <td>{{ Carbon::parse($s->tanggal_tanam)->format('d M Y') }}</td>
                                        <td>{{ Carbon::parse($s->perkiraan_panen)->format('d M Y') }}</td>
                                        <td>
                                            @if($sisaBulat <= 0)<span class="badge bg-danger">Panen!</span>
                                            @elseif($sisaBulat <= 7)<span class="badge bg-warning">{{ $sisaBulat }} Hari</span>
                                            @else<span class="badge bg-info">{{ $sisaBulat }} Hari</span>@endif
                                        </td>
                                    </tr>
                        @if($loop->last)
                                </tbody>
                            </table>
                        </div>
                        @endif
                        @empty
                        <div class="text-center py-4 text-muted">
                            <i class="ti ti-calendar-off fs-1"></i><p class="mt-2">Belum ada siklus tanam aktif.</p>
                            <a wire:navigate href="{{ url('petani/siklustanam/index') }}" class="btn btn-sm btn-success"><i class="ti ti-plus me-1"></i> Buat Siklus</a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- KANAN --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    @if($cuacaSekarang)
                    @php
                        $jam = Carbon::parse($cuacaSekarang->waktu_lokal)->hour;
                        if ($jam >= 5 && $jam <= 10) { $bg1 = '#FF8008'; $bg2 = '#FFC837'; $waktuText = 'Pagi'; }
                        elseif ($jam >= 11 && $jam <= 15) { $bg1 = '#2196F3'; $bg2 = '#64B5F6'; $waktuText = 'Siang'; }
                        elseif ($jam >= 16 && $jam <= 18) { $bg1 = '#FF6B35'; $bg2 = '#F7C948'; $waktuText = 'Sore'; }
                        else { $bg1 = '#0F2027'; $bg2 = '#2C5364'; $waktuText = 'Malam'; }
                        $iconUrl = $cuacaSekarang->Gambar ?? null;
                    @endphp
                    <div class="card-body text-center py-4 text-white" style="background: linear-gradient(135deg, {{ $bg1 }} 0%, {{ $bg2 }} 100%); border-radius: 0.5rem;">
                        <h6 class="text-white mb-2" style="opacity:0.9;">Cuaca · {{ $waktuText }}</h6>
                        @if($iconUrl)<img src="{{ $iconUrl }}" style="width:70px;height:70px;" class="mb-2">@endif
                        <h1 class="text-white fw-bold display-5 mb-0">{{ $cuacaSekarang->suhu }}°C</h1>
                        <h5 class="text-white mb-1">{{ $cuacaSekarang->deskripsi_cuaca }}</h5>
                        <hr class="border-white my-3" style="opacity:0.3;">
                        <div class="row text-white">
                            <div class="col-4"><small style="opacity:0.8;">Kelembapan</small><h6 class="mb-0 text-white">{{ $cuacaSekarang->kelembapan }}%</h6></div>
                            <div class="col-4"><small style="opacity:0.8;">Hujan</small><h6 class="mb-0 text-white">{{ $cuacaSekarang->curah_hujan }} mm</h6></div>
                            <div class="col-4"><small style="opacity:0.8;">Angin</small><h6 class="mb-0 text-white">{{ $cuacaSekarang->kecepatan_angin }} km/j</h6></div>
                        </div>
                        <div class="mt-3 small" style="opacity:0.6;font-size:0.65rem;"><i class="ti ti-info-circle me-1"></i>BMKG</div>
                    </div>
                    @else
                    <div class="card-body text-center py-4"><i class="ti ti-cloud-off fs-1 text-muted"></i><p class="mt-2">Data cuaca belum tersedia</p></div>
                    @endif
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between">
                        <h5 class="mb-0"><i class="ti ti-calendar-week me-1 text-success"></i>Jadwal Hari Ini</h5>
                        <a wire:navigate href="{{ url('petani/jadwal/index') }}" class="btn btn-sm btn-outline-success">Lihat Semua</a>
                    </div>
                    <div class="card-body p-0">
                        @forelse($jadwalHariIni as $jadwal)
                        @php
                            $icon = 'ti-check'; $color = 'primary';
                            if ($jadwal->pupuk_id) { $icon = 'ti-droplet'; $color = 'success'; }
                            elseif ($jadwal->pestisida_id) { $icon = 'ti-shield'; $color = 'warning'; }
                        @endphp
                        <div class="list-group-item border-0 py-3">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle bg-{{ $color }} bg-opacity-10 p-2"><i class="ti {{ $icon }} text-{{ $color }}"></i></div>
                                <div class="flex-grow-1"><strong>{{ $jadwal->nama_fase }}</strong><div class="small text-muted">{{ optional($jadwal->siklusTanam->lahan)->nama ?? '-' }}</div></div>
                                @if(!$jadwal->sudah_dikonfirmasi)
                                    <a wire:navigate href="{{ url('petani/jadwal/index') }}" class="btn btn-sm btn-success">Konfirmasi</a>
                                @else
                                    <span class="badge bg-success rounded-pill"><i class="ti ti-check"></i></span>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted"><i class="ti ti-calendar-off fs-1"></i><p class="mt-2">Tidak ada jadwal hari ini.</p></div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@script
<script>
    const ctx = document.getElementById('grafikPetani');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($grafikLabels ?? []),
                datasets: [{ label: 'Hasil Panen (Ton)', data: @json($grafikData ?? []), backgroundColor: 'rgba(46,125,50,0.7)', borderColor: '#2e7d32', borderWidth: 2, borderRadius: 5 }]
            },
            options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
        });
    }
</script>
@endscript