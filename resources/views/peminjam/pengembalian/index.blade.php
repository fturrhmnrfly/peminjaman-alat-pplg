<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengembalian Alat</title>
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

        .section-title {
            font-size: 22px;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .section-desc {
            color: #6b7280;
            margin-bottom: 20px;
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

        table th,
        table td {
            padding: 12px;
            border-bottom: 1px solid #f3f4f6;
            text-align: left;
            vertical-align: top;
        }

        .item-list {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .item {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            background: #f9fafb;
            border-radius: 8px;
            padding: 8px 10px;
        }

        .item-name {
            font-weight: 600;
            color: #111827;
        }

        .item-qty {
            font-size: 12px;
            color: #6b7280;
            white-space: nowrap;
        }

        .btn-primary {
            background: #0f766e;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
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
        <x-peminjam-sidebar></x-peminjam-sidebar>

        <main class="main">
            <div class="topbar">
                <strong>Pengembalian Alat</strong>
                <div class="user-info">
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    </div>
                    <span>{{ auth()->user()->nama }}</span>
                </div>
            </div>

            <div class="content-card">
                <h2 class="section-title">Daftar Alat Yang Sedang Dipinjam</h2>
                <p class="section-desc">Ajukan pengembalian untuk peminjaman yang sudah disetujui.</p>

                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
                @endif

                <table>
                    <thead>
                        <tr>
                            <th>Tanggal Pinjam</th>
                            <th>Detail Alat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman as $row)
                        <tr>
                            <td>{{ optional($row->tanggal_pinjam)->format('d/m/Y') ?? '-' }}</td>
                            <td>
                                <div class="item-list">
                                    @forelse($row->detailPeminjamans as $detail)
                                    <div class="item">
                                        <span class="item-name">{{ $detail->alat->nama_alat ?? '-' }}</span>
                                        <span class="item-qty">x{{ $detail->jumlah_pinjam }}</span>
                                    </div>
                                    @empty
                                    <div style="color: #9ca3af;">Tidak ada detail alat</div>
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('peminjam.pengembalian.update', $row->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-primary">Ajukan Pengembalian</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 30px; color: #9ca3af;">
                                Tidak ada peminjaman yang perlu dikembalikan
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