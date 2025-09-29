@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="background-color: #f8f9fa;">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h1 class="mb-0 text-primary fw-bold display-5">
                    <i class="fas fa-ticket-alt me-3 text-secondary"></i>Sistem Antrian Modern
                </h1>
                <a class="btn btn-secondary btn-lg rounded-pill shadow-sm d-flex align-items-center px-4" href="{{ route('monitor.index') }}" target="_blank">
                    <i class="fas fa-desktop me-2"></i>Lihat Papan Monitor
                </a>
            </div>

            @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <p class="mb-0">{!! $message !!}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            {{-- BLOK ANTRIAN SAAT INI (REAL-TIME STATUS) --}}
            @if(isset($antrianSaatIni) && $antrianSaatIni)
            <div class="alert alert-info shadow-lg border-0 rounded-4 mb-5 p-4 text-center">
                <h4 class="mb-1 text-info fw-bold"><i class="fas fa-bullhorn me-2"></i> ANTRIAN SEDANG DILAYANI</h4>
                <div class="d-flex justify-content-center align-items-center my-3">
                    <span class="display-1 fw-bolder text-info">{{ $antrianSaatIni->nomor_antrian }}</span>
                    <span class="fs-4 fw-light text-secondary ms-4">di {{ $antrianSaatIni->loket->nama_loket }}</span>
                </div>
            </div>
            @endif
            
            <div class="row g-4">
                {{-- Ambil Nomor Antrian Card (FORM) --}}
                <div class="col-md-5">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body p-4 p-md-5">
                            <h4 class="card-title text-primary fw-bold mb-4 d-flex align-items-center">
                                <i class="fas fa-plus-square me-3"></i>Ambil Nomor Antrian
                            </h4>
                            <p class="text-muted mb-4">Pilih loket yang Anda tuju untuk mendapatkan nomor antrian digital.</p>
                            
                            @if ($lokets->isEmpty())
                                <div class="alert alert-warning text-center">Tidak ada loket aktif saat ini.</div>
                            @else
                                <form action="{{ route('antrians.store') }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="loket_id" class="form-label fw-semibold">Pilih Loket</label>
                                        <select class="form-select form-select-lg rounded-3" name="loket_id" required>
                                            <option value="" selected disabled>-- Pilih Loket --</option>
                                            @foreach ($lokets as $loket)
                                                @if($loket->status === 'aktif')
                                                    <option value="{{ $loket->id }}">{{ $loket->nama_loket }} (Buka)</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill shadow-sm">
                                        <i class="fas fa-ticket-alt me-2"></i> Dapatkan Antrian
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Daftar Antrian Card (TABLE) --}}
                <div class="col-md-7">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body p-4 p-md-5">
                            <h4 class="card-title text-primary fw-bold mb-4 d-flex align-items-center">
                                <i class="fas fa-clipboard-list me-3"></i>Daftar Antrian Hari Ini
                            </h4>
                            <p class="text-muted mb-4">Urutan antrian terbaru dan statusnya.</p>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0">
                                    <thead class="bg-primary text-white rounded-top">
                                        <tr>
                                            <th scope="col">Nomor</th>
                                            <th scope="col">Loket</th>
                                            <th scope="col">Status</th>
                                            <th scope="col" class="text-end">
                                                @auth Aksi @else Waktu Ambil @endauth
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($antrians as $antrian)
                                        <tr>
                                            <td><span class="fw-bold fs-5 text-primary">{{ $antrian->nomor_antrian }}</span></td>
                                            <td>{{ $antrian->loket->nama_loket }}</td>
                                            <td>
                                                @if($antrian->status == 'menunggu')
                                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="fas fa-clock me-1"></i> {{ ucfirst($antrian->status) }}</span>
                                                @elseif($antrian->status == 'dipanggil')
                                                <span class="badge bg-info text-dark px-3 py-2 rounded-pill"><i class="fas fa-bullhorn me-1"></i> {{ ucfirst($antrian->status) }}</span>
                                                @else
                                                <span class="badge bg-success px-3 py-2 rounded-pill"><i class="fas fa-check-circle me-1"></i> {{ ucfirst($antrian->status) }}</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @auth
                                                {{-- Aksi hanya untuk user yang terautentikasi (Admin/Petugas) --}}
                                                <div class="d-flex justify-content-end gap-2">
                                                    
                                                    @if ($antrian->status == 'menunggu')
                                                        {{-- Tombol PANGGIL (Hanya untuk Menunggu) --}}
                                                        <form action="{{ route('antrians.call', $antrian->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-info btn-sm rounded-circle shadow-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Panggil Antrian">
                                                                <i class="fas fa-bullhorn"></i>
                                                            </button>
                                                        </form>
                                                    @elseif ($antrian->status == 'dipanggil')
                                                        {{-- Tombol SELESAI (Hanya untuk Dipanggil) --}}
                                                        <form action="{{ route('antrians.finish', $antrian->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success btn-sm rounded-circle shadow-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Selesaikan Antrian">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    {{-- Tombol HAPUS (untuk Admin/Petugas) --}}
                                                    <form action="{{ route('antrians.destroy', $antrian->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus antrian ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm rounded-circle shadow-sm" data-bs-toggle="tooltip" data-bs-placement="top" title="Hapus Antrian">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                                @else
                                                {{-- Untuk Pengunjung Publik --}}
                                                <span class="text-muted small">{{ $antrian->created_at->diffForHumans() }}</span>
                                                @endauth
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-5">
                                                <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                                <p class="mb-0 text-muted">Tidak ada antrian yang tersedia saat ini.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection