<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    protected $fillable = [
        'nama_alat',
        'kategori_id',
        'jumlah',
        'kondisi',
        'keterangan',
        'foto',
    ];

    // Relasi ke Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    // Relasi ke Peminjaman (jika nanti dibutuhkan)
    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'alat_id');
    }
}