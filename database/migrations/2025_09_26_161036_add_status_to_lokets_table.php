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
        // Menambahkan kolom 'status' ke tabel 'lokets'
        Schema::table('lokets', function (Blueprint $table) {
            // Kolom status, default 'aktif'. Status bisa: 'aktif' (buka), 'istirahat', 'tutup'.
            $table->string('status')->default('aktif');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        // Menghapus kolom 'status' dari tabel 'lokets'
        Schema::table('lokets', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};