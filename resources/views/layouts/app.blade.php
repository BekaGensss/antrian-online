<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Sistem Antrian') }}</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    {{-- Font Awesome (Penting untuk Ikon) --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    {{-- Vite / Bootstrap JS & CSS --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
        }
        /* Style untuk Navbar (Diambil dari Bootstrap default yang Anda gunakan) */
        .navbar {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            /* Catatan: Warna navbar diganti ke dark di komponen yang disisipkan */
        }
        /* Style untuk tautan di navbar white (jika ada) */
        .navbar-light .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #1a73e8;
            display: flex;
            align-items: center;
        }
        .navbar-light .nav-link {
            font-weight: 500;
            color: #555;
            transition: color 0.3s ease;
        }
        .navbar-light .nav-link:hover {
            color: #1a73e8;
        }
        /* Style Tambahan untuk Navbar Dark Custom */
        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .dropdown-item {
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div id="app">
        
        {{-- BLOK NAVIGASI DINAMIS BERBASIS PERAN (Menggantikan Navbar Standar) --}}
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg">
            <div class="container-fluid px-4">
                <a class="navbar-brand d-flex align-items-center fw-bold" href="{{ route('antrians.index') }}">
                    <i class="fas fa-ticket-alt me-2 text-warning"></i>
                    <span class="fs-4">{{ config('app.name', 'Sistem Antrian') }}</span>
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    
                    {{-- Tautan Kiri (Publik) --}}
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item me-lg-2">
                            <a class="nav-link d-flex align-items-center" href="{{ route('monitor.index') }}">
                                <i class="fas fa-desktop me-2"></i> Papan Monitor
                            </a>
                        </li>
                    </ul>

                    {{-- Tautan Kanan (Otentikasi & Manajemen) --}}
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt me-2"></i> {{ __('Login') }}
                                </a>
                            </li>
                        @else
                            {{-- TAUTAN ADMIN --}}
                            @if (Auth::user()->role === 'admin')
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center fw-bold text-success" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-user-shield me-2"></i> Admin Panel
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="adminDropdown">
                                        <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                                        <li><a class="dropdown-item" href="{{ route('users.index') }}"><i class="fas fa-users-cog me-2"></i> Kelola Pengguna</a></li>
                                        <li><a class="dropdown-item" href="{{ route('lokets.index') }}"><i class="fas fa-cogs me-2"></i> Kelola Loket</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item" href="{{ route('laporan.index') }}"><i class="fas fa-chart-line me-2"></i> Laporan Kinerja</a></li>
                                    </ul>
                                </li>
                            @endif

                            {{-- TAUTAN PETUGAS --}}
                            @if (Auth::user()->role === 'petugas')
                                <li class="nav-item me-lg-2">
                                    <a class="nav-link d-flex align-items-center fw-bold text-info" href="{{ route('loket.monitor') }}">
                                        <i class="fas fa-headset me-2"></i> Layanan Petugas
                                    </a>
                                </li>
                            @endif

                            {{-- LOGOUT untuk semua pengguna terautentikasi --}}
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user me-2"></i> {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-home me-2"></i> Home Dashboard</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i> {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        {{-- AKHIR BLOK NAVIGASI DINAMIS --}}

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>