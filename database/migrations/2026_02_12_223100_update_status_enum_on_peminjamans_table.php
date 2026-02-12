<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE peminjamans MODIFY status ENUM('pending','disetujui','ditolak','pengembalian_pending','dikembalikan') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE peminjamans MODIFY status ENUM('pending','disetujui','ditolak','dikembalikan') NOT NULL");
    }
};
