<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip')->nullable()->after('nis');
            $table->string('nomor_telepon')->nullable()->after('email');
            $table->string('kelas')->nullable()->after('nomor_telepon');
            $table->text('alamat')->nullable()->after('kelas');
            $table->string('foto_profil')->nullable()->after('alamat');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nip',
                'nomor_telepon',
                'kelas',
                'alamat',
                'foto_profil',
            ]);
        });
    }
};
