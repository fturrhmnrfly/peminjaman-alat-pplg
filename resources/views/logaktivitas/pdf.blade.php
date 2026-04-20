<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Ruang Alat</title>
    <style>
        @include('reports.partials.pdf-report-styles')
    </style>
</head>

<body>
    @include('reports.partials.header', ['title' => 'Laporan Log Aktivitas'])
    @include('reports.partials.meta-table', [
        'rows' => [
            ['label' => 'Dicetak pada', 'value' => $printedAt->format('d/m/Y H:i') . ' WIB'],
            ['label' => 'Pencarian', 'value' => ($filters['search'] ?? '') !== '' ? $filters['search'] : 'Semua data'],
            ['label' => 'Modul', 'value' => ($filters['modul'] ?? '') !== '' ? $filters['modul'] : 'Semua modul'],
            ['label' => 'Aksi', 'value' => ($filters['aksi'] ?? '') !== '' ? $filters['aksi'] : 'Semua aksi'],
            ['label' => 'Periode', 'value' => (($filters['tanggal_dari'] ?? '') !== '' ? \Carbon\Carbon::parse($filters['tanggal_dari'])->format('d/m/Y') : 'Semua') . ' s.d. ' . (($filters['tanggal_sampai'] ?? '') !== '' ? \Carbon\Carbon::parse($filters['tanggal_sampai'])->format('d/m/Y') : 'Semua')],
        ],
    ])

    <table class="report">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 14%;">Waktu</th>
                <th style="width: 18%;">User</th>
                <th style="width: 9%;">Aksi</th>
                <th style="width: 11%;">Modul</th>
                <th style="width: 31%;">Keterangan</th>
                <th style="width: 12%;">IP Address</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $index => $log)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $log->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</td>
                    <td>
                        <div><strong>{{ $log->nama_user }}</strong></div>
                        <div class="muted">{{ $log->user->email ?? '-' }}</div>
                    </td>
                    <td><strong>{{ $log->aksi }}</strong></td>
                    <td>{{ $log->modul }}</td>
                    <td>{{ $log->keterangan ?? '-' }}</td>
                    <td>{{ $log->ip_address }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="empty">Tidak ada data log aktivitas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>

