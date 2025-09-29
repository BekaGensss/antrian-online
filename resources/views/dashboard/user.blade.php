@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="background-color: #f8f9fa;">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            {{-- BLOK UTAMA INFORMASI --}}
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-body p-4 p-md-5 text-center">
                    <div class="d-flex justify-content-center align-items-center mb-4">
                        <i class="fas fa-user-tie fa-4x text-primary me-3"></i>
                        <h1 class="mb-0 text-primary fw-bold display-5">
                            Dashboard Petugas
                        </h1>
                    </div>
                    <hr class="my-4">
                    <p class="fs-5 text-muted mb-2">
                        Halo, <span class="fw-semibold text-dark">{{ auth()->user()->name }}</span>!
                    </p>
                    <p class="text-secondary mb-4">
                        Selamat datang di panel layanan. Silakan bersiap untuk memulai panggilan antrian.
                    </p>
                    
                    {{-- Tombol Utama untuk Pindah ke Halaman Monitor --}}
                    {{-- Menggunakan route('loket.monitor') yang disarankan di Controller --}}
                    <a href="{{ route('loket.monitor') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow-lg fw-bold">
                        <i class="fas fa-headset me-2"></i> Mulai Layanan Antrian
                    </a>
                </div>
            </div>

            {{-- BLOK STATISTIK RINGAN HARI INI --}}
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow-sm border-0 rounded-4 text-white" style="background-image: linear-gradient(to right, #f7b42c 0%, #fc575e 100%);">
                        <div class="card-body p-4 text-center">
                            <i class="fas fa-list-ol fa-3x mb-3"></i>
                            <h5 class="card-title fw-semibold">Total Antrian Menunggu Hari Ini</h5>
                            <p class="card-text display-4 fw-bold">
                                {{ $antrianMenunggu ?? '0' }}
                            </p>
                            <p class="mb-0 small">Siap dilayani setelah Anda memulai sesi.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection