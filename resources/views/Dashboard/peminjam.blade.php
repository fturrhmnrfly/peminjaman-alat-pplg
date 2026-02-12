<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Peminjam</title>
    @vite(['resources/css/peminjam-sidebar.css', 'resources/js/app.js'])

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
            background: linear-gradient(135deg, #34d399, #a7f3d0);
            color: #065f46;
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
            color: #0f766e;
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
            border-left-color: #10b981;
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
            background: linear-gradient(135deg, #0f766e, #14b8a6);
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
    </style>
</head>

<body>

    <div class="layout">
        <x-peminjam-sidebar><</x-peminjam-sidebar>

        <!-- MAIN -->
        <main class="main">

            <!-- TOPBAR -->
            <div class="topbar">
                <strong>Dashboard Peminjam</strong>
                <div class="user-info">
                    <x-notification-bell />
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    </div>
                    <span>{{ auth()->user()->nama }}</span>
                </div>
            </div>

            <!-- HEADER -->
            <div class="header">
                <h1>Halo, <span>{{ auth()->user()->nama }}</span> 👋</h1>
                <p>Ajukan peminjaman alat dan pantau statusnya dengan mudah</p>
            </div>

            <!-- CARDS -->  
            <div class="cards">
                <a href="{{ route('peminjam.alat.index') }}" class="card">
                    <div class="card-icon">🧰</div>
                    <h3>Daftar Alat</h3>
                    <p>Lihat alat yang tersedia</p>
                </a>

                <a href="{{ route('peminjaman.index') }}" class="card">
                    <div class="card-icon">📝</div>
                    <h3>Ajukan Peminjaman</h3>
                    <p>Buat pengajuan peminjaman alat pembelajaran</p>
                </a>

                <a href="{{ route('peminjam.pengembalian.index') }}" class="card">
                    <div class="card-icon">📦</div>
                    <h3>Pengembalian</h3>
                    <p>Ajukan pengembalian untuk peminjaman yang disetujui</p>
                </a>
            </div>

            <!-- STATS -->
            <div class="stats">
                <strong>📊 Ringkasan</strong>
                <div class="stats-grid">
                    <div class="stat-box">
                        <div class="stat-number">{{ $pendingCount ?? 0 }}</div>
                        <div class="stat-label">Menunggu Verifikasi</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">{{ $disetujuiCount ?? 0 }}</div>
                        <div class="stat-label">Disetujui</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">{{ $ditolakCount ?? 0 }}</div>
                        <div class="stat-label">Ditolak</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">{{ $dikembalikanCount ?? 0 }}</div>
                        <div class="stat-label">Dikembalikan</div>
                    </div>
                </div>
            </div>

        </main>
    </div>

</body>

</html>
