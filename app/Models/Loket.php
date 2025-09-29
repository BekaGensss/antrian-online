<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Kolom baru 'nomor_antrian_saat_ini' dan 'status' ditambahkan.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_loket',
        'slug',
        'deskripsi',
        'nomor_antrian_saat_ini', // <--- Ditambahkan
        'status',               // <--- Ditambahkan
    ];

    /**
     * The attributes that should be cast.
     * Mengubah nomor antrian saat ini menjadi integer.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'nomor_antrian_saat_ini' => 'integer',
    ];

    /**
     * Dapatkan semua antrian untuk loket ini (Relasi One-to-Many).
     */
    public function antrians()
    {
        return $this->hasMany(Antrian::class);
    }
}