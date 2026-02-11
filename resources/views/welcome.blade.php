<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Peminjaman PPLG') }} - Sistem Peminjaman Alat Modern</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1f2937;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ===== NAVBAR ===== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 0 40px;
        }

        .navbar-container {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 80px;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
            font-weight: 800;
            color: #0f766e;
            text-decoration: none;
            transition: transform 0.3s;
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .navbar-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .navbar-links {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .navbar-links a {
            text-decoration: none;
            color: #6b7280;
            font-weight: 600;
            transition: color 0.3s;
            position: relative;
        }

        .navbar-links a:hover {
            color: #0f766e;
        }

        .navbar-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: #0f766e;
            transition: width 0.3s;
        }

        .navbar-links a:hover::after {
            width: 100%;
        }

        .btn {
            padding: 10px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-outline {
            background: transparent;
            color: #0f766e;
            border: 2px solid #0f766e;
        }

        .btn-outline:hover {
            background: #e0f2f1;
            color: #0f766e;
            transform: translateY(-2px);
            border-color: #0f766e;
        }

        .btn-primary {
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            color: white;
            box-shadow: 0 8px 20px rgba(15, 118, 110, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(15, 118, 110, 0.4);
            background: linear-gradient(135deg, #065f46, #0d9488);
        }

        /* ===== HERO SECTION ===== */
        .hero {
            margin-top: 80px;
            padding: 80px 40px;
            background: linear-gradient(135deg, #ffffff 0%, #f0fdf4 100%);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(20, 184, 166, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -20%;
            left: -5%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(15, 118, 110, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .hero-content {
            max-width: 900px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-badge {
            display: inline-block;
            background: #d1fae5;
            color: #0f766e;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 20px;
            animation: slideUp 0.8s ease-out 0.1s backwards;
        }

        .hero h1 {
            font-size: 56px;
            font-weight: 800;
            line-height: 1.3;
            margin-bottom: 20px;
            color: #111827;
            animation: slideUp 0.8s ease-out;
        }

        .hero h1 .highlight {
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.4em;
            font-weight: 900;
            letter-spacing: -1px;
            display: inline-block;
            text-shadow: 0 4px 20px rgba(15, 118, 110, 0.2);
            animation: slideIn 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) 0.3s backwards;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .hero p {
            font-size: 18px;
            color: #6b7280;
            margin-bottom: 40px;
            line-height: 1.8;
            animation: slideUp 0.8s ease-out 0.3s backwards;
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-bottom: 60px;
            animation: slideUp 0.8s ease-out 0.4s backwards;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            animation: slideUp 0.8s ease-out 0.5s backwards;
        }

        .stat-item {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .stat-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-number {
            font-size: 32px;
            font-weight: 800;
            color: #0f766e;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #6b7280;
            font-weight: 600;
        }

        /* ===== FEATURES SECTION ===== */
        .features {
            padding: 100px 40px;
            background: white;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .section-header h2 {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 15px;
            color: #111827;
        }

        .section-header p {
            font-size: 18px;
            color: #6b7280;
            line-height: 1.8;
        }

        .features-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            max-width: 1280px;
            margin: 0 auto 80px;
        }

        .feature-banner {
            background: linear-gradient(135deg, #ffffff 0%, #f5f7fa 100%);
            padding: 40px;
            border-radius: 20px;
            border: 2px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 30px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .feature-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(20, 184, 166, 0.05) 0%, rgba(15, 118, 110, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.4s;
            z-index: 0;
        }

        .feature-banner:hover {
            transform: translateY(-8px);
            border-color: #14b8a6;
            box-shadow: 0 20px 50px rgba(20, 184, 166, 0.15);
        }

        .feature-banner:hover::before {
            opacity: 1;
        }

        .feature-banner-content {
            flex: 1;
            position: relative;
            z-index: 1;
        }

        .feature-banner-icon {
            font-size: 80px;
            min-width: 100px;
            text-align: center;
            opacity: 0.8;
            transition: all 0.4s;
        }

        .feature-banner:hover .feature-banner-icon {
            transform: scale(1.15) rotate(5deg);
            opacity: 1;
        }

        .feature-number {
            font-size: 48px;
            font-weight: 800;
            background: linear-gradient(135deg, #14b8a6, #0f766e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 12px;
        }

        .feature-banner h3 {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .feature-banner p {
            font-size: 15px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .feature-highlights {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .highlight {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #065f46;
            font-weight: 600;
            padding: 8px 12px;
            background: #d1fae5;
            border-radius: 8px;
            width: fit-content;
        }

        .feature-banner-alt {
            flex-direction: row-reverse;
        }

        /* ===== COMPARISON TABLE ===== */
        .comparison-section {
            max-width: 1280px;
            margin: 0 auto;
            padding-top: 60px;
        }

        .comparison-section h3 {
            font-size: 32px;
            font-weight: 800;
            text-align: center;
            margin-bottom: 40px;
            color: #111827;
        }

        .comparison-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .comparison-table thead {
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            color: white;
        }

        .comparison-table th {
            padding: 20px;
            text-align: left;
            font-weight: 700;
            font-size: 16px;
            letter-spacing: 0.5px;
        }

        .comparison-table th.highlight-col {
            background: linear-gradient(135deg, #10b981, #34d399);
            font-size: 17px;
        }

        .comparison-table td {
            padding: 18px 20px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 15px;
            color: #374151;
        }

        .comparison-table tbody tr:hover {
            background: #f9fafb;
        }

        .comparison-table tbody tr:last-child td {
            border-bottom: none;
        }

        .comparison-table td.check {
            color: #065f46;
            font-weight: 700;
            background: rgba(209, 250, 229, 0.4);
        }

        .comparison-table tbody tr:hover td.check {
            background: rgba(209, 250, 229, 0.6);
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="/" class="navbar-brand">
                <span class="navbar-icon">📚</span>
                {{ config('', 'Peminjaman PPLG') }}
            </a>
            
            <div class="navbar-links">
                <a href="#features">Fitur</a>
                <a href="#benefits">Manfaat</a>
                
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-badge">✨ Solusi Peminjaman Alat Terpercaya</div>
            <h1>Kelola Peminjaman Alat Dengan <span class="highlight">Lebih Mudah</span></h1>
            <p>Sistem peminjaman alat sekolah yang modern, efisien, dan transparan. Proses lebih cepat, riwayat tercatat otomatis, dan monitoring real-time.</p>
            
            <div class="hero-buttons">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary">Akses Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary">Mulai Sekarang</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-outline">Buat Akun</a>
                        @endif
                    @endauth
                @endif
            </div>

            <div class="stats">
                <div class="stat-item">
                    <div class="stat-number">100+</div>
                    <div class="stat-label">Pengguna Aktif</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">1000+</div>
                    <div class="stat-label">Peminjaman</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">99%</div>
                    <div class="stat-label">Uptime</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Support</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section class="features" id="features">
        <div class="section-header">
            <h2>Fitur Unggulan</h2>
            <p>Semua yang Anda butuhkan untuk mengelola peminjaman alat dengan efisien</p>
        </div>

        <div class="features-container">
            <!-- FEATURE 1 -->
            <div class="feature-banner">
                <div class="feature-banner-content">
                    <div class="feature-number">01</div>
                    <h3>Dashboard Admin Lengkap</h3>
                    <p>Kelola data user, alat, kategori, dan monitoring semua aktivitas dalam satu tempat yang intuitif dan mudah digunakan</p>
                    <div class="feature-highlights">
                        <span class="highlight">✓ Manajemen User</span>
                        <span class="highlight">✓ Kelola Alat</span>
                        <span class="highlight">✓ Log Aktivitas</span>
                    </div>
                </div>
                <div class="feature-banner-icon">📊</div>
            </div>

            <!-- FEATURE 2 -->
            <div class="feature-banner feature-banner-alt">
                <div class="feature-banner-icon">✅</div>
                <div class="feature-banner-content">
                    <div class="feature-number">02</div>
                    <h3>Verifikasi Otomatis</h3>
                    <p>Proses persetujuan peminjaman yang cepat dengan sistem notifikasi real-time dan tampilan data yang terstruktur</p>
                    <div class="feature-highlights">
                        <span class="highlight">✓ Notifikasi Real-time</span>
                        <span class="highlight">✓ Verifikasi Cepat</span>
                        <span class="highlight">✓ Tracking Otomatis</span>
                    </div>
                </div>
            </div>

            <!-- FEATURE 3 -->
            <div class="feature-banner">
                <div class="feature-banner-content">
                    <div class="feature-number">03</div>
                    <h3>Laporan & Analitik Komprehensif</h3>
                    <p>Generate laporan peminjaman lengkap dengan filter tanggal, status, dan pengguna untuk evaluasi dan pengambilan keputusan</p>
                    <div class="feature-highlights">
                        <span class="highlight">✓ Filter Laporan</span>
                        <span class="highlight">✓ Export CSV</span>
                        <span class="highlight">✓ Grafik Analisis</span>
                    </div>
                </div>
                <div class="feature-banner-icon">📈</div>
            </div>

            <!-- FEATURE 4 -->
            <div class="feature-banner feature-banner-alt">
                <div class="feature-banner-icon">🛡️</div>
                <div class="feature-banner-content">
                    <div class="feature-number">04</div>
                    <h3>Keamanan Terjamin</h3>
                    <p>Akses terkontrol dengan sistem role berbasis (Admin, Petugas, Peminjam) dan enkripsi data untuk keamanan maksimal</p>
                    <div class="feature-highlights">
                        <span class="highlight">✓ Role-based Access</span>
                        <span class="highlight">✓ Enkripsi Password</span>
                        <span class="highlight">✓ Audit Trail</span>
                    </div>
                </div>
            </div>

            <!-- FEATURE 5 -->
            <div class="feature-banner">
                <div class="feature-banner-content">
                    <div class="feature-number">05</div>
                    <h3>Tracking Pengembalian</h3>
                    <p>Pantau status peminjaman, pengembalian, dan deteksi keterlambatan secara real-time dengan sistem notifikasi otomatis</p>
                    <div class="feature-highlights">
                        <span class="highlight">✓ Monitoring Aktif</span>
                        <span class="highlight">✓ Alert Keterlambatan</span>
                        <span class="highlight">✓ Status Real-time</span>
                    </div>
                </div>
                <div class="feature-banner-icon">⏱️</div>
            </div>

            <!-- FEATURE 6 -->
            <div class="feature-banner feature-banner-alt">
                <div class="feature-banner-icon">⚡</div>
                <div class="feature-banner-content">
                    <div class="feature-number">06</div>
                    <h3>Performa Tinggi & Cepat</h3>
                    <p>Interface yang responsif dengan loading cepat, proses peminjaman yang singkat, dan sistem yang stabil 24/7</p>
                    <div class="feature-highlights">
                        <span class="highlight">✓ Loading Cepat</span>
                        <span class="highlight">✓ 99% Uptime</span>
                        <span class="highlight">✓ Optimized Database</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- COMPARISON TABLE -->
        <div class="comparison-section">
            <h3>Mengapa Kami Berbeda?</h3>
            <table class="comparison-table">
                <thead>
                    <tr>
                        <th>Fitur</th>
                        <th class="highlight-col">Peminjaman PPLG</th>
                        <th>Sistem Manual</th>
                        <th>Aplikasi Lainnya</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Proses Peminjaman</td>
                        <td class="check">✓ 1 Menit</td>
                        <td>30+ Menit</td>
                        <td>5-10 Menit</td>
                    </tr>
                    <tr>
                        <td>Riwayat Otomatis</td>
                        <td class="check">✓ Tersimpan Otomatis</td>
                        <td>Manual</td>
                        <td>Terbatas</td>
                    </tr>
                    <tr>
                        <td>Notifikasi Real-time</td>
                        <td class="check">✓ Ada</td>
                        <td>Tidak Ada</td>
                        <td>Terbatas</td>
                    </tr>
                    <tr>
                        <td>Laporan Terukur</td>
                        <td class="check">✓ Komprehensif</td>
                        <td>Sulit</td>
                        <td>Sederhana</td>
                    </tr>
                    <tr>
                        <td>Keamanan Data</td>
                        <td class="check">✓ Tinggi</td>
                        <td>Rendah</td>
                        <td>Sedang</td>
                    </tr>
                    <tr>
                        <td>Support 24/7</td>
                        <td class="check">✓ Tersedia</td>
                        <td>Tidak Ada</td>
                        <td>Terbatas</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>
