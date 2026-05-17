<div>
    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                PARSBA
            </a>
            <button class="navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="Toggle navigation">

                <span class="navbar-toggler-icon"></span>

            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#carakerja">Cara Kerja</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                    <li class="nav-item ms-3">
                        <a href="{{ url('loginsisfoparsba') }}" class="nav-link btn-nav">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

{{-- HERO --}}
<section class="hero" id="beranda">
    <div class="hero-bg-slideshow">
        <div class="hero-bg-slide"></div>
    </div>

    <div class="container" style="position: relative; z-index: 1;">
        <div class="row align-items-center">

            {{-- KONTEN KIRI --}}
            <div class="col-lg-7" data-aos="fade-right">

                <h1>
                    PARSBA - Sistem Informasi
                    <span>Manajemen Pertanian Padi</span>
                    Terintegrasi API Cuaca BMKG
                </h1>

                <p>
                    Kelola pertanian padi Anda dengan mudah dan efisien.
                    Pantau siklus tanam, jadwal pemupukan, cuaca real-time dari BMKG,
                    dan hasil panen dalam satu platform.
                </p>

                {{-- JAM DIGITAL --}}
                <div class="clock-container"
                     x-data="{
                        jam: '00',
                        menit: '00',
                        detik: '00',
                        tanggal: '',
                        hari: ''
                     }"

                     x-init="
                        setInterval(() => {
                            let now = new Date();

                            jam = String(now.getHours()).padStart(2, '0');
                            menit = String(now.getMinutes()).padStart(2, '0');
                            detik = String(now.getSeconds()).padStart(2, '0');

                            let h = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

                            let b = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

                            hari = h[now.getDay()];

                            tanggal = now.getDate() + ' ' +
                                      b[now.getMonth()] + ' ' +
                                      now.getFullYear();

                        }, 1000)
                     ">

                    <div class="digital-clock">
                        <div class="clock-segment">
                            <span x-text="jam"></span>
                        </div>

                        <span class="clock-colon">:</span>

                        <div class="clock-segment">
                            <span x-text="menit"></span>
                        </div>

                        <span class="clock-colon">:</span>

                        <div class="clock-segment">
                            <span x-text="detik"></span>
                        </div>
                    </div>

                    <div class="clock-date">
                        <span x-text="hari"></span>,
                        <span x-text="tanggal"></span>
                    </div>
                </div>

                {{-- BUTTON --}}
                <div class="d-flex gap-3 flex-wrap mt-4">

                    <a href="{{ url('loginsisfoparsba') }}"
                       class="btn btn-hero">

                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Mulai Sekarang
                    </a>

                    <a href="#fitur"
                       class="btn btn-outline-hero">

                        <i class="bi bi-info-circle me-2"></i>
                        Pelajari Lebih
                    </a>
                </div>

                {{-- LOKASI --}}
                <div class="d-flex gap-4 mt-4 text-white"
                     style="opacity:0.8;">

                    <div>
                        📍 Nagari Bahagia Padang Gelugua
                    </div>

                    <div>
                        🏛️ Kabupaten Pasaman, Sumatera Barat
                    </div>
                </div>

            </div>

            {{-- WEATHER CARD --}}
            <div class="col-lg-5" data-aos="fade-left">

                @php

                    $cuaca = \App\Models\PrakiraanCuaca::whereHas(
                        'lokasi',
                        fn($q) => $q->where('kode_desa', '13.08.17.2004')
                    )
                    ->where('waktu_lokal', '<=', now()->addHour())
                    ->orderBy('waktu_lokal', 'desc')
                    ->first();

                    if (!$cuaca) {

                        $cuaca = \App\Models\PrakiraanCuaca::whereHas(
                            'lokasi',
                            fn($q) => $q->where('kode_desa', '13.08.17.2004')
                        )
                        ->orderBy('waktu_lokal', 'asc')
                        ->first();
                    }

                    $bg1 = 'rgba(15,32,39,0.3)';
                    $bg2 = 'rgba(44,83,100,0.3)';
                    $waktuText = 'Malam';

                    $iconUrl = null;
                    $adaHujan = false;

                    if ($cuaca) {

                        $jam =
                            \Carbon\Carbon::parse(
                                $cuaca->waktu_lokal
                            )->hour;

                        if ($jam >= 5 && $jam <= 10) {

                            $bg1 = 'rgba(255,128,8,0.25)';
                            $bg2 = 'rgba(255,200,55,0.25)';
                            $waktuText = 'Pagi';

                        } elseif ($jam >= 11 && $jam <= 15) {

                            $bg1 = 'rgba(33,150,243,0.25)';
                            $bg2 = 'rgba(100,181,246,0.25)';
                            $waktuText = 'Siang';

                        } elseif ($jam >= 16 && $jam <= 18) {

                            $bg1 = 'rgba(255,107,53,0.25)';
                            $bg2 = 'rgba(247,201,72,0.25)';
                            $waktuText = 'Sore';
                        }

                        $iconUrl = $cuaca->Gambar ?? null;

                        $adaHujan =
                            $cuaca->curah_hujan > 0;
                    }

                @endphp

                @if($cuaca)

                    <div class="weather-card-transparent mx-auto"
                         style="max-width: 320px;">

                        <div style="
                            background: linear-gradient(
                                135deg,
                                {{ $bg1 }} 0%,
                                {{ $bg2 }} 100%
                            );

                            backdrop-filter: blur(15px);
                            -webkit-backdrop-filter: blur(15px);

                            border: 1px solid rgba(255,255,255,0.2);

                            border-radius: 20px;

                            padding: 30px 25px;

                            text-align: center;

                            color: white;

                            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
                        ">

                            <h2 class="weather-location">
                                <i class="bi bi-geo-alt me-1"></i>

                                {{ $cuaca->lokasi->desa ?? 'Nagari Bahagia' }}
                            </h2>

                            @if($iconUrl)

                               <img
                                    src="{{ $iconUrl }}"
                                    alt="Icon Cuaca"
                                    width="90"
                                    height="90"

                                    loading="lazy"
                                    decoding="async"

                                    onerror="
                                        this.onerror=null;
                                        this.src='{{ asset('dashboard/images/padi/cuaca.webp') }}';
                                    "

                                    style="
                                        width:90px;
                                        height:90px;
                                        filter:drop-shadow(0 4px 8px rgba(0,0,0,0.3));
                                    "
                                >

                            @else

                                <i class="bi bi-cloud-sun"
                                   style="
                                        font-size:4rem;
                                        opacity:0.9;
                                   ">
                                </i>

                            @endif

                            <h1 style="
                                font-weight:200;
                                font-size:4.5rem;
                                margin:10px 0;
                                color:white;
                                text-shadow:
                                    2px 2px 10px rgba(0,0,0,0.3);
                            ">

                                {{ $cuaca->suhu }}
                                <span style="font-size:2rem;">°C</span>

                            </h1>

                            <p class="weather-status">
                                {{ $cuaca->deskripsi_cuaca }}
                            </p>

                            <small style="opacity:0.8;">

                                {{ $waktuText }}

                                ·

                                {{ \Carbon\Carbon::parse($cuaca->waktu_lokal)->format('H:i') }}

                                WIB

                            </small>

                            <hr style="
                                opacity:0.2;
                                border-color:white;
                                margin:15px 0;
                            ">

                            <div style="
                                display:flex;
                                justify-content:space-around;
                                font-size:0.85rem;
                            ">

                                <div>
                                    <i class="bi bi-droplet d-block mb-1"></i>

                                    {{ $cuaca->kelembapan }}%

                                    <small style="
                                        display:block;
                                        opacity:0.6;
                                        font-size:0.65rem;
                                    ">
                                        Kelembapan
                                    </small>
                                </div>

                                <div>
                                    <i class="bi bi-cloud-rain d-block mb-1"></i>

                                    {{ $cuaca->curah_hujan }} mm

                                    <small style="
                                        display:block;
                                        opacity:0.6;
                                        font-size:0.65rem;
                                    ">
                                        Curah Hujan
                                    </small>
                                </div>

                                <div>
                                    <i class="bi bi-wind d-block mb-1"></i>

                                    {{ $cuaca->kecepatan_angin }} km/j

                                    <small style="
                                        display:block;
                                        opacity:0.6;
                                        font-size:0.65rem;
                                    ">
                                        Angin
                                    </small>
                                </div>

                            </div>

                        </div>

                        {{-- STATUS --}}
                        <div style="
                            background:
                            {{ $adaHujan
                                ? 'rgba(255,152,0,0.2)'
                                : 'rgba(76,175,80,0.2)' }};

                            backdrop-filter: blur(10px);

                            border: 1px solid rgba(255,255,255,0.15);

                            border-radius: 0 0 15px 15px;

                            padding: 10px;

                            text-align: center;

                            margin-top: -5px;
                        ">

                            <small style="
                                color:white;
                                font-weight:500;
                            ">

                                @if($adaHujan)

                                    ⚠️ Waspada Hujan ·
                                    Tunda Pemupukan & Penyemprotan

                                @else

                                    ☀️ Kondisi Aman ·
                                    Baik untuk Aktivitas Pertanian

                                @endif

                            </small>

                        </div>

                    </div>

                @else

                    <div class="text-center text-white">

                        <i class="bi bi-cloud-slash"
                           style="
                                font-size:4rem;
                                opacity:0.5;
                           ">
                        </i>

                        <p style="opacity:0.7;">
                            Data cuaca belum tersedia
                        </p>

                        <small style="opacity:0.5;">
                            Sinkronisasi dengan BMKG diperlukan
                        </small>

                    </div>

                @endif

            </div>

        </div>
    </div>
