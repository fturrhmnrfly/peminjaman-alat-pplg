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

        body.modal-open {
            overflow: hidden;
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
            padding: 28px;
            border-radius: 22px;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
            overflow: hidden;
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
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
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 16px;
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

        .alert-warning {
            background: #fef3c7;
            color: #92400e;
            border-left: 4px solid #f59e0b;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            overflow: hidden;
            border-radius: 18px;
        }

        .table th,
        .table td {
            padding: 16px 14px;
            border-bottom: 1px solid #f3f4f6;
            text-align: left;
            vertical-align: middle;
        }

        .table thead {
            background: #f8fafc;
        }

        .table th {
            font-size: 11px;
            font-weight: 700;
            color: #111827;
            letter-spacing: 0.01em;
            line-height: 1.35;
        }

        .table tbody tr {
            background: white;
            transition: background 0.2s ease;
        }

        .table tbody tr:hover {
            background: #fcfdfd;
        }

        .table td {
            font-size: 13px;
            line-height: 1.4;
        }

        .table th:nth-child(5),
        .table td:nth-child(5) {
            padding-right: 8px;
        }

        .table th:nth-child(6),
        .table td:nth-child(6) {
            padding-left: 8px;
        }

        .table th:nth-child(1) {
            width: 16%;
        }

        .table th:nth-child(2) {
            width: 13%;
        }

        .table th:nth-child(3) {
            width: 13%;
        }

        .table th:nth-child(4) {
            width: 12%;
        }

        .table th:nth-child(5) {
            width: 18%;
        }

        .table th:nth-child(6) {
            width: 28%;
        }

        .badge {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 11px;
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
            padding: 10px 14px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
            margin-top: 6px;
        }

        .inspection-toggle {
            display: inline-flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            background: #0f766e;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 9px 12px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-align: left;
            line-height: 1.3;
            min-width: 138px;
            box-shadow: 0 10px 20px rgba(15, 118, 110, 0.14);
            transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .inspection-toggle:hover {
            background: #115e59;
            transform: translateY(-1px);
            box-shadow: 0 14px 24px rgba(15, 118, 110, 0.18);
        }

        .inspection-toggle.is-open {
            background: #1f2937;
        }

        .inspection-toggle small {
            display: block;
            color: rgba(255, 255, 255, 0.8);
            font-size: 10px;
            font-weight: 500;
            margin-top: 1px;
        }

        .inspection-modal {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: rgba(15, 23, 42, 0.42);
            backdrop-filter: blur(5px);
            z-index: 50;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }

        .inspection-modal[hidden] {
            display: none;
        }

        .inspection-modal.is-visible {
            opacity: 1;
            pointer-events: auto;
        }

        .inspection-dialog {
            width: min(860px, 100%);
            max-height: min(86vh, 860px);
            overflow: auto;
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.22);
            transform: translateY(18px) scale(0.97);
            opacity: 0;
            transition: transform 0.28s ease, opacity 0.28s ease;
        }

        .inspection-modal.is-visible .inspection-dialog {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .inspection-dialog-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding: 22px 24px 14px;
            border-bottom: 1px solid #eef2f7;
        }

        .inspection-dialog-title {
            font-size: 20px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }

        .inspection-dialog-subtitle {
            font-size: 13px;
            color: #6b7280;
            line-height: 1.5;
        }

        .inspection-close {
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 999px;
            background: #f3f4f6;
            color: #374151;
            font-size: 20px;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.2s ease;
        }

        .inspection-close:hover {
            background: #e5e7eb;
            transform: scale(1.04);
        }

        .inspection-dialog-body {
            padding: 20px 24px 24px;
            background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
        }

        .return-confirm-form {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .inspection-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            min-width: 0;
        }

        .inspection-card {
            border: 1px solid #dbe2ea;
            border-radius: 18px;
            padding: 16px;
            background: linear-gradient(180deg, #fbfdff 0%, #f8fafc 100%);
            box-shadow: 0 10px 20px rgba(148, 163, 184, 0.08);
            overflow: hidden;
        }

        .inspection-title {
            font-weight: 700;
            font-size: 15px;
            color: #111827;
            margin-bottom: 4px;
        }

        .inspection-meta {
            color: #6b7280;
            font-size: 12px;
            margin-bottom: 14px;
        }

        .inspection-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .inspection-item-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 82px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #ccfbf1;
            color: #115e59;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .inspection-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 14px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 0;
        }

        .form-group label {
            font-size: 12px;
            font-weight: 600;
            color: #374151;
        }

        .form-control {
            width: 100%;
            max-width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            background: white;
            font-size: 13px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        select.form-control,
        input.form-control,
        textarea.form-control {
            display: block;
        }

        .form-control:focus {
            outline: none;
            border-color: #14b8a6;
            box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.12);
        }

        textarea.form-control {
            min-height: 88px;
            max-height: 140px;
            resize: vertical;
            line-height: 1.45;
        }

        .form-group-span {
            grid-column: 1 / -1;
        }

        .form-hint {
            color: #6b7280;
            font-size: 12px;
            line-height: 1.5;
            padding: 12px 14px;
            border-radius: 14px;
            background: #ecfeff;
            border: 1px solid #bae6fd;
        }

        .payment-method-box {
            padding: 14px;
            border: 1px solid #dbe2ea;
            border-radius: 16px;
            background: #ffffff;
        }

        .payment-method-box .form-group {
            margin-bottom: 0;
        }

        .payment-method-box[hidden] {
            display: none;
        }

        .payment-summary {
            margin-top: 8px;
            font-size: 12px;
            color: #374151;
            line-height: 1.5;
        }

        .payment-summary strong {
            color: #0f766e;
        }

        .inspection-actions {
            display: flex;
            justify-content: flex-end;
        }

        .inspection-actions .btn-primary {
            width: auto;
            min-width: 220px;
            margin-top: 0;
        }

        .qris-modal-card {
            width: min(420px, 100%);
            background: #ffffff;
            border-radius: 24px;
            padding: 26px 24px 24px;
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.22);
            transform: translateY(18px) scale(0.97);
            opacity: 0;
            transition: transform 0.28s ease, opacity 0.28s ease;
            text-align: center;
        }

        .inspection-modal.is-visible .qris-modal-card {
            transform: translateY(0) scale(1);
            opacity: 1;
        }

        .qris-title {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 8px;
        }

        .qris-desc {
            color: #6b7280;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .qris-amount {
            margin-bottom: 18px;
            font-size: 26px;
            font-weight: 800;
            color: #0f766e;
        }

        .dummy-qris {
            width: 220px;
            height: 220px;
            margin: 0 auto 18px;
            border-radius: 18px;
            padding: 16px;
            background:
                linear-gradient(90deg, #111827 10px, transparent 10px) 0 0 / 22px 22px,
                linear-gradient(#111827 10px, transparent 10px) 0 0 / 22px 22px,
                linear-gradient(90deg, transparent 12px, #111827 12px) 11px 11px / 22px 22px,
                linear-gradient(transparent 12px, #111827 12px) 11px 11px / 22px 22px,
                #ffffff;
            border: 10px solid #f3f4f6;
            box-shadow: inset 0 0 0 1px #e5e7eb;
        }

        .dummy-qris::before,
        .dummy-qris::after {
            content: '';
            position: absolute;
        }

        .qris-box-wrap {
            position: relative;
            width: 220px;
            margin: 0 auto 18px;
        }

        .qris-box-wrap .dummy-qris {
            margin-bottom: 0;
        }

        .dummy-qris-corner {
            position: absolute;
            width: 48px;
            height: 48px;
            border: 8px solid #111827;
            background: #ffffff;
        }

        .dummy-qris-corner.top-left {
            top: 16px;
            left: 16px;
        }

        .dummy-qris-corner.top-right {
            top: 16px;
            right: 16px;
        }

        .dummy-qris-corner.bottom-left {
            bottom: 16px;
            left: 16px;
        }

        .qris-note {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 18px;
        }

        .qris-actions {
            display: grid;
            gap: 10px;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
            border: none;
            padding: 10px 14px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            width: 100%;
        }

        .denda-breakdown {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .text-muted {
            color: #6b7280;
            font-size: 11px;
        }

        .status-wrap {
            display: flex;
            flex-direction: column;
            gap: 6px;
            align-items: flex-start;
            justify-content: center;
            min-height: 100%;
        }

        .status-note {
            color: #6b7280;
            font-size: 12px;
            line-height: 1.45;
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

        @media (max-width: 1200px) {
            .table {
                table-layout: auto;
            }
        }

        @media (max-width: 900px) {
            .main {
                padding: 18px;
            }

            .content-card {
                padding: 20px;
            }

            .table th,
            .table td {
                padding: 14px 10px;
            }

            .inspection-toggle {
                min-width: 136px;
            }

            .inspection-dialog {
                width: min(100%, 640px);
                max-height: 90vh;
            }

            .inspection-dialog-header,
            .inspection-dialog-body {
                padding-left: 18px;
                padding-right: 18px;
            }

            .inspection-grid {
                grid-template-columns: 1fr;
            }

            .inspection-card-header {
                flex-direction: column;
            }

            .inspection-item-badge {
                min-width: 0;
            }

            .inspection-actions .btn-primary {
                width: 100%;
                min-width: 0;
            }
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
                    <x-profile-shortcut />
                </div>
            </div>

            <div class="content-card">
                <h2 class="section-title">Konfirmasi Pengembalian</h2>
                <p class="section-desc">Catat hasil pemeriksaan setelah barang diterima. Jika ada tagihan, sistem akan mengirim notifikasi email lebih dulu dan pembayaran denda dikonfirmasi pada langkah terpisah.</p>

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

                @if(session('warning'))
                <div class="alert alert-warning">
                    {{ session('warning') }}
                </div>
                @endif

                @if($errors->any())
                <div class="alert alert-error">
                    {{ $errors->first() }}
                </div>
                @endif

                <div class="table-wrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Peminjam</th>
                            <th>Tanggal Pinjam</th>
                            <th>Batas Kembali</th>
                            <th>Denda Terlambat</th>
                            <th>Aksi Petugas</th>
                            <th>Status</th>
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
                                <div>{{ optional($row->tanggal_pinjam)->format('d/m/Y') ?? '-' }}</div>
                                <div class="text-muted">
                                    {{ optional($row->waktu_pinjam)->format('H:i') ?? '-' }} WIB
                                </div>
                            </td>
                            <td>
                                <div>{{ optional($row->batas_kembali)->format('d/m/Y') ?? '-' }}</div>
                                <div class="text-muted">
                                    {{ optional($row->batas_kembali)->format('H:i') ?? '-' }} WIB
                                </div>
                            </td>
                            <td>
                                @if(($row->denda ?? 0) > 0)
                                    <div class="denda-breakdown">
                                        <strong style="color: #b91c1c;">{{ $row->denda_formatted }}</strong>
                                        <div class="text-muted">
                                            Diajukan {{ optional($row->waktu_pengajuan_pengembalian)->format('H:i') ?? '-' }} WIB
                                        </div>
                                        <div class="text-muted">
                                            {{ $row->jumlah_hari_terlambat }} kali melewati batas 15:00 WIB
                                        </div>
                                    </div>
                                @else
                                    <span style="color: #065f46; font-weight: 600;">Tidak ada</span>
                                @endif
                            </td>
                            @php
                                $modalId = 'inspection-modal-' . $row->id;
                            @endphp
                            <td>
                                @if($row->status === \App\Models\Peminjaman::STATUS_PENGEMBALIAN_PENDING)
                                    <form method="POST" action="{{ route('petugas.pengembalian.terima', $row->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="inspection-toggle" style="width: 100%;">
                                            Konfirmasi Diterima
                                            <small>Barang sudah dikembalikan peminjam</small>
                                        </button>
                                    </form>
                                @elseif($row->status === \App\Models\Peminjaman::STATUS_MENUNGGU_PEMERIKSAAN)
                                    <button type="button" class="inspection-toggle" data-target="{{ $modalId }}" aria-expanded="false" aria-controls="{{ $modalId }}">
                                        Form Pemeriksaan
                                        <small>{{ $row->detailPeminjamans->count() }} barang</small>
                                    </button>
                                @else
                                    <div class="status-note">
                                        Pemeriksaan selesai.
                                        <br>
                                        Menunggu penyelesaian tagihan.
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="status-wrap">
                                    @php
                                        $badgeClass = $row->status === \App\Models\Peminjaman::STATUS_DIKEMBALIKAN ? 'badge-ok' : 'badge-late';
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $row->status_label }}</span>
                                    @if($row->status === \App\Models\Peminjaman::STATUS_PENGEMBALIAN_PENDING)
                                        <div class="status-note">
                                            Pengembalian sudah diajukan peminjam dan masih menunggu petugas menerima barang fisik.
                                        </div>
                                    @endif
                                    @if($row->status === \App\Models\Peminjaman::STATUS_MENUNGGU_PEMERIKSAAN)
                                        <div class="status-note">
                                            Denda keterlambatan sudah otomatis tercatat. Denda kerusakan belum ada sampai pemeriksaan selesai.
                                        </div>
                                    @endif
                                    @if($row->status === \App\Models\Peminjaman::STATUS_MENUNGGU_PEMBAYARAN)
                                        <div class="status-note">
                                            Total tagihan: <strong>{{ $row->total_denda_formatted }}</strong>
                                        </div>
                                        <form method="POST" action="{{ route('petugas.pengembalian.pembayaran', $row->id) }}" class="payment-confirm-form" data-total-denda="{{ (int) $row->total_denda }}" data-peminjam="{{ $row->user->nama ?? '-' }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="payment_confirmed" value="0" class="payment-confirmed-input">
                                            <div class="form-group" style="width: 100%;">
                                                <label for="pay_method_{{ $row->id }}">Metode Pembayaran</label>
                                                <select id="pay_method_{{ $row->id }}" name="metode_pembayaran" class="form-control payment-method-select" required>
                                                    <option value="">Pilih metode pembayaran</option>
                                                    <option value="tunai">Tunai</option>
                                                    <option value="qris_all_payment">QRIS All Payment</option>
                                                </select>
                                            </div>
                                            <button type="submit" class="btn-primary">Konfirmasi Pembayaran</button>
                                        </form>
                                    @elseif($row->status === \App\Models\Peminjaman::STATUS_PENGEMBALIAN_PENDING)
                                        <div class="status-note">
                                            Barang sudah diajukan kembali pada {{ optional($row->waktu_pengajuan_pengembalian)->format('H:i') ?? '-' }} WIB dan menunggu pemeriksaan petugas.
                                        </div>
                                    @endif
                                </div>
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
            </div>
        </main>
    </div>

    @foreach($peminjaman as $row)
    @php
        $modalId = 'inspection-modal-' . $row->id;
    @endphp
    <div id="{{ $modalId }}" class="inspection-modal" hidden>
        <div class="inspection-dialog" role="dialog" aria-modal="true" aria-labelledby="inspection-title-{{ $row->id }}">
            <div class="inspection-dialog-header">
                <div>
                    <div id="inspection-title-{{ $row->id }}" class="inspection-dialog-title">Pemeriksaan Barang</div>
                    <div class="inspection-dialog-subtitle">
                        {{ $row->user->nama ?? '-' }} | {{ optional($row->tanggal_pinjam)->format('d/m/Y') ?? '-' }} | {{ $row->detailPeminjamans->count() }} barang
                    </div>
                </div>
                <button type="button" class="inspection-close" data-close-modal="{{ $modalId }}" aria-label="Tutup form pemeriksaan">x</button>
            </div>

            <div class="inspection-dialog-body">
                <form method="POST" action="{{ route('petugas.pengembalian.konfirmasi', $row->id) }}" class="return-confirm-form" data-row-id="{{ $row->id }}">
                    @csrf
                    @method('PATCH')

                    <div class="inspection-list">
                        @forelse($row->detailPeminjamans as $detail)
                        <div class="inspection-card">
                            <div class="inspection-card-header">
                                <div>
                                    <div class="inspection-title">
                                        {{ $detail->alatUnit?->alat?->nama_alat ?? $detail->alat->nama_alat ?? '-' }}
                                    </div>
                                    <div class="inspection-meta">
                                        Kode unit: {{ $detail->alatUnit?->kode_unik ?? '-' }}
                                    </div>
                                </div>
                                <span class="inspection-item-badge">1 barang</span>
                            </div>

                            <div class="inspection-grid">
                                <div class="form-group">
                                    <label for="kondisi_{{ $detail->id }}">Kondisi Barang</label>
                                    <select id="kondisi_{{ $detail->id }}" name="items[{{ $detail->id }}][kondisi_pengembalian]" class="form-control" required>
                                        <option value="baik" @selected(old("items.{$detail->id}.kondisi_pengembalian", $detail->kondisi_pengembalian ?? 'baik') === 'baik')>Baik</option>
                                        <option value="rusak_ringan" @selected(old("items.{$detail->id}.kondisi_pengembalian", $detail->kondisi_pengembalian) === 'rusak_ringan')>Rusak Ringan</option>
                                        <option value="rusak_berat" @selected(old("items.{$detail->id}.kondisi_pengembalian", $detail->kondisi_pengembalian) === 'rusak_berat')>Rusak Berat</option>
                                        <option value="hilang" @selected(old("items.{$detail->id}.kondisi_pengembalian", $detail->kondisi_pengembalian) === 'hilang')>Hilang</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="denda_{{ $detail->id }}">Denda Kerusakan</label>
                                    <input
                                        id="denda_{{ $detail->id }}"
                                        type="number"
                                        min="0"
                                        step="1000"
                                        name="items[{{ $detail->id }}][denda_kerusakan]"
                                        class="form-control"
                                        value="{{ old("items.{$detail->id}.denda_kerusakan", $detail->denda_kerusakan ?? 0) }}"
                                        placeholder="Contoh: 50000"
                                    >
                                </div>

                                <div class="form-group form-group-span">
                                    <label for="detail_kerusakan_{{ $detail->id }}">Detail Kerusakan</label>
                                    <textarea
                                        id="detail_kerusakan_{{ $detail->id }}"
                                        name="items[{{ $detail->id }}][detail_kerusakan]"
                                        class="form-control"
                                        placeholder="Contoh: Tombol keyboard kiri tidak berfungsi, casing retak di sudut kanan."
                                    >{{ old("items.{$detail->id}.detail_kerusakan", $detail->detail_kerusakan) }}</textarea>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div style="color: #9ca3af;">Tidak ada detail alat</div>
                        @endforelse
                    </div>

                    <div class="form-hint">
                        Isi denda kerusakan dengan biaya service atau penggantian. Jika total denda lebih dari `0`, sistem akan mengirim email pemberitahuan ke peminjam dan status berubah menjadi menunggu pembayaran denda.
                    </div>

                    <div class="inspection-actions">
                        <button type="submit" class="btn-primary">Simpan Hasil Pemeriksaan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    <div id="qris-payment-modal" class="inspection-modal" hidden>
        <div class="qris-modal-card" role="dialog" aria-modal="true" aria-labelledby="qris-modal-title">
            <div id="qris-modal-title" class="qris-title">Pembayaran QRIS</div>
            <div class="qris-desc">
                Silakan lanjutkan simulasi pembayaran QRIS untuk menyelesaikan konfirmasi pengembalian.
            </div>
            <div class="qris-amount" id="qris-payment-amount">Rp 0</div>
            <div class="qris-box-wrap">
                <div class="dummy-qris"></div>
                <span class="dummy-qris-corner top-left"></span>
                <span class="dummy-qris-corner top-right"></span>
                <span class="dummy-qris-corner bottom-left"></span>
            </div>
            <div class="qris-note" id="qris-payment-note">
                QR dummy ini hanya untuk simulasi pembayaran.
            </div>
            <div class="qris-actions">
                <button type="button" class="btn-primary" id="qris-confirm-button">Tandai Sudah Dibayar</button>
                <button type="button" class="btn-secondary" id="qris-cancel-button">Kembali</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var activeQrisForm = null;
            var qrisModal = document.getElementById('qris-payment-modal');
            var qrisAmount = document.getElementById('qris-payment-amount');
            var qrisNote = document.getElementById('qris-payment-note');
            var qrisConfirmButton = document.getElementById('qris-confirm-button');
            var qrisCancelButton = document.getElementById('qris-cancel-button');

            function formatRupiah(value) {
                return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
            }

            function closeAllModals() {
                document.querySelectorAll('.inspection-modal').forEach(function(modal) {
                    modal.setAttribute('hidden', '');
                    modal.classList.remove('is-visible');
                });
                document.querySelectorAll('.inspection-toggle').forEach(function(button) {
                    button.classList.remove('is-open');
                    button.setAttribute('aria-expanded', 'false');
                });
                document.body.classList.remove('modal-open');
                activeQrisForm = null;
                qrisConfirmButton.disabled = false;
                qrisCancelButton.disabled = false;
                qrisConfirmButton.textContent = 'Tandai Sudah Dibayar';
            }

            function openQrisModal(form) {
                activeQrisForm = form;
                var total = Number(form.dataset.totalDenda || 0);
                var borrower = form.dataset.peminjam || 'peminjam';

                qrisAmount.textContent = formatRupiah(total);
                qrisNote.textContent = 'QR dummy ini hanya untuk simulasi pembayaran ' + borrower + '. Setelah pembayaran dianggap selesai, klik tombol di bawah.';
                qrisModal.removeAttribute('hidden');
                requestAnimationFrame(function() {
                    qrisModal.classList.add('is-visible');
                });
                document.body.classList.add('modal-open');
                qrisConfirmButton.disabled = false;
                qrisCancelButton.disabled = false;
                qrisConfirmButton.textContent = 'Tandai Sudah Dibayar';
            }

            document.querySelectorAll('.inspection-toggle').forEach(function(button) {
                button.addEventListener('click', function() {
                    var modal = document.getElementById(button.dataset.target);
                    var isOpen = modal.classList.contains('is-visible');

                    if (isOpen) {
                        closeAllModals();
                    } else {
                        closeAllModals();
                        modal.removeAttribute('hidden');
                        requestAnimationFrame(function() {
                            modal.classList.add('is-visible');
                        });
                        button.classList.add('is-open');
                        button.setAttribute('aria-expanded', 'true');
                        document.body.classList.add('modal-open');
                    }
                });
            });

            document.querySelectorAll('.return-confirm-form').forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.dataset.submitting === '1') {
                        event.preventDefault();
                        return;
                    }
                    form.dataset.submitting = '1';
                });
            });

            document.querySelectorAll('.payment-confirm-form').forEach(function(form) {
                var select = form.querySelector('.payment-method-select');
                var confirmedInput = form.querySelector('.payment-confirmed-input');

                if (select && confirmedInput) {
                    select.addEventListener('change', function() {
                        confirmedInput.value = '0';
                    });
                }

                form.addEventListener('submit', function(event) {
                    if (form.dataset.submitting === '1') {
                        event.preventDefault();
                        return;
                    }

                    var paymentMethod = select ? select.value : '';

                    if (!paymentMethod) {
                        event.preventDefault();
                        if (select) {
                            select.focus();
                        }
                        return;
                    }

                    if (paymentMethod === 'qris_all_payment' && confirmedInput && confirmedInput.value !== '1') {
                        event.preventDefault();
                        openQrisModal(form);
                        return;
                    }

                    form.dataset.submitting = '1';
                });
            });

            document.querySelectorAll('[data-close-modal]').forEach(function(button) {
                button.addEventListener('click', function() {
                    closeAllModals();
                });
            });

            qrisConfirmButton.addEventListener('click', function() {
                if (!activeQrisForm) {
                    closeAllModals();
                    return;
                }

                var confirmedInput = activeQrisForm.querySelector('.payment-confirmed-input');
                var submitButton = activeQrisForm.querySelector('button[type="submit"]');

                if (confirmedInput) {
                    confirmedInput.value = '1';
                }

                activeQrisForm.dataset.submitting = '1';
                qrisConfirmButton.disabled = true;
                qrisCancelButton.disabled = true;
                qrisConfirmButton.textContent = 'Memproses...';

                if (submitButton) {
                    submitButton.disabled = true;
                }

                qrisModal.classList.remove('is-visible');
                qrisModal.setAttribute('hidden', '');
                activeQrisForm.submit();
            });

            qrisCancelButton.addEventListener('click', function() {
                if (activeQrisForm) {
                    var confirmedInput = activeQrisForm.querySelector('.payment-confirmed-input');
                    if (confirmedInput) {
                        confirmedInput.value = '0';
                    }
                }
                closeAllModals();
            });

            document.querySelectorAll('.inspection-modal').forEach(function(modal) {
                modal.addEventListener('click', function(event) {
                    if (event.target === modal) {
                        closeAllModals();
                    }
                });
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeAllModals();
                }
            });
        });
    </script>
</body>

</html>
