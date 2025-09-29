<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Loket;

class Antrian extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignable).
     * Kolom 'tanggal' dan 'finished_at' WAJIB ditambahkan.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'loket_id',
        'nomor_antrian',
        'status',
        'tanggal',       // <--- Ditambahkan: Untuk filter harian
        'finished_at',   // <--- Ditambahkan: Untuk menghitung waktu layanan
    ];

    /**
     * Tipe data yang harus di-cast.
     * Menggunakan 'date' untuk 'tanggal' dan 'datetime' untuk 'finished_at'.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date',
        'finished_at' => 'datetime',
    ];

    /**
     * Dapatkan loket yang memiliki antrian ini (Relasi One-to-Many terbalik).
     */
    public function loket()
    {
        return $this->belongsTo(Loket::class);
    }
}