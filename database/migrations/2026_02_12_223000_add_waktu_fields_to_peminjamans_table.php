<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dateTime('waktu_pinjam')->nullable()->after('tanggal_pinjam');
            $table->dateTime('batas_kembali')->nullable()->after('waktu_pinjam');
            $table->dateTime('waktu_pengembalian')->nullable()->after('tanggal_kembali');
        });
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn(['waktu_pinjam', 'batas_kembali', 'waktu_pengembalian']);
        });
    }
};
