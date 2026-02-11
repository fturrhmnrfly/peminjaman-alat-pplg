<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman</title>
    @vite(['resources/css/petugas-sidebar.css', 'resources/js/app.js'])
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f5f7fb; }
        .layout { display: flex; min-height: 100vh; }
        .logout-btn { background: #ef4444; color: white; padding: 8px 16px; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; width: 100%; transition: 0.3s; }
        .logout-btn:hover { background: #dc2626; }
        .main { flex: 1; padding: 30px; }
        .topbar { background: white; padding: 18px 25px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05); display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .user-info { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 42px; height: 42px; border-radius: 50%; background: linear-gradient(135deg, #facc15, #fde68a); color: #1e3a8a; font-weight: 600; display: flex; align-items: center; justify-content: center; }
        .content-card { background: white; padding: 25px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05); }
        .section-title { font-size: 22px; color: #1f2937; margin-bottom: 8px; }
        .section-desc { color: #6b7280; margin-bottom: 20px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; color: #374151; margin-bottom: 6px; }
        .form-group input, .form-group select { width: 100%; padding: 10px 12px; border-radius: 10px; border: 1px solid #e5e7eb; outline: none; }
        .actions { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px; }
        .btn { border: none; border-radius: 10px; padding: 10px 16px; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; }
        .btn-primary { background: #1e40af; color: white; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-outline { background: transparent; color: #1e40af; border: 1px solid #c7d2fe; }
        .btn-outline:hover { background: #eef2ff; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table thead { background: #f9fafb; }
        .table th, .table td { padding: 12px; border-bottom: 1px solid #f3f4f6; text-align: left; vertical-align: top; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
        .badge-pending { background: #e0e7ff; color: #3730a3; }
        .badge-disetujui { background: #d1fae5; color: #065f46; }
        .badge-ditolak { background: #fee2e2; color: #991b1b; }
        .badge-dikembalikan { background: #fef3c7; color: #92400e; }
    </style>
</head>
<body>
    <div class="layout">

        <x-petugas-sidebar></x-petugas-sidebar>

        <main class="main">
            <div class="topbar">
                <strong>Laporan Peminjaman</strong>
                <div class="user-info">
                    <div class="user-avatar">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</div>
                    <span>{{ auth()->user()->nama }}</span>
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
                                <option value="dikembalikan" @selected($filters['status'] === 'dikembalikan')>Dikembalikan</option>
                            </select>
                        </div>
                    </div>

                    <div class="actions">
                        <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                        <a href="{{ route('petugas.laporan') }}" class="btn btn-outline">Reset</a>
                        <button type="button" class="btn btn-outline" onclick="window.print()">Cetak Halaman</button>
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
                                    @php
                                        $status = strtolower($row->status ?? 'pending');
                                        $badgeClass = 'badge-pending';
                                        if ($status === 'disetujui') $badgeClass = 'badge-disetujui';
                                        elseif ($status === 'ditolak') $badgeClass = 'badge-ditolak';
                                        elseif ($status === 'dikembalikan') $badgeClass = 'badge-dikembalikan';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                </td>
                                <td>
                                    @forelse($row->detailPeminjamans as $detail)
                                        <div>{{ $detail->alat->nama_alat ?? '-' }} (x{{ $detail->jumlah_pinjam }})</div>
                                    @empty
                                        <div style="color:#9ca3af;">Tidak ada detail alat</div>
                                    @endforelse
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center;padding:30px;color:#9ca3af;">
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
