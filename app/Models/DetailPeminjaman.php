<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailPeminjaman extends Model
{
    protected $table = 'detail_peminjamans';

    protected $fillable = [
        'peminjaman_id',
        'alat_id',
        'alat_unit_id',
        'jumlah_pinjam',
        'kondisi_pengembalian',
        'denda_kerusakan',
        'detail_kerusakan',
    ];

    protected $casts = [
        'denda_kerusakan' => 'integer',
    ];

    public function peminjaman(): BelongsTo
    {
        return $this->belongsTo(Peminjaman::class);
    }

    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class);
    }

    public function alatUnit(): BelongsTo
    {
        return $this->belongsTo(AlatUnit::class, 'alat_unit_id');
    }

    public function getDendaKerusakanFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->denda_kerusakan ?? 0, 0, ',', '.');
    }

    public function getKondisiPengembalianLabelAttribute(): string
    {
        return match ($this->kondisi_pengembalian) {
            'rusak_ringan' => 'Rusak Ringan',
            'rusak_berat' => 'Rusak Berat',
            'hilang' => 'Hilang',
            default => 'Baik',
        };
    }
}
