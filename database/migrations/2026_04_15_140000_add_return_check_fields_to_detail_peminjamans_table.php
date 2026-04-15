<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_peminjamans', function (Blueprint $table) {
            $table->enum('kondisi_pengembalian', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])
                ->nullable()
                ->after('jumlah_pinjam');
            $table->unsignedInteger('denda_kerusakan')->default(0)->after('kondisi_pengembalian');
        });
    }

    public function down(): void
    {
        Schema::table('detail_peminjamans', function (Blueprint $table) {
            $table->dropColumn(['kondisi_pengembalian', 'denda_kerusakan']);
        });
    }
};
