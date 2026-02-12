<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajukan Peminjaman</title>
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

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
        }

        .item-rows {
            display: grid;
            gap: 12px;
            margin-bottom: 15px;
        }

        .item-row {
            display: grid;
            grid-template-columns: 1fr 40px;
            gap: 10px;
            align-items: center;
        }

        .item-row button {
            border: none;
            background: #fee2e2;
            color: #991b1b;
            padding: 6px 8px;
            border-radius: 8px;
            cursor: pointer;
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .badge-pending {
            background: #e0e7ff;
            color: #3730a3;
        }

        .badge-disetujui {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-ditolak {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-dikembalikan {
            background: #fef3c7;
            color: #92400e;
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
                <strong>Ajukan Peminjaman</strong>
                <div class="user-info">
                    <x-notification-bell />
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    </div>
                    <span>{{ auth()->user()->nama }}</span>
                </div>
            </div>

            <div class="content-card">
                <h2 class="section-title">Form Pengajuan</h2>
                <p class="section-desc">Pilih kode unik unit yang ingin dipinjam. Peminjaman dibuka pukul 07:00 - 15:00 WIB.</p>

                <div class="alert alert-error" style="margin-bottom: 15px;">
                    <strong>Peringatan:</strong> Alat harus dikembalikan paling lambat pukul 15:00 WIB ke ruang PPLG.
                    Terlambat dikenakan denda Rp 2.000. Jika terjadi kerusakan atau kehilangan, peminjam wajib bertanggung jawab kepada pihak sekolah.
                </div>

                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-error">
                    {{ $errors->first() }}
                </div>
                @endif

                <form method="POST" action="{{ route('peminjaman.store') }}">
                    @csrf
                    <div class="form-group">
                        <label>Detail Alat</label>
                        <div class="item-rows" id="item-rows">
                            <div class="item-row" data-index="0">
                                <select name="items[0][alat_unit_id]" required>
                                    <option value="">Pilih Kode Unik</option>
                                    @forelse($alatUnits as $unit)
                                    @php
                                        $label = ($unit->alat->nama_alat ?? '-') . ' - ' . $unit->kode_unik;
                                    @endphp
                                    <option value="{{ $unit->id }}">{{ $label }}</option>
                                    @empty
                                    <option value="" disabled>Tidak ada kode tersedia</option>
                                    @endforelse
                                </select>
                                <button type="button" class="remove-row" style="display:none;">✕</button>
                            </div>
                        </div>
                        <button type="button" id="add-row" class="btn-secondary">Tambah Kode</button>
                    </div>

                    <div style="margin-top: 15px;">
                        <button type="submit" class="btn-primary">Kirim Pengajuan</button>
                    </div>
                </form>

                <hr style="margin: 25px 0; border: none; border-top: 1px solid #e5e7eb;">

                <h2 class="section-title">Riwayat Pengajuan</h2>
                <p class="section-desc">Daftar pengajuan yang pernah Anda buat.</p>

                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Detail Alat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjaman as $row)
                        <tr>
                            <td>
                                <div>Pinjam: {{ optional($row->tanggal_pinjam)->format('d/m/Y') ?? '-' }}</div>
                                <div style="color: #6b7280; font-size: 12px;">
                                    Jam: {{ optional($row->waktu_pinjam)->format('H:i') ?? '-' }} WIB
                                </div>
                                <div>Batas: {{ optional($row->batas_kembali)->format('d/m/Y H:i') ?? '-' }} WIB</div>
                                <div>Kembali: {{ optional($row->tanggal_kembali)->format('d/m/Y') ?? '-' }}</div>
                            </td>
                            <td>
                                @php
                                    $status = strtolower($row->status ?? 'pending');
                                    $badgeClass = 'badge-pending';
                                    if ($status === 'disetujui') $badgeClass = 'badge-disetujui';
                                    elseif ($status === 'ditolak') $badgeClass = 'badge-ditolak';
                                    elseif ($status === 'pengembalian_pending') $badgeClass = 'badge-pending';
                                    elseif ($status === 'dikembalikan') $badgeClass = 'badge-dikembalikan';
                                @endphp
                                @php
                                    $statusLabel = $status;
                                    if ($status === 'pengembalian_pending') $statusLabel = 'Menunggu Konfirmasi';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ ucfirst($statusLabel) }}</span>
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
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 30px; color: #9ca3af;">
                                Belum ada pengajuan peminjaman
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <script>
        const itemRows = document.getElementById('item-rows');
        const addRowBtn = document.getElementById('add-row');

        addRowBtn.addEventListener('click', () => {
            const lastRow = itemRows.querySelector('.item-row:last-child');
            const nextIndex = Number(lastRow.dataset.index) + 1;
            const newRow = lastRow.cloneNode(true);

            newRow.dataset.index = nextIndex;
            newRow.querySelectorAll('select').forEach((field) => {
                field.name = `items[${nextIndex}][alat_unit_id]`;
                field.value = '';
            });

            const removeBtn = newRow.querySelector('.remove-row');
            removeBtn.style.display = 'inline-block';
            removeBtn.addEventListener('click', () => newRow.remove());

            itemRows.appendChild(newRow);
        });
    </script>
</body>

</html>
