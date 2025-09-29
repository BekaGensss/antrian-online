<?php

namespace App\Http\Controllers;

use App\Models\Loket;
use App\Models\Antrian;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LoketController extends Controller
{
    /**
     * Terapkan middleware untuk membatasi akses.
     */
    public function __construct()
    {
        // Admin bisa mengakses semua, Petugas hanya monitor & updateStatus
        $this->middleware('admin')->except(['monitor', 'updateStatus']);
        $this->middleware('petugas')->only(['monitor', 'updateStatus']);
    }
    
    // ... (metode CRUD lainnya: index, create, store, edit, update, destroy, updateStatus)

    /**
     * Menampilkan dashboard layanan untuk Petugas yang login.
     */
    public function monitor()
    {
        $user = Auth::user();
        
        // 1. Cek: Apakah Petugas sudah ditugaskan ke Loket?
        if ($user->role === 'petugas' && !$user->loket_id) {
             return redirect()->route('dashboard')->with('error', 'Anda belum ditugaskan ke Loket manapun oleh Admin. Hubungi administrator.');
        }

        // 2. Ambil data loket yang akan dimonitor
        if ($user->role === 'admin') {
            $loketSaya = Loket::first();
            if (!$loketSaya) {
                return redirect()->route('dashboard')->with('error', 'Tidak ada loket yang terdaftar di sistem.');
            }
        } else {
            $loketSaya = $user->loket;
        }

        // 3. Pengecekan Keamanan Ekstra 
        if (!$loketSaya) {
             return redirect()->route('dashboard')->with('error', 'Gagal memuat detail loket. Coba hubungi administrator.');
        }

        $today = Carbon::today()->toDateString();
        
        // 4. Ambil Antrian Sesuai Loket
        $antrianMenunggu = Antrian::where('loket_id', $loketSaya->id)
            ->where('tanggal', $today)
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'asc')
            ->get();
            
        $antrianSaatIni = Antrian::where('loket_id', $loketSaya->id)
            ->where('tanggal', $today)
            ->where('status', 'dipanggil')
            ->first();
            
        // 5. Kirim data ke view lokets.monitor
        return view('lokets.monitor', compact('loketSaya', 'antrianMenunggu', 'antrianSaatIni'));
    }

    // ... (melanjutkan implementasi metode CRUD lainnya untuk memastikan LoketController utuh)
    public function index()
    {
        $lokets = Loket::latest()->get(); 
        return view('lokets.index', compact('lokets'));
    }
    public function create()
    {
        return view('lokets.create');
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_loket' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);
        $validatedData['slug'] = Str::slug($request->nama_loket);
        Loket::create($validatedData);
        return redirect()->route('lokets.index')->with('success', 'Loket berhasil ditambahkan.');
    }
    public function show(Loket $loket)
    {
        return redirect()->route('lokets.edit', $loket->id);
    }
    public function edit(Loket $loket)
    {
        return view('lokets.edit', compact('loket'));
    }
    public function update(Request $request, Loket $loket)
    {
        $validatedData = $request->validate([
            'nama_loket' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'status' => ['required', 'string', Rule::in(['aktif', 'istirahat', 'tutup'])],
        ]);
        $validatedData['slug'] = Str::slug($request->nama_loket);
        $loket->update($validatedData);
        return redirect()->route('lokets.index')->with('success', 'Loket berhasil diupdate.');
    }
    public function updateStatus(Request $request, Loket $loket)
    {
        if (Auth::user()->role !== 'admin' && Auth::user()->loket_id !== $loket->id) {
             return response()->json(['success' => false, 'message' => 'Anda tidak diizinkan mengubah status loket ini.'], 403);
        }
        $request->validate(['status' => ['required', 'string', Rule::in(['aktif', 'istirahat', 'tutup'])]]);
        $loket->update(['status' => $request->status]);
        return response()->json(['success' => true, 'message' => 'Status loket berhasil diperbarui.']);
    }
    public function destroy(Loket $loket)
    {
        $loket->delete();
        return redirect()->route('lokets.index')->with('success', 'Loket berhasil dihapus.');
    }
}