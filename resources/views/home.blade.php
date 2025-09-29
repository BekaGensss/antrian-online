@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="background-color: #f8f9fa;">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header text-white text-center py-4 rounded-top-4" style="background-image: linear-gradient(to right, #6a11cb 0%, #2575fc 100%);">
                    <h3 class="card-title mb-0 d-flex align-items-center justify-content-center fw-bold">
                        <i class="fas fa-home me-3"></i>Dashboard
                    </h3>
                </div>

                <div class="card-body p-4 p-md-5">
                    {{-- Notifikasi Status --}}
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
                            <p class="mb-0 fw-semibold">{{ session('status') }}</p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                        <p class="fs-4 mb-0 text-dark fw-semibold">Selamat datang, {{ Auth::user()->name }}!</p>
                        <p class="text-muted mt-2">Anda berhasil masuk sebagai **{{ ucfirst(Auth::user()->role) }}**.</p>
                    </div>

                    {{-- Kontrol Navigasi Berdasarkan Peran (Role-Based Navigation) --}}
                    <div class="d-flex justify-content-center mt-4 flex-column flex-sm-row gap-3">
                        @if (Auth::user()->role === 'admin')
                            <a href="{{ route('users.index') }}" class="btn btn-danger btn-lg rounded-pill px-5 shadow-sm">
                                <i class="fas fa-users-cog me-2"></i> Kelola Admin & Petugas
                            </a>
                            <a href="{{ route('laporan.index') }}" class="btn btn-info btn-lg rounded-pill px-5 shadow-sm text-white">
                                <i class="fas fa-chart-line me-2"></i> Lihat Laporan
                            </a>
                        @elseif (Auth::user()->role === 'petugas')
                            <a href="{{ route('antrian.monitor') }}" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm">
                                <i class="fas fa-headset me-2"></i> Mulai Layani Antrian
                            </a>
                        @else
                            {{-- Pengguna Biasa / Publik --}}
                            <a href="{{ route('antrian.ambil') }}" class="btn btn-success btn-lg rounded-pill px-5 shadow-sm">
                                <i class="fas fa-ticket-alt me-2"></i> Ambil Nomor Antrian
                            </a>
                        @endif
                    </div>

                    {{-- Link tambahan untuk semua pengguna --}}
                    <div class="text-center mt-4 border-top pt-3">
                        <a href="{{ url('/') }}" class="text-muted text-decoration-none">
                            <i class="fas fa-home me-1"></i> Kembali ke Beranda Utama
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection