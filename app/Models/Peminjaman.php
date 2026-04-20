<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peminjaman extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_DISETUJUI = 'disetujui';
    public const STATUS_DITOLAK = 'ditolak';
    public const STATUS_PENGEMBALIAN_PENDING = 'pengembalian_pending';
    public const STATUS_MENUNGGU_PEMERIKSAAN = 'menunggu_pemeriksaan';
    public const STATUS_MENUNGGU_PEMBAYARAN = 'menunggu_pembayaran';
    public const STATUS_DIKEMBALIKAN = 'dikembalikan';

    public const BATAS_PENGEMBALIAN_JAM = 15;
    public const DENDA_TERLAMBAT = 2000;

    protected $table = 'peminjamans';

    protected $fillable = [
        'user_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'waktu_pinjam',
        'batas_kembali',
        'waktu_pengajuan_pengembalian',
        'waktu_pengembalian',
        'denda',
        'metode_pembayaran',
        'status',
    ];


    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'waktu_pinjam' => 'datetime',
        'batas_kembali' => 'datetime',
        'waktu_pengajuan_pengembalian' => 'datetime',
        'waktu_pengembalian' => 'datetime',
        'denda' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function detailPeminjamans(): HasMany
    {
        return $this->hasMany(DetailPeminjaman::class);
    }

    public function isTerlambat(?Carbon $waktuPengembalian = null): bool
    {
        $waktuPengembalian ??= $this->waktu_pengajuan_pengembalian ?? $this->waktu_pengembalian;

        if (! $waktuPengembalian) {
            return false;
        }

        $waktuPengembalian = $waktuPengembalian->copy()->timezone('Asia/Jakarta');
        $batasKembali = $this->batasDendaPengembalian();

        if (! $batasKembali) {
            return false;
        }

        return $waktuPengembalian->gt($batasKembali);
    }

    public function hitungDendaOtomatis(?Carbon $waktuPengembalian = null): int
    {
        $waktuPengembalian ??= $this->waktu_pengajuan_pengembalian ?? $this->waktu_pengembalian;

        if (! $waktuPengembalian) {
            return 0;
        }

        $waktuPengembalian = $waktuPengembalian->copy()->timezone('Asia/Jakarta');
        $batasKembali = $this->batasDendaPengembalian();

        if (! $batasKembali) {
            return 0;
        }

        if ($waktuPengembalian->lessThanOrEqualTo($batasKembali)) {
            return 0;
        }

        $selisihDetik = $batasKembali->diffInSeconds($waktuPengembalian);
        $jumlahPeriodeTerlambat = (int) ceil($selisihDetik / Carbon::SECONDS_PER_DAY);

        return $jumlahPeriodeTerlambat * self::DENDA_TERLAMBAT;
    }

    public function getJumlahHariTerlambatAttribute(): int
    {
        $waktuPengembalian = $this->waktu_pengajuan_pengembalian ?? $this->waktu_pengembalian;

        if (! $waktuPengembalian || ! $this->isTerlambat($waktuPengembalian)) {
            return 0;
        }

        return (int) ($this->hitungDendaOtomatis($waktuPengembalian) / self::DENDA_TERLAMBAT);
    }

    public function getDendaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->denda ?? 0, 0, ',', '.');
    }

    public function getDendaKerusakanTotalAttribute(): int
    {
        if ($this->relationLoaded('detailPeminjamans')) {
            return (int) $this->detailPeminjamans->sum('denda_kerusakan');
        }

        return (int) $this->detailPeminjamans()->sum('denda_kerusakan');
    }

    public function getDendaKerusakanTotalFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->denda_kerusakan_total, 0, ',', '.');
    }

    public function getTotalDendaAttribute(): int
    {
        return (int) (($this->denda ?? 0) + $this->denda_kerusakan_total);
    }

    public function getTotalDendaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->total_denda, 0, ',', '.');
    }

    public function getBatasDendaFormattedAttribute(): string
    {
        return $this->batasDendaPengembalian()?->format('d/m/Y H:i') ?? '-';
    }

    public function getIsDendaLunasAttribute(): bool
    {
        return $this->status === self::STATUS_DIKEMBALIKAN
            && $this->total_denda > 0
            && ! empty($this->metode_pembayaran);
    }

    public function getSisaDendaAttribute(): int
    {
        return $this->is_denda_lunas ? 0 : $this->total_denda;
    }

    public function getSisaDendaFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->sisa_denda, 0, ',', '.');
    }

    public function getStatusPembayaranDendaLabelAttribute(): string
    {
        if ($this->total_denda <= 0) {
            return 'Tidak ada denda';
        }

        if ($this->is_denda_lunas) {
            return 'Denda sudah dibayar';
        }

        return match ($this->status) {
            self::STATUS_MENUNGGU_PEMBAYARAN => 'Menunggu pembayaran denda',
            self::STATUS_MENUNGGU_PEMERIKSAAN => 'Menunggu hasil pemeriksaan',
            self::STATUS_PENGEMBALIAN_PENDING => 'Menunggu konfirmasi petugas',
            default => 'Status denda belum tersedia',
        };
    }

    public function getMetodePembayaranLabelAttribute(): string
    {
        return match ($this->metode_pembayaran) {
            'tunai' => 'Tunai',
            'qris_all_payment' => 'QRIS All Payment',
            'gopay' => 'GoPay',
            'ovo' => 'OVO',
            'dana' => 'DANA',
            'shopeepay' => 'ShopeePay',
            default => '-',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENGEMBALIAN_PENDING => 'Menunggu Konfirmasi Petugas',
            self::STATUS_MENUNGGU_PEMERIKSAAN => 'Menunggu Pemeriksaan',
            self::STATUS_MENUNGGU_PEMBAYARAN => 'Menunggu Pembayaran Denda',
            self::STATUS_DIKEMBALIKAN => 'Dikembalikan',
            self::STATUS_DISETUJUI => 'Disetujui',
            self::STATUS_DITOLAK => 'Ditolak',
            default => 'Pending',
        };
    }

    private function batasDendaPengembalian(): ?Carbon
    {
        if ($this->batas_kembali) {
            return $this->batas_kembali->copy()->timezone('Asia/Jakarta');
        }

        if ($this->tanggal_kembali) {
            return $this->tanggal_kembali->copy()->timezone('Asia/Jakarta')->setTime(self::BATAS_PENGEMBALIAN_JAM, 0, 0);
        }

        if ($this->tanggal_pinjam) {
            return $this->tanggal_pinjam->copy()->timezone('Asia/Jakarta')->setTime(self::BATAS_PENGEMBALIAN_JAM, 0, 0);
        }

        return null;
    }
}
