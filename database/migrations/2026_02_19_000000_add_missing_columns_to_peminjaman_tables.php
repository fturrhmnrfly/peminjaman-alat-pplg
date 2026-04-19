<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add missing columns to peminjamans table
        Schema::table('peminjamans', function (Blueprint $table) {
            // Check if columns don't already exist
            if (!Schema::hasColumn('peminjamans', 'waktu_pinjam')) {
                $table->dateTime('waktu_pinjam')->nullable();
            }
            if (!Schema::hasColumn('peminjamans', 'batas_kembali')) {
                $table->dateTime('batas_kembali')->nullable();
            }
            if (!Schema::hasColumn('peminjamans', 'waktu_pengajuan_pengembalian')) {
                $table->dateTime('waktu_pengajuan_pengembalian')->nullable();
            }
            if (!Schema::hasColumn('peminjamans', 'waktu_pengembalian')) {
                $table->dateTime('waktu_pengembalian')->nullable();
            }
            if (!Schema::hasColumn('peminjamans', 'denda')) {
                $table->integer('denda')->nullable()->default(0);
            }
            if (!Schema::hasColumn('peminjamans', 'metode_pembayaran')) {
                $table->string('metode_pembayaran')->nullable();
            }
            // Update status enum to include all required statuses
            if (Schema::hasColumn('peminjamans', 'status')) {
                $table->dropColumn('status');
            }
            $table->enum('status', ['pending', 'disetujui', 'ditolak', 'pengembalian_pending', 'menunggu_pemeriksaan', 'menunggu_pembayaran', 'dikembalikan'])->default('pending');
        });

        // Add missing columns to detail_peminjamans table
        Schema::table('detail_peminjamans', function (Blueprint $table) {
            if (!Schema::hasColumn('detail_peminjamans', 'alat_unit_id')) {
                $table->foreignId('alat_unit_id')->nullable()->constrained('alat_units')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('detail_peminjamans', 'kondisi_pengembalian')) {
                $table->enum('kondisi_pengembalian', ['baik', 'rusak_ringan', 'rusak_berat'])->nullable();
            }
            if (!Schema::hasColumn('detail_peminjamans', 'denda_kerusakan')) {
                $table->integer('denda_kerusakan')->nullable()->default(0);
            }
            if (!Schema::hasColumn('detail_peminjamans', 'detail_kerusakan')) {
                $table->text('detail_kerusakan')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            if (Schema::hasColumn('peminjamans', 'waktu_pinjam')) {
                $table->dropColumn('waktu_pinjam');
            }
            if (Schema::hasColumn('peminjamans', 'batas_kembali')) {
                $table->dropColumn('batas_kembali');
            }
            if (Schema::hasColumn('peminjamans', 'waktu_pengajuan_pengembalian')) {
                $table->dropColumn('waktu_pengajuan_pengembalian');
            }
            if (Schema::hasColumn('peminjamans', 'waktu_pengembalian')) {
                $table->dropColumn('waktu_pengembalian');
            }
            if (Schema::hasColumn('peminjamans', 'denda')) {
                $table->dropColumn('denda');
            }
            if (Schema::hasColumn('peminjamans', 'metode_pembayaran')) {
                $table->dropColumn('metode_pembayaran');
            }
        });

        Schema::table('detail_peminjamans', function (Blueprint $table) {
            if (Schema::hasColumn('detail_peminjamans', 'alat_unit_id')) {
                $table->dropForeign(['alat_unit_id']);
                $table->dropColumn('alat_unit_id');
            }
            if (Schema::hasColumn('detail_peminjamans', 'kondisi_pengembalian')) {
                $table->dropColumn('kondisi_pengembalian');
            }
            if (Schema::hasColumn('detail_peminjamans', 'denda_kerusakan')) {
                $table->dropColumn('denda_kerusakan');
            }
            if (Schema::hasColumn('detail_peminjamans', 'detail_kerusakan')) {
                $table->dropColumn('detail_kerusakan');
            }
        });
    }
};
