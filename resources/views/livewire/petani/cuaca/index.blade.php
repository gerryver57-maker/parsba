<div>
    <div class="container-fluid">
        
        {{-- HEADER --}}
        <div class="mb-4">
            <h4 class="fw-bold mb-1"><i class="ti ti-cloud-rain me-2"></i>Prakiraan Cuaca</h4>
            <p class="text-muted mb-0">
                @if($this->lokasi)
                    {{ $this->lokasi->desa }}, {{ $this->lokasi->kecamatan }}, {{ $this->lokasi->kabupaten }}
                @else
                    Data lokasi belum tersedia
                @endif
            </p>
        </div>

        @if(!$this->lokasi)
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="ti ti-cloud-off fs-1 text-muted"></i>
                <h5 class="mt-3">Data Cuaca Belum Tersedia</h5>
                <p class="text-muted">Silakan hubungi admin untuk sinkronisasi data cuaca.</p>
            </div>
        </div>
        @else

        {{-- CUACA SEKARANG --}}
        @if($this->cuacaSekarang && $this->cuacaSekarang->Gambar)
        @php
            $cuaca = $this->cuacaSekarang;
            $jam = Carbon\Carbon::parse($cuaca->waktu_lokal)->hour;
            
            if ($jam >= 5 && $jam <= 10) { $bg1 = '#FF8008'; $bg2 = '#FFC837'; $waktuText = 'Pagi'; }
            elseif ($jam >= 11 && $jam <= 15) { $bg1 = '#2196F3'; $bg2 = '#64B5F6'; $waktuText = 'Siang'; }
            elseif ($jam >= 16 && $jam <= 18) { $bg1 = '#FF6B35'; $bg2 = '#F7C948'; $waktuText = 'Sore'; }
            else { $bg1 = '#0F2027'; $bg2 = '#2C5364'; $waktuText = 'Malam'; }

            $iconUrl = $cuaca->Gambar;
        @endphp
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white border-0 shadow-sm" style="background: linear-gradient(135deg, {{ $bg1 }} 0%, {{ $bg2 }} 100%); border-radius: 20px;">
                    <div class="card-body text-center py-4">
                        <h6 class="text-white mb-3" style="opacity: 0.9;">Cuaca Saat Ini · {{ $waktuText }}</h6>
                        <img src="{{ $iconUrl }}" alt="icon" style="width:80px;height:80px;" class="mb-2">
                        <h1 class="display-4 fw-bold mb-1 text-white">{{ $cuaca->suhu }}°C</h1>
                        <h5 class="mb-1 text-white">{{ $cuaca->deskripsi_cuaca }}</h5>
                        <small class="text-white" style="opacity: 0.8;">{{ Carbon\Carbon::parse($cuaca->waktu_lokal)->format('d M Y, H:i') }} WIB</small>
                        <hr class="border-white my-3" style="opacity: 0.3;">
                        <div class="row text-white">
                            <div class="col-4"><small style="opacity: 0.8;">Kelembapan</small><h6 class="mb-0 text-white">{{ $cuaca->kelembapan }}%</h6></div>
                            <div class="col-4"><small style="opacity: 0.8;">Hujan</small><h6 class="mb-0 text-white">{{ $cuaca->curah_hujan }} mm</h6></div>
                            <div class="col-4"><small style="opacity: 0.8;">Angin</small><h6 class="mb-0 text-white">{{ $cuaca->kecepatan_angin }} km/j</h6></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">Informasi Lokasi</h6>
                        <table class="table table-sm">
                            <tr><td width="30%">Desa</td><td>: {{ $this->lokasi->desa }}</td></tr>
                            <tr><td>Kecamatan</td><td>: {{ $this->lokasi->kecamatan }}</td></tr>
                            <tr><td>Kabupaten</td><td>: {{ $this->lokasi->kabupaten }}</td></tr>
                            <tr><td>Provinsi</td><td>: {{ $this->lokasi->provinsi }}</td></tr>
                            <tr><td>Total Data</td><td>: {{ number_format($this->totalData) }} record</td></tr>
                            <tr><td>Update Terakhir</td><td>: {{ $this->lastSync ? $this->lastSync->diffForHumans() : '-' }}</td></tr>
                        </table>

                        <div class="alert alert-light border mt-3 mb-0 small text-muted">
                            <i class="ti ti-info-circle me-1"></i>
                            <strong>Disclaimer:</strong> Data prakiraan cuaca ini bersumber dari <strong>BMKG</strong>. 
                            Informasi yang ditampilkan merupakan <strong>prakiraan</strong> dan tidak 100% akurat.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- TAB HARI --}}
        <div class="card">
            <div class="card-header bg-white">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a href="javascript:void(0)" wire:click="setTab('hari-ini')" 
                           class="nav-link {{ $this->selectedTab === 'hari-ini' ? 'active' : '' }}">
                            <i class="ti ti-calendar me-1"></i> Hari Ini
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0)" wire:click="setTab('besok')" 
                           class="nav-link {{ $this->selectedTab === 'besok' ? 'active' : '' }}">
                            <i class="ti ti-calendar me-1"></i> Besok
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0)" wire:click="setTab('lusa')" 
                           class="nav-link {{ $this->selectedTab === 'lusa' ? 'active' : '' }}">
                            <i class="ti ti-calendar me-1"></i> Lusa
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                
                @php
                    $cuacaList = $this->selectedTab === 'hari-ini' ? $this->hariIni : 
                                ($this->selectedTab === 'besok' ? $this->besok : $this->lusa);
                    $tanggal = $this->selectedTab === 'hari-ini' ? Carbon\Carbon::now() : 
                              ($this->selectedTab === 'besok' ? Carbon\Carbon::now()->addDay() : Carbon\Carbon::now()->addDays(2));
                @endphp

                <h5 class="mb-3">{{ $tanggal->translatedFormat('l, d F Y') }}</h5>

                @if($cuacaList->count() > 0)
                <div class="row g-3">
                    @foreach($cuacaList as $c)
                    @php
                        $waktuParse = Carbon\Carbon::parse($c->waktu_lokal);
                        $isNow = $waktuParse->between(Carbon\Carbon::now()->subHour(), Carbon\Carbon::now()->addHour());
                        $iconUrl = $c->Gambar;
                        $jamItem = $waktuParse->hour;
                        
                        // Background per jam
                        if ($jamItem >= 5 && $jamItem <= 10) { $cardBg = '#FFF8E1'; $textColor = '#E65100'; }
                        elseif ($jamItem >= 11 && $jamItem <= 15) { $cardBg = '#E3F2FD'; $textColor = '#0D47A1'; }
                        elseif ($jamItem >= 16 && $jamItem <= 18) { $cardBg = '#FFF3E0'; $textColor = '#BF360C'; }
                        else { $cardBg = '#ECEFF1'; $textColor = '#263238'; }
                        
                        // 🟢 Highlight jam sekarang
                        if ($isNow) { $cardBg = '#C8E6C9'; $textColor = '#1B5E20'; }
                    @endphp
                    <div class="col-md-3 col-6">
                        <div class="card border-0 shadow-sm text-center py-3" 
                             style="background: {{ $cardBg }}; border-radius: 15px; color: {{ $textColor }}; {{ $isNow ? 'border: 2px solid #2E7D32; box-shadow: 0 4px 15px rgba(46,125,50,0.3);' : '' }}">
                            
                            <small style="color: {{ $textColor }}; font-weight: {{ $isNow ? 'bold' : 'normal' }}; font-size: {{ $isNow ? '0.85rem' : '0.75rem' }};">
                                @if($isNow)
                                    🟢 Sekarang
                                @else
                                    {{ $waktuParse->format('H:i') }}
                                @endif
                                WIB
                            </small>
                            
                            @if($iconUrl)
                                <div class="my-2">
                                    <img src="{{ $iconUrl }}" alt="icon" style="width:50px;height:50px;">
                                </div>
                            @endif
                            
                            <h4 class="fw-bold mb-1" style="color: {{ $textColor }};">{{ $c->suhu }}°C</h4>
                            <p class="mb-2 small" style="color: {{ $textColor }}; opacity: 0.8;">{{ $c->deskripsi_cuaca }}</p>
                            
                            <div class="d-flex justify-content-center gap-3">
                                <small style="color: {{ $textColor }}; opacity: 0.8;">
                                    <i class="ti ti-droplet me-1"></i>{{ $c->kelembapan }}%
                                </small>
                                <small style="color: {{ $textColor }}; opacity: 0.8;">
                                    <i class="ti ti-rain me-1"></i>{{ $c->curah_hujan }} mm
                                </small>
                            </div>
                            <small class="mt-1" style="color: {{ $textColor }}; opacity: 0.7;">
                                {{ $c->kecepatan_angin }} km/j ({{ $c->arah_angin }})
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="ti ti-cloud-off fs-1"></i>
                    <p class="mt-2">Belum ada data cuaca.</p>
                </div>
                @endif

            </div>
        </div>

        {{-- DISCLAIMER FOOTER --}}
        <div class="text-center mt-4 text-muted small">
            <i class="ti ti-info-circle me-1"></i>
            Sumber: BMKG. Data ini merupakan prakiraan, bukan data aktual 100%. 
            Diperbarui: {{ $this->lastSync ? $this->lastSync->diffForHumans() : 'belum pernah' }}.
        </div>

        @endif

    </div>
</div>