</section>

    {{-- FITUR --}}
    <section class="section" id="fitur">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Fitur Unggulan</h2>
                <p>Semua yang Anda butuhkan untuk manajemen pertanian padi modern</p>
            </div>
            <div class="row g-4">
                @php
                    $fiturs = [
                        ['icon'=>'bi-calendar-check','title'=>'Manajemen Siklus Tanam','desc'=>'Catat dan pantau siklus tanam dari awal hingga panen. Sistem otomatis menghitung perkiraan panen.'],
                        ['icon'=>'bi-cloud-rain','title'=>'Prakiraan Cuaca BMKG','desc'=>'Data cuaca real-time dari API BMKG. Dapatkan rekomendasi kapan harus memupuk dan menyemprot.'],
                        ['icon'=>'bi-calendar-week','title'=>'Kalender Manajemen','desc'=>'Lihat semua aktivitas dalam kalender interaktif. Konfirmasi kegiatan langsung dari kalender.'],
                        ['icon'=>'bi-droplet','title'=>'Jadwal Pemupukan Otomatis','desc'=>'Jadwal otomatis berdasarkan fase tumbuh padi. Dosis dihitung sesuai luas lahan Anda.'],
                        ['icon'=>'bi-shield-check','title'=>'Info Hama & Penyakit','desc'=>'Database hama dan penyakit lengkap dengan gejala dan rekomendasi pengendalian.'],
                        ['icon'=>'bi-graph-up','title'=>'Laporan & Analitik','desc'=>'Laporan hasil panen lengkap dengan grafik. Download PDF untuk arsip Anda.'],
                    ];
                @endphp
                @foreach($fiturs as $i => $f)
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ ($i+1)*100 }}">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi {{ $f['icon'] }}"></i></div>
                        <h3>{{ $f['title'] }}</h3>
                        <p>{{ $f['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- STATS --}}
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4 stat-item" data-aos="zoom-in">
                    <h3>🌱</h3>
                    <p>Manajemen Siklus Tanam</p>
                    <small>Dari tanam hingga panen</small>
                </div>
                <div class="col-md-4 stat-item" data-aos="zoom-in" data-aos-delay="200">
                    <h3>🌤️</h3>
                    <p>Integrasi API BMKG</p>
                    <small>Data cuaca real-time</small>
                </div>
                <div class="col-md-4 stat-item" data-aos="zoom-in" data-aos-delay="400">
                    <h3>📱</h3>
                    <p>Akses Web & Mobile</p>
                    <small>Pantau kapan saja</small>
                </div>
            </div>
        </div>
    </section>

    {{-- CARA KERJA --}}
    <section class="section" id="carakerja">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Cara Kerja</h2>
                <p>Mudah digunakan oleh petani dan penyuluh</p>
            </div>
            <div class="row g-4">
                @foreach(['Daftar & Login','Input Lahan & Tanam','Ikuti Jadwal','Panen & Laporan'] as $i => $step)
                <div class="col-md-3 step-card" data-aos="fade-up" data-aos-delay="{{ ($i+1)*100 }}">
                    <div class="step-number">{{ $i+1 }}</div>
                    <h3>{{ $step }}</h3>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="container" data-aos="zoom-in">
        <div class="cta-section">
            <h2>Siap Mengelola Pertanian Padi dengan Lebih Baik?</h2>
            <p class="text-muted mb-4">Bergabunglah dengan petani di Nagari Bahagia Padang Gelugua</p>
            <a href="{{ url('loginsisfoparsba') }}" class="btn btn-hero btn-lg">
                <i class="bi bi-box-arrow-in-right me-2"></i>Mulai Sekarang
            </a>
        </div>
    </section>

    {{-- FOOTER --}}
        <footer class="footer" id="tentang">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="footer-title">PARSBA</p>
                        <p class="footer-desc">Sistem Informasi Manajemen Pertanian Padi<br>
                        Nagari Bahagia Padang Gelugua, Kec. Padang Gelugur, Kab. Pasaman, Sumatera Barat</p>
                    </div>
                    <div class="col-md-6 text-md-end mt-3 mt-md-0">
                        <p class="footer-copy">
                        	© {{ date('Y') }} PARSBA. All rights reserved.
                        </p>
                        
                        {{--  TANDA TANGAN AAS --}}
                        <div class="signature-aas">
                            <svg width="80" height="50" viewBox="0 0 120 60">
                                <!-- A -->
                                <text x="5" y="45" font-family="'Poppins', sans-serif" font-size="28" font-weight="700" fill="rgba(255,255,255,0.6)" letter-spacing="2">
                                    <animate attributeName="fill" values="rgba(255,255,255,0.4);rgba(255,215,0,0.8);rgba(255,255,255,0.4)" dur="3s" repeatCount="indefinite"/>
                                    A
                                </text>
                                <!-- A -->
                                <text x="42" y="45" font-family="'Poppins', sans-serif" font-size="28" font-weight="700" fill="rgba(255,255,255,0.6)" letter-spacing="2">
                                    <animate attributeName="fill" values="rgba(255,255,255,0.6);rgba(255,215,0,0.8);rgba(255,255,255,0.6)" dur="3s" begin="0.5s" repeatCount="indefinite"/>
                                    A
                                </text>
                                <!-- S -->
                                <text x="79" y="45" font-family="'Poppins', sans-serif" font-size="28" font-weight="700" fill="rgba(255,255,255,0.6)" letter-spacing="2">
                                    <animate attributeName="fill" values="rgba(255,255,255,0.6);rgba(255,215,0,0.8);rgba(255,255,255,0.6)" dur="3s" begin="1s" repeatCount="indefinite"/>
                                    S
                                </text>
                                
                                <!-- Garis bawah -->
                                <line x1="5" y1="50" x2="115" y2="50" stroke="rgba(255,215,0,0.4)" stroke-width="1.5" stroke-dasharray="4,4">
                                    <animate attributeName="stroke" values="rgba(255,215,0,0.2);rgba(255,215,0,0.8);rgba(255,215,0,0.2)" dur="3s" repeatCount="indefinite"/>
                                </line>
                                
                                <!-- Titik glow -->
                                <circle cx="20" cy="48" r="2" fill="#FFD700" opacity="0.6">
                                    <animate attributeName="opacity" values="0.2;0.8;0.2" dur="2s" repeatCount="indefinite"/>
                                    <animate attributeName="r" values="1;3;1" dur="2s" repeatCount="indefinite"/>
                                </circle>
                                <circle cx="60" cy="48" r="2" fill="#FFD700" opacity="0.6">
                                    <animate attributeName="opacity" values="0.2;0.8;0.2" dur="2s" begin="0.7s" repeatCount="indefinite"/>
                                    <animate attributeName="r" values="1;3;1" dur="2s" begin="0.7s" repeatCount="indefinite"/>
                                </circle>
                                <circle cx="100" cy="48" r="2" fill="#FFD700" opacity="0.6">
                                    <animate attributeName="opacity" values="0.2;0.8;0.2" dur="2s" begin="1.4s" repeatCount="indefinite"/>
                                    <animate attributeName="r" values="1;3;1" dur="2s" begin="1.4s" repeatCount="indefinite"/>
                                </circle>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
</div>
