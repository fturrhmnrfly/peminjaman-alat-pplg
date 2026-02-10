<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas - Admin</title>
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

        /* ===== TOPBAR ===== */
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

        /* ===== CONTENT ===== */
        .content-header {
            margin-bottom: 25px;
        }

        .content-header h1 {
            font-size: 26px;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .content-header p {
            color: #6b7280;
        }

        /* ===== FILTER SECTION ===== */
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
        }

        .form-control {
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        /* ===== TABLE ===== */
        .table-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        /* ===== BADGE ===== */
        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .badge-login {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-logout {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-create {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-update {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-delete {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-view {
            background: #e0e7ff;
            color: #3730a3;
        }

        /* ===== PAGINATION ===== */
        .pagination-wrapper {
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .pagination {
            display: flex;
            gap: 5px;
            list-style: none;
        }

        .pagination a,
        .pagination span {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            text-decoration: none;
            color: #374151;
            font-size: 14px;
            transition: 0.3s;
        }

        .pagination a:hover {
            background: #f3f4f6;
        }

        .pagination .active span {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .pagination .disabled span {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* ===== ALERT ===== */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
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

        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }

        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 15px;
            opacity: 0.5;
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
                <a href="{{ route('admin.user.index') }}">👥 User</a>
                <a href="{{ route('admin.kategori.index') }}">📂 Kategori</a>
                <a href="{{ route('admin.alat.index') }}">🛠️ Alat</a>
                <a href="{{ route('admin.log.index') }}" class="active">📋 Log Aktivitas</a>
                <a href="{{ route('admin.peminjaman.index') }}">📦 Data Peminjaman</a>
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
                <strong>Log Aktivitas</strong>
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    </div>
                    <span>{{ auth()->user()->nama }}</span>
                </div>
            </div>

            <!-- HEADER -->
            <div class="content-header">
                <h1>📋 Log Aktivitas</h1>
                <p>Riwayat semua aktivitas pengguna dalam sistem</p>
            </div>

            <!-- ALERT -->
            @if(session('success'))
            <div class="alert alert-success">
                ✓ {{ session('success') }}
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-error">
                ✗ {{ session('error') }}
            </div>
            @endif

            <!-- FILTER SECTION -->
            <div class="filter-section">
                <form method="GET" action="{{ route('admin.log.index') }}">
                    <div class="filter-grid">
                        <div class="form-group">
                            <label>🔍 Cari</label>
                            <input type="text" name="search" class="form-control" placeholder="Nama user, keterangan, IP..." value="{{ request('search') }}">
                        </div>

                        <div class="form-group">
                            <label>📂 Modul</label>
                            <select name="modul" class="form-control">
                                <option value="">Semua Modul</option>
                                @foreach($moduls as $modul)
                                <option value="{{ $modul }}" {{ request('modul') == $modul ? 'selected' : '' }}>
                                    {{ $modul }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>⚡ Aksi</label>
                            <select name="aksi" class="form-control">
                                <option value="">Semua Aksi</option>
                                @foreach($aksis as $aksi)
                                <option value="{{ $aksi }}" {{ request('aksi') == $aksi ? 'selected' : '' }}>
                                    {{ $aksi }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>📅 Dari Tanggal</label>
                            <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
                        </div>

                        <div class="form-group">
                            <label>📅 Sampai Tanggal</label>
                            <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
                        </div>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary">🔍 Filter</button>
                        <a href="{{ route('admin.log.index') }}" class="btn btn-secondary">🔄 Reset</a>
                        <a href="{{ route('admin.log.export', request()->all()) }}" class="btn btn-success">📥 Export CSV</a>
                    </div>
                </form>
            </div>

            <!-- TABLE -->
            <div class="table-container">
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
                                <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <strong>{{ $log->nama_user }}</strong>
                                    @if($log->user)
                                    <br><small style="color: #6b7280;">{{ $log->user->email ?? '-' }}</small>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $badgeClass = 'badge-view';
                                        if(strtolower($log->aksi) == 'login') $badgeClass = 'badge-login';
                                        elseif(strtolower($log->aksi) == 'logout') $badgeClass = 'badge-logout';
                                        elseif(strtolower($log->aksi) == 'create') $badgeClass = 'badge-create';
                                        elseif(strtolower($log->aksi) == 'update') $badgeClass = 'badge-update';
                                        elseif(strtolower($log->aksi) == 'delete') $badgeClass = 'badge-delete';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $log->aksi }}</span>
                                </td>
                                <td>{{ $log->modul }}</td>
                                <td>{{ $log->keterangan ?? '-' }}</td>
                                <td>
                                    <small style="color: #6b7280;">{{ $log->ip_address }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <div class="empty-state">
                        <div class="empty-state-icon">📋</div>
                        <h3>Tidak ada log aktivitas</h3>
                        <p>Belum ada aktivitas yang tercatat dalam sistem</p>
                    </div>
                    @endif
                </div>

                @if($logs->count() > 0)
                <div class="pagination-wrapper">
                    <div>
                        Menampilkan {{ $logs->firstItem() }} - {{ $logs->lastItem() }} dari {{ $logs->total() }} log
                    </div>
                    <div>
                        {{ $logs->links() }}
                    </div>
                </div>
                @endif
            </div>

        </main>
    </div>

</body>

</html>
