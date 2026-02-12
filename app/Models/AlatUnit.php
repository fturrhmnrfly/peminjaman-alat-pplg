<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlatUnit extends Model
{
    protected $table = 'alat_units';

    protected $fillable = [
        'alat_id',
        'kode_unik',
        'kondisi',
        'status',
        'keterangan',
    ];

    public function alat(): BelongsTo
    {
        return $this->belongsTo(Alat::class, 'alat_id');
    }
}
