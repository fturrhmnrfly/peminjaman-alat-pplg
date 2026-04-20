<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruang Alat</title>
    @vite(['resources/css/admin-sidebar.css', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #f5f7fb;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* ===== MAIN CONTENT ===== */
        .main {
            flex: 1;
            padding: 30px;
        }

        .topbar {
            background: white;
            padding: 18px 25px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: linear-gradient(135deg, #facc15, #fde68a);
            color: #1e3a8a;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .content-card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .header-action {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 16px;
            flex-wrap: wrap;
        }

        .header-action h2 {
            font-size: 22px;
            color: #1f2937;
        }

        .header-copy p {
            margin-top: 6px;
            color: #6b7280;
            font-size: 14px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            border: none;
            border-radius: 12px;
            padding: 10px 16px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: #1d4ed8;
            color: white;
        }

        .btn-outline {
            background: white;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .alert {
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: #f9fafb;
        }

        table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: top;
        }

        table tbody tr:hover {
            background: #f9fafb;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .badge-menunggu {
            background: #e0e7ff;
            color: #3730a3;
        }

        .badge-disetujui {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-ditolak {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-selesai {
            background: #fef3c7;
            color: #92400e;
        }

        .item-list {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .item {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            background: #f9fafb;
            border-radius: 8px;
            padding: 8px 10px;
        }

        .item-name {
            font-weight: 600;
            color: #111827;
        }

        .item-qty {
            font-size: 12px;
            color: #6b7280;
            white-space: nowrap;
        }

        .text-muted {
            color: #6b7280;
            font-size: 12px;
        }

        .text-danger {
            color: #b91c1c;
            font-weight: 700;
        }

        .text-success {
            color: #065f46;
            font-weight: 700;
        }

        .meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        .meta td {
            padding: 4px 0;
            border: none;
            vertical-align: top;
        }

        .report-meta-label {
            width: 150px;
            color: #6b7280;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 20px;
        }

        .print-only {
            display: none;
        }

        .logout-btn {
            background: #ef4444;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: 0.3s;
            width: 100%;
        }

        .logout-btn:hover {
            background: #dc2626;
        }

        @media (max-width: 768px) {
            .header-action {
                flex-direction: column;
                align-items: flex-start;
            }

            .action-buttons {
                width: 100%;
            }

            .action-buttons .btn {
                width: 100%;
            }
        }

        @media print {
            body {
                background: white;
            }

            .layout {
                display: block;
            }

            x-admin-sidebar,
            .topbar,
            .action-buttons,
            .pagination,
            .screen-only {
                display: none !important;
            }

            .main {
                padding: 0;
            }

            .content-card {
                box-shadow: none;
                border: none;
                border-radius: 0;
                padding: 0;
            }

            .print-only {
                display: block;
                margin-bottom: 18px;
            }

            .print-only .title {
                font-size: 20px;
                font-weight: 700;
                color: #111827;
                margin-bottom: 4px;
            }

            .print-only .subtitle {
                color: #4b5563;
                font-size: 11px;
                margin-bottom: 24px;
            }

            table thead {
                background: #e5e7eb !important;
            }

            table th,
            table td {
                border: 1px solid #d1d5db;
                padding: 8px 10px;
                background: white !important;
            }

            table th {
                font-size: 11px;
                font-weight: 700;
                color: #111827;
            }

            table tbody tr:hover {
                background: transparent;
            }

            .badge {
                padding: 0;
                border-radius: 0;
                background: transparent !important;
                color: #111827 !important;
                font-size: 12px;
                font-weight: 400;
            }

            .item-list {
                gap: 4px;
            }

            .item {
                display: block;
                background: transparent;
                border-radius: 0;
                padding: 0;
            }

            .item-name,
            .item-qty {
                display: inline;
                font-weight: 400;
                color: #111827;
                font-size: 12px;
            }

            .item-name::after {
                content: " ";
            }

            .item-qty::before {
                content: "(";
            }

            .item-qty::after {
                content: ")";
            }
        }
    </style>
</head>

<body>

    <div class="layout">

        <!-- SIDEBAR - DARI COMPONENT -->
        <x-admin-sidebar />

        <!-- MAIN -->
        <main class="main">

            <!-- TOPBAR -->
            <div class="topbar">
                <strong>Data Peminjaman</strong>
                <div class="user-info">
                    <x-notification-bell />
                    <x-profile-shortcut />
                </div>
            </div>

            <!-- CONTENT -->
            <div class="content-card">
                <div class="print-only">
                    @include('reports.partials.header', ['title' => 'Laporan Peminjaman Alat'])
                    @include('reports.partials.meta-table', [
                        'rows' => [
                            ['label' => 'Dicetak pada', 'value' => now('Asia/Jakarta')->format('d/m/Y H:i') . ' WIB'],
                            ['label' => 'Dari tanggal', 'value' => 'Semua'],
                            ['label' => 'Sampai tanggal', 'value' => 'Semua'],
                            ['label' => 'Status', 'value' => 'Semua status'],
                        ],
                        'labelClass' => 'report-meta-label',
                    ])
                </div>

                <!-- Alert Messages -->
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
                @endif

                <div class="header-action">
                    <div class="header-copy">
                        <h2>Daftar Peminjaman</h2>
                        <p class="screen-only">Pantau data peminjaman, lihat denda, lalu cetak halaman atau unduh PDF sebagai laporan.</p>
                    </div>
                    <div class="action-buttons screen-only">
                        <button type="button" class="btn btn-outline" onclick="window.print()">Cetak Halaman</button>
                        <a href="{{ route('admin.peminjaman.export-pdf') }}" class="btn btn-primary">Unduh PDF</a>
                    </div>
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
                        @forelse($peminjaman as $index => $row)
                        <tr>
                            <td>{{ $peminjaman->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $row->user->nama ?? '-' }}</strong>
                                <div style="color: #6b7280; font-size: 12px;">
                                    {{ $row->user->username ?? $row->user->email ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <div>Pinjam: {{ optional($row->tanggal_pinjam)->format('d/m/Y') ?? '-' }}</div>
                                <div>Kembali: {{ optional($row->tanggal_kembali)->format('d/m/Y') ?? '-' }}</div>
                                <div class="text-muted">
                                    Pengajuan: {{ optional($row->waktu_pengajuan_pengembalian)->format('d/m/Y H:i') ?? '-' }} WIB
                                </div>
                            </td>
                            <td>
                                @if($row->total_denda > 0)
                                    <div class="text-danger">{{ $row->total_denda_formatted }}</div>
                                    <div class="text-muted">Terlambat: {{ $row->denda_formatted }}</div>
                                    <div class="text-muted">Kerusakan: {{ $row->denda_kerusakan_total_formatted }}</div>
                                @else
                                    <div class="text-success">Tidak ada</div>
                                @endif
                            </td>
                            <td>
                                @php
                                    $status = strtolower($row->status ?? 'menunggu');
                                    $badgeClass = 'badge-menunggu';
                                    if ($status === 'disetujui') $badgeClass = 'badge-disetujui';
                                    elseif ($status === 'ditolak') $badgeClass = 'badge-ditolak';
                                    elseif ($status === 'pengembalian_pending') $badgeClass = 'badge-menunggu';
                                    elseif ($status === 'menunggu_pemeriksaan') $badgeClass = 'badge-menunggu';
                                    elseif ($status === 'menunggu_pembayaran') $badgeClass = 'badge-menunggu';
                                    elseif ($status === 'selesai' || $status === 'dikembalikan') $badgeClass = 'badge-selesai';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $row->status_label }}</span>
                            </td>
                            <td>
                                <div class="item-list">
                                    @forelse($row->detailPeminjamans as $detail)
                                    <div class="item">
                                        <span class="item-name">{{ $detail->alatUnit?->alat?->nama_alat ?? $detail->alat?->nama_alat ?? '-' }}</span>
                                        <span class="item-qty">{{ $detail->alatUnit?->kode_unik ?? '-' }}</span>
                                    </div>
                                    @empty
                                    <div style="color: #9ca3af;">Tidak ada detail alat</div>
                                    @endforelse
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #9ca3af;">
                                Belum ada data peminjaman
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination">
                    {{ $peminjaman->links() }}
                </div>

            </div>

        </main>
    </div>

</body>

</html>

