<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ruang Alat</title>
</head>
<body style="margin:0;padding:24px;background:#f5f7fb;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <div style="max-width:640px;margin:0 auto;background:#ffffff;border-radius:18px;padding:32px;border:1px solid #e5e7eb;">
        <div style="font-size:24px;font-weight:700;margin-bottom:12px;">Hasil Pemeriksaan Pengembalian</div>
        <p style="margin:0 0 14px;line-height:1.6;">Halo {{ $peminjaman->user?->nama }},</p>
        <p style="margin:0 0 18px;line-height:1.6;">
            Petugas sudah menyelesaikan pemeriksaan barang untuk peminjaman <strong>#{{ $peminjaman->id }}</strong>. Dari hasil pemeriksaan, masih ada tagihan denda yang perlu diselesaikan agar proses pengembalian dapat ditutup.
        </p>

        @if($peminjaman->detailPeminjamans->where('denda_kerusakan', '>', 0)->isNotEmpty())
            <div style="margin-bottom:20px;padding:18px 20px;background:#f8fafc;border-radius:14px;">
                <div style="font-size:16px;font-weight:700;margin-bottom:10px;">Rincian hasil pemeriksaan</div>
                <ul style="margin:0;padding-left:18px;line-height:1.8;">
                    @foreach($peminjaman->detailPeminjamans->where('denda_kerusakan', '>', 0) as $detail)
                        <li>
                            {{ $detail->alatUnit?->alat?->nama_alat ?? $detail->alat?->nama_alat ?? 'Alat' }}
                            @if($detail->alatUnit?->kode_unik)
                                ({{ $detail->alatUnit->kode_unik }})
                            @endif
                            - {{ $detail->kondisi_pengembalian_label }}
                            - Denda: Rp {{ number_format($detail->denda_kerusakan, 0, ',', '.') }}
                            @if($detail->detail_kerusakan)
                                - Catatan: {{ $detail->detail_kerusakan }}
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div style="margin-bottom:18px;line-height:1.8;">
            <div>Status pengembalian: <strong>Menunggu pembayaran denda</strong></div>
            <div>Waktu pengembalian dicatat: <strong>{{ optional($peminjaman->waktu_pengembalian)?->timezone('Asia/Jakarta')->format('d/m/Y H:i') ?? '-' }} WIB</strong></div>
            <div>Denda terlambat: <strong>Rp {{ number_format($peminjaman->denda ?? 0, 0, ',', '.') }}</strong></div>
            <div>Denda kerusakan: <strong>Rp {{ number_format($peminjaman->denda_kerusakan_total, 0, ',', '.') }}</strong></div>
            <div>Total tagihan: <strong>Rp {{ number_format($peminjaman->total_denda, 0, ',', '.') }}</strong></div>
        </div>

        <p style="margin:0;line-height:1.6;color:#6b7280;">
            Silakan selesaikan pembayaran denda melalui halaman peminjam menggunakan QRIS atau e-wallet. Setelah pembayaran berhasil, status pengembalian akan diperbarui otomatis.
        </p>
    </div>
</body>
</html>
