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
            background: var(--admin-page-bg);
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
            background: linear-gradient(135deg, var(--admin-avatar-start), var(--admin-avatar-end));
            color: var(--admin-avatar-text);
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ===== HEADER ===== */
        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 26px;
            color: #1f2937;
        }

        .header span {
            color: var(--admin-accent);
            font-weight: 600;
        }

        .header p {
            color: #6b7280;
            margin-top: 5px;
        }

        /* ===== CARDS ===== */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            transition: 0.3s;
            border-left: 5px solid transparent;
            text-decoration: none;
            color: inherit;
        }

        .card:hover {
            transform: translateY(-6px);
            border-left-color: var(--admin-accent-soft-2);
        }

        .card-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .card h3 {
            font-size: 18px;
            margin-bottom: 8px;
        }

        .card p {
            font-size: 14px;
            color: #6b7280;
        }

        /* ===== STATS ===== */
        .stats {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .stat-box {
            background: linear-gradient(135deg, var(--admin-stat-start), var(--admin-stat-end));
            color: white;
            padding: 18px;
            border-radius: 12px;
            text-align: center;
        }

        .stat-number {
            font-size: 26px;
            font-weight: 600;
        }

        .stat-label {
            font-size: 13px;
            opacity: 0.9;
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

        @media (max-width: 900px) {
            .main {
                padding: 20px;
            }

            .topbar {
                padding: 16px 18px;
                gap: 14px;
            }

            .cards,
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .topbar,
            .user-info {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>

<body data-page-motion="dashboard">

    <div class="layout">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-brand">📚 Ruang Alat</div>

            <nav class="sidebar-menu">
                    <a href="{{ route('dashboard') }}" class="active">🏠 Dashboard</a>
                    <a href="{{ route('admin.user.index') }}">👥 User</a>
                    <a href="{{ route('admin.kategori.index') }}">📂 Kategori</a>
                    <a href="{{ route('admin.alat.index') }}">🛠️ Alat</a>
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
                <strong>Dashboard Admin</strong>
                <div class="user-info">
                    <x-notification-bell />
                    <x-profile-shortcut />
                </div>
            </div>

            <!-- HEADER -->
            <div class="header">
                <h1>Halo, <span>{{ auth()->user()->nama }}</span> 👋</h1>
                <p>Kelola sistem Ruang Alat dengan mudah dan rapi</p>
            </div>

            <!-- CARDS -->  
            <div class="cards">
                <a href="{{ route('admin.user.index') }}" class="card">
                    <div class="card-icon">👥</div>
                    <h3>Kelola User</h3>
                    <p>Menambah, Membantu, Menghapus, Mengubah User</p>
                </a>

                <a href="{{ route('admin.alat.index') }}" class="card">
                    <div class="card-icon">🛠️</div>
                    <h3>Kelola Alat</h3>
                    <p>Menambah, Membantu, Menghapus, Mengubah Alat</p>
                </a>

                <a href="{{ route('admin.kategori.index') }}" class="card">
                    <div class="card-icon">📂</div>
                    <h3>Kelola Kategori</h3>
                    <p>Menambah, Membantu, Menghapus, Mengubah Kategori</p>
                </a>

                <a href="{{ route('admin.log.index') }}" class="card">
                    <div class="card-icon">📝</div>
                    <h3>Log Aktivitas</h3>
                    <p>Lihat riwayat aktivitas pengguna sistem</p>
                </a>

                <a href="{{ route('admin.peminjaman.index') }}" class="card">
                    <div class="card-icon">📦</div>
                    <h3>Data Peminjaman</h3>
                    <p>Lihat data Ruang Alat pembelajaran</p>
                </a>
            </div>

            <!-- STATS -->
            <div class="stats">
                <strong>📊 Ringkasan Data</strong>
                <div class="stats-grid">
                    <div class="stat-box">
                        <div class="stat-number">{{ $totalUsers ?? 0 }}</div>
                        <div class="stat-label">User</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">{{ $totalKategori ?? 0 }}</div>
                        <div class="stat-label">Kategori</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">{{ $totalAlat ?? 0 }}</div>
                        <div class="stat-label">Alat</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">{{ $totalPeminjaman ?? 0 }}</div>
                        <div class="stat-label">Peminjaman</div>
                    </div>
                </div>
            </div>

        </main>
    </div>

</body>

</html>

