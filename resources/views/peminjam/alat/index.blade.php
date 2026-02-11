<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Alat</title>
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

        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #0f766e, #0d9488);
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
            background: rgba(16, 185, 129, 0.2);
            color: #d1fae5;
        }

        .sidebar-footer {
            margin-top: auto;
            font-size: 13px;
            opacity: 0.8;
        }

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
            background: linear-gradient(135deg, #34d399, #a7f3d0);
            color: #065f46;
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

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 10px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
        }

        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
        }

        .btn-primary {
            background: #0f766e;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #111827;
            border: none;
            padding: 10px 16px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
        }

        .section-title {
            font-size: 22px;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .section-desc {
            color: #6b7280;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: #f9fafb;
        }

        table th,
        table td {
            padding: 12px;
            border-bottom: 1px solid #f3f4f6;
            text-align: left;
        }

        table tbody tr:hover {
            background: #f9fafb;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .badge-baik {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-rusak {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-hilang {
            background: #fef3c7;
            color: #92400e;
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
        <aside class="sidebar">
            <div class="sidebar-brand">📚 Peminjaman Alat</div>

            <nav class="sidebar-menu">
                <a href="{{ route('dashboard') }}">🏠 Dashboard</a>
                <a href="{{ route('peminjam.alat.index') }}" class="active">🧰 Daftar Alat</a>
                <a href="{{ route('peminjaman.index') }}">📝 Ajukan Peminjaman</a>
                <a href="{{ route('peminjam.pengembalian.index') }}">📦 Pengembalian</a>
            </nav>

            <form method="POST" action="{{ route('logout') }}" style="margin-top: auto;">
                @csrf
                <button type="submit" class="logout-btn">🚪 Logout</button>
            </form>

            <div class="sidebar-footer">
                © {{ date('Y') }} Sistem Sekolah
            </div>
        </aside>

        <main class="main">
            <div class="topbar">
                <strong>Daftar Alat</strong>
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    </div>
                    <span>{{ auth()->user()->nama }}</span>
                </div>
            </div>

            <div class="content-card">
                <h2 class="section-title">Alat Tersedia</h2>
                <p class="section-desc">Lihat alat yang bisa dipinjam beserta kondisi dan jumlahnya.</p>

                <form method="GET" action="{{ route('peminjam.alat.index') }}" style="margin-bottom: 20px;">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="kategori_id">Kategori</label>
                            <select id="kategori_id" name="kategori_id">
                                <option value="">Semua Kategori</option>
                                @foreach($kategori as $item)
                                <option value="{{ $item->id }}" @if((string) $selectedKategori === (string) $item->id) selected @endif>
                                    {{ $item->nama_kategori }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kondisi">Kondisi</label>
                            <select id="kondisi" name="kondisi">
                                <option value="">Semua Kondisi</option>
                                <option value="baik" @if($selectedKondisi === 'baik') selected @endif>Kondisi Baik</option>
                                <option value="rusak" @if($selectedKondisi === 'rusak') selected @endif>Rusak</option>
                                <option value="hilang" @if($selectedKondisi === 'hilang') selected @endif>Hilang</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary">Terapkan Filter</button>
                    <a href="{{ route('peminjam.alat.index') }}" class="btn-secondary" style="text-decoration: none;">Reset</a>
                </form>

                <table>
                    <thead>
                        <tr>
                            <th>Nama Alat</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                            <th>Kondisi</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($alat as $item)
                        <tr>
                            <td>{{ $item->nama_alat }}</td>
                            <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                            <td>{{ $item->jumlah }}</td>
                            <td>
                                @php
                                    $kondisi = strtolower($item->kondisi ?? 'baik');
                                    $badgeClass = 'badge-baik';
                                    if ($kondisi === 'rusak') $badgeClass = 'badge-rusak';
                                    elseif ($kondisi === 'hilang') $badgeClass = 'badge-hilang';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($kondisi) }}</span>
                            </td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 30px; color: #9ca3af;">
                                Belum ada data alat
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>

</html>