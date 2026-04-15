<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peminjaman extends Model
{
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
        $batasKembali = $this->batas_kembali
            ? $this->batas_kembali->copy()->timezone('Asia/Jakarta')
            : Carbon::parse($this->tanggal_pinjam, 'Asia/Jakarta')->setTime(self::BATAS_PENGEMBALIAN_JAM, 0, 0);

        return $waktuPengembalian->gt($batasKembali);
    }

    public function hitungDendaOtomatis(?Carbon $waktuPengembalian = null): int
    {
        $waktuPengembalian ??= $this->waktu_pengajuan_pengembalian ?? $this->waktu_pengembalian;

        if (! $waktuPengembalian) {
            return 0;
        }

        $waktuPengembalian = $waktuPengembalian->copy()->timezone('Asia/Jakarta');
        $batasKembali = $this->batas_kembali
            ? $this->batas_kembali->copy()->timezone('Asia/Jakarta')
            : Carbon::parse($this->tanggal_pinjam, 'Asia/Jakarta')->setTime(self::BATAS_PENGEMBALIAN_JAM, 0, 0);

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

    public function getMetodePembayaranLabelAttribute(): string
    {
        return match ($this->metode_pembayaran) {
            'tunai' => 'Tunai',
            'qris_all_payment' => 'QRIS All Payment',
            default => '-',
        };
    }
}
