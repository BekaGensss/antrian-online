<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Nama-nama URI yang harus dikecualikan dari verifikasi CSRF.
     * Kita tambahkan 'antrians/store' di sini untuk memperbaiki masalah 419.
     *
     * @var array<int, string>
     */
    protected $except = [
        'antrians/store', // <--- Pengecualian wajib untuk form ambil antrian publik
    ];
}