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
        Schema::create('detail_peminjamans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('peminjaman_id')->constrained()->cascadeOnDelete();
    $table->foreignId('alat_id')->constrained()->cascadeOnDelete();
    $table->integer('jumlah_pinjam');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_peminjamans');
    }
};
