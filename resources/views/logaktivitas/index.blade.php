<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Aktivitas - Admin</title>
    @vite(['resources/css/admin-sidebar.css', 'resources/js/app.js'])

    <style>
        /* ===== RESET & BASE ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: #f5f7fb;
        }

        /* ===== LAYOUT ===== */
        .layout {
            display: flex;
            min-height: 100vh;
        }

        /* ===== MAIN CONTENT ===== */
        .main {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: white;
            padding: 18px 25px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .topbar strong {
            font-size: 18px;
            color: #1f2937;
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
            font-size: 16px;
        }

        .user-info span {
            font-size: 14px;
            color: #374151;
            font-weight: 500;
        }

        /* ===== LOGOUT BUTTON ===== */
        .logout-btn {
            background: #ef4444;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            width: 100%;
        }

        .logout-btn:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        /* ===== CONTENT HEADER ===== */
        .content-header {
            margin-bottom: 30px;
        }

        .content-header h1 {
            font-size: 28px;
            color: #1f2937;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .content-header p {
            color: #6b7280;
            font-size: 15px;
        }

        /* ===== ALERT ===== */
        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
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

        /* ===== FILTER SECTION ===== */
        .filter-section {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            margin-bottom: 24px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 18px;
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
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #fafbfc;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            background: white;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* ===== BUTTONS ===== */
        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-2px);
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        /* ===== TABLE ===== */
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
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
            padding: 16px;
            text-align: left;
            font-weight: 600;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        td {
            padding: 14px 16px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 14px;
        }

        tbody tr:hover {
            background: #f9fafb;
        }

        /* ===== BADGE ===== */
        .badge {
            padding: 5px 11px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            text-transform: capitalize;
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
        
        /* ===== EMPTY STATE ===== */
        .empty-state {
            text-align: center;
            padding: 60px 24px;
        }

        .empty-state-icon {
            font-size: 56px;
            margin-bottom: 16px;
            opacity: 0.4;
        }

        .empty-state h3 {
            font-size: 18px;
            color: #1f2937;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .empty-state p {
            color: #6b7280;
            font-size: 14px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                height: auto;
                position: static;
            }

            .main {
                padding: 20px;
            }

            .filter-grid {
                grid-template-columns: 1fr;
            }

            .topbar {
                flex-direction: column;
                gap: 15px;
            }

            .pagination-wrapper {
                flex-direction: column;
                gap: 12px;
                justify-content: center;
                text-align: center;
                padding: 10px 24px;
            }

            .pagination {
                justify-content: center;
            }

            .pagination a,
            .pagination span {
                padding: 5px 7px;
                font-size: 11px;
            }

            table {
                font-size: 12px;
            }

            th, td {
                padding: 10px 8px;
            }
        }
    </style>
</head>

<body>

    <div class="layout">
        <!-- SIDEBAR - DARI COMPONENT -->
        <x-admin-sidebar />
        
        <!-- MAIN CONTENT -->
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
                                <td>{{ $log->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</td>
                                <td>
                                    <strong>{{ $log->nama_user }}</strong>
                                    @if($log->user)
                                    <br><small style="color: #6b7280; font-size: 12px;">{{ $log->user->email ?? '-' }}</small>
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
            </div>

        </main>
    </div>

</body>

</html>
