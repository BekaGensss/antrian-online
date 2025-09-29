<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsPetugas
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Memeriksa apakah pengguna sudah login DAN memiliki peran 'petugas' atau 'admin'
        if (auth()->check()) {
            $role = auth()->user()->role;
            
            // Izinkan akses jika peran adalah 'petugas' atau 'admin'
            if ($role === 'petugas' || $role === 'admin') {
                return $next($request);
            }
        }

        // Akses Ditolak
        return redirect('/dashboard')->with('error', 'Akses Ditolak! Anda tidak memiliki izin Petugas untuk area ini.');
    }
}