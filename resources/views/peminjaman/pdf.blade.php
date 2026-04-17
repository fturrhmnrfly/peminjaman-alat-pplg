<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Ruang Alat</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            font-size: 12px;
        }

        .header {
            margin-bottom: 18px;
        }

        .title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .subtitle {
            font-size: 11px;
            color: #4b5563;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #e5e7eb;
            font-size: 11px;
        }

        .muted {
            color: #6b7280;
            font-size: 10px;
        }

        .danger {
            color: #b91c1c;
            font-weight: 700;
        }

        .success {
            color: #065f46;
            font-weight: 700;
        }

        .item {
            margin-bottom: 4px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">Laporan Data Peminjaman</div>
        <div class="subtitle">Dicetak pada {{ $printedAt->format('d/m/Y H:i') }} WIB</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Tanggal</th>
                <th>Denda</th>
                <th>Status</th>
                <th>Detail Alat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div>{{ $row->user->nama ?? '-' }}</div>
                        <div class="muted">{{ $row->user->email ?? '-' }}</div>
                    </td>
                    <td>
                        <div>Pinjam: {{ optional($row->tanggal_pinjam)->format('d/m/Y') ?? '-' }}</div>
                        <div>Kembali: {{ optional($row->tanggal_kembali)->format('d/m/Y') ?? '-' }}</div>
                        <div class="muted">Pengajuan: {{ optional($row->waktu_pengajuan_pengembalian)->format('d/m/Y H:i') ?? '-' }} WIB</div>
                    </td>
                    <td>
                        @if($row->total_denda > 0)
                            <div class="danger">{{ $row->total_denda_formatted }}</div>
                            <div class="muted">Terlambat: {{ $row->denda_formatted }}</div>
                            <div class="muted">Kerusakan: {{ $row->denda_kerusakan_total_formatted }}</div>
                        @else
                            <div class="success">Tidak ada</div>
                        @endif
                    </td>
                    <td>{{ ucfirst(str_replace('_', ' ', $row->status ?? '-')) }}</td>
                    <td>
                        @forelse($row->detailPeminjamans as $detail)
                            <div class="item">
                                {{ $detail->alatUnit?->alat?->nama_alat ?? $detail->alat->nama_alat ?? '-' }}
                                ({{ $detail->alatUnit?->kode_unik ?? '-' }})
                            </div>
                        @empty
                            <div>Tidak ada detail alat</div>
                        @endforelse
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Belum ada data peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>

