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
            ['label' => 'Dari tanggal', 'value' => 'Semua'],
            ['label' => 'Sampai tanggal', 'value' => 'Semua'],
            ['label' => 'Status', 'value' => 'Semua status'],
        ],
    ])

    <table class="report">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 18%;">Peminjam</th>
                <th style="width: 18%;">Tanggal</th>
                <th style="width: 15%;">Denda</th>
                <th style="width: 14%;">Status</th>
                <th style="width: 30%;">Detail Alat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <div><strong>{{ $row->user->nama ?? '-' }}</strong></div>
                        <div class="muted">{{ $row->user->username ?? $row->user->email ?? '-' }}</div>
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
                    <td>{{ $row->status_label }}</td>
                    <td>
                        @forelse($row->detailPeminjamans as $detail)
                            <div class="item">
                                {{ $detail->alatUnit?->alat?->nama_alat ?? $detail->alat?->nama_alat ?? '-' }}
                                ({{ $detail->alatUnit?->kode_unik ?? '-' }})
                            </div>
                        @empty
                            <div class="muted">Tidak ada detail alat</div>
                        @endforelse
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="empty">Belum ada data peminjaman.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>

