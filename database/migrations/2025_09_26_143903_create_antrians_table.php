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
        Schema::create('antrians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loket_id')->constrained('lokets')->onDelete('cascade'); // Foreign key ke tabel lokets
            $table->string('nomor_antrian');
            $table->date('tanggal'); // Kolom baru untuk mencatat tanggal antrian
            $table->string('status')->default('menunggu'); // Bisa 'menunggu', 'dipanggil', 'selesai'
            $table->timestamps();

            // Kombinasi kolom yang unik untuk memastikan nomor antrian unik per loket per hari
            $table->unique(['loket_id', 'nomor_antrian', 'tanggal']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrians');
    }
};