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

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-pending {
            background: #e0e7ff;
            color: #3730a3;
        }

        .badge-selesai {
            background: #d1fae5;
            color: #065f46;
        }

        .divider {
            margin: 28px 0;
            border: none;
            border-top: 1px solid #e5e7eb;
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
                    <x-notification-bell />
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    </div>
                    <span>{{ auth()->user()->nama }}</span>
                </div>
            </div>

            <div class="content-card">
                <h2 class="section-title">Daftar Alat Yang Sedang Dipinjam</h2>
                <p class="section-desc">Ajukan pengembalian untuk peminjaman yang sudah disetujui.</p>
                <div class="alert alert-error" style="margin-bottom: 15px;">
                    <strong>Peringatan:</strong> Alat harus dikembalikan paling lambat pukul 15:00 WIB ke ruang PPLG.
                    Terlambat dikenakan denda Rp 2.000 setiap kali melewati batas pukul 15:00 WIB. Jika besok lewat jam 15:00 WIB lagi, denda menjadi Rp 4.000, lalu terus bertambah untuk hari berikutnya. Jika terjadi kerusakan atau kehilangan, peminjam wajib bertanggung jawab kepada pihak sekolah.
                </div>

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
                            <th>Denda</th>
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
                                        <span class="item-name">{{ $detail->alatUnit?->alat?->nama_alat ?? $detail->alat->nama_alat ?? '-' }}</span>
                                        <span class="item-qty">{{ $detail->alatUnit?->kode_unik ?? '-' }}</span>
                                    </div>
                                    @empty
                                    <div style="color: #9ca3af;">Tidak ada detail alat</div>
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                @php
                                    $estimasiDenda = $row->hitungDendaOtomatis(now('Asia/Jakarta'));
                                    $estimasiHariTerlambat = (int) ($estimasiDenda / \App\Models\Peminjaman::DENDA_TERLAMBAT);
                                @endphp
                                @if($estimasiDenda > 0)
                                    <strong style="color: #b91c1c;">Rp {{ number_format($estimasiDenda, 0, ',', '.') }}</strong>
                                    <div style="color: #6b7280; font-size: 12px;">{{ $estimasiHariTerlambat }} kali melewati batas jam 15:00 WIB</div>
                                @else
                                    <span style="color: #065f46; font-weight: 600;">Tidak ada</span>
                                @endif
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
                            <td colspan="4" style="text-align: center; padding: 30px; color: #9ca3af;">
                                Tidak ada peminjaman yang perlu dikembalikan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <hr class="divider">

                <h2 class="section-title">Riwayat Pengembalian</h2>
                <p class="section-desc">Lihat pengembalian yang sudah diajukan, sedang menunggu konfirmasi, atau sudah selesai diproses.</p>

                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Detail Alat</th>
                            <th>Status</th>
                            <th>Total Denda</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatPengembalian as $row)
                        <tr>
                            <td>
                                <div>Pinjam: {{ optional($row->tanggal_pinjam)->format('d/m/Y') ?? '-' }}</div>
                                <div style="color: #6b7280; font-size: 12px;">
                                    Ajukan kembali: {{ optional($row->waktu_pengajuan_pengembalian)->format('d/m/Y H:i') ?? '-' }} WIB
                                </div>
                                <div style="color: #6b7280; font-size: 12px;">
                                    Dikonfirmasi: {{ optional($row->waktu_pengembalian)->format('d/m/Y H:i') ?? '-' }} WIB
                                </div>
                            </td>
                            <td>
                                <div class="item-list">
                                    @forelse($row->detailPeminjamans as $detail)
                                    <div class="item">
                                        <span class="item-name">{{ $detail->alatUnit?->alat?->nama_alat ?? $detail->alat->nama_alat ?? '-' }}</span>
                                        <span class="item-qty">{{ $detail->alatUnit?->kode_unik ?? '-' }}</span>
                                    </div>
                                    @empty
                                    <div style="color: #9ca3af;">Tidak ada detail alat</div>
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                @if($row->status === 'pengembalian_pending')
                                    <span class="badge badge-pending">Menunggu Konfirmasi</span>
                                @else
                                    <span class="badge badge-selesai">Dikembalikan</span>
                                @endif
                            </td>
                            <td>
                                @if($row->total_denda > 0)
                                    <strong style="color: #b91c1c;">{{ $row->total_denda_formatted }}</strong>
                                    <div style="color: #6b7280; font-size: 12px;">
                                        Terlambat: {{ $row->denda_formatted }} | Kerusakan: {{ $row->denda_kerusakan_total_formatted }}
                                    </div>
                                @else
                                    <span style="color: #065f46; font-weight: 600;">Tidak ada</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 30px; color: #9ca3af;">
                                Belum ada riwayat pengembalian
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
