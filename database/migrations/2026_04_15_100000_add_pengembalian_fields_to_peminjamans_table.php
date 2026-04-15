<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dateTime('waktu_pengajuan_pengembalian')->nullable()->after('batas_kembali');
            $table->unsignedInteger('denda')->default(0)->after('waktu_pengembalian');
        });
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn(['waktu_pengajuan_pengembalian', 'denda']);
        });
    }
};
