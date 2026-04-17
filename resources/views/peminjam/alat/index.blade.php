<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ruang Alat</title>
    @vite(['resources/css/peminjam-sidebar.css', 'resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(109, 135, 111, 0.12), transparent 32%),
                linear-gradient(180deg, #f5f7f4 0%, #f4f7f3 45%, #eef2ef 100%);
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

        .topbar {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            padding: 18px 25px;
            border-radius: 20px;
            box-shadow: 0 18px 35px rgba(15, 23, 42, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            gap: 16px;
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
            background: linear-gradient(135deg, var(--peminjam-avatar-start), var(--peminjam-avatar-end));
            color: var(--peminjam-avatar-text);
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 18px rgba(16, 185, 129, 0.2);
        }

        .catalog-shell {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(255, 255, 255, 0.8);
            padding: 26px;
            border-radius: 24px;
            box-shadow: 0 24px 50px rgba(15, 23, 42, 0.08);
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 22px;
            flex-wrap: wrap;
        }

        .section-title {
            font-size: 28px;
            color: #111827;
            margin-bottom: 8px;
        }

        .section-desc {
            color: #6b7280;
            max-width: 640px;
            line-height: 1.6;
        }

        .catalog-badge {
            background: linear-gradient(135deg, var(--peminjam-accent), var(--peminjam-stat-end));
            color: white;
            padding: 10px 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.02em;
            box-shadow: 0 12px 24px rgba(15, 118, 110, 0.18);
        }

        .filter-panel {
            display: grid;
            grid-template-columns: minmax(220px, 320px) auto;
            gap: 14px;
            align-items: end;
            margin-bottom: 24px;
            padding: 18px;
            border-radius: 20px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(240, 253, 250, 0.96));
            border: 1px solid #d1fae5;
        }

        .form-group label {
            display: block;
            font-weight: 700;
            margin-bottom: 8px;
            color: #374151;
        }

        .form-group select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 14px;
            font-size: 14px;
            background: white;
            color: #111827;
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-primary,
        .btn-secondary,
        .btn-detail {
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 700;
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background: var(--peminjam-accent);
            color: white;
            padding: 12px 18px;
            box-shadow: 0 14px 24px rgba(15, 118, 110, 0.2);
        }

        .btn-secondary {
            background: #f0f5ef;
            color: #0f172a;
            padding: 12px 18px;
            border: 1px solid #bae6fd;
        }

        .btn-detail {
            width: 100%;
            padding: 12px 14px;
            background: #111827;
            color: white;
        }

        .btn-primary:hover,
        .btn-secondary:hover,
        .btn-detail:hover {
            transform: translateY(-1px);
        }

        .catalog-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .product-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            box-shadow: 0 14px 28px rgba(15, 23, 42, 0.07);
            display: flex;
            flex-direction: column;
            min-height: 100%;
        }

        .product-media {
            position: relative;
            height: 180px;
            background: linear-gradient(135deg, #dff7f3, #eff6ff);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 14px;
        }

        .product-media img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 14px;
        }

        .product-placeholder {
            width: 72px;
            height: 72px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: var(--peminjam-accent);
            border: 1px solid rgba(255, 255, 255, 0.9);
        }

        .stock-pill {
            position: absolute;
            top: 12px;
            left: 12px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(17, 24, 39, 0.78);
            color: white;
            font-size: 11px;
            font-weight: 700;
            backdrop-filter: blur(8px);
        }

        .category-pill {
            position: absolute;
            right: 12px;
            bottom: 12px;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.92);
            color: var(--peminjam-accent);
            font-size: 11px;
            font-weight: 700;
        }

        .product-body {
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            flex: 1;
        }

        .product-content {
            display: flex;
            flex-direction: column;
            gap: 12px;
            flex: 1;
        }

        .product-title {
            font-size: 17px;
            font-weight: 800;
            color: #111827;
            line-height: 1.35;
            min-height: 46px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-subtitle {
            color: #6b7280;
            font-size: 13px;
            line-height: 1.55;
            min-height: 60px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .stat-box {
            padding: 10px 12px;
            border-radius: 14px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .stat-label {
            display: block;
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
        }

        .kode-preview {
            background: linear-gradient(135deg, #f0fdfa, #f8fafc);
            border-radius: 14px;
            border: 1px solid #ccfbf1;
            padding: 12px;
        }

        .kode-preview-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.02em;
            color: var(--peminjam-accent);
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .kode-preview-text {
            font-size: 13px;
            color: #334155;
            line-height: 1.55;
            min-height: 60px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .btn-detail {
            padding: 10px 12px;
            font-size: 13px;
            margin-top: auto;
        }

        .empty-state {
            padding: 40px 24px;
            text-align: center;
            border: 1px dashed #cbd5e1;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.75);
            color: #64748b;
        }

        @media (max-width: 900px) {
            .main {
                padding: 20px;
            }

            .filter-panel {
                grid-template-columns: 1fr;
            }

            .topbar {
                padding: 16px 18px;
            }

            .catalog-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .topbar,
            .section-head {
                flex-direction: column;
                align-items: flex-start;
            }

            .catalog-shell {
                padding: 18px;
            }

            .product-media {
                height: 160px;
            }

            .catalog-grid {
                grid-template-columns: 1fr;
            }

            .unit-badges {
                justify-content: flex-start;
            }
        }
    </style>
</head>

<body>
    @php
        /** @var \Illuminate\Filesystem\FilesystemAdapter $publicDisk */
        $publicDisk = \Illuminate\Support\Facades\Storage::disk('public');
    @endphp

    <div class="layout">
        <x-peminjam-sidebar></x-peminjam-sidebar>

        <main class="main">
            <div class="topbar">
                <strong>Daftar Alat</strong>
                <div class="user-info">
                    <x-notification-bell />
                    <x-profile-shortcut />
                </div>
            </div>

            <div class="catalog-shell">
                <div class="section-head">
                    <div>
                        <h2 class="section-title">Katalog Alat Tersedia</h2>
                        <p class="section-desc">Pilih alat seperti melihat katalog produk. Setiap kartu menampilkan foto, stok tersedia, dan tombol detail untuk melihat kondisi barang berdasarkan kode unik.</p>
                    </div>
                    <div class="catalog-badge">{{ $alat->count() }} alat tampil</div>
                </div>

                <form method="GET" action="{{ route('peminjam.alat.index') }}" class="filter-panel">
                    <div class="form-group">
                        <label for="kategori_id">Filter Kategori</label>
                        <select id="kategori_id" name="kategori_id">
                            <option value="">Semua Kategori</option>
                            @foreach($kategori as $item)
                                <option value="{{ $item->id }}" @selected((string) $selectedKategori === (string) $item->id)>
                                    {{ $item->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn-primary">Terapkan Filter</button>
                        <a href="{{ route('peminjam.alat.index') }}" class="btn-secondary">Reset</a>
                    </div>
                </form>

                <div class="catalog-grid">
                    @forelse($alat as $item)
                        @php
                            $totalUnit = $item->units->count();
                            $availableUnits = $item->units->where('status', 'tersedia');
                            $availableCount = $availableUnits->count();
                            $kodeList = $availableUnits->pluck('kode_unik')->filter()->values();
                            $kodePreview = $kodeList->take(3)->implode(', ');
                            $fotoUrl = null;

                            if ($item->foto) {
                                $fotoValue = $item->foto;
                                $fotoPath = \Illuminate\Support\Str::startsWith($fotoValue, ['alat/', 'public/alat/'])
                                    ? $fotoValue
                                    : 'alat/' . $fotoValue;
                                $fotoPath = \Illuminate\Support\Str::startsWith($fotoPath, 'public/')
                                    ? \Illuminate\Support\Str::after($fotoPath, 'public/')
                                    : $fotoPath;
                                $fotoUrl = $publicDisk->url($fotoPath);
                            }
                        @endphp

                        <article class="product-card">
                            <div class="product-media">
                                <div class="stock-pill">
                                    Tersedia {{ $totalUnit > 0 ? $availableCount . ' / ' . $totalUnit : $item->jumlah }}
                                </div>
                                <div class="category-pill">{{ $item->kategori->nama_kategori ?? 'Tanpa kategori' }}</div>

                                @if($fotoUrl)
                                    <img src="{{ $fotoUrl }}" alt="{{ $item->nama_alat }}">
                                @else
                                    <div class="product-placeholder">AL</div>
                                @endif
                            </div>

                            <div class="product-body">
                                <div class="product-content">
                                    <div>
                                        <h3 class="product-title">{{ $item->nama_alat }}</h3>
                                        <p class="product-subtitle">{{ $item->keterangan ?: 'Belum ada keterangan tambahan untuk alat ini.' }}</p>
                                    </div>

                                    <div class="stats-grid">
                                        <div class="stat-box">
                                            <span class="stat-label">Stok tersedia</span>
                                            <span class="stat-value">{{ $totalUnit > 0 ? $availableCount : $item->jumlah }}</span>
                                        </div>
                                        <div class="stat-box">
                                            <span class="stat-label">Total unit</span>
                                            <span class="stat-value">{{ $totalUnit > 0 ? $totalUnit : $item->jumlah }}</span>
                                        </div>
                                    </div>

                                    <div class="kode-preview">
                                        <div class="kode-preview-title">Kode unik tersedia</div>
                                        <div class="kode-preview-text">
                                            @if($kodeList->isNotEmpty())
                                                {{ $kodePreview }}@if($kodeList->count() > 3) dan {{ $kodeList->count() - 3 }} kode lainnya @endif
                                            @elseif($totalUnit > 0)
                                                Belum ada unit yang sedang berstatus tersedia.
                                            @else
                                                Alat ini tidak menggunakan kode unik per unit.
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('peminjam.alat.show', $item->id) }}" class="btn-detail">
                                    Lihat Detail Alat
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="empty-state">
                            Belum ada alat yang cocok dengan filter yang dipilih.
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>
</body>

</html>

