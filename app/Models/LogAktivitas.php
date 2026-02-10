<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';

    protected $fillable = [
        'user_id',
        'nama_user',
        'aksi',
        'modul',
        'keterangan',
        'ip_address',
        'user_agent',
    ];

    /**
     * Relasi ke tabel users
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Helper untuk mencatat log aktivitas
     */
    public static function catat($aksi, $modul, $keterangan = null)
    {
        $user = auth()->user();
        
        return self::create([
            'user_id' => $user ? $user->id : null,
            'nama_user' => $user ? $user->nama : 'System',
            'aksi' => $aksi,
            'modul' => $modul,
            'keterangan' => $keterangan,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Scope untuk filter berdasarkan modul
     */
    public function scopeModul($query, $modul)
    {
        return $query->where('modul', $modul);
    }

    /**
     * Scope untuk filter berdasarkan aksi
     */
    public function scopeAksi($query, $aksi)
    {
        return $query->where('aksi', $aksi);
    }

    /**
     * Scope untuk filter berdasarkan user
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
