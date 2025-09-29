<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antrian;
use App\Models\Loket;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard berdasarkan peran pengguna.
     */
    public function index()
    {
        // Pengecekan Kritis: Pastikan pengguna sudah login
        if (!Auth::check()) {
            // Jika belum login, redirect ke halaman login
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 1. Logika Dashboard Admin
        if ($user->role === 'admin') {
            
            $today = Carbon::today()->toDateString();

            // Statistik Admin
            $totalAntrianHariIni = Antrian::where('tanggal', $today)->count();
            $antrianDipanggil = Antrian::where('status', 'dipanggil')->count();
            $antrianMenunggu = Antrian::where('status', 'menunggu')
                                      ->where('tanggal', $today)
                                      ->count();
            $loketAktif = Loket::where('status', 'aktif')->count();
            
            // Mengambil 5 antrian menunggu terbaru
            $antrianTerbaru = Antrian::with('loket')
                                     ->where('status', 'menunggu')
                                     ->where('tanggal', $today)
                                     ->orderBy('created_at', 'asc')
                                     ->limit(5)
                                     ->get();

            return view('dashboard.admin', compact(
                'totalAntrianHariIni', 
                'loketAktif', 
                'antrianMenunggu',
                'antrianDipanggil',
                'antrianTerbaru'
            ));
        }
        
        // 2. Logika Dashboard Petugas (redirect ke monitor)
        if ($user->role === 'petugas') {
            return redirect()->route('loket.monitor');
        }

        // 3. Logika Dashboard Pengguna Biasa
        return view('dashboard.user');
    }
}