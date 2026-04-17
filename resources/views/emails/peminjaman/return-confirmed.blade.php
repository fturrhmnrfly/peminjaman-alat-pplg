<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ruang Alat</title>
</head>
<body style="margin:0;padding:24px;background:#f5f7fb;font-family:Arial,Helvetica,sans-serif;color:#1f2937;">
    <div style="max-width:640px;margin:0 auto;background:#ffffff;border-radius:18px;padding:32px;border:1px solid #e5e7eb;">
        <div style="font-size:24px;font-weight:700;margin-bottom:12px;">Pengembalian Sudah Dikonfirmasi</div>
        <p style="margin:0 0 14px;line-height:1.6;">Halo {{ $peminjaman->user?->nama }},</p>
        <p style="margin:0 0 18px;line-height:1.6;">
            Pengembalian alat untuk peminjaman <strong>#{{ $peminjaman->id }}</strong> sudah dikonfirmasi oleh petugas.
        </p>

        <div style="margin-bottom:20px;padding:18px 20px;background:#f8fafc;border-radius:14px;">
            <div style="font-size:16px;font-weight:700;margin-bottom:10px;">Detail alat</div>
            <ul style="margin:0;padding-left:18px;line-height:1.8;">
                @foreach($peminjaman->detailPeminjamans as $detail)
                    <li>
                        {{ $detail->alatUnit?->alat?->nama_alat ?? $detail->alat?->nama_alat ?? 'Alat' }}
                        @if($detail->alatUnit?->kode_unik)
                            ({{ $detail->alatUnit->kode_unik }})
                        @endif
                        - Kondisi:
                        @switch($detail->kondisi_pengembalian)
                            @case('rusak_ringan') Rusak ringan @break
                            @case('rusak_berat') Rusak berat @break
                            @case('hilang') Hilang @break
                            @default Baik
                        @endswitch
                        @if(($detail->denda_kerusakan ?? 0) > 0)
                            - Denda kerusakan: Rp {{ number_format($detail->denda_kerusakan, 0, ',', '.') }}
                        @endif
                        @if($detail->detail_kerusakan)
                            - Catatan: {{ $detail->detail_kerusakan }}
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        <div style="margin-bottom:18px;line-height:1.8;">
            <div>Denda terlambat: <strong>Rp {{ number_format($peminjaman->denda ?? 0, 0, ',', '.') }}</strong></div>
            <div>Denda kerusakan: <strong>Rp {{ number_format($peminjaman->denda_kerusakan_total, 0, ',', '.') }}</strong></div>
            <div>Total denda: <strong>Rp {{ number_format($peminjaman->total_denda, 0, ',', '.') }}</strong></div>
            <div>Waktu konfirmasi: <strong>{{ optional($peminjaman->waktu_pengembalian)?->timezone('Asia/Jakarta')->format('d/m/Y H:i') ?? '-' }} WIB</strong></div>
        </div>

        <p style="margin:0;line-height:1.6;color:#6b7280;">
            Terima kasih sudah mengembalikan alat. Jika ada pertanyaan, silakan hubungi petugas.
        </p>
    </div>
</body>
</html>

