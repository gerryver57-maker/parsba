<div>
    <div class="container-fluid">
        
        {{-- HEADER --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1"><i class="ti ti-calendar me-2"></i>Kalender Manajemen</h4>
                <p class="text-muted mb-0">Pantau semua aktivitas pertanian & cuaca dalam kalender</p>
            </div>
        </div>

        {{-- CUACA SEKARANG + INDIKATOR HUJAN --}}
        @php
            $cuacaSekarang = \App\Models\PrakiraanCuaca::whereHas('lokasi', fn($q) => $q->where('kode_desa', '13.08.17.2004'))
                ->where('waktu_lokal', '<=', \Carbon\Carbon::now()->addHour())
                ->orderBy('waktu_lokal', 'desc')
                ->first();
            if (!$cuacaSekarang) {
                $cuacaSekarang = \App\Models\PrakiraanCuaca::whereHas('lokasi', fn($q) => $q->where('kode_desa', '13.08.17.2004'))
                    ->orderBy('waktu_lokal', 'asc')->first();
            }
        @endphp

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
            $kelembapanTinggi = $cuacaSekarang->kelembapan > 80;
        @endphp

        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, {{ $bg1 }} 0%, {{ $bg2 }} 100%); border-radius: 15px 15px 0 0;">
                    <div class="card-body p-3" x-data="{ jamDigital: '', tanggal: '' }" x-init="
                        setInterval(() => {
                            let now = new Date();
                            let hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                            let bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                            jamDigital = now.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit', second:'2-digit'});
                            tanggal = hari[now.getDay()] + ', ' + now.getDate() + ' ' + bulan[now.getMonth()] + ' ' + now.getFullYear();
                        }, 1000)
                    ">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-3">
                                @if($iconUrl)
                                    <img src="{{ $iconUrl }}" alt="icon" style="width:60px;height:60px;">
                                @endif
                                <div>
                                    <h3 class="mb-0 text-white" style="font-weight: 300; font-size: 2.5rem;">{{ $cuacaSekarang->suhu }}°C</h3>
                                    <small class="text-white" style="opacity: 0.8;">{{ $cuacaSekarang->deskripsi_cuaca }} · {{ $waktuText }}</small>
                                </div>
                            </div>
                            <div class="d-flex gap-3 small text-white" style="opacity: 0.8;">
                                <div class="text-center"><i class="ti ti-droplet d-block mb-1"></i>{{ $cuacaSekarang->kelembapan }}%</div>
                                <div class="text-center"><i class="ti ti-rain d-block mb-1"></i>{{ $cuacaSekarang->curah_hujan }} mm</div>
                                <div class="text-center"><i class="ti ti-wind d-block mb-1"></i>{{ $cuacaSekarang->kecepatan_angin }} km/j</div>
                            </div>
                            <div class="text-end" style="background: rgba(255,255,255,0.15); padding: 10px 18px; border-radius: 12px; min-width: 150px;">
                                <div style="font-size: 1.8rem; font-weight: 300; font-family: 'Courier New', monospace; letter-spacing: 2px; color: white;" x-text="jamDigital"></div>
                                <small class="text-white" style="opacity: 0.8; font-size: 0.7rem;" x-text="tanggal"></small>
                            </div>
                        </div>
                    </div>
                </div>

                @if($adaHujan || $kelembapanTinggi)
                <div style="background: {{ $hujanLebat ? '#FFF3E0' : '#E3F2FD' }}; padding: 12px 15px;">
                    <div class="d-flex align-items-start gap-2">
                        <i class="ti ti-cloud-rain fs-5 mt-1" style="color: {{ $hujanLebat ? '#E65100' : '#1565C0' }};"></i>
                        <div style="width: 100%;">
                            <strong style="color: {{ $hujanLebat ? '#E65100' : '#1565C0' }}; font-size: 0.85rem;">
                                @if($hujanLebat) ⚠️ WASPADA HUJAN LEBAT!
                                @elseif($adaHujan) 🌧️ AKAN TURUN HUJAN
                                @else 💧 POTENSI HUJAN
                                @endif
                            </strong>
                            <div class="row g-2 mt-2">
                                @if($cuacaSekarang->kelembapan > 80)
                                <div class="col-6">
                                    <div style="background: rgba(255,255,255,0.6); border-radius: 8px; padding: 8px;">
                                        <div class="d-flex align-items-center gap-1"><i class="ti ti-droplet text-info"></i><small style="font-weight:600;">Kelembapan Tinggi</small></div>
                                        <small style="font-size:0.7rem;">{{ $cuacaSekarang->kelembapan }}% (Normal: 60-80%)</small>
                                    </div>
                                </div>
                                @endif
                                @if($cuacaSekarang->kecepatan_angin > 10)
                                <div class="col-6">
                                    <div style="background: rgba(255,255,255,0.6); border-radius: 8px; padding: 8px;">
                                        <div class="d-flex align-items-center gap-1"><i class="ti ti-wind text-primary"></i><small style="font-weight:600;">Angin Kencang</small></div>
                                        <small style="font-size:0.7rem;">{{ $cuacaSekarang->kecepatan_angin }} km/j ({{ $cuacaSekarang->arah_angin }})</small>
                                    </div>
                                </div>
                                @endif
                                @if($cuacaSekarang->curah_hujan > 0)
                                <div class="col-6">
                                    <div style="background: rgba(255,255,255,0.6); border-radius: 8px; padding: 8px;">
                                        <div class="d-flex align-items-center gap-1"><i class="ti ti-rain text-primary"></i><small style="font-weight:600;">Curah Hujan</small></div>
                                        <small style="font-size:0.7rem;">{{ $cuacaSekarang->curah_hujan }} mm</small>
                                    </div>
                                </div>
                                @endif
                                <div class="col-6">
                                    <div style="background: rgba(255,255,255,0.6); border-radius: 8px; padding: 8px;">
                                        <div class="d-flex align-items-center gap-1"><i class="ti ti-cloud text-secondary"></i><small style="font-weight:600;">Kondisi</small></div>
                                        <small style="font-size:0.7rem;">{{ $cuacaSekarang->deskripsi_cuaca }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 small" style="color: {{ $hujanLebat ? '#BF360C' : '#0D47A1' }}; font-size: 0.73rem;">
                                <i class="ti ti-alert-triangle me-1"></i><strong>Rekomendasi:</strong> 
                                @if($hujanLebat) Tunda semua aktivitas di lahan! Pastikan saluran irigasi lancar.
                                @elseif($adaHujan) Tunda pemupukan & penyemprotan. Pupuk dapat tercuci air hujan.
                                @else Pantau terus perubahan cuaca. Siapkan perlengkapan jika turun hujan.
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div style="background: #E8F5E9; padding: 12px 15px;">
                    <div class="d-flex align-items-start gap-2">
                        <i class="ti ti-check-circle fs-5 mt-1" style="color: #2E7D32;"></i>
                        <div style="width: 100%;">
                            <strong style="color: #2E7D32; font-size: 0.85rem;">☀️ KONDISI AMAN</strong>
                            <div class="row g-2 mt-2">
                                <div class="col-6">
                                    <div style="background: rgba(255,255,255,0.6); border-radius: 8px; padding: 8px;">
                                        <div class="d-flex align-items-center gap-1"><i class="ti ti-droplet text-success"></i><small style="font-weight:600;">Kelembapan Normal</small></div>
                                        <small style="font-size:0.7rem;">{{ $cuacaSekarang->kelembapan }}%</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div style="background: rgba(255,255,255,0.6); border-radius: 8px; padding: 8px;">
                                        <div class="d-flex align-items-center gap-1"><i class="ti ti-sun text-warning"></i><small style="font-weight:600;">Kondisi Baik</small></div>
                                        <small style="font-size:0.7rem;">{{ $cuacaSekarang->deskripsi_cuaca }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 small" style="color: #1B5E20; font-size: 0.73rem;">
                                <i class="ti ti-check me-1"></i><strong>Rekomendasi:</strong> Kondisi baik untuk aktivitas pertanian. Lakukan pemupukan & penyemprotan sesuai jadwal.
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                <div class="text-center py-1" style="background: #f8f9fa; border-radius: 0 0 15px 15px;">
                    <small style=" font-size: 0.6rem;"><i class="ti ti-info-circle me-1"></i>Sumber: BMKG. Data prakiraan tidak 100% akurat.</small>
                </div>
            </div>
        </div>
        @endif

        {{-- LEGENDA --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="mb-2">Legenda:</h6>
                <div class="d-flex flex-wrap gap-3">
                    <span><img src="{{ asset('dashboard/images/padi/tanampadi.png') }}" style="width:16px;height:16px;"> Tanam</span>
                    <span><img src="{{ asset('dashboard/images/padi/memupukpadi.png') }}" style="width:16px;height:16px;"> Pemupukan</span>
                    <span><img src="{{ asset('dashboard/images/padi/menyemprotpadi.png') }}" style="width:16px;height:16px;"> Penyemprotan</span>
                    <span><img src="{{ asset('dashboard/images/padi/panenpadi.png') }}" style="width:16px;height:16px;"> Panen</span>
                    <span style="background:#9e9e9e;width:16px;height:16px;display:inline-block;border-radius:3px;"></span> Selesai
                    <span style="background:#87ceeb;width:16px;height:16px;display:inline-block;border-radius:3px;"></span> Cuaca
                </div>
            </div>
        </div>

        {{-- KALENDER --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>

    </div>
</div>

@push('styles')
<style>
    #calendar { max-width: 100%; margin: 0 auto; }
    .fc-toolbar-title { font-size: 1.1rem !important; font-weight: bold; }
    .fc-button { font-size: 0.8rem !important; padding: 4px 10px !important; }
    .fc-event { cursor: pointer; font-size: 0.7rem; padding: 2px 3px; border-radius: 3px; border: none !important; }
    .fc-daygrid-event { white-space: normal !important; margin: 1px 2px !important; }
    .fc-day-today { background-color: #e8f5e9 !important; }
    .cuaca-event { font-weight: bold !important; font-size: 0.75rem !important; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script>
    var calendarInstance = null;

    function initCalendar() {
        var calendarEl = document.getElementById('calendar');
        if (!calendarEl) return;
        if (calendarInstance) { calendarInstance.destroy(); calendarInstance = null; }

        var eventsData = @json($this->events);

        calendarInstance = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth', height: 'auto',
            headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,dayGridWeek' },
            events: eventsData,
            
            eventContent: function(info) {
                var imageUrl = info.event.extendedProps.imageUrl || info.event.imageUrl;
                if (imageUrl) {
                    return { html: `<div style="display:flex;align-items:center;gap:3px;padding:1px;"><img src="${imageUrl}" style="width:20px;height:20px;border-radius:2px;"><span style="font-size:0.65rem;">${info.event.title}</span></div>` };
                }
                return { html: '<span style="font-size:0.65rem;">' + info.event.title + '</span>' };
            },
            
            eventClick: function(info) {
                var props = info.event.extendedProps;
                var imageUrl = props.imageUrl || info.event.imageUrl;
                var iconHtml = imageUrl ? `<div class="text-center mb-3"><img src="${imageUrl}" style="width:80px;height:80px;"></div>` : '';
                
            if (props.isCuaca) {
                // 🆕 Ambil langsung dari extendedProps (lebih akurat)
                var suhu = props.suhu || 0;
                var hujan = props.curah_hujan || 0;
                var kelembapan = props.kelembapan || 0;
                var angin = props.kecepatan_angin || 0;
                
                // Fallback: parse dari detail string
                if (!suhu && props.detail) {
                    var suhuMatch = props.detail.match(/Suhu:\s*(\d+)/);
                    suhu = suhuMatch ? parseInt(suhuMatch[1]) : 0;
                }
                if (!hujan && props.detail) {
                    var hujanMatch = props.detail.match(/Curah Hujan:\s*([\d.]+)/);
                    hujan = hujanMatch ? parseFloat(hujanMatch[1]) : 0;
                }
                if (!kelembapan && props.detail) {
                    var kelembapanMatch = props.detail.match(/Kelembapan:\s*(\d+)/);
                    kelembapan = kelembapanMatch ? parseInt(kelembapanMatch[1]) : 0;
                }
                if (!angin && props.detail) {
                    var anginMatch = props.detail.match(/Angin:\s*([\d.]+)/);
                    angin = anginMatch ? parseFloat(anginMatch[1]) : 0;
                }
                
                var indikatorHtml = '', rekomendasiHtml = '';
                
                if (hujan > 5) {
                    indikatorHtml = `<div style="background:#FFF3E0;border-radius:8px;padding:10px;margin-top:10px;text-align:left;border-left:3px solid #E65100;"><strong style="color:#E65100;">⚠️ WASPADA HUJAN LEBAT</strong><div class="row g-2 mt-1"><div class="col-6"><small>🌧️ Hujan: ${hujan} mm</small></div><div class="col-6"><small>💧 Kelembapan: ${kelembapan}%</small></div></div></div>`;
                    rekomendasiHtml = `<p style="color:#BF360C;font-size:0.8rem;margin-top:5px;"><strong>Rekomendasi:</strong> Tunda semua aktivitas!</p>`;
                } else if (hujan > 0) {
                    indikatorHtml = `<div style="background:#E3F2FD;border-radius:8px;padding:10px;margin-top:10px;text-align:left;border-left:3px solid #1565C0;"><strong style="color:#1565C0;">🌧️ AKAN TURUN HUJAN</strong><div class="row g-2 mt-1"><div class="col-6"><small>🌧️ Hujan: ${hujan} mm</small></div><div class="col-6"><small>💧 Kelembapan: ${kelembapan}%</small></div></div></div>`;
                    rekomendasiHtml = `<p style="color:#0D47A1;font-size:0.8rem;margin-top:5px;"><strong>Rekomendasi:</strong> Tunda pemupukan.</p>`;
                } else if (kelembapan > 80) {
                    indikatorHtml = `<div style="background:#E8EAF6;border-radius:8px;padding:10px;margin-top:10px;text-align:left;border-left:3px solid #283593;"><strong style="color:#283593;">💧 KELEMBAPAN TINGGI</strong><div class="row g-2 mt-1"><div class="col-6"><small>💧 ${kelembapan}%</small></div><div class="col-6"><small>🌡️ ${suhu}°C</small></div></div></div>`;
                    rekomendasiHtml = `<p style="color:#1A237E;font-size:0.8rem;margin-top:5px;"><strong>Rekomendasi:</strong> Waspada jamur.</p>`;
                } else if (suhu > 30) {
                    indikatorHtml = `<div style="background:#FFF8E1;border-radius:8px;padding:10px;margin-top:10px;text-align:left;border-left:3px solid #F57F17;"><strong style="color:#F57F17;">🌡️ SUHU TINGGI</strong><div class="row g-2 mt-1"><div class="col-6"><small>🌡️ ${suhu}°C</small></div><div class="col-6"><small>💧 ${kelembapan}%</small></div></div></div>`;
                    rekomendasiHtml = `<p style="color:#E65100;font-size:0.8rem;margin-top:5px;"><strong>Rekomendasi:</strong> Tambah irigasi.</p>`;
                } else {
                    indikatorHtml = `<div style="background:#E8F5E9;border-radius:8px;padding:10px;margin-top:10px;text-align:left;border-left:3px solid #2E7D32;"><strong style="color:#2E7D32;">☀️ KONDISI AMAN</strong><div class="row g-2 mt-1"><div class="col-6"><small>🌡️ ${suhu}°C</small></div><div class="col-6"><small>💧 ${kelembapan}%</small></div></div></div>`;
                    rekomendasiHtml = `<p style="color:#1B5E20;font-size:0.8rem;margin-top:5px;"><strong>Rekomendasi:</strong> Aman beraktivitas.</p>`;
                }
                
                Swal.fire({ 
                    title: '🌤️ Info Cuaca', 
                    html: iconHtml + `
                        <table class="table table-sm text-start">
                            <tr><td><strong>Kondisi</strong></td><td>: ${props.jenis}</td></tr>
                            <tr><td><strong>Tanggal</strong></td><td>: ${info.event.start.toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'})}</td></tr>
                        </table>
                        ${indikatorHtml}
                        ${rekomendasiHtml}
                    `, 
                    icon: null, confirmButtonColor: '#3085d6', width: '500px' 
                });
                return;
            }
                
                if (props.isPending && props.jadwal_id) {
                    Swal.fire({ title: 'Konfirmasi Aktivitas', html: iconHtml + `<p>Apakah Anda sudah melakukan?</p><strong class="fs-5 text-success">${props.jenis}</strong><p class="text-muted small">📍 ${props.lahan}</p><p class="text-muted small">📋 ${props.detail}</p>`, icon: null, showCancelButton: true, confirmButtonColor: '#28a745', cancelButtonColor: '#6c757d', confirmButtonText: 'Ya, Sudah!', cancelButtonText: 'Belum', reverseButtons: true }).then((r) => { if (r.isConfirmed) { var comp = Livewire.find(document.getElementById('calendar').closest('[wire\\:id]')?.getAttribute('wire:id')); if (comp) comp.call('konfirmasiJadwal', props.jadwal_id); } });
                    return;
                }

                var title = info.event.title.replace(/[🌱💧🛡️🌾✅⏳☀️🌦️🌧️⛈️☁️🌫️⚠️🧑‍🌾📋]/g, '').trim();
                Swal.fire({ title: title || props.jenis, html: iconHtml + `<table class="table table-sm text-start"><tr><td><strong>Jenis</strong></td><td>: ${props.jenis}</td></tr><tr><td><strong>Lahan</strong></td><td>: ${props.lahan}</td></tr><tr><td><strong>Tanggal</strong></td><td>: ${info.event.start.toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'})}</td></tr>${props.status ? `<tr><td><strong>Status</strong></td><td>: ${props.status}</td></tr>` : ''}<tr><td colspan="2"><small>${props.detail}</small></td></tr></table>`, icon: null, confirmButtonColor: '#3085d6', width: '480px' });
            },
            
            locale: 'id', buttonText: { today: 'Hari Ini', month: 'Bulan', week: 'Minggu' }, allDaySlot: false,
        });
        calendarInstance.render();
    }
    // ========== PERINGATAN URUTAN ==========
Livewire.on('tampilPeringatanUrutan', (data) => {
    Swal.fire({
        title: '⚠️ Konfirmasi Harus Berurutan!',
        html: `
            <p>Anda belum mengkonfirmasi jadwal sebelumnya:</p>
            <strong class="fs-5 text-warning">${data[0].nama}</strong>
            <p class="text-muted small">Tanggal: ${data[0].tanggal}</p>
            <hr>
            <p class="text-muted small">Silakan konfirmasi jadwal yang lebih dulu sebelum melanjutkan ke jadwal ini.</p>
        `,
        icon: 'warning',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Mengerti',
    }).then(() => {
        // Refresh kalender setelah tutup peringatan
        initCalendar();
    });
});

    var attempts = 0;
    var interval = setInterval(function() {
        var el = document.getElementById('calendar');
        if (el && window.Livewire) { initCalendar(); clearInterval(interval); }
        attempts++; if (attempts >= 20) clearInterval(interval);
    }, 500);

    Livewire.on('tampilPesan', (data) => { Swal.fire({ icon: data[0].tipe, title: data[0].judul, text: data[0].teks, timer: 2500, timerProgressBar: true, showConfirmButton: false }); });
    Livewire.on('updateCalendarEvents', () => { setTimeout(() => initCalendar(), 300); });
</script>
@endpush