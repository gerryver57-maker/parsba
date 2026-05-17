<div>
    <?php
// Get API URL
$api_url = "https://api.bmkg.go.id/publik/prakiraan-cuaca?adm4=13.08.17.2004";
$response_body = @file_get_contents($api_url);

// Check if fail
if ($response_body === false) {
    die("ERROR: Gagal mengambil data.");
}

// Decode String JSON
$data = json_decode($response_body, true);

if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    die(
        "ERROR: Data bukan format JSON yang valid. " .
            htmlspecialchars(json_last_error_msg())
    );
}

// Set header
header("Content-Type: text/html; charset=utf-8");
?>
    <div class="card">
        <div class="card-body">
            <table class="table table-borderless">
  <tbody>
            <?php
// Location
if (isset($data["lokasi"]["desa"]) && isset($data["lokasi"]["kecamatan"])) {
    echo "<h2>Desa/Kelurahan :" .
        htmlspecialchars($data["lokasi"]["desa"]) .
        "</h2>";
    echo "<p>";
    echo "<tr><td>Kecamatan</td><td>:</td><td>" .
        htmlspecialchars($data["lokasi"]["kecamatan"] ?? "N/A") .
        "<td></tr>";
    echo "<tr><td>Kota/Kab<td>:</td><td> " .
        htmlspecialchars($data["lokasi"]["kotkab"] ?? "N/A") .
        "<td></tr>";
    echo "<tr><td>Provinsi<td>:</td><td> " .
        htmlspecialchars($data["lokasi"]["provinsi"] ?? "N/A") .
        "<td></tr>";
    echo "<tr><td>Koordinat<td>:</td><td> Lat: " .
        htmlspecialchars($data["lokasi"]["lat"] ?? "N/A") .
        ", Lon: " .
        htmlspecialchars($data["lokasi"]["lon"] ?? "N/A") .
        "<td></tr>";
    echo "<tr><td>Timezone<td>:</td><td> " .
        htmlspecialchars($data["lokasi"]["timezone"] ?? "N/A") .
        "<td></tr>";
    echo "</p>";
} else {
    echo "<h2>Lokasi Tidak Ditemukan</h2>";
}
?>
    </tr>
</table>
<?php
// Weather forecast data
echo "<h3>Detail Prakiraan Cuaca:</h3>";

if (isset($data["data"][0]["cuaca"]) && is_array($data["data"][0]["cuaca"])) {
    foreach ($data["data"][0]["cuaca"] as $index_hari => $prakiraan_harian) {
        echo "<h4>Hari ke-" . ($index_hari + 1) . "</h4>";
?>
<table class="table table-hover">
  <thead>
    <tr>
      <th scope="col">Hari</th>
      <th scope="col">Jam</th>
      <th scope="col">Tanggal</th>
      <th scope="col">Cuaca</th>
      <th scope="col">Suhu</th>
      <th scope="col">Kelembapan</th>
      <th scope="col">Kecepatan Angin</th>
      <th scope="col">Arah Angin</th>
      <th scope="col">Jarak Pandang</th>
    </tr>
  </thead>
  <tbody>
<?php
        if (is_array($prakiraan_harian)) {
            foreach ($prakiraan_harian as $prakiraan) {
                $waktu_lokal = isset($prakiraan["local_datetime"])
                    ? htmlspecialchars($prakiraan["local_datetime"])
                    : "N/A";
                $deskripsi = isset($prakiraan["weather_desc"])
                    ? htmlspecialchars($prakiraan["weather_desc"])
                    : "N/A";
                $alt_text = isset($prakiraan["weather_desc"])
                    ? htmlspecialchars(
                        $prakiraan["weather_desc"],
                        ENT_QUOTES,
                        "UTF-8"
                    )
                    : "Ikon Cuaca";
                $suhu = isset($prakiraan["t"])
                    ? htmlspecialchars($prakiraan["t"])
                    : "N/A";
                $kelembapan = isset($prakiraan["hu"])
                    ? htmlspecialchars($prakiraan["hu"])
                    : "N/A";
                $kec_angin = isset($prakiraan["ws"])
                    ? htmlspecialchars($prakiraan["ws"])
                    : "N/A";
                $arah_angin = isset($prakiraan["wd"])
                    ? htmlspecialchars($prakiraan["wd"])
                    : "N/A";
                $jarak_pandang = isset($prakiraan["vs_text"])
                    ? htmlspecialchars($prakiraan["vs_text"])
                    : "N/A";

                $raw_img_url = isset($prakiraan["image"])
                    ? $prakiraan["image"]
                    : "";
                $img_url_processed = "";

                if (!empty($raw_img_url)) {
                    $img_url_processed = str_replace(" ", "%20", $raw_img_url);
                }

                $waktu=$waktu_lokal;
                list($tanggal1,$waktu1)=explode(" ",$waktu);
                    $tanggal2=$tanggal1;
                    $daftarHari = [
                        'Sunday'    => 'Minggu',
                        'Monday'    => 'Senin',
                        'Tuesday'   => 'Selasa',
                        'Wednesday' => 'Rabu',
                        'Thursday'  => 'Kamis',
                        'Friday'    => 'Jumat',
                        'Saturday'  => 'Sabtu'
                    ];
                    $tang=new DateTime($tanggal2);
                    $hariInggris=$tang->format('l');
                    $hariIndo=$daftarHari[$hariInggris];
                                
?>
  <tr>
      <td scope="row"><?php echo $hariIndo; ?></td>
      <td><?php echo $waktu1; ?></td>
      <td><?php echo $tanggal1; ?></td>
      <td><?php echo $deskripsi ?>

<?php
                if (
                    $img_url_processed &&
                    filter_var($img_url_processed, FILTER_VALIDATE_URL)
                ) {
                    echo '<img style="width: 20px; height: 20px; vertical-align: middle; margin-left: 5px;" src="' .
                        $img_url_processed .
                        '" alt="' .
                        $alt_text .
                        '" title="' .
                        $alt_text .
                        '"> </td>';
                }
                echo "<td>" . $suhu . "°C  </td>";
                echo "<td>" . $kelembapan . "%  </td>";
                echo "<td>" . $kec_angin . "km/j  </td>";
                echo "<td>" . $arah_angin . "  </td>";
                echo "<td>" . $jarak_pandang ."  </td>";
            }
?>
    </tr>
  </tbody>
</table>
<?php
        } else {
            echo "<li>Data tidak valid.</li>";
        }
    }
} else {
    echo "<p>Struktur data prakiraan cuaca tidak ditemukan.</p>";
}
?>

        </div>
    </div>
</div>
