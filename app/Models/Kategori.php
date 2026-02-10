<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    // Relasi ke Alat (jika nanti dibutuhkan)
    public function alats()
    {
        return $this->hasMany(Alat::class, 'kategori_id');
    }
}