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
            margin-bottom: 10px;
        }

        .filters {
            margin-bottom: 16px;
            padding: 10px 12px;
            background: #f3f4f6;
            border-radius: 8px;
        }

        .filters div {
            margin-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 8px;
            vertical-align: top;
            text-align: left;
        }

        th {
            background: #e5e7eb;
            font-size: 11px;
        }

        .muted {
            color: #6b7280;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="title">Laporan Log Aktivitas</div>
        <div class="subtitle">Dicetak pada {{ $printedAt->format('d/m/Y H:i') }} WIB</div>
    </div>

    <div class="filters">
        <div><strong>Pencarian:</strong> {{ $filters['search'] ?? 'Semua data' ?: 'Semua data' }}</div>
        <div><strong>Modul:</strong> {{ $filters['modul'] ?? 'Semua modul' ?: 'Semua modul' }}</div>
        <div><strong>Aksi:</strong> {{ $filters['aksi'] ?? 'Semua aksi' ?: 'Semua aksi' }}</div>
        <div><strong>Periode:</strong>
            {{ ($filters['tanggal_dari'] ?? '') ?: '-' }} s.d. {{ ($filters['tanggal_sampai'] ?? '') ?: '-' }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Waktu</th>
                <th>User</th>
                <th>Aksi</th>
                <th>Modul</th>
                <th>Keterangan</th>
                <th>IP Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $index => $log)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $log->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</td>
                    <td>
                        <div>{{ $log->nama_user }}</div>
                        <div class="muted">{{ $log->user->email ?? '-' }}</div>
                    </td>
                    <td>{{ $log->aksi }}</td>
                    <td>{{ $log->modul }}</td>
                    <td>{{ $log->keterangan ?? '-' }}</td>
                    <td>{{ $log->ip_address }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">Tidak ada data log aktivitas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>

