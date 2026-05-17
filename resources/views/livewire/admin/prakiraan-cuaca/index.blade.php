<div>
    <div class="container-fluid" wire:ignore.self>
        
        {{-- HEADER --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">
                    <i class="ti ti-cloud-rain me-2"></i>Prakiraan Cuaca
                </h4>
                <p class="text-muted mb-0">
                    @if($this->lokasi)
                        {{ $this->lokasi->desa }}, {{ $this->lokasi->kecamatan }}, {{ $this->lokasi->kabupaten }}
                    @else
                        Data lokasi belum tersedia
                    @endif
                </p>
            </div>
            <div class="d-flex gap-2 align-items-center">
                @if($this->lastSync)
                <small class="text-muted">
                    <i class="ti ti-clock me-1"></i> Update: {{ $this->lastSync->diffForHumans() }}
                </small>
                @endif
                <button wire:click="sinkronisasi" 
                        wire:loading.attr="disabled"
                        class="btn btn-primary">
                    <span wire:loading.remove wire:target="sinkronisasi">
                        <i class="ti ti-cloud-upload me-1"></i> Sinkronisasi BMKG
                    </span>
                    <span wire:loading wire:target="sinkronisasi">
                        <span class="spinner-border spinner-border-sm me-1"></span> Menyinkronkan...
                    </span>
                </button>
            </div>
        </div>

        {{-- EMPTY STATE --}}
        @if(!$this->lokasi || count($this->cuacaPerHari) == 0)
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="ti ti-database-off fs-1 text-muted"></i>
                <h5 class="mt-3">Belum Ada Data Cuaca</h5>
                <p class="text-muted">Klik tombol Sinkronisasi untuk mengambil data dari BMKG.</p>
                <button wire:click="sinkronisasi" 
                        wire:loading.attr="disabled"
                        class="btn btn-primary">
                    <span wire:loading.remove wire:target="sinkronisasi">
                        <i class="ti ti-cloud-upload me-1"></i> Sinkronisasi Sekarang
                    </span>
                    <span wire:loading wire:target="sinkronisasi">
                        <span class="spinner-border spinner-border-sm me-1"></span> Memproses...
                    </span>
                </button>
            </div>
        </div>
        @else

        {{-- INFO LOKASI --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="ti ti-map-pin me-1"></i> {{ $this->lokasi->desa }}</h5>
                        <table class="table table-sm table-borderless mb-0">
                            <tr><td width="40%">Kecamatan</td><td>: {{ $this->lokasi->kecamatan }}</td></tr>
                            <tr><td>Kabupaten</td><td>: {{ $this->lokasi->kabupaten }}</td></tr>
                            <tr><td>Provinsi</td><td>: {{ $this->lokasi->provinsi }}</td></tr>
                            <tr><td>Kode Desa</td><td>: {{ $this->lokasi->kode_desa }}</td></tr>
                            <tr><td>Koordinat</td><td>: {{ $this->lokasi->lintang }}, {{ $this->lokasi->bujur }}</td></tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="row">
                            <div class="col-4">
                                <small class="text-muted">Total Data</small>
                                <h3 class="mt-1">{{ number_format($this->totalData) }}</h3>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">Jumlah Hari</small>
                                <h3 class="mt-1">{{ count($this->cuacaPerHari) }}</h3>
                            </div>
                            <div class="col-4">
                                <small class="text-muted">Terakhir Sinkron</small>
                                <h5 class="mt-1">{{ $this->lastSync ? $this->lastSync->format('d M Y H:i') : '-' }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- TABEL CUACA PER HARI --}}
        @foreach($this->cuacaPerHari as $hari)
        <div class="card mb-4">
            <div class="card-header bg-success bg-opacity-10 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-success">
                    <i class="ti ti-calendar me-1"></i> 
                    Hari ke-{{ $hari['hari_ke'] }} ({{ \Carbon\Carbon::parse($hari['tanggal'])->translatedFormat('d F Y') }})
                </h5>
                <span class="badge bg-success rounded-pill">{{ count($hari['data']) }} interval</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Icon</th>
                                <th>Hari</th>
                                <th>Jam</th>
                                <th>Cuaca</th>
                                <th>Suhu</th>
                                <th>Kelembapan</th>
                                <th>Angin</th>
                                <th>Hujan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($hari['data'] as $cuaca)
                            <tr>
                                {{-- 🆕 ICON BMKG --}}
                                <td>
                                    @if(!empty($cuaca['icon_url']))
                                        <img src="{{ $cuaca['icon_url'] }}" alt="icon" style="width:40px;height:40px;">
                                    @else
                                        @if($cuaca['deskripsi'] == 'Cerah')
                                            <i class="ti ti-sun fs-3 text-warning"></i>
                                        @elseif(str_contains($cuaca['deskripsi'], 'Cerah'))
                                            <i class="ti ti-sun-cloud fs-3 text-warning"></i>
                                        @elseif(str_contains($cuaca['deskripsi'], 'Hujan'))
                                            <i class="ti ti-cloud-rain fs-3 text-info"></i>
                                        @else
                                            <i class="ti ti-cloud fs-3 text-secondary"></i>
                                        @endif
                                    @endif
                                </td>
                                <td><strong>{{ $cuaca['hari'] }}</strong></td>
                                <td>{{ $cuaca['jam'] }} WIB</td>
                                <td>{{ $cuaca['deskripsi'] }}</td>
                                <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $cuaca['suhu'] }}°C</span></td>
                                <td>{{ $cuaca['kelembapan'] }}%</td>
                                <td>{{ $cuaca['kecepatan_angin'] }} km/j ({{ $cuaca['arah_angin'] }})</td>
                                <td>
                                    @if($cuaca['curah_hujan'] > 0)
                                    <span class="badge bg-info"><i class="ti ti-rain me-1"></i> {{ $cuaca['curah_hujan'] }} mm</span>
                                    @else
                                    <span class="badge bg-light text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endforeach

        {{-- DISCLAIMER --}}
        <div class="alert alert-light border small text-muted text-center mt-3">
            <i class="ti ti-info-circle me-1"></i>
            <strong>Disclaimer:</strong> Data prakiraan cuaca ini bersumber dari <strong>BMKG</strong>. 
            Informasi yang ditampilkan merupakan prakiraan dan tidak 100% akurat.
        </div>

        @endif

        <footer class="mt-4 text-center text-muted small border-top pt-3">
            <i class="ti ti-database me-1"></i> 
            Data tersimpan di database | Sumber: BMKG (Badan Meteorologi, Klimatologi, dan Geofisika) | 
            @if($this->lastSync)
                Terakhir diperbarui: {{ $this->lastSync->diffForHumans() }}
            @else
                Belum pernah disinkronisasi
            @endif
        </footer>

    </div>
</div>

@script
<script>
    $wire.on('tampilPesan', (data) => {
        Swal.fire({
            icon: data[0].tipe,
            title: data[0].judul,
            text: data[0].teks,
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: data[0].tipe !== 'success'
        });
    });
</script>
@endscript