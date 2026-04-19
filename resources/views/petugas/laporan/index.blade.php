<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruang Alat</title>
    @vite(['resources/css/petugas-sidebar.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: var(--petugas-page-bg); }
        .layout { display: flex; min-height: 100vh; }
        .logout-btn { background: #ef4444; color: white; padding: 8px 16px; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; width: 100%; transition: 0.3s; }
        .logout-btn:hover { background: #dc2626; }
        .main { flex: 1; padding: 30px; }
        .topbar { background: white; padding: 18px 25px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05); display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .user-info { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 42px; height: 42px; border-radius: 50%; background: linear-gradient(135deg, var(--petugas-avatar-start), var(--petugas-avatar-end)); color: var(--petugas-avatar-text); font-weight: 600; display: flex; align-items: center; justify-content: center; }
        .content-card { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05); }
        .section-title { font-size: 22px; color: #1f2937; margin-bottom: 8px; }
        .section-desc { color: #6b7280; margin-bottom: 20px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; color: #374151; margin-bottom: 6px; }
        .form-group input, .form-group select { width: 100%; padding: 10px 12px; border-radius: 10px; border: 1px solid #e5e7eb; outline: none; }
        .actions { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px; }
        .btn { border: none; border-radius: 10px; padding: 10px 16px; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; }
        .btn-primary { background: var(--petugas-accent); color: white; }
        .btn-primary:hover { background: var(--petugas-accent-strong); }
        .btn-outline { background: transparent; color: var(--petugas-accent); border: 1px solid var(--petugas-accent-soft-2); }
        .btn-outline:hover { background: #edf4f2; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table thead { background: #f9fafb; }
        .table th, .table td { padding: 12px; border-bottom: 1px solid #f3f4f6; text-align: left; vertical-align: top; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
        .badge-pending { background: #e0e7ff; color: #3730a3; }
        .badge-disetujui { background: #d1fae5; color: #065f46; }
        .badge-ditolak { background: #fee2e2; color: #991b1b; }
        .badge-dikembalikan { background: #fef3c7; color: #92400e; }

        @media print {
            body {
                background: white;
            }

            .layout {
                display: block;
            }

            .sidebar,
            .topbar,
            .actions {
                display: none !important;
            }

            .main {
                padding: 0;
            }

            .content-card {
                box-shadow: none;
                border-radius: 0;
                padding: 0;
            }

            .section-title {
                font-size: 20px;
                margin-bottom: 6px;
            }

            .section-desc {
                margin-bottom: 14px;
            }

            #filterForm {
                margin-bottom: 12px;
            }

            .form-grid {
                display: flex;
                gap: 18px;
                margin-bottom: 12px;
            }

            .form-group input,
            .form-group select {
                border: none;
                padding: 0;
                background: transparent;
                color: #111827;
            }

            .table {
                margin-top: 12px;
                font-size: 12px;
            }

            .table th,
            .table td {
                padding: 8px 6px;
            }

            .badge {
                border: 1px solid #d1d5db;
                color: #111827 !important;
                background: transparent !important;
            }
        }
    </style>
</head>
<body>
    <div class="layout">

        <x-petugas-sidebar></x-petugas-sidebar>

        <main class="main">
            <div class="topbar">
                <strong>Laporan Peminjaman</strong>
                <div class="user-info">
                    <x-notification-bell />
                    <x-profile-shortcut />
                </div>
            </div>

            <div class="content-card">
                <h2 class="section-title">Filter Laporan</h2>
                <p class="section-desc">Atur periode dan status, lalu cetak atau unduh CSV.</p>

                <form method="GET" action="{{ route('petugas.laporan') }}" id="filterForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="from_date">Dari Tanggal</label>
                            <input id="from_date" name="from_date" type="date" value="{{ $filters['from_date'] }}">
                        </div>
                        <div class="form-group">
                            <label for="to_date">Sampai Tanggal</label>
                            <input id="to_date" name="to_date" type="date" value="{{ $filters['to_date'] }}">
                        </div>
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status">
                                <option value="">Semua</option>
                                <option value="pending" @selected($filters['status'] === 'pending')>Pending</option>
                                <option value="disetujui" @selected($filters['status'] === 'disetujui')>Disetujui</option>
                                <option value="ditolak" @selected($filters['status'] === 'ditolak')>Ditolak</option>
                                <option value="pengembalian_pending" @selected($filters['status'] === 'pengembalian_pending')>Menunggu Konfirmasi Petugas</option>
                                <option value="menunggu_pemeriksaan" @selected($filters['status'] === 'menunggu_pemeriksaan')>Menunggu Pemeriksaan</option>
                                <option value="menunggu_pembayaran" @selected($filters['status'] === 'menunggu_pembayaran')>Menunggu Pembayaran Denda</option>
                                <option value="dikembalikan" @selected($filters['status'] === 'dikembalikan')>Dikembalikan</option>
                            </select>
                        </div>
                    </div>

                    <div class="actions">
                        <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                        <a href="{{ route('petugas.laporan') }}" class="btn btn-outline">Reset</a>
                        <button type="button" class="btn btn-outline" onclick="window.print()">Cetak Halaman</button>
                        <a
                            class="btn btn-primary"
                            href="{{ route('petugas.laporan.export-pdf', $filters) }}"
                        >Unduh PDF</a>
                        <a
                            class="btn btn-outline"
                            href="{{ route('petugas.laporan', array_merge($filters, ['export' => 'csv'])) }}"
                        >Unduh CSV</a>
                    </div>
                </form>

                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Peminjam</th>
                            <th>Tanggal</th>
                            <th>Denda</th>
                            <th>Status</th>
                            <th>Detail Alat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $row)
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td>
                                    <strong>{{ $row->user->nama ?? '-' }}</strong>
                                    <div style="color:#6b7280;font-size:12px;">{{ $row->user->username ?? '-' }}</div>
                                </td>
                                <td>
                                    <div>Pinjam: {{ optional($row->tanggal_pinjam)->format('d/m/Y') ?? '-' }}</div>
                                    <div>Kembali: {{ optional($row->tanggal_kembali)->format('d/m/Y') ?? '-' }}</div>
                                </td>
                                <td>
                                    @if($row->total_denda > 0)
                                        <strong style="color:#b91c1c;">{{ $row->total_denda_formatted }}</strong>
                                        <div style="color:#6b7280;font-size:12px;">
                                            Terlambat: {{ $row->denda_formatted }} ({{ $row->jumlah_hari_terlambat }} kali) | Kerusakan: {{ $row->denda_kerusakan_total_formatted }}
                                        </div>
                                    @else
                                        <span style="color:#065f46;font-weight:600;">Tidak ada</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                    $status = strtolower($row->status ?? 'pending');
                                    $badgeClass = 'badge-pending';
                                    if ($status === 'disetujui') $badgeClass = 'badge-disetujui';
                                    elseif ($status === 'ditolak') $badgeClass = 'badge-ditolak';
                                    elseif ($status === 'pengembalian_pending') $badgeClass = 'badge-pending';
                                    elseif ($status === 'menunggu_pemeriksaan') $badgeClass = 'badge-pending';
                                    elseif ($status === 'menunggu_pembayaran') $badgeClass = 'badge-pending';
                                    elseif ($status === 'dikembalikan') $badgeClass = 'badge-dikembalikan';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $row->status_label }}</span>
                                </td>
                                <td>
                                    @forelse($row->detailPeminjamans as $detail)
                                        <div>
                                            {{ $detail->alatUnit?->alat?->nama_alat ?? $detail->alat->nama_alat ?? '-' }}
                                            ({{ $detail->alatUnit?->kode_unik ?? '-' }})
                                        </div>
                                    @empty
                                        <div style="color:#9ca3af;">Tidak ada detail alat</div>
                                    @endforelse
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center;padding:30px;color:#9ca3af;">
                                    Tidak ada data untuk filter ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>

