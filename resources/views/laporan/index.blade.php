@extends('layouts.app')

@section('content')

<div class="container-fluid py-5" style="background-color: #f8f9fa;">
    <div class="row justify-content-center">
        <div class="col-lg-12">

            <h1 class="mb-4 text-primary fw-bold display-6">
                <i class="fas fa-chart-bar me-2 text-secondary"></i>Dashboard Laporan Antrian
            </h1>
            
            {{-- Menampilkan pesan error jika ada --}}
            @if (session('error'))
                <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
            @endif

            <!-- 1. FORM FILTER -->
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <h5 class="mb-0 text-dark fw-bold"><i class="fas fa-filter me-2"></i>Filter Laporan</h5>
                </div>
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('laporan.index') }}">
                        <div class="row g-3 align-items-end">
                            {{-- Filter Tanggal Mulai --}}
                            <div class="col-md-4 col-sm-6">
                                <label for="tanggal_mulai" class="form-label text-muted">Tanggal Mulai</label>
                                <input 
                                    type="date" 
                                    class="form-control" 
                                    id="tanggal_mulai" 
                                    name="tanggal_mulai" 
                                    value="{{ $tanggalMulai ?? \Carbon\Carbon::now()->subDays(6)->toDateString() }}" 
                                    required
                                >
                            </div>
                            {{-- Filter Tanggal Akhir --}}
                            <div class="col-md-4 col-sm-6">
                                <label for="tanggal_akhir" class="form-label text-muted">Tanggal Akhir</label>
                                <input 
                                    type="date" 
                                    class="form-control" 
                                    id="tanggal_akhir" 
                                    name="tanggal_akhir" 
                                    value="{{ $tanggalAkhir ?? \Carbon\Carbon::now()->toDateString() }}" 
                                    required
                                >
                            </div>
                            {{-- Filter Loket --}}
                            <div class="col-md-3 col-sm-6">
                                <label for="loket_id" class="form-label text-muted">Loket</label>
                                <select class="form-select" id="loket_id" name="loket_id">
                                    <option value="">Semua Loket</option>
                                    @foreach($lokets as $loket)
                                        <option value="{{ $loket->id }}" {{ $loketId == $loket->id ? 'selected' : '' }}>
                                            {{ $loket->nama_loket }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Tombol Submit --}}
                            <div class="col-md-1 col-sm-6">
                                <button type="submit" class="btn btn-primary w-100 fw-bold">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 2. REKAPITULASI HARIAN -->
            <div class="card shadow-lg border-0 rounded-4 mb-5">
                <div class="card-body p-4 p-md-5">
                    <h4 class="card-title text-primary fw-bold mb-4 d-flex align-items-center border-bottom pb-2">
                        <i class="fas fa-table me-3"></i>Rekapitulasi Harian & Per Loket
                    </h4>
                    
                    @if(count($laporanHarian) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="bg-primary text-white" style="background-image: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);">
                                <tr>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Loket</th>
                                    <th scope="col" class="text-center">Total Antrian</th>
                                    <th scope="col" class="text-center">Antrian Selesai</th>
                                    <th scope="col" class="text-center">Persentase Selesai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporanHarian as $laporan)
                                <tr>
                                    <td class="align-middle fw-semibold">{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d F Y') }}</td>
                                    <td class="align-middle">{{ $laporan->nama_loket }}</td>
                                    <td class="align-middle text-center text-primary fw-bold">{{ $laporan->total_antrian }}</td>
                                    <td class="align-middle text-center text-success fw-bold">{{ $laporan->total_selesai }}</td>
                                    <td class="align-middle text-center">
                                        @php
                                            $persentase = ($laporan->total_antrian > 0) ? round(($laporan->total_selesai / $laporan->total_antrian) * 100, 2) : 0;
                                        @endphp
                                        <span class="badge {{ $persentase >= 80 ? 'bg-success' : ($persentase >= 50 ? 'bg-warning text-dark' : 'bg-danger') }} p-2">
                                            {{ $persentase }}%
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5 bg-light rounded">
                        <i class="fas fa-search-minus fa-3x text-muted mb-3"></i>
                        <p class="text-muted fs-5 mb-0">Tidak ada data rekapitulasi untuk filter ini.</p>
                        <p class="text-muted">Coba ubah rentang tanggal atau filter loket.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- 3. KINERJA WAKTU LAYANAN (Service Time) -->
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <h4 class="card-title text-secondary fw-bold mb-4 d-flex align-items-center border-bottom pb-2">
                        <i class="fas fa-clock me-3"></i>Rata-Rata Waktu Layanan (Kinerja Loket)
                    </h4>
                    
                    @if(count($laporanKinerja) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0">
                            <thead class="bg-secondary text-white">
                                <tr>
                                    <th scope="col">Loket</th>
                                    <th scope="col" class="text-center">Rata-Rata Waktu Layanan (HH:MM:SS)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporanKinerja as $kinerja)
                                <tr>
                                    <td class="align-middle fw-semibold">{{ $kinerja->nama_loket }}</td>
                                    <td class="align-middle text-center fs-5 text-secondary fw-bold">{{ $kinerja->rata_rata_waktu }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5 bg-light rounded">
                        <i class="fas fa-hourglass-empty fa-3x text-muted mb-3"></i>
                        <p class="text-muted fs-5 mb-0">Belum ada data kinerja waktu layanan yang dapat dihitung.</p>
                        <p class="text-muted">Pastikan ada antrian berstatus "selesai" dalam rentang filter.</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

@endsection