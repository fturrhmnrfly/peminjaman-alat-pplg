<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Ruang Alat</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111827;
            margin: 18px;
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
            color: #4b5563;
            font-size: 11px;
            margin-bottom: 8px;
        }

        .meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .meta td {
            padding: 4px 0;
            vertical-align: top;
        }

        .meta-label {
            width: 120px;
            color: #6b7280;
        }

        table.report {
            width: 100%;
            border-collapse: collapse;
        }

        .report th,
        .report td {
            border: 1px solid #d1d5db;
            padding: 8px;
            vertical-align: top;
            text-align: left;
        }

        .report thead th {
            background: #e5e7eb;
            font-weight: 700;
        }

        .muted {
            color: #6b7280;
            font-size: 10px;
        }

        .empty {
            text-align: center;
            color: #6b7280;
            padding: 18px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">Laporan Peminjaman Petugas</div>
        <div class="subtitle">Ruang Alat</div>
    </div>

    <table class="meta">
        <tr>
            <td class="meta-label">Dicetak pada</td>
            <td>: {{ $printedAt->format('d/m/Y H:i') }} WIB</td>
        </tr>
        <tr>
            <td class="meta-label">Dari tanggal</td>
            <td>: {{ $filters['from_date'] ? \Carbon\Carbon::parse($filters['from_date'])->format('d/m/Y') : 'Semua' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Sampai tanggal</td>
            <td>: {{ $filters['to_date'] ? \Carbon\Carbon::parse($filters['to_date'])->format('d/m/Y') : 'Semua' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Status</td>
            <td>: {{ $filters['status'] ? ucfirst(str_replace('_', ' ', $filters['status'])) : 'Semua status' }}</td>
        </tr>
    </table>

    <table class="report">
        <thead>
            <tr>
                <th style="width: 5%;">ID</th>
                <th style="width: 18%;">Peminjam</th>
                <th style="width: 14%;">Tanggal</th>
                <th style="width: 14%;">Denda</th>
                <th style="width: 13%;">Status</th>
                <th style="width: 36%;">Detail Alat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    <td>{{ $row->id }}</td>
                    <td>
                        <strong>{{ $row->user->nama ?? '-' }}</strong><br>
                        <span class="muted">{{ $row->user->username ?? '-' }}</span>
                    </td>
                    <td>
                        Pinjam: {{ optional($row->tanggal_pinjam)->format('d/m/Y') ?? '-' }}<br>
                        Kembali: {{ optional($row->tanggal_kembali)->format('d/m/Y') ?? '-' }}
                    </td>
                    <td>
                        @if($row->total_denda > 0)
                            <strong>{{ $row->total_denda_formatted }}</strong><br>
                            <span class="muted">
                                Terlambat: {{ $row->denda_formatted }} |
                                Kerusakan: {{ $row->denda_kerusakan_total_formatted }}
                            </span>
                        @else
                            Tidak ada
                        @endif
                    </td>
                    <td>{{ $row->status_label }}</td>
                    <td>
                        @forelse($row->detailPeminjamans as $detail)
                            {{ $detail->alatUnit?->alat?->nama_alat ?? $detail->alat?->nama_alat ?? '-' }}
                            ({{ $detail->alatUnit?->kode_unik ?? '-' }})<br>
                        @empty
                            <span class="muted">Tidak ada detail alat</span>
                        @endforelse
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty">Tidak ada data untuk filter ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
