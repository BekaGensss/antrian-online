<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Antrian;
use App\Models\Loket;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan dengan filter dan data ringkasan.
     */
    public function index(Request $request)
    {
        // Ambil semua Loket untuk opsi filter
        $lokets = Loket::all();

        // 1. Tentukan rentang tanggal default (7 hari terakhir) dan filter Loket
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::now()->subDays(6)->toDateString());
        $tanggalAkhir = $request->input('tanggal_akhir', Carbon::now()->toDateString());
        $loketId = $request->input('loket_id');
        
        // Pastikan rentang tanggal valid
        if (Carbon::parse($tanggalMulai)->greaterThan(Carbon::parse($tanggalAkhir))) {
             return redirect()->back()->with('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.');
        }

        // 2. Query Dasar
        $queryDasar = Antrian::with('loket')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalAkhir]);

        if ($loketId) {
            $queryDasar->where('loket_id', $loketId);
        }

        // --- LAPORAN 1: REKAPITULASI HARIAN (DAILY SUMMARY) ---

        // Mengambil data jumlah antrian per loket per tanggal
        $laporanHarian = (clone $queryDasar)->select(
            DB::raw('tanggal'),
            DB::raw('loket_id'),
            DB::raw('COUNT(*) as total_antrian'),
            DB::raw('SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as total_selesai')
        )
        ->groupBy('tanggal', 'loket_id')
        ->orderBy('tanggal', 'desc')
        ->get()
        ->map(function ($item) {
            // Menyertakan nama loket melalui relasi
            $item->nama_loket = $item->loket->nama_loket ?? 'Loket Tidak Ditemukan';
            return $item;
        });

        // --- LAPORAN 2: KINERJA WAKTU LAYANAN (PERFORMANCE) ---
        
        // Menghitung rata-rata waktu yang dibutuhkan untuk menyelesaikan antrian (Service Time)
        $laporanKinerja = (clone $queryDasar)->select(
            DB::raw('loket_id'),
            DB::raw('AVG(TIMESTAMPDIFF(SECOND, created_at, finished_at)) as rata_rata_detik')
        )
        ->whereNotNull('finished_at') // Hanya antrian yang sudah selesai yang dihitung
        ->where('status', 'selesai')
        ->groupBy('loket_id')
        ->get()
        ->map(function ($item) {
            // Konversi rata-rata detik ke format waktu yang mudah dibaca (mis: HH:MM:SS)
            $seconds = round($item->rata_rata_detik);
            $item->rata_rata_waktu = gmdate("H:i:s", $seconds);
            $item->nama_loket = $item->loket->nama_loket ?? 'Loket Tidak Ditemukan';
            return $item;
        });

        // Data yang dikirim ke view
        return view('laporan.index', compact(
            'lokets', 
            'laporanHarian', 
            'laporanKinerja',
            'tanggalMulai',
            'tanggalAkhir',
            'loketId'
        ));
    }
}
