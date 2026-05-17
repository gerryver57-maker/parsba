<div>
    <div class="container-fluid" wire:ignore.self>
        
        {{-- HEADER --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">
                    <i class="ti ti-cloud-upload me-2"></i>Sinkronisasi BMKG
                </h4>
                <p class="text-muted mb-0">Sinkronisasi data cuaca dari API BMKG ke database</p>
            </div>
        </div>

        <div class="row g-4">
            
            {{-- KIRI: STATUS & TOMBOL --}}
            <div class="col-md-5">
                
                {{-- KARTU STATUS --}}
                <div class="card mb-4">
                    <div class="card-header bg-success bg-opacity-10">
                        <h5 class="mb-0"><i class="ti ti-info-circle me-1"></i> Status Sinkronisasi</h5>
                    </div>
                    <div class="card-body text-center py-4">
                        
                        @if($status === 'syncing')
                        <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;"></div>
                        <h5>Sedang Menyinkronkan...</h5>
                        <p class="text-muted">Mengambil data dari server BMKG</p>
                        @elseif($status === 'success')
                        <i class="ti ti-circle-check fs-1 text-success mb-3"></i>
                        <h5 class="text-success">Sinkronisasi Berhasil</h5>
                        @elseif($status === 'error')
                        <i class="ti ti-alert-triangle fs-1 text-danger mb-3"></i>
                        <h5 class="text-danger">Sinkronisasi Gagal</h5>
                        @else
                        <i class="ti ti-cloud fs-1 text-muted mb-3"></i>
                        <h5>Siap Sinkronisasi</h5>
                        <p class="text-muted">Klik tombol di bawah untuk memulai</p>
                        @endif

                        <div class="mt-4">
                            <table class="table table-sm table-borderless text-start mx-auto" style="max-width: 300px;">
                                <tr>
                                    <td>Kode Desa</td>
                                    <td>: {{ $kodeDesa }}</td>
                                </tr>
                                <tr>
                                    <td>Total Data</td>
                                    <td>: {{ number_format($totalData) }} record</td>
                                </tr>
                                <tr>
                                    <td>Sinkron Terakhir</td>
                                    <td>: {{ $lastSync ? $lastSync->format('d M Y, H:i:s') : 'Belum pernah' }}</td>
                                </tr>
                            </table>
                        </div>

                        <button wire:click="sinkronisasiSekarang" 
                                wire:loading.attr="disabled"
                                class="btn btn-primary btn-lg mt-3 px-5"
                                {{ $status === 'syncing' ? 'disabled' : '' }}>
                            <span wire:loading.remove wire:target="sinkronisasiSekarang">
                                <i class="ti ti-cloud-upload me-1"></i> Sinkronisasi Sekarang
                            </span>
                            <span wire:loading wire:target="sinkronisasiSekarang">
                                <span class="spinner-border spinner-border-sm me-1"></span> Menyinkronkan...
                            </span>
                        </button>
                    </div>
                </div>

                {{-- KARTU INFORMASI --}}
                <div class="card">
                    <div class="card-header bg-info bg-opacity-10">
                        <h5 class="mb-0"><i class="ti ti-help-circle me-1"></i> Informasi</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2"><i class="ti ti-check text-success me-1"></i> Data diambil dari API BMKG resmi</li>
                            <li class="mb-2"><i class="ti ti-check text-success me-1"></i> Update setiap 3 jam sekali (06:00, 12:00, 18:00 WIB)</li>
                            <li class="mb-2"><i class="ti ti-check text-success me-1"></i> Data disimpan ke tabel <code>prakiraan_cuaca</code></li>
                            <li><i class="ti ti-check text-success me-1"></i> Log disimpan di <code>storage/logs/bmkg-sync.log</code></li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- KANAN: LOG --}}
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="ti ti-file-text me-1"></i> Log Sinkronisasi</h5>
                        @if(count($logSinkronisasi) > 0)
                        <button wire:click="hapusLog" class="btn btn-sm btn-outline-light">
                            <i class="ti ti-trash me-1"></i> Hapus Log
                        </button>
                        @endif
                    </div>
                    <div class="card-body">
                        @if(count($logSinkronisasi) > 0)
                        <div class="bg-dark text-light p-3 rounded" style="max-height: 500px; overflow-y: auto; font-family: 'Courier New', monospace; font-size: 0.8rem;">
                            @foreach($logSinkronisasi as $line)
                                @php
                                    $class = 'text-light';
                                    if (str_contains($line, '[ERROR]')) $class = 'text-danger';
                                    elseif (str_contains($line, '[SUCCESS]')) $class = 'text-success';
                                    elseif (str_contains($line, '[WARNING]')) $class = 'text-warning';
                                    elseif (str_contains($line, '[INFO]')) $class = 'text-info';
                                @endphp
                                <div class="{{ $class }}">{{ trim($line) }}</div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-4 text-muted">
                            <i class="ti ti-notes fs-1"></i>
                            <p class="mt-2">Belum ada log sinkronisasi.</p>
                            <p class="small">Log akan muncul setelah sinkronisasi pertama.</p>
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