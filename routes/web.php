<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AntrianController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\HomeController; 
use App\Http\Middleware\IsAdmin; 
use App\Http\Middleware\IsPetugas; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Rute web untuk aplikasi Anda. Semua rute dilindungi oleh middleware 'web'
| secara default.
*/

// --- Rute Otentikasi (Login, Register, Logout) ---
Auth::routes();

// Rute Dashboard Utama (Setelah Login, mengarahkan ke DashboardController)
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// --- Rute yang Dilindungi (Membutuhkan Login) ---
Route::middleware(['auth'])->group(function () {
    
    // Rute untuk Admin (Membutuhkan hak akses 'admin')
    Route::middleware([IsAdmin::class])->group(function () {
        
        // Rute manajemen loket (resource)
        Route::resource('lokets', LoketController::class);
        
        // Rute manajemen pengguna (resource)
        Route::resource('users', UserController::class);

        // Rute laporan
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    });

    // Rute untuk Petugas (Membutuhkan hak akses 'petugas' atau 'admin')
    Route::middleware([IsPetugas::class])->group(function () {
        
        // Rute Monitor Layanan Loket (Controller yang bertanggung jawab untuk petugas)
        // Catatan: Monitor View petugas ini belum dibuat, tetapi rutenya sudah siap.
        Route::get('/petugas/monitor', [LoketController::class, 'monitor'])->name('loket.monitor');
        
        // Rute Aksi Antrian (Petugas/Admin)
        Route::post('/antrians/{antrian}/call', [AntrianController::class, 'call'])->name('antrians.call');
        Route::post('/antrians/{antrian}/finish', [AntrianController::class, 'finish'])->name('antrians.finish');
        Route::delete('/antrians/{antrian}', [AntrianController::class, 'destroy'])->name('antrians.destroy');
    });

});

// --- Rute untuk Pengguna Publik (Tidak Membutuhkan Login) ---

// Halaman utama (form ambil antrian & info antrian hari ini)
Route::get('/', [AntrianController::class, 'index'])->name('antrians.index');

// Rute untuk proses ambil antrian (publik)
Route::post('/antrians/store', [AntrianController::class, 'store'])->name('antrians.store');

// Rute Papan Monitor Antrian (PUBLIK) - View Pusher
Route::get('/monitor', function () {
    return view('monitor'); 
})->name('monitor.index');

// Rute API untuk data awal monitor (digunakan oleh monitor.blade.php untuk AJAX)
Route::get('/api/antrian-data', [AntrianController::class, 'getMonitorData'])->name('api.antrian.monitor_data');

// CATATAN: Rute /api/antrian-latest (Long Polling) TELAH DIHAPUS.