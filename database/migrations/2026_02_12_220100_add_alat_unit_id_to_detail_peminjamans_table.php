<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_peminjamans', function (Blueprint $table) {
            $table->foreignId('alat_unit_id')
                ->nullable()
                ->after('alat_id')
                ->constrained('alat_units')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('detail_peminjamans', function (Blueprint $table) {
            $table->dropForeign(['alat_unit_id']);
            $table->dropColumn('alat_unit_id');
        });
    }
};
