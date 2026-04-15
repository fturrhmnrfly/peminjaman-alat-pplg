<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_peminjamans', function (Blueprint $table) {
            $table->text('detail_kerusakan')->nullable()->after('denda_kerusakan');
        });
    }

    public function down(): void
    {
        Schema::table('detail_peminjamans', function (Blueprint $table) {
            $table->dropColumn('detail_kerusakan');
        });
    }
};
