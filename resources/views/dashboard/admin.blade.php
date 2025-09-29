@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="background-color: #f8f9fa;">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h1 class="mb-0 text-primary fw-bold display-5">
                    <i class="fas fa-tachometer-alt me-3 text-secondary"></i>Dashboard Admin
                </h1>
            </div>

            <div class="card shadow-lg border-0 rounded-4 mb-5">
                <div class="card-body p-4 p-md-5">
                    <h4 class="card-title mb-1">Halo, {{ auth()->user()->name }}! 👋</h4>
                    <p class="text-muted">Selamat datang di panel administrasi sistem antrian Anda. Tanggal Hari Ini: **{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}**</p>
                </div>
            </div>

            {{-- BLOK STATISTIK UTAMA --}}
            <div class="row g-4 mb-5">
                {{-- Card Total Antrian Hari Ini --}}
                <div class="col-lg-3 col-md-6">
                    <div class="card shadow-sm border-0 rounded-4 text-white" style="background-image: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);">
                        <div class="card-body p-4 text-center">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <h5 class="card-title fw-semibold">Total Antrian Hari Ini</h5>
                            <p class="card-text display-4 fw-bold">{{ $totalAntrianHariIni }}</p>
                        </div>
                    </div>
                </div>

                {{-- Card Antrian Sedang Menunggu --}}
                <div class="col-lg-3 col-md-6">
                    <div class="card shadow-sm border-0 rounded-4 text-white" style="background-image: linear-gradient(to right, #f7b42c 0%, #fc575e 100%);">
                        <div class="card-body p-4 text-center">
                            <i class="fas fa-clock fa-3x mb-3"></i>
                            <h5 class="card-title fw-semibold">Antrian Sedang Menunggu</h5>
                            <p class="card-text display-4 fw-bold">{{ $antrianMenunggu }}</p>
                        </div>
                    </div>
                </div>

                {{-- Card Antrian Sedang Dipanggil --}}
                <div class="col-lg-3 col-md-6">
                    <div class="card shadow-sm border-0 rounded-4 text-white" style="background-image: linear-gradient(to right, #25aae1 0%, #4481eb 100%);">
                        <div class="card-body p-4 text-center">
                            <i class="fas fa-headset fa-3x mb-3"></i>
                            <h5 class="card-title fw-semibold">Antrian Sedang Dilayani</h5>
                            <p class="card-text display-4 fw-bold">{{ $antrianDipanggil }}</p>
                        </div>
                    </div>
                </div>

                {{-- Card Loket Aktif --}}
                <div class="col-lg-3 col-md-6">
                    <div class="card shadow-sm border-0 rounded-4 text-white" style="background-image: linear-gradient(to right, #6a11cb 0%, #2575fc 100%);">
                        <div class="card-body p-4 text-center">
                            <i class="fas fa-door-open fa-3x mb-3"></i>
                            <h5 class="card-title fw-semibold">Loket Aktif</h5>
                            <p class="card-text display-4 fw-bold">{{ $loketAktif }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                {{-- BLOK ANTRIAN TERBARU (5 Antrian Menunggu Pertama) --}}
                <div class="col-lg-6">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body p-4 p-md-5">
                            <h4 class="card-title text-danger fw-bold mb-4 d-flex align-items-center border-bottom pb-2">
                                <i class="fas fa-list-ol me-3"></i>5 Antrian Menunggu Terdepan
                            </h4>
                            <div class="list-group list-group-flush">
                                @forelse ($antrianTerbaru as $antrian)
                                    <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                                        <div>
                                            <h5 class="mb-1 fw-bold text-danger">{{ $antrian->nomor_antrian }}</h5>
                                            <p class="mb-0 text-muted small">Loket Tujuan: {{ $antrian->loket->nama_loket }}</p>
                                        </div>
                                        <span class="badge bg-warning text-dark rounded-pill px-3 py-2 fw-normal">Menunggu</span>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-muted">Tidak ada antrian yang sedang menunggu.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BLOK AKSI CEPAT --}}
                <div class="col-lg-6">
                    <div class="card shadow-lg border-0 rounded-4 h-100">
                        <div class="card-body p-4 p-md-5">
                            <h4 class="card-title text-primary fw-bold mb-4 d-flex align-items-center border-bottom pb-2">
                                <i class="fas fa-bolt me-3"></i>Aksi Cepat
                            </h4>
                            <p class="text-muted mb-4">Pilih salah satu menu di bawah untuk memulai pengelolaan sistem.</p>
                            <div class="d-flex flex-wrap gap-3">
                                <a href="{{ route('lokets.index') }}" class="btn btn-outline-primary btn-lg rounded-pill px-4 shadow-sm">
                                    <i class="fas fa-cogs me-2"></i>Kelola Loket
                                </a>
                                <a href="{{ route('users.index') }}" class="btn btn-outline-warning btn-lg rounded-pill px-4 shadow-sm">
                                    <i class="fas fa-users-cog me-2"></i>Kelola Pengguna
                                </a>
                                <a href="{{ route('laporan.index') }}" class="btn btn-outline-info btn-lg rounded-pill px-4 shadow-sm">
                                    <i class="fas fa-chart-line me-2"></i>Lihat Laporan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection