<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-success text-white">
            <h3 class="text-center">Kalender Manajemen Pertanian Padi</h3>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="variety" class="form-label">Pilih Varietas Padi:</label>
                    <select wire:model="selectedVariety" id="variety" class="form-select">
                        @foreach($varieties as $name => $data)
                        <option value="{{ $name }}">{{ $name }} ({{ $data['total'] }} hari)</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-9">
                            <label for="plantingDate" class="form-label">Tanggal Tanam:</label>
                            <input type="date" wire:model="plantingDate" id="plantingDate" class="form-control">
                        </div>
                        <div class="col-3 d-flex align-items-end">
                            <button wire:click="changePlantingData" class="btn btn-primary w-100">
                                <i class="fas fa-sync-alt"></i> Ubah
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-success">
                        <tr>
                            <th>Hari ke-</th>
                            <th>Tanggal</th>
                            <th>Fase</th>
                            <th>Kegiatan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($days as $day)
                        <tr class="{{ $this->getPhaseColor($day['phase']) }}">
                            <td>{{ $day['dayCount'] }}</td>
                            <td>
                                {{ $day['date']->format('d M Y') }}
                                @if($day['isToday'])
                                 <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalHari1">Hari ini</button>
                                @elseif($day['isTomorrow'])
                                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalHari2">Besok</button>
                                @elseif($day['isDayAfterTomorrow'])
                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalHari3">Lusa</button>

                                @endif
                            </td>
                            <td>
                                @php
                                $icons = [
                                'persemaian' => '🌱',
                                'vegetatif' => '🌿',
                                'pembungaan' => '🌸',
                                'pengisian_gabah' => '🌾',
                                'pemasakan' => '🟫',
                                'panen' => '✂️'
                                ];
                                @endphp
                                <span style="font-size: 1.5em;">{{ $icons[$day['phase']] }}</span>
                                {{ ucfirst(str_replace('_', ' ', $day['phase'])) }}
                            </td>
                            <td>{{ $this->getActivityRecommendation($day['phase'], $day['dayCount']) }}</td>
                            <td>
                                @if($day['date']->isPast())
                                <span class="badge bg-secondary">Selesai</span>
                                @elseif($day['isToday'])
                                <span class="badge bg-primary">Berlangsung</span>
                                @else
                                <span class="badge bg-light text-dark">Akan Datang</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Keterangan Fase -->
    <div class="card mt-4 shadow">
        <div class="card-header bg-info text-white">
            <h4 class="card-title">Keterangan Fase Pertumbuhan Padi</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h5 class="card-title">🌱 Fase Persemaian</h5>
                        </div>
                        <div class="card-body">
                            <p>Masa ketika benih padi disemai hingga siap ditanam di sawah (biasanya 5-7 hari).</p>
                            <ul>
                                <li>Pemilihan benih berkualitas</li>
                                <li>Penyiapan media semai</li>
                                <li>Penyiraman teratur</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h5 class="card-title">🌿 Fase Vegetatif</h5>
                        </div>
                        <div class="card-body">
                            <p>Masa pertumbuhan tanaman sejak ditanam hingga mulai berbunga (25-35 hari).</p>
                            <ul>
                                <li>Pemupukan dasar</li>
                                <li>Pengendalian gulma</li>
                                <li>Pengairan berselang</li>
                                <li>Pengendalian hama</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h5 class="card-title">🌸 Fase Pembungaan</h5>
                        </div>
                        <div class="card-body">
                            <p>Masa ketika tanaman mulai berbunga hingga selesai (8-12 hari).</p>
                            <ul>
                                <li>Pemupukan susulan</li>
                                <li>Menjaga kelembaban</li>
                                <li>Pengendalian hama</li>
                                <li>Hindari kekeringan</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h5 class="card-title">🌾 Fase Pengisian Gabah</h5>
                        </div>
                        <div class="card-body">
                            <p>Masa pengisian bulir gabah setelah pembungaan (12-18 hari).</p>
                            <ul>
                                <li>Pengairan cukup</li>
                                <li>Pengendalian hama</li>
                                <li>Pemantauan penyakit</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h5 class="card-title">🟫 Fase Pemasakan</h5>
                        </div>
                        <div class="card-body">
                            <p>Masa pemasakan gabah hingga siap panen (15-25 hari).</p>
                            <ul>
                                <li>Pengeringan lahan</li>
                                <li>Pengawasan burung</li>
                                <li>Persiapan panen</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header bg-light">
                            <h5 class="card-title">✂️ Fase Panen</h5>
                        </div>
                        <div class="card-body">
                            <p>Masa panen ketika 90% gabah sudah menguning.</p>
                            <ul>
                                <li>Penentuan waktu panen tepat</li>
                                <li>Peralatan panen siap</li>
                                <li>Penanganan pascapanen</li>
                                <li>Pengolahan hasil</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Legenda -->
    <div class="card mt-4 shadow">
        <div class="card-header bg-secondary text-white">
            <h4 class="card-title">Legenda</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <h5>Status Hari:</h5>
                    <p><span class="badge bg-primary">Hari Ini</span> - Tanggal saat ini</p>
                    <p><span class="badge bg-info">Besok</span> - Tanggal besok</p>
                    <p><span class="badge bg-warning text-dark">Lusa</span> - Tanggal lusa</p>
                </div>
                <div class="col-md-4">
                    <h5>Status Kegiatan:</h5>
                    <p><span class="badge bg-secondary">Selesai</span> - Kegiatan telah lewat</p>
                    <p><span class="badge bg-primary">Berlangsung</span> - Kegiatan hari ini</p>
                    <p><span class="badge bg-light text-dark">Akan Datang</span> - Kegiatan mendatang</p>
                </div>
                <div class="col-md-4">
                    <h5>Ikon Fase:</h5>
                    <p>🌱 - Persemaian</p>
                    <p>🌿 - Vegetatif</p>
                    <p>🌸 - Pembungaan</p>
                    <p>🌾 - Pengisian Gabah</p>
                    <p>🟫 - Pemasakan</p>
                    <p>✂️ - Panen</p>
                </div>
            </div>
        </div>
    </div>
    <?php
