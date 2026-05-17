<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Aktivitas</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            margin: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 2px solid #2e7d32;
            padding-bottom: 8px;
        }

        .header h3 {
            margin: 0;
            font-size: 14px;
            color: #2e7d32;
        }

        .header p {
            margin: 2px 0;
            font-size: 9px;
        }

        .header h4 {
            margin-top: 6px;
            font-size: 12px;
        }

        .info {
            margin-bottom: 10px;
            width: 100%;
        }

        .info td {
            padding: 3px 6px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data th {
            background: #2e7d32;
            color: #fff;
            padding: 5px;
            font-size: 9px;
        }

        table.data td {
            border: 1px solid #ccc;
            padding: 4px;
            font-size: 9px;
            vertical-align: top;
        }

        table.data tr:nth-child(even) {
            background: #f9f9f9;
        }

        .center { text-align: center; }
        .right { text-align: right; }

        .badge-selesai {
            color: green;
            font-weight: bold;
        }

        .badge-pending {
            color: orange;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
        }

        .ttd {
            display: inline-block;
            text-align: center;
            min-width: 180px;
        }

        /* supaya tidak kepotong */
        tr { page-break-inside: avoid; }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header">
        <h3>PARSBA - Sistem Informasi Pertanian</h3>
        <p>Nagari Bahagia Padang Gelugur, Kab. Pasaman</p>

        <h4>LAPORAN AKTIVITAS PERTANIAN</h4>
        <p>
            Tahun: {{ $filterTahun }} |
            Bulan: {{ $bulanText }} |
            Status: {{ $statusText }} |
            Jenis: {{ $jenisText }}
        </p>
    </div>

    {{-- INFO --}}
    <table class="info">
        <tr>
            <td><strong>Total:</strong> {{ $totalAktivitas }}</td>
            <td><strong>Selesai:</strong> {{ $totalSelesai }} ({{ $persentaseSelesai }}%)</td>
            <td><strong>Pending:</strong> {{ $totalPending }}</td>
        </tr>
    </table>

    {{-- TABEL --}}
    <table class="data">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th width="11%">Tanggal</th>
                <th width="14%">Petani</th>
                <th width="12%">Lahan</th>
                <th width="11%">Fase</th>
                <th width="10%">Jenis</th>
                <th width="15%">Detail</th>
                <th width="9%">Status</th>
                <th width="14%">Konfirmasi</th>
            </tr>
        </thead>

        <tbody>
        @forelse($aktivitas as $index => $item)
            <tr>
                <td class="center">{{ $index + 1 }}</td>

                <td class="center">
                    {{ \Carbon\Carbon::parse($item->tanggal_rekomendasi)->format('d/m/Y') }}<br>
                    {{ \Carbon\Carbon::parse($item->tanggal_rekomendasi)->format('H:i') }}
                </td>

                <td>{{ $item->siklusTanam->petani->nama ?? '-' }}</td>
                <td>{{ $item->siklusTanam->lahan->nama ?? '-' }}</td>
                <td>{{ $item->nama_fase }}</td>

                <td class="center">
                    @if($item->pupuk_id)
                        Pemupukan
                    @elseif($item->pestisida_id)
                        Penyemprotan
                    @else
                        Lainnya
                    @endif
                </td>

                <td>
                    @if($item->pupuk)
                        {{ $item->pupuk->nama }}
                        ({{ $item->dosis_dihitung }} {{ $item->pupuk->satuan }})
                    @elseif($item->pestisida)
                        {{ $item->pestisida->nama }}
                    @else
                        {{ \Illuminate\Support\Str::limit($item->deskripsi_aktivitas, 40) }}
                    @endif
                </td>

                <td class="center">
                    @if($item->sudah_dikonfirmasi)
                        <span class="badge-selesai">✓ Selesai</span>
                    @else
                        <span class="badge-pending">Pending</span>
                    @endif
                </td>

                <td class="center">
                    {{ $item->tanggal_konfirmasi 
                        ? \Carbon\Carbon::parse($item->tanggal_konfirmasi)->format('d/m/Y H:i') 
                        : '-' }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="center">Tidak ada data</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        <p>Padang Gelugur, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>

        <div class="ttd">
            <p><strong>Admin PARSBA</strong></p>
            <br><br><br>
            <p>(__________________)</p>
        </div>
    </div>

</body>
</html>