<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE alat_units MODIFY status ENUM('tersedia','dipinjam','tidak_tersedia') NOT NULL DEFAULT 'tersedia'");
    }

    public function down(): void
    {
        DB::statement("UPDATE alat_units SET status = 'dipinjam' WHERE status = 'tidak_tersedia'");
        DB::statement("ALTER TABLE alat_units MODIFY status ENUM('tersedia','dipinjam') NOT NULL DEFAULT 'tersedia'");
    }
};