$api_url = "https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=31.71.01.1001";
$response_body = @file_get_contents($api_url);

if ($response_body === false) {
    die("ERROR: Gagal mengambil data.");
}

$data = json_decode($response_body, true);
if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    die("ERROR: Data bukan format JSON yang valid. " . htmlspecialchars(json_last_error_msg()));
}

    // Loop untuk 3 modal
    for ($i = 0; $i < 3; $i++) {
        $hariIndex = $i + 1;
        $modalId = "modalHari$hariIndex";
?>
    <div class="modal fade" id="{{$modalId}}" tabindex="-1" aria-labelledby="{{$modalId}}Label" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Prakiraan Cuaca Hari ke-$hariIndex</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <?php
                 if (isset($data["data"][0]["cuaca"][$i])) {
            echo "<ul class='list-group'>";
            foreach ($data["data"][0]["cuaca"][$i] as $prakiraan) {
                $jam = htmlspecialchars($prakiraan["local_datetime"] ?? "N/A");
                $desc = htmlspecialchars($prakiraan["weather_desc"] ?? "N/A");
                $img = isset($prakiraan["image"]) ? str_replace(" ", "%20", $prakiraan["image"]) : "";
                $t = htmlspecialchars($prakiraan["t"] ?? "N/A");
                $hu = htmlspecialchars($prakiraan["hu"] ?? "N/A");
                $ws = htmlspecialchars($prakiraan["ws"] ?? "N/A");
                $wd = htmlspecialchars($prakiraan["wd"] ?? "N/A");
                $vs = htmlspecialchars($prakiraan["vs_text"] ?? "N/A");

                echo "<li class='list-group-item'>";
                echo "<strong>Jam:</strong> $jam | <strong>Cuaca:</strong> $desc";
                if ($img && filter_var($img, FILTER_VALIDATE_URL)) {
                    echo " <img src='$img' alt='$desc' width='20' height='20'>";
                }
                echo " | <strong>Suhu:</strong> ".$t."°C | <strong>Kelembapan:</strong> $hu% | ";
                echo "<strong>Kec. Angin:</strong> $ws km/j | <strong>Arah:</strong> $wd | <strong>Jarak Pandang:</strong> $vs";
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>Data Hari ke-$hariIndex tidak tersedia.</p>";
        }}
        ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
</div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function() {
        // Inisialisasi datepicker jika diperlukan
    });
</script>
@endpush