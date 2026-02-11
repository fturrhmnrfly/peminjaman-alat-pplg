<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas</title>
    @vite(['resources/css/petugas-sidebar.css', 'resources/js/app.js'])

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
            background: linear-gradient(135deg, #facc15, #fde68a);
            color: #1e3a8a;
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
            color: #1e40af;
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
            border-left-color: #facc15;
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
            background: linear-gradient(135deg, #1e40af, #3b82f6);
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

        <x-petugas-sidebar />

        <!-- MAIN -->
        <main class="main">

            <!-- TOPBAR -->
            <div class="topbar">
                <strong>Dashboard Petugas</strong>
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    </div>
                    <span>{{ auth()->user()->nama }}</span>
                </div>
            </div>

            <!-- HEADER -->
            <div class="header">
                <h1>Halo, <span>{{ auth()->user()->nama }}</span> 👋</h1>
                <p>Verifikasi pengajuan peminjaman dengan cepat dan akurat</p>
            </div>

            <!-- CARDS -->  
            <div class="cards">
                <a href="{{ route('verifikasi') }}" class="card">
                    <div class="card-icon">✅</div>
                    <h3>Menyetujui Peminjaman</h3>
                    <p>Lihat daftar pengajuan, cek ketersediaan alat, lalu setujui/ tolak</p>
                </a>

                <a href="{{ route('petugas.laporan') }}" class="card">
                    <div class="card-icon">🧾</div>
                    <h3>Mencetak Laporan</h3>
                    <p>Pilih periode, lalu cetak atau unduh laporan</p>
                </a>

                <a href="{{ route('petugas.pengembalian') }}" class="card">
                    <div class="card-icon">📦</div>
                    <h3>Memantau Pengembalian</h3>
                    <p>Monitor alat yang belum dikembalikan dan keterlambatan</p>
                </a>
            </div>

            <!-- STATS -->
            <div class="stats">
                <strong>📊 Ringkasan Alur Petugas</strong>
                <div class="stats-grid">
                    <div class="stat-box">
                        <div class="stat-number">Verifikasi</div>
                        <div class="stat-label">Setujui / Tolak</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">Laporan</div>
                        <div class="stat-label">Cetak / Unduh</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">Pengembalian</div>
                        <div class="stat-label">Pantau Keterlambatan</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">Logout</div>
                        <div class="stat-label">Selesai Tugas</div>
                    </div>
                </div>
            </div>

        </main>
    </div>

</body>

</html>