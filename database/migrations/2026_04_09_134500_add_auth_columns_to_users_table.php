<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'email')) {
                $table->string('email')->nullable()->after('nis');
            }

            if (! Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('email');
            }

            if (! Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken()->after('role');
            }
        });

        DB::table('users')
            ->whereNull('email')
            ->orderBy('id')
            ->get(['id', 'username'])
            ->each(function ($user) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update([
                        'email' => "{$user->username}@local.test",
                    ]);
            });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [];

            if (Schema::hasColumn('users', 'email_verified_at')) {
                $columns[] = 'email_verified_at';
            }

            if (Schema::hasColumn('users', 'email')) {
                $columns[] = 'email';
            }

            if (! empty($columns)) {
                $table->dropColumn($columns);
            }

            if (Schema::hasColumn('users', 'remember_token')) {
                $table->dropRememberToken();
            }
        });
    }
};
