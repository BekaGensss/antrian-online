<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Loket;
use Illuminate\Http\Request;
use App\Events\AntrianDipanggil;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AntrianController extends Controller
{
    /**
     * Menampilkan daftar antrian hari ini (Halaman Publik/Petugas).
     */
    public function index()
    {
        $today = Carbon::today()->toDateString();
        
        $antrians = Antrian::with('loket')
            ->where('tanggal', $today)
            ->orderBy('created_at', 'desc')
            ->get(); 
            
        $lokets = Loket::where('status', 'aktif')->get(); 
        
        $antrianSaatIni = Antrian::with('loket')
            ->where('status', 'dipanggil')
            ->where('tanggal', $today)
            ->first();
            
        return view('antrians.index', compact('antrians', 'lokets', 'antrianSaatIni'));
    }

    /**
     * Menyimpan antrian baru yang diambil oleh pengguna.
     */
    public function store(Request $request)
    {
        $request->validate([
            'loket_id' => 'required|exists:lokets,id',
        ]);
        
        $loket = Loket::find($request->loket_id);
        $today = Carbon::today()->toDateString();

        $last_antrian = Antrian::where('loket_id', $request->loket_id)
            ->where('tanggal', $today)
            ->orderBy('id', 'desc')
            ->first();
        
        $loket_prefix = strtoupper(substr($loket->nama_loket, 0, 1)); 

        $nomor_urut = 1;
        if ($last_antrian) {
            $nomor_terakhir_arr = explode('-', $last_antrian->nomor_antrian);
            $nomor_urut = (int)end($nomor_terakhir_arr) + 1;
        }

        $nomor_antrian_baru = $loket_prefix . '-' . str_pad($nomor_urut, 3, '0', STR_PAD_LEFT);

        Antrian::create([
            'loket_id' => $request->loket_id,
            'nomor_antrian' => $nomor_antrian_baru,
            'status' => 'menunggu',
            'tanggal' => $today,
        ]);

        return redirect()->route('antrians.index')->with('success', "Nomor antrian Anda: **$nomor_antrian_baru**");
    }
    
    public function create() { /* not used */ }

    /**
     * Mengupdate status antrian menjadi 'dipanggil' dan menyiarkan event (Pusher).
     */
    public function call(Antrian $antrian)
    {
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'petugas')) {
             abort(403, 'Akses Ditolak.');
        }

        if ($antrian->status !== 'menunggu') {
             return redirect()->back()->with('error', 'Antrian sudah dipanggil atau selesai.');
        }

        $antrian->status = 'dipanggil';
        $antrian->save();
        
        broadcast(new AntrianDipanggil($antrian)); 

        return redirect()->back()->with('success', 'Antrian berhasil dipanggil!');
    }

    /**
     * Mengupdate status antrian menjadi 'selesai'.
     */
    public function finish(Antrian $antrian)
    {
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'petugas')) {
             abort(403, 'Akses Ditolak.');
        }

        if ($antrian->status !== 'dipanggil') {
             return redirect()->back()->with('error', 'Antrian harus berstatus dipanggil sebelum diselesaikan.');
        }

        $antrian->status = 'selesai';
        $antrian->finished_at = now(); 
        $antrian->save();

        return redirect()->back()->with('success', 'Antrian berhasil diselesaikan.');
    }

    /**
     * Menghapus antrian.
     */
    public function destroy(Antrian $antrian)
    {
        if (!Auth::check() || (Auth::user()->role !== 'admin' && Auth::user()->role !== 'petugas')) {
             abort(403, 'Akses Ditolak.');
        }
        $antrian->delete();
        return redirect()->route('antrians.index')->with('success', 'Antrian berhasil dihapus.');
    }
    
    /**
     * Metode API untuk mendapatkan data monitor saat initial loading (menggantikan Long Polling).
     */
    public function getMonitorData()
    {
        $today = Carbon::today()->toDateString();
        
        $antrianDipanggil = Antrian::with('loket')
            ->where('status', 'dipanggil')
            ->where('tanggal', $today)
            ->orderBy('updated_at', 'desc')
            ->first();
        
        $antriansMenunggu = Antrian::with('loket')
            ->where('status', 'menunggu')
            ->where('tanggal', $today)
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get();

        return response()->json([
            'antrian_dipanggil' => $antrianDipanggil, 
            'antrians_menunggu' => $antriansMenunggu
        ]);
    }
    
    public function show(Antrian $antrian) { /* not used */ }
    public function edit(Antrian $antrian) { /* not used */ }
    public function update(Request $request, Antrian $antrian) { /* not used */ }
}