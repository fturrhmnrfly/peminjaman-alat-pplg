<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Alat</title>
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

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #1e40af;
            color: white;
        }

        .btn-primary:hover {
            background: #1e3a8a;
        }

        .btn-success {
            background: #10b981;
            color: white;
            padding: 6px 12px;
            font-size: 13px;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
            padding: 6px 12px;
            font-size: 13px;
        }

        .btn-danger:hover {
            background: #dc2626;
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
        }

        table tbody tr:hover {
            background: #f9fafb;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-baik {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-rusak {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-hilang {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 20px;
        }

        .foto-alat {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
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
            text-decoration: none;
            display: inline-block;
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
                <a href="{{ route('admin.user.index') }}">👥 User</a>
                <a href="{{ route('admin.kategori.index') }}">📂 Kategori</a>
                <a href="{{ route('admin.alat.index') }}" class="active">🛠️ Alat</a>
                <a href="{{ route('admin.log.index') }}">📋 Log Aktivitas</a>
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
                <strong>Kelola Alat</strong>
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    </div>
                    <span>{{ auth()->user()->nama }}</span>
                </div>
            </div>

            <!-- CONTENT -->
            <div class="content-card">

                <!-- Alert Messages -->
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

                <div class="header-action">
                    <h2>🛠️ Daftar Alat</h2>
                    <a href="{{ route('admin.alat.create') }}" class="btn btn-primary">+ Tambah Alat</a>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Foto</th>
                            <th>Nama Alat</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Kondisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alats as $index => $alat)
                        <tr>
                            <td>{{ $alats->firstItem() + $index }}</td>
                            <td>
                                @if($alat->foto)
                                <img src="{{ asset('storage/alat/' . $alat->foto) }}" alt="{{ $alat->nama_alat }}" class="foto-alat">
                                @else
                                <div style="width: 50px; height: 50px; background: #e5e7eb; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                                    🛠️
                                </div>
                                @endif
                            </td>
                            <td><strong>{{ $alat->nama_alat }}</strong></td>
                            <td>{{ $alat->kategori->nama_kategori ?? '-' }}</td>
                            <td>{{ $alat->jumlah }}</td>
                            <td>
                                <span class="badge badge-{{ $alat->kondisi }}">
                                    {{ ucfirst($alat->kondisi) }}
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.alat.edit', $alat->id) }}" class="btn btn-success">Edit</a>
                                    <form action="{{ route('admin.alat.destroy', $alat->id) }}" method="POST" 
                                          onsubmit="return confirm('Yakin ingin menghapus alat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: #9ca3af;">
                                Belum ada data alat
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination">
                    {{ $alats->links() }}
                </div>

            </div>

        </main>
    </div>

</body>

</html>
