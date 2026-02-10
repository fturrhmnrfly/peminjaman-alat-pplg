<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alats', function (Blueprint $table) {
            $table->id();
            $table->string('nama_alat');
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('cascade');
            $table->integer('jumlah');
            $table->enum('kondisi', ['baik', 'rusak', 'hilang'])->default('baik');
            $table->text('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alats');
    }
};