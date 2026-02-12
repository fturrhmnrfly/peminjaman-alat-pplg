<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Memantau Pengembalian</title>
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

        .section-title {
            font-size: 22px;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .section-desc {
            color: #6b7280;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 12px;
            border-bottom: 1px solid #f3f4f6;
            text-align: left;
        }

        .table thead {
            background: #f9fafb;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-late {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-ok {
            background: #d1fae5;
            color: #065f46;
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

        <x-petugas-sidebar></x-petugas-sidebar>

        <main class="main">
            <div class="topbar">
                <strong>Memantau Pengembalian</strong>
                <div class="user-info">
                    <x-notification-bell />
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    </div>
                    <span>{{ auth()->user()->nama }}</span>
                </div>
            </div>

            <div class="content-card">
                <h2 class="section-title">Konfirmasi Pengembalian</h2>
                <p class="section-desc">Periksa alat yang dikembalikan dan konfirmasi jika sudah benar.</p>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Tanggal Pinjam</th>
                            <th>Batas Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman as $row)
                        <tr>
                            <td>
                                <strong>{{ $row->user->nama ?? '-' }}</strong>
                                <div style="color: #6b7280; font-size: 12px;">
                                    {{ $row->user->email ?? '-' }}
                                </div>
                            </td>
                            <td>
                                @forelse($row->detailPeminjamans as $detail)
                                <div>
                                    {{ $detail->alatUnit?->alat?->nama_alat ?? $detail->alat->nama_alat ?? '-' }}
                                    ({{ $detail->alatUnit?->kode_unik ?? '-' }})
                                </div>
                                @empty
                                <div style="color: #9ca3af;">Tidak ada detail alat</div>
                                @endforelse
                            </td>
                            <td>
                                <div>{{ optional($row->tanggal_pinjam)->format('d/m/Y') ?? '-' }}</div>
                                <div style="color: #6b7280; font-size: 12px;">
                                    {{ optional($row->waktu_pinjam)->format('H:i') ?? '-' }} WIB
                                </div>
                            </td>
                            <td>
                                <div>{{ optional($row->batas_kembali)->format('d/m/Y') ?? '-' }}</div>
                                <div style="color: #6b7280; font-size: 12px;">
                                    {{ optional($row->batas_kembali)->format('H:i') ?? '-' }} WIB
                                </div>
                            </td>
                            <td>
                                @php
                                    $isLate = optional($row->batas_kembali)->lt(\Carbon\Carbon::now('Asia/Jakarta'));
                                @endphp
                                @if($isLate)
                                    <span class="badge badge-late">Terlambat</span>
                                @else
                                    <span class="badge badge-ok">Menunggu Konfirmasi</span>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('petugas.pengembalian.konfirmasi', $row->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-primary">Konfirmasi</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #9ca3af;">
                                Tidak ada data pengembalian
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
