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

        .detail-shell {
            background: rgba(255, 255, 255, 0.92);
            border: 1px solid rgba(255, 255, 255, 0.85);
            padding: 26px;
            border-radius: 24px;
            box-shadow: 0 24px 50px rgba(15, 23, 42, 0.08);
        }

        .detail-header {
            display: grid;
            grid-template-columns: minmax(280px, 360px) minmax(0, 1fr);
            gap: 22px;
            margin-bottom: 24px;
        }

        .detail-media {
            position: relative;
            min-height: 290px;
            border-radius: 24px;
            background: linear-gradient(135deg, #dff7f3, #eff6ff);
            padding: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .detail-media img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 18px;
        }

        .detail-placeholder {
            width: 96px;
            height: 96px;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 800;
            color: var(--peminjam-accent);
        }

        .stock-pill,
        .category-pill {
            position: absolute;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .stock-pill {
            top: 16px;
            left: 16px;
            background: rgba(17, 24, 39, 0.8);
            color: #fff;
        }

        .category-pill {
            right: 16px;
            bottom: 16px;
            background: rgba(255, 255, 255, 0.95);
            color: var(--peminjam-accent);
        }

        .detail-copy {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: fit-content;
            padding: 10px 14px;
            border-radius: 12px;
            text-decoration: none;
            background: #f0f5ef;
            border: 1px solid #bae6fd;
            color: #0f172a;
            font-weight: 700;
        }

        .detail-title {
            font-size: 30px;
            font-weight: 800;
            color: #111827;
            line-height: 1.2;
        }

        .detail-subtitle {
            color: #6b7280;
            line-height: 1.7;
            font-size: 15px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }

        .summary-card {
            padding: 16px;
            border-radius: 18px;
            background: #f8fafc;
            border: 1px solid #e5e7eb;
        }

        .summary-label {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #6b7280;
        }

        .summary-value {
            display: block;
            margin-top: 8px;
            font-size: 20px;
            font-weight: 800;
            color: #111827;
        }

        .panel {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 22px;
            padding: 22px;
        }

        .panel-title {
            font-size: 22px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 8px;
        }

        .panel-desc {
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 18px;
        }

        .unit-list {
            display: grid;
            gap: 12px;
        }

        .unit-item {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 16px;
            border-radius: 18px;
            border: 1px solid #e5e7eb;
            background: #fbfdff;
            align-items: flex-start;
        }

        .unit-name {
            font-size: 17px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 4px;
        }

        .unit-meta {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
        }

        .unit-badges {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .badge {
            padding: 7px 11px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-transform: capitalize;
        }

        .badge-status-tersedia {
            background: #dcfce7;
            color: #166534;
        }

        .badge-status-tidak_tersedia {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-condition-baik {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-condition-rusak,
        .badge-condition-rusak_ringan,
        .badge-condition-rusak_berat {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-condition-hilang {
            background: #fef3c7;
            color: #92400e;
        }

        .fallback-note {
            padding: 16px;
            border-radius: 16px;
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #92400e;
            line-height: 1.6;
        }

        @media (max-width: 1000px) {
            .detail-header,
            .summary-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .main {
                padding: 18px;
            }

            .topbar,
            .unit-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .detail-shell {
                padding: 18px;
            }

            .detail-media {
                min-height: 220px;
            }

            .detail-title {
                font-size: 24px;
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
        $totalUnit = $alat->units->count();
        $availableUnits = $alat->units->where('status', 'tersedia');
        $availableCount = $availableUnits->count();
        $defaultCondition = match ($alat->kondisi) {
            'rusak' => 'rusak',
            'hilang' => 'hilang',
            default => 'baik',
        };
        $fotoUrl = null;

        if ($alat->foto) {
            $fotoValue = $alat->foto;
            $fotoPath = \Illuminate\Support\Str::startsWith($fotoValue, ['alat/', 'public/alat/'])
                ? $fotoValue
                : 'alat/' . $fotoValue;
            $fotoPath = \Illuminate\Support\Str::startsWith($fotoPath, 'public/')
                ? \Illuminate\Support\Str::after($fotoPath, 'public/')
                : $fotoPath;
            $fotoUrl = $publicDisk->url($fotoPath);
        }
    @endphp

    <div class="layout">
        <x-peminjam-sidebar></x-peminjam-sidebar>

        <main class="main">
            <div class="topbar">
                <strong>Detail Alat</strong>
                <div class="user-info">
                    <x-notification-bell />
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    </div>
                    <span>{{ auth()->user()->nama }}</span>
                </div>
            </div>

            <div class="detail-shell">
                <div class="detail-header">
                    <div class="detail-media">
                        <div class="stock-pill">
                            Tersedia {{ $totalUnit > 0 ? $availableCount . ' / ' . $totalUnit : $alat->jumlah }}
                        </div>
                        <div class="category-pill">{{ $alat->kategori->nama_kategori ?? 'Tanpa kategori' }}</div>

                        @if($fotoUrl)
                            <img src="{{ $fotoUrl }}" alt="{{ $alat->nama_alat }}">
                        @else
                            <div class="detail-placeholder">AL</div>
                        @endif
                    </div>

                    <div class="detail-copy">
                        <a href="{{ route('peminjam.alat.index') }}" class="back-link">Kembali ke Daftar Alat</a>
                        <div>
                            <h1 class="detail-title">{{ $alat->nama_alat }}</h1>
                            <p class="detail-subtitle">
                                {{ $alat->keterangan ?: 'Belum ada keterangan tambahan untuk alat ini. Anda tetap bisa melihat stok, kategori, dan kondisi setiap unit pada halaman ini.' }}
                            </p>
                        </div>

                        <div class="summary-grid">
                            <div class="summary-card">
                                <span class="summary-label">Kategori</span>
                                <span class="summary-value">{{ $alat->kategori->nama_kategori ?? '-' }}</span>
                            </div>
                            <div class="summary-card">
                                <span class="summary-label">Stok Tersedia</span>
                                <span class="summary-value">{{ $totalUnit > 0 ? $availableCount . ' / ' . $totalUnit : $alat->jumlah }}</span>
                            </div>
                            <div class="summary-card">
                                <span class="summary-label">Kondisi Umum</span>
                                <span class="summary-value">{{ ucfirst(str_replace('_', ' ', $alat->kondisi)) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-title">Detail Unit Alat</div>
                    <div class="panel-desc">
                        Periksa kode unik dan kondisi setiap unit. Halaman ini khusus menampilkan informasi detail alat yang dipilih.
                    </div>

                    @if($totalUnit > 0)
                        <div class="unit-list">
                            @foreach($alat->units as $unit)
                                <div class="unit-item">
                                    <div>
                                        <div class="unit-name">{{ $unit->kode_unik ?: $alat->nama_alat }}</div>
                                        <div class="unit-meta">
                                            {{ $alat->nama_alat }}<br>
                                            {{ $unit->keterangan ?: 'Tidak ada catatan tambahan untuk unit ini.' }}
                                        </div>
                                    </div>

                                    <div class="unit-badges">
                                        <span class="badge badge-status-{{ $unit->status }}">
                                            {{ str_replace('_', ' ', $unit->status) }}
                                        </span>
                                        <span class="badge badge-condition-{{ $unit->kondisi }}">
                                            {{ str_replace('_', ' ', $unit->kondisi) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="fallback-note">
                            Detail per kode unik belum tersedia untuk alat ini. Sistem saat ini hanya menyimpan stok umum sebanyak
                            <strong>{{ $alat->jumlah }}</strong> dengan kondisi <strong>{{ str_replace('_', ' ', $defaultCondition) }}</strong>.
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>

</html>

