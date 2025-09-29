<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        // Menambahkan kolom 'role' ke tabel 'users'
        Schema::table('users', function (Blueprint $table) {
            // Kolom role dengan nilai default 'petugas', diletakkan setelah kolom 'name'
            $table->string('role')->default('petugas')->after('name');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        // Menghapus kolom 'role' dari tabel 'users' saat rollback
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};