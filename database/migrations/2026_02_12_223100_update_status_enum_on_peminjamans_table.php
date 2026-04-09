<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE peminjamans MODIFY status ENUM('pending','disetujui','ditolak','pengembalian_pending','dikembalikan') NOT NULL");
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE peminjamans MODIFY status ENUM('pending','disetujui','ditolak','dikembalikan') NOT NULL");
    }
};
