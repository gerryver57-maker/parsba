<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPADI - Sistem Informasi Manajemen Pertanian Padi</title>
    
    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    {{-- AOS Animation --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --green-dark: #1B5E20;
            --green: #2E7D32;
            --green-light: #4CAF50;
            --gold: #FFC107;
            --white: #ffffff;
        }
        
        * { font-family: 'Poppins', sans-serif; }
        
        body { overflow-x: hidden; }
        
        /* ========== NAVBAR ========== */
        .navbar {
            background: rgba(255,255,255,0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            padding: 10px 0;
        }
        .navbar-brand { font-weight: 700; font-size: 1.3rem; color: var(--green-dark) !important; }
        .navbar-brand img { width: 40px; margin-right: 10px; }
        .nav-link { font-weight: 500; color: #333 !important; margin: 0 5px; transition: 0.3s; }
        .nav-link:hover { color: var(--green) !important; }
        .btn-nav { background: var(--green); color: white !important; border-radius: 25px; padding: 8px 20px !important; }
        .btn-nav:hover { background: var(--green-dark); }
        
        /* ========== HERO ========== */
        .hero {
            background: linear-gradient(135deg, #E8F5E9 0%, #C8E6C9 50%, #A5D6A7 100%);
            padding: 120px 0 80px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 400px;
            height: 400px;
            background: rgba(46,125,50,0.1);
            border-radius: 50%;
        }
        .hero::after {
            content: '';
            position: absolute;
            bottom: -100px;
            left: -100px;
            width: 500px;
            height: 500px;
            background: rgba(255,193,7,0.05);
            border-radius: 50%;
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            color: var(--green-dark);
            line-height: 1.2;
        }
        .hero h1 span { color: var(--gold); }
        .hero p { font-size: 1.1rem; color: #555; margin: 20px 0; }
        .btn-hero {
            background: var(--green);
            color: white;
            border-radius: 30px;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 1rem;
            transition: 0.3s;
            border: none;
        }
        .btn-hero:hover { background: var(--green-dark); transform: translateY(-3px); box-shadow: 0 10px 30px rgba(46,125,50,0.3); }
        .btn-outline-hero {
            border: 2px solid var(--green);
            color: var(--green);
            border-radius: 30px;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 1rem;
            transition: 0.3s;
            background: transparent;
        }
        .btn-outline-hero:hover { background: var(--green); color: white; }
        .hero-img {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        
        /* ========== SECTION ========== */
        .section { padding: 80px 0; }
        .section-title {
            text-align: center;
            margin-bottom: 50px;
        }
        .section-title h2 {
            font-weight: 700;
            color: var(--green-dark);
            font-size: 2.2rem;
            margin-bottom: 10px;
        }
        .section-title p { color: #666; font-size: 1.05rem; }
        
        /* ========== FEATURES ========== */
        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 5px 30px rgba(0,0,0,0.08);
            transition: 0.3s;
            height: 100%;
            border: none;
        }
        .feature-card:hover { transform: translateY(-10px); box-shadow: 0 15px 40px rgba(0,0,0,0.12); }
        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #E8F5E9, #C8E6C9);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
            color: var(--green);
        }
        .feature-card h5 { font-weight: 600; color: #333; margin-bottom: 10px; }
        .feature-card p { color: #666; font-size: 0.9rem; }
        
        /* ========== STATS ========== */
        .stats-section {
            background: linear-gradient(135deg, var(--green-dark), var(--green));
            color: white;
            padding: 60px 0;
        }
        .stat-item { text-align: center; padding: 20px; }
        .stat-item h3 { font-size: 2.5rem; font-weight: 700; }
        .stat-item p { opacity: 0.9; font-size: 1rem; }
        
        /* ========== HOW IT WORKS ========== */
        .step-card {
            text-align: center;
            padding: 30px;
            position: relative;
        }
        .step-number {
            width: 50px;
            height: 50px;
            background: var(--green);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.3rem;
            margin: 0 auto 20px;
        }
        .step-card h5 { font-weight: 600; }
        .step-arrow { position: absolute; top: 40px; right: -20px; color: var(--green); font-size: 2rem; }
        
        /* ========== TESTIMONI ========== */
        .testimoni-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.08);
            text-align: center;
        }
        .testimoni-card img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 3px solid var(--green-light);
        }
        .stars { color: var(--gold); margin-bottom: 10px; }
        
        /* ========== CTA ========== */
        .cta-section {
            background: linear-gradient(135deg, #E8F5E9, #C8E6C9);
            border-radius: 20px;
            padding: 50px;
            text-align: center;
            margin: 80px 0;
        }
        .cta-section h2 { font-weight: 700; color: var(--green-dark); }
        
        /* ========== FOOTER ========== */
        .footer {
            background: var(--green-dark);
            color: white;
            padding: 30px 0;
        }
        .footer a { color: rgba(255,255,255,0.7); text-decoration: none; }
        .footer a:hover { color: white; }
    </style>
</head>
<body>

    {{-- ========== NAVBAR ========== --}}
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('dashboard/images/logos/logo.png') }}" alt="SIPADI"> SIPADI
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#fitur">Fitur</a></li>
                    <li class="nav-item"><a class="nav-link" href="#carakerja">Cara Kerja</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tentang">Tentang</a></li>
                    <li class="nav-item ms-3">
                        <a href="{{ url('login') }}" class="nav-link btn-nav">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- ========== HERO ========== --}}
    <section class="hero" id="beranda">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6" data-aos="fade-right">
                    <h1>Sistem Informasi <span>Manajemen Pertanian Padi</span> Terintegrasi</h1>
                    <p>
                        Kelola pertanian padi Anda dengan mudah dan efisien. 
                        Pantau siklus tanam, jadwal pemupukan, cuaca real-time, 
                        dan hasil panen dalam satu platform.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ url('login') }}" class="btn btn-hero">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Mulai Sekarang
                        </a>
                        <a href="#fitur" class="btn btn-outline-hero">
                            <i class="bi bi-info-circle me-2"></i>Pelajari Lebih
                        </a>
                    </div>
                    <div class="d-flex gap-4 mt-4">
                        <div><strong style="color:var(--green-dark);">📍</strong> Nagari Bahagia Padang Gelugua</div>
                        <div><strong style="color:var(--green-dark);">🏛️</strong> Kabupaten Pasaman</div>
                    </div>
                </div>
                <div class="col-lg-6 text-center" data-aos="fade-left">
                    <img src="{{ asset('dashboard/images/logos/logo.png') }}" alt="SIPADI" class="hero-img" style="max-width:350px;">
                </div>
            </div>
        </div>
    </section>

    {{-- ========== FITUR ========== --}}
    <section class="section" id="fitur">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Fitur Unggulan</h2>
                <p>Semua yang Anda butuhkan untuk manajemen pertanian padi modern</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-calendar-check"></i></div>
                        <h5>Manajemen Siklus Tanam</h5>
                        <p>Catat dan pantau siklus tanam dari awal hingga panen. Sistem otomatis menghitung perkiraan panen.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-cloud-rain"></i></div>
                        <h5>Prakiraan Cuaca BMKG</h5>
                        <p>Data cuaca real-time dari BMKG. Dapatkan rekomendasi kapan harus memupuk dan menyemprot.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-calendar-week"></i></div>
                        <h5>Kalender Manajemen</h5>
                        <p>Lihat semua aktivitas dalam kalender interaktif. Konfirmasi kegiatan langsung dari kalender.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-droplet"></i></div>
                        <h5>Jadwal Pemupukan</h5>
                        <p>Jadwal otomatis berdasarkan fase tumbuh padi. Dosis dihitung sesuai luas lahan Anda.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
                        <h5>Info Hama & Penyakit</h5>
                        <p>Database hama dan penyakit lengkap dengan gejala dan rekomendasi pengendalian.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-graph-up"></i></div>
                        <h5>Laporan & Analitik</h5>
                        <p>Laporan hasil panen lengkap dengan grafik. Download PDF untuk arsip Anda.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== STATS ========== --}}
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

    {{-- ========== CARA KERJA ========== --}}
    <section class="section" id="carakerja">
        <div class="container">
            <div class="section-title" data-aos="fade-up">
                <h2>Cara Kerja</h2>
                <p>Mudah digunakan oleh petani dan penyuluh</p>
            </div>
            <div class="row g-4">
                <div class="col-md-3 step-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-number">1</div>
                    <h5>Daftar & Login</h5>
                    <p>Buat akun petani dengan NIK, verifikasi oleh admin.</p>
                    <i class="bi bi-arrow-right step-arrow d-none d-md-block"></i>
                </div>
                <div class="col-md-3 step-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-number">2</div>
                    <h5>Input Lahan & Tanam</h5>
                    <p>Daftarkan lahan, pilih varietas, dan catat tanggal tanam.</p>
                    <i class="bi bi-arrow-right step-arrow d-none d-md-block"></i>
                </div>
                <div class="col-md-3 step-card" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-number">3</div>
                    <h5>Ikuti Jadwal</h5>
                    <p>Sistem otomatis membuat jadwal pemupukan & penyemprotan.</p>
                    <i class="bi bi-arrow-right step-arrow d-none d-md-block"></i>
                </div>
                <div class="col-md-3 step-card" data-aos="fade-up" data-aos-delay="400">
                    <div class="step-number">4</div>
                    <h5>Panen & Laporan</h5>
                    <p>Catat hasil panen, lihat grafik, dan download laporan.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ========== CTA ========== --}}
    <section class="container" data-aos="zoom-in">
        <div class="cta-section">
            <h2>Siap Mengelola Pertanian Padi dengan Lebih Baik?</h2>
            <p class="text-muted mb-4">Bergabunglah dengan petani lain di Nagari Bahagia Padang Gelugua</p>
            <a href="{{ url('login') }}" class="btn btn-hero btn-lg">
                <i class="bi bi-box-arrow-in-right me-2"></i>Mulai Sekarang
            </a>
        </div>
    </section>

    {{-- ========== FOOTER ========== --}}
    <footer class="footer" id="tentang">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-2">SIPADI</h5>
                    <p class="mb-0" style="opacity:0.7;">Sistem Informasi Manajemen Pertanian Padi<br>
                    Nagari Bahagia Padang Gelugua, Kec. Padang Gelugur, Kab. Pasaman, Sumatera Barat</p>
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <p class="mb-1" style="opacity:0.7;">Kontak: <a href="mailto:admin@sipadi.id">admin@sipadi.id</a></p>
                    <p class="mb-0" style="opacity:0.5; font-size:0.85rem;">© {{ date('Y') }} SIPADI. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
    </script>
</body>
</html>