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
            background: var(--peminjam-page-bg);
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
            background: linear-gradient(135deg, var(--peminjam-avatar-start), var(--peminjam-avatar-end));
            color: var(--peminjam-avatar-text);
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
            color: var(--peminjam-accent-strong);
            border-left: 4px solid var(--peminjam-accent-soft-2);
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
            background: linear-gradient(135deg, var(--peminjam-accent) 0%, #2563eb 100%);
            color: white;
            border: none;
            padding: 10px 18px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(30, 58, 138, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
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
            color: var(--peminjam-accent-strong);
        }

        .divider {
            margin: 28px 0;
            border: none;
            border-top: 1px solid #e5e7eb;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #1e293b;
            border: 1px solid #cbd5e1;
            padding: 10px 18px;
            border-radius: 12px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .btn-secondary:hover {
            background: #e2e8f0;
            border-color: #94a3b8;
            transform: translateY(-2px);
        }

        .btn-secondary:active {
            transform: translateY(0);
        }



        .payment-modal {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 50;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .payment-modal[hidden] {
            display: none;
        }

        .payment-modal-card {
            width: min(100%, 520px);
            background: #ffffff;
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.25), 0 0 0 1px rgba(255, 255, 255, 0.1) inset;
            animation: slideUp 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideUp {
            from {
                transform: translateY(24px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .payment-modal-title {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .payment-modal-desc {
            color: #64748b;
            margin-bottom: 20px;
            line-height: 1.6;
            font-size: 14px;
        }

        .payment-amount {
            font-size: 36px;
            font-weight: 800;
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #0f172a;
            font-weight: 700;
            text-transform: capitalize;
        }

        .form-control {
            width: 100%;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            padding: 12px 14px;
            font-size: 14px;
            background: #ffffff;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-family: inherit;
        }

        .form-control:hover {
            border-color: #94a3b8;
        }

        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .qris-preview {
            margin: 20px 0;
            padding: 20px;
            border-radius: 18px;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .qris-preview[hidden] {
            display: none;
        }

        .qris-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            font-weight: 700;
            font-size: 15px;
        }

        .dummy-qris {
            width: 180px;
            height: 180px;
            margin: 0 auto 16px;
            border-radius: 16px;
            background:
                linear-gradient(90deg, #fff 10px, transparent 10px) 0 0 / 20px 20px,
                linear-gradient(#fff 10px, transparent 10px) 0 0 / 20px 20px,
                linear-gradient(135deg, #93c5fd, #ffffff);
            box-shadow: inset 0 0 0 10px rgba(255, 255, 255, 0.12), 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .qris-note {
            font-size: 13px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.88);
            text-align: center;
            font-weight: 500;
        }



        .payment-confirm {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin: 20px 0 24px;
            color: #334155;
            font-size: 13px;
            line-height: 1.6;
        }

        .payment-confirm input {
            margin-top: 3px;
            cursor: pointer;
            width: 18px;
            height: 18px;
            accent-color: #2563eb;
        }

        .payment-modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            flex-wrap: wrap;
        }

        .logout-btn {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
        }

        /* Hover effect untuk button */
        button {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Table row hover effect yang subtle */
        table tbody tr {
            transition: background-color 0.2s ease;
        }

        table tbody tr:hover {
            background-color: #f9fafb;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .main {
                padding: 15px;
            }

            .payment-modal-card {
                width: min(100%, 95%);
                padding: 20px;
            }

            .btn-primary,
            .btn-secondary {
                padding: 9px 14px;
                font-size: 13px;
            }

            .payment-amount {
                font-size: 28px;
            }

            .payment-modal-title {
                font-size: 20px;
            }

            table {
                font-size: 12px;
            }

            table th,
            table td {
                padding: 8px;
            }
        }
    </style>
</head>

<body data-swal-title="Peringatan" data-page-motion="table">
    <div class="layout">
        <x-peminjam-sidebar></x-peminjam-sidebar>

        <main class="main">
            <div class="topbar">
                <strong>Pengembalian Alat</strong>
                <div class="user-info">
                    <x-notification-bell />
                    <x-profile-shortcut />
                </div>
            </div>

            <div class="content-card">
                <h2 class="section-title">Daftar Alat Yang Sedang Dipinjam</h2>
                <p class="section-desc">Ajukan pengembalian untuk peminjaman yang sudah disetujui.</p>
                <div class="alert alert-error" data-swal-ignore="true" style="margin-bottom: 15px;">
                    <strong>Peringatan:</strong> Alat harus dikembalikan paling lambat pukul 15:00 WIB ke ruang PPLG.
                    Terlambat dikenakan denda Rp 2.000 setiap kali melewati batas pukul 15:00 WIB. Jika terjadi kerusakan atau kehilangan, peminjam wajib bertanggung jawab kepada pihak sekolah.
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

                @if(session('warning'))
                <div class="alert alert-warning">
                    {{ session('warning') }}
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
                            <th>Pembayaran</th>
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
                                    Waktu pengembalian dicatat: {{ optional($row->waktu_pengembalian)->format('d/m/Y H:i') ?? '-' }} WIB
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
                                @php
                                    $badgeClass = $row->status === \App\Models\Peminjaman::STATUS_DIKEMBALIKAN
                                        ? 'badge-selesai'
                                        : 'badge-pending';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $row->status_label }}</span>
                                @if($row->status === \App\Models\Peminjaman::STATUS_MENUNGGU_PEMERIKSAAN)
                                    <div style="color: #6b7280; font-size: 12px; margin-top: 6px;">
                                        Barang sudah diterima petugas, tetapi kondisi kerusakan masih diperiksa.
                                    </div>
                                @elseif($row->status === \App\Models\Peminjaman::STATUS_MENUNGGU_PEMBAYARAN)
                                    <div style="color: #6b7280; font-size: 12px; margin-top: 6px;">
                                        Denda keterlambatan sudah tercatat. Jika ada kerusakan, tagihan kerusakan juga akan muncul di total denda.
                                    </div>
                                @elseif($row->is_denda_lunas)
                                    <div style="color: #065f46; font-size: 12px; margin-top: 6px; font-weight: 600;">
                                        Denda sudah dibayar{{ $row->metode_pembayaran ? ' via ' . $row->metode_pembayaran_label : '' }}.
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($row->is_denda_lunas)
                                    <span style="color: #065f46; font-weight: 600;">Sudah dibayar</span>
                                    <div style="color: #6b7280; font-size: 12px;">
                                        Status: {{ $row->status_pembayaran_denda_label }}
                                    </div>
                                @elseif($row->total_denda > 0)
                                    <strong style="color: #b91c1c;">{{ $row->sisa_denda_formatted }}</strong>
                                    <div style="color: #6b7280; font-size: 12px;">
                                        Terlambat: {{ $row->denda_formatted }} | Kerusakan: {{ $row->denda_kerusakan_total_formatted }}
                                    </div>
                                @else
                                    <span style="color: #065f46; font-weight: 600;">Tidak ada</span>
                                @endif
                            </td>
                            <td>
                                @if($row->status === \App\Models\Peminjaman::STATUS_MENUNGGU_PEMBAYARAN && $row->total_denda > 0)
                                    <div class="payment-card">
                                        <div class="payment-actions">
                                            <button
                                                type="button"
                                                class="btn-primary open-payment-modal"
                                                data-form-id="payment-form-{{ $row->id }}"
                                                data-total="{{ (int) $row->total_denda }}"
                                                data-peminjaman="{{ $row->id }}"
                                                style="width: 100%;">
                                                💳 Bayar Sekarang
                                            </button>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('peminjam.pengembalian.bayar-denda', $row->id) }}" id="payment-form-{{ $row->id }}" hidden>
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="metode_pembayaran" class="payment-method-input">
                                        <input type="hidden" name="payment_confirmed" value="0" class="payment-confirmed-input">
                                    </form>
                                @elseif($row->is_denda_lunas)
                                    <span style="color: #065f46; font-weight: 600;">Selesai</span>
                                    <div style="color: #6b7280; font-size: 12px;">Pembayaran tercatat via {{ $row->metode_pembayaran_label }}.</div>
                                @else
                                    <span style="color: #94a3b8; font-size: 13px;">Belum diperlukan</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 30px; color: #9ca3af;">
                                Belum ada riwayat pengembalian
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <div id="payment-modal" class="payment-modal" hidden>
        <div class="payment-modal-card" role="dialog" aria-modal="true" aria-labelledby="payment-modal-title">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                <div style="font-size: 28px;">💳</div>
                <div>
                    <div id="payment-modal-title" class="payment-modal-title" style="margin-bottom: 0;">Pembayaran Denda</div>
                </div>
            </div>
            <div class="payment-modal-desc">Scan QRIS di bawah menggunakan aplikasi e-wallet untuk menyelesaikan pembayaran denda, lalu konfirmasi di halaman ini.</div>
            <div class="payment-amount" id="payment-modal-amount">Rp 0</div>

            <div id="qris-preview" class="qris-preview">
                <div class="qris-header">
                    <span>🔗 QRIS Code</span>
                    <span id="qris-loan-label" style="font-size: 12px; opacity: 0.8;">Tagihan #0</span>
                </div>
                <div class="dummy-qris"></div>
                <div class="qris-note">📸 Scan QR ini menggunakan aplikasi QRIS atau e-wallet untuk membayar. Setelah itu, centang konfirmasi di bawah.</div>
            </div>



            <label class="payment-confirm">
                <input type="checkbox" id="payment-confirm-checkbox">
                <span>✅ Saya sudah menyelesaikan pembayaran dan ingin menandai tagihan ini sebagai lunas.</span>
            </label>

            <div class="payment-modal-actions">
                <button type="button" class="btn-secondary" id="payment-cancel-button">Batal</button>
                <button type="button" class="btn-primary" id="payment-submit-button">💳 Konfirmasi Pembayaran</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = document.getElementById('payment-modal');
            var amountNode = document.getElementById('payment-modal-amount');
            var confirmCheckbox = document.getElementById('payment-confirm-checkbox');
            var submitButton = document.getElementById('payment-submit-button');
            var cancelButton = document.getElementById('payment-cancel-button');
            var qrisLoanLabel = document.getElementById('qris-loan-label');
            var activeForm = null;

            function formatRupiah(value) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(value || 0));
            }

            document.querySelectorAll('.open-payment-modal').forEach(function(button) {
                button.addEventListener('click', function() {
                    activeForm = document.getElementById(button.dataset.formId);
                    amountNode.textContent = formatRupiah(button.dataset.total);
                    qrisLoanLabel.textContent = 'Tagihan #' + button.dataset.peminjaman;
                    confirmCheckbox.checked = false;
                    modal.hidden = false;
                });
            });

            cancelButton.addEventListener('click', function() {
                modal.hidden = true;
                activeForm = null;
            });

            modal.addEventListener('click', function(event) {
                if (event.target === modal) {
                    modal.hidden = true;
                    activeForm = null;
                }
            });

            submitButton.addEventListener('click', function() {
                if (!activeForm) {
                    return;
                }

                if (!confirmCheckbox.checked) {
                    window.alert('⚠️ Centang konfirmasi terlebih dahulu setelah Anda menyelesaikan pembayaran.');
                    return;
                }

                activeForm.querySelector('.payment-method-input').value = 'qris_all_payment';
                activeForm.querySelector('.payment-confirmed-input').value = '1';
                activeForm.submit();
            });
        });
    </script>
</body>

</html>
