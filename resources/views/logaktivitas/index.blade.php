<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas - Admin</title>
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
            color: #1f2937;
        }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .main {
            flex: 1;
            padding: 30px;
        }

        .topbar,
        .content-card,
        .filter-card,
        .table-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
        }

        .topbar {
            padding: 18px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .page-intro {
            margin-bottom: 20px;
        }

        .page-intro h1 {
            font-size: 28px;
            margin-bottom: 6px;
        }

        .page-intro p {
            color: #6b7280;
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
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 20px;
        }

        .summary-card {
            background: linear-gradient(135deg, #ffffff, #f8fafc);
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 16px 18px;
        }

        .summary-label {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 6px;
        }

        .summary-value {
            font-size: 24px;
            font-weight: 700;
        }

        .summary-note {
            color: #6b7280;
            font-size: 12px;
            margin-top: 4px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 16px;
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

        .filter-card {
            padding: 22px;
            margin-bottom: 20px;
        }

        .filter-head {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: center;
            margin-bottom: 18px;
        }

        .filter-head h2 {
            font-size: 20px;
        }

        .filter-head p {
            color: #6b7280;
            font-size: 14px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
            margin-bottom: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .form-control {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #dbe2ea;
            border-radius: 12px;
            font-size: 14px;
            background: #f8fafc;
            transition: 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .filter-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn {
            border: none;
            border-radius: 12px;
            padding: 10px 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: 0.2s ease;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: #1d4ed8;
            color: #fff;
        }

        .btn-secondary {
            background: #64748b;
            color: #fff;
        }

        .btn-success {
            background: #059669;
            color: #fff;
        }

        .btn-outline {
            background: #fff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }

        .table-card {
            overflow: hidden;
        }

        .table-head {
            padding: 18px 22px;
            border-bottom: 1px solid #eef2f7;
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: center;
        }

        .table-head h2 {
            font-size: 20px;
        }

        .table-head p {
            color: #6b7280;
            font-size: 14px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #eff6ff;
        }

        th,
        td {
            padding: 14px 16px;
            text-align: left;
            vertical-align: top;
            border-bottom: 1px solid #f1f5f9;
        }

        th {
            font-size: 13px;
            color: #334155;
        }

        tbody tr:hover {
            background: #fafcff;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .badge-login {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-logout,
        .badge-delete {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-create {
            background: #d1fae5;
            color: #047857;
        }

        .badge-update {
            background: #fef3c7;
            color: #b45309;
        }

        .badge-view {
            background: #e0e7ff;
            color: #4338ca;
        }

        .muted {
            color: #6b7280;
            font-size: 12px;
        }

        .empty-state {
            padding: 52px 24px;
            text-align: center;
        }

        .empty-state h3 {
            font-size: 20px;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #6b7280;
        }

        .pagination-wrap {
            padding: 16px 22px;
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
            width: 100%;
        }

        .logout-btn:hover {
            background: #dc2626;
        }

        @media (max-width: 768px) {
            .main {
                padding: 20px;
            }

            .topbar,
            .filter-head,
            .table-head {
                flex-direction: column;
                align-items: flex-start;
            }

            .filter-actions {
                flex-direction: column;
            }

            .filter-actions .btn {
                width: 100%;
            }
        }

        @media print {
            body {
                background: #fff;
            }

            .layout {
                display: block;
            }

            x-admin-sidebar,
            .topbar,
            .filter-card,
            .pagination-wrap,
            .screen-only {
                display: none !important;
            }

            .main {
                padding: 0;
            }

            .table-card,
            .summary-card {
                box-shadow: none;
                border: 1px solid #d1d5db;
            }

            .print-only {
                display: block;
                margin-bottom: 18px;
            }
        }
    </style>
</head>

<body>
    <div class="layout">
        <x-admin-sidebar />

        <main class="main">
            <div class="topbar screen-only">
                <strong>Log Aktivitas</strong>
                <div class="user-info">
                    <x-notification-bell />
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    </div>
                    <span>{{ auth()->user()->nama }}</span>
                </div>
            </div>

            <div class="page-intro">
                <h1>Log Aktivitas</h1>
                <p>Riwayat aktivitas pengguna sistem yang bisa difilter, dicetak, diexport CSV, atau diunduh sebagai PDF.</p>
            </div>

            <div class="print-only">
                <h2>Laporan Log Aktivitas</h2>
                <p class="muted">Dicetak pada {{ now('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            <div class="summary-grid">
                <div class="summary-card">
                    <div class="summary-label">Total Data</div>
                    <div class="summary-value">{{ number_format($summary['total']) }}</div>
                    <div class="summary-note">Sesuai filter yang aktif</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Aktivitas Hari Ini</div>
                    <div class="summary-value">{{ number_format($summary['today']) }}</div>
                    <div class="summary-note">Pada tanggal {{ now('Asia/Jakarta')->format('d/m/Y') }}</div>
                </div>
                <div class="summary-card">
                    <div class="summary-label">Aktivitas Terbaru</div>
                    <div class="summary-value" style="font-size: 18px;">
                        {{ optional($summary['latest'])->timezone('Asia/Jakarta')->format('d/m/Y H:i') ?? '-' }}
                    </div>
                    <div class="summary-note">Waktu log terakhir yang cocok</div>
                </div>
            </div>

            <div class="filter-card screen-only">
                <div class="filter-head">
                    <div>
                        <h2>Filter Laporan</h2>
                        <p>Gunakan filter di bawah untuk mempersempit data log yang ingin ditinjau atau dilaporkan.</p>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.log.index') }}">
                    <div class="filter-grid">
                        <div class="form-group" style="grid-column: span 2;">
                            <label for="search">Pencarian</label>
                            <input id="search" type="text" name="search" class="form-control" placeholder="Nama user, keterangan, atau IP address" value="{{ $filters['search'] ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label for="modul">Modul</label>
                            <select id="modul" name="modul" class="form-control">
                                <option value="">Semua modul</option>
                                @foreach($moduls as $modul)
                                    <option value="{{ $modul }}" @selected(($filters['modul'] ?? '') === $modul)>{{ $modul }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="aksi">Aksi</label>
                            <select id="aksi" name="aksi" class="form-control">
                                <option value="">Semua aksi</option>
                                @foreach($aksis as $aksi)
                                    <option value="{{ $aksi }}" @selected(($filters['aksi'] ?? '') === $aksi)>{{ $aksi }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tanggal_dari">Dari Tanggal</label>
                            <input id="tanggal_dari" type="date" name="tanggal_dari" class="form-control" value="{{ $filters['tanggal_dari'] ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label for="tanggal_sampai">Sampai Tanggal</label>
                            <input id="tanggal_sampai" type="date" name="tanggal_sampai" class="form-control" value="{{ $filters['tanggal_sampai'] ?? '' }}">
                        </div>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                        <a href="{{ route('admin.log.index') }}" class="btn btn-secondary">Reset</a>
                        <a href="{{ route('admin.log.export', request()->query()) }}" class="btn btn-success">Unduh CSV</a>
                        <button type="button" class="btn btn-outline" onclick="window.print()">Cetak</button>
                        <a href="{{ route('admin.log.export-pdf', request()->query()) }}" class="btn btn-outline">Unduh PDF</a>
                    </div>
                </form>
            </div>

            <div class="table-card">
                <div class="table-head">
                    <div>{{--  --}}
                        <h2>Daftar Aktivitas</h2>
                        <p>Menampilkan {{ $logs->count() }} data pada halaman ini.</p>
                    </div>
                </div>

                <div class="table-wrapper">
                    @if($logs->count() > 0)
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
                                @foreach($logs as $index => $log)
                                    <tr>
                                        <td>{{ $logs->firstItem() + $index }}</td>
                                        <td>{{ $log->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</td>
                                        <td>
                                            <div><strong>{{ $log->nama_user }}</strong></div>
                                            <div class="muted">{{ $log->user->email ?? '-' }}</div>
                                        </td>
                                        <td>
                                            @php
                                                $badgeClass = 'badge-view';
                                                if (strtolower($log->aksi) === 'login') $badgeClass = 'badge-login';
                                                elseif (strtolower($log->aksi) === 'logout') $badgeClass = 'badge-logout';
                                                elseif (strtolower($log->aksi) === 'create') $badgeClass = 'badge-create';
                                                elseif (strtolower($log->aksi) === 'update') $badgeClass = 'badge-update';
                                                elseif (strtolower($log->aksi) === 'delete') $badgeClass = 'badge-delete';
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $log->aksi }}</span>
                                        </td>
                                        <td>{{ $log->modul }}</td>
                                        <td>{{ $log->keterangan ?? '-' }}</td>
                                        <td><span class="muted">{{ $log->ip_address }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="empty-state">
                            <h3>Tidak ada log aktivitas</h3>
                            <p>Belum ada data yang sesuai dengan filter saat ini.</p>
                        </div>
                    @endif
                </div>

                @if($logs->hasPages())
                    <div class="pagination-wrap screen-only">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>

</html>
