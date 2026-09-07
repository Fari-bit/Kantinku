<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom `deleted_at` (soft delete) pada tabel users & menus.
     * Fitur: Recycle Bin — data yang "dihapus" tidak langsung hilang dari
     * database, melainkan hanya ditandai deleted_at-nya sehingga bisa
     * dipulihkan (restore) sebelum dihapus permanen (force delete).
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes()->after('remember_token');
            }
        });

        Schema::table('menus', function (Blueprint $table) {
            if (!Schema::hasColumn('menus', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
