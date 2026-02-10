<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Peminjaman</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

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

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1e3a8a, #1e40af);
            color: white;
            padding: 25px 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 40px;
        }

        .sidebar-menu {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .sidebar-menu a {
            text-decoration: none;
            color: #e5e7eb;
            padding: 12px 15px;
            border-radius: 10px;
            transition: 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(250, 204, 21, 0.15);
            color: #fde68a;
        }

        .sidebar-footer {
            margin-top: auto;
            font-size: 13px;
            opacity: 0.8;
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
        }

        .header-action h2 {
            font-size: 22px;
            color: #1f2937;
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

        .actions {
            display: flex;
            gap: 8px;
        }

        .btn {
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-approve {
            background: #10b981;
            color: white;
        }

        .btn-approve:hover {
            background: #059669;
        }

        .btn-reject {
            background: #ef4444;
            color: white;
        }

        .btn-reject:hover {
            background: #dc2626;
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
    </style>
</head>

<body>

    <div class="layout">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-brand">📚 Peminjaman Alat</div>

            <nav class="sidebar-menu">
                <a href="{{ route('dashboard') }}">🏠 Dashboard</a>
                <a href="{{ route('verifikasi') }}" class="active">✅ Menyetujui Peminjaman</a>
                <a href="{{ route('petugas.laporan') }}">🧾 Mencetak Laporan</a>
                <a href="{{ route('petugas.pengembalian') }}">📦 Memantau Pengembalian</a>
            </nav>

            <form method="POST" action="{{ route('logout') }}" style="margin-top: auto;">
                @csrf
                <button type="submit" class="logout-btn">🚪 Logout</button>
            </form>

            <div class="sidebar-footer">
                © {{ date('Y') }} Sistem Sekolah
            </div>
        </aside>

        <!-- MAIN -->
        <main class="main">

            <!-- TOPBAR -->
            <div class="topbar">
                <strong>Verifikasi Peminjaman</strong>
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    </div>
                    <span>{{ auth()->user()->nama }}</span>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="content-card">

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
                    <h2>Daftar Pengajuan</h2>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Peminjam</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Detail Alat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $row->user->nama ?? '-' }}</strong>
                                <div style="color: #6b7280; font-size: 12px;">
                                    {{ $row->user->email ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <div>Pinjam: {{ optional($row->tanggal_pinjam)->format('d/m/Y') ?? '-' }}</div>
                                <div>Kembali: {{ optional($row->tanggal_kembali)->format('d/m/Y') ?? '-' }}</div>
                            </td>
                            <td>
                                @php
                                    $status = strtolower($row->status ?? 'menunggu');
                                    $badgeClass = 'badge-menunggu';
                                    if ($status === 'disetujui') $badgeClass = 'badge-disetujui';
                                    elseif ($status === 'ditolak') $badgeClass = 'badge-ditolak';
                                    elseif ($status === 'selesai') $badgeClass = 'badge-selesai';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                            </td>
                            <td>
                                <div class="item-list">
                                    @forelse($row->detailPeminjamans as $detail)
                                    <div class="item">
                                        <span class="item-name">{{ $detail->alat->nama_alat ?? '-' }}</span>
                                        <span class="item-qty">x{{ $detail->jumlah_pinjam }}</span>
                                    </div>
                                    @empty
                                    <div style="color: #9ca3af;">Tidak ada detail alat</div>
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                <div class="actions">
                                    <form method="POST" action="{{ route('peminjaman.setujui', $row->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-approve">Setujui</button>
                                    </form>
                                    <form method="POST" action="{{ route('peminjaman.tolak', $row->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-reject">Tolak</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #9ca3af;">
                                Tidak ada pengajuan menunggu
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
