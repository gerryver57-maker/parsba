<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Panen</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 25px;
            color: #000;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
            letter-spacing: 1px;
        }

        .header h3 {
            margin: 3px 0;
            font-size: 14px;
        }

        .header p {
            margin: 2px 0;
            font-size: 10px;
        }

        .judul {
            text-align: center;
            margin-top: 10px;
            margin-bottom: 15px;
        }

        .judul h4 {
            margin: 0;
            font-size: 13px;
            text-transform: uppercase;
        }

        .filter {
            text-align: center;
            font-size: 11px;
            margin-top: 5px;
        }

        .info {
            margin-bottom: 15px;
            width: 100%;
        }

        .info td {
            padding: 3px 6px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data th {
            border: 1px solid #000;
            background-color: #2e7d32;
            color: #fff;
            padding: 6px;
            font-size: 10px;
        }

        table.data td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 10px;
        }

        table.data tr:nth-child(even) {
            background-color: #f4f4f4;
        }

        .center { text-align: center; }
        .right { text-align: right; }

        .total-row {
            background-color: #d0e7d2;
            font-weight: bold;
        }

        .footer {
            margin-top: 40px;
            width: 100%;
        }

        .ttd {
            width: 200px;
            text-align: center;
            float: right;
        }

        .ttd p {
            margin: 5px 0;
        }

        .clear {
            clear: both;
        }
    </style>
</head>
<body>

    {{-- HEADER / KOP --}}
    <div class="header">
        <h2>PEMERINTAH KABUPATEN PASAMAN</h2>
        <h3>SISTEM INFORMASI PERTANIAN PADI (PARSBA)</h3>
        <p>Nagari Bahagia Padang Gelugua, Kecamatan Padang Gelugur</p>
    </div>

    {{-- JUDUL --}}
    <div class="judul">
        <h4>LAPORAN HASIL PANEN PADI</h4>
        <div class="filter">
            Tahun: <strong>{{ $filterTahun }}</strong> |
            Bulan: <strong>{{ $bulanText }}</strong> |
            Kualitas: <strong>{{ $filterKualitas ? ucfirst($filterKualitas) : 'Semua' }}</strong>
        </div>
    </div>

    {{-- INFO --}}
    <table class="info">
        <tr>
            <td width="20%"><strong>Total Panen</strong></td>
            <td width="30%">: {{ $totalPanen }} kali</td>
            <td width="20%"><strong>Total Hasil</strong></td>
            <td width="30%">: {{ number_format($totalJumlah, 1) }} Ton</td>
        </tr>
        <tr>
            <td><strong>Rata-rata</strong></td>
            <td>: {{ number_format($rataHasil, 1) }} Ton/panen</td>
            <td><strong>Panen Terbaik</strong></td>
            <td>: {{ number_format($panenTerbaik, 1) }} Ton</td>
        </tr>
    </table>

    {{-- TABEL --}}
    <table class="data">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Tanggal</th>
                <th>Petani</th>
                <th>Lahan</th>
                <th>Varietas</th>
                <th width="12%">Jumlah</th>
                <th width="10%">Kualitas</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($panen as $i => $item)
            <tr>
                <td class="center">{{ $i+1 }}</td>
                <td class="center">{{ \Carbon\Carbon::parse($item->tanggal_panen)->format('d/m/Y') }}</td>
                <td>{{ $item->siklusTanam->petani->nama ?? '-' }}</td>
                <td>{{ $item->siklusTanam->lahan->nama ?? '-' }}</td>
                <td>{{ $item->siklusTanam->varietasPadi->nama ?? '-' }}</td>
                <td class="right">{{ number_format($item->jumlah, 1) }} Ton</td>
                <td class="center">{{ ucfirst($item->kualitas) }}</td>
                <td>{{ \Illuminate\Support\Str::limit($item->catatan ?? '-', 30) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="center">Tidak ada data panen</td>
            </tr>
            @endforelse
        </tbody>

        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="right">TOTAL</td>
                <td class="right">{{ number_format($totalJumlah, 1) }} Ton</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    {{-- TTD --}}
    <div class="footer">
        <div class="ttd">
            <p>Padang Gelugur, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p><strong>Admin PARSBA</strong></p>
            <br><br><br>
            <p>(______________________)</p>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>