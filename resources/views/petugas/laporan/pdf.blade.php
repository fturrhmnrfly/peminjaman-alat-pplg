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
    @include('reports.partials.header', ['title' => 'Laporan Peminjaman Alat'])
    @include('reports.partials.meta-table', [
        'rows' => [
            ['label' => 'Dicetak pada', 'value' => $printedAt->format('d/m/Y H:i') . ' WIB'],
            ['label' => 'Dari tanggal', 'value' => $filters['from_date'] ? \Carbon\Carbon::parse($filters['from_date'])->format('d/m/Y') : 'Semua'],
            ['label' => 'Sampai tanggal', 'value' => $filters['to_date'] ? \Carbon\Carbon::parse($filters['to_date'])->format('d/m/Y') : 'Semua'],
            ['label' => 'Status', 'value' => $filters['status'] ? ucfirst(str_replace('_', ' ', $filters['status'])) : 'Semua status'],
        ],
    ])

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
                    <td>{{ $row->id }}</td>Signing in to github.com...
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
                            <strong class="danger">{{ $row->total_denda_formatted }}</strong><br>
                            <span class="muted">
                                Terlambat: {{ $row->denda_formatted }} |
                                Kerusakan: {{ $row->denda_kerusakan_total_formatted }}
                            </span>
                        @else
                            <span class="success">Tidak ada</span>
                        @endif
                    </td>
                    <td>{{ $row->status_label }}</td>
                    <td>
                        @forelse($row->detailPeminjamans as $detail)
                            <div class="item">
                                {{ $detail->alatUnit?->alat?->nama_alat ?? $detail->alat?->nama_alat ?? '-' }}
                                ({{ $detail->alatUnit?->kode_unik ?? '-' }})
                            </div>
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
