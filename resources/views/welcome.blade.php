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
            line-height: 1.2;
            margin-bottom: 20px;
            color: #111827;
            animation: slideUp 0.8s ease-out 0.2s backwards;
        }

        .hero h1 .highlight {
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1280px;
            margin: 0 auto;
        }

        .feature-card {
            background: linear-gradient(135deg, #ffffff 0%, #f5f7fa 100%);
            padding: 40px;
            border-radius: 15px;
            border: 1px solid #e5e7eb;
            transition: all 0.3s;
            text-align: center;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
            border-color: #14b8a6;
        }

        .feature-icon {
            font-size: 48px;
            margin-bottom: 20px;
            display: inline-block;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }

        .feature-card h3 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #111827;
        }

        .feature-card p {
            color: #6b7280;
            line-height: 1.7;
            font-size: 15px;
        }

        /* ===== BENEFITS SECTION ===== */
        .benefits {
            padding: 100px 40px;
            background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%);
        }

        .benefits-container {
            max-width: 1280px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .benefits-content h2 {
            font-size: 42px;
            font-weight: 800;
            margin-bottom: 20px;
            color: #111827;
        }

        .benefits-content p {
            font-size: 16px;
            color: #6b7280;
            line-height: 1.8;
            margin-bottom: 30px;
        }

        .benefits-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .benefit-item {
            display: flex;
            gap: 15px;
            align-items: flex-start;
        }

        .benefit-check {
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 18px;
        }

        .benefit-item h4 {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 5px;
        }

        .benefit-item p {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
        }

        .benefits-image {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 100px;
            opacity: 0.9;
        }

        /* ===== CTA SECTION ===== */
        .cta-section {
            padding: 80px 40px;
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            text-align: center;
            color: white;
        }

        .cta-content {
            max-width: 800px;
            margin: 0 auto;
        }

        .cta-content h2 {
            font-size: 48px;
            font-weight: 800;
            margin-bottom: 15px;
        }

        .cta-content p {
            font-size: 18px;
            margin-bottom: 40px;
            opacity: 0.95;
            line-height: 1.8;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
        }

        .cta-buttons .btn {
            font-size: 16px;
            padding: 12px 30px;
        }

        .btn-light {
            background: white;
            color: #0f766e;
        }

        .btn-light:hover {
            background: #f0fdf4;
            transform: translateY(-2px);
        }

        /* ===== FOOTER ===== */
        footer {
            background: #1f2937;
            color: #d1d5db;
            padding: 40px;
            text-align: center;
            font-size: 14px;
        }

        footer a {
            color: #14b8a6;
            text-decoration: none;
            transition: color 0.3s;
        }

        footer a:hover {
            color: white;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .navbar-container {
                padding: 0 20px;
            }

            .hero h1 {
                font-size: 36px;
            }

            .hero p {
                font-size: 16px;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .benefits-container {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .section-header h2 {
                font-size: 32px;
            }

            .cta-content h2 {
                font-size: 36px;
            }

            .navbar-links {
                gap: 15px;
            }

            .cta-buttons {
                flex-direction: column;
            }
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

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Dashboard Admin</h3>
                <p>Kelola data user, alat, kategori, dan monitoring semua aktivitas dalam satu tempat</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">✅</div>
                <h3>Verifikasi Otomatis</h3>
                <p>Proses persetujuan peminjaman yang cepat dengan sistem notifikasi real-time</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">📈</div>
                <h3>Laporan & Analitik</h3>
                <p>Buat laporan peminjaman dan unduh dalam format CSV untuk analisis lebih lanjut</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🛡️</div>
                <h3>Keamanan Terjamin</h3>
                <p>Akses terkontrol dengan sistem role berbasis (Admin, Petugas, Peminjam)</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">⏱️</div>
                <h3>Tracking Otomatis</h3>
                <p>Pantau status peminjaman, pengembalian, dan deteksi keterlambatan secara real-time</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">📱</div>
                <h3>Responsif & Mobile</h3>
                <p>Akses dari mana saja, kapan saja menggunakan desktop, tablet, atau smartphone</p>
            </div>
        </div>
    </section>

    <!-- BENEFITS SECTION -->
    <section class="benefits" id="benefits">
        <div class="benefits-container">
            <div class="benefits-content">
                <h2>Mengapa Pilih Kami?</h2>
                <p>Kami menawarkan solusi lengkap untuk manajemen peminjaman alat yang lebih efisien, transparan, dan terukur.</p>

                <div class="benefits-list">
                    <div class="benefit-item">
                        <div class="benefit-check">✓</div>
                        <div>
                            <h4>Proses Lebih Singkat</h4>
                            <p>Pengajuan peminjaman hanya membutuhkan waktu 1 menit</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-check">✓</div>
                        <div>
                            <h4>Riwayat Tercatat</h4>
                            <p>Semua aktivitas tersimpan otomatis untuk dokumentasi dan evaluasi</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-check">✓</div>
                        <div>
                            <h4>Status Real-time</h4>
                            <p>Peminjam dapat memantau status pengajuan mereka kapan saja</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-check">✓</div>
                        <div>
                            <h4>Akses Terkontrol</h4>
                            <p>Setiap user hanya dapat mengakses data sesuai dengan role mereka</p>
                        </div>
                    </div>

                    <div class="benefit-item">
                        <div class="benefit-check">✓</div>
                        <div>
                            <h4>Laporan Komprehensif</h4>
                            <p>Generate laporan untuk evaluasi dan pengambilan keputusan</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="benefits-image">
                🎯
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>&copy; {{ date('Y') }} <strong>{{ config('app.name', 'Peminjaman PPLG') }}</strong> - Sistem Manajemen Peminjaman Alat Pembelajaran</p>
        <p style="margin-top: 10px;">Dibuat dengan ❤️ untuk sekolah yang lebih baik</p>
    </footer>
</body>
</html>
