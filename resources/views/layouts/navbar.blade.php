<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-lg">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center fw-bold" href="{{ route('antrians.index') }}">
            <i class="fas fa-ticket-alt me-2 text-warning"></i>
            <span class="fs-4">Sistem Antrian</span>
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
                {{-- Tautan tambahan untuk pengguna non-admin/non-petugas bisa ditambahkan di sini --}}
            </ul>

            {{-- Tautan Kanan (Otentikasi & Manajemen) --}}
            <ul class="navbar-nav ms-auto">
                @guest
                    <li class="nav-item">
                        <a class="nav-link d-flex align-items-center" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt me-2"></i> {{ __('Login Admin/Petugas') }}
                        </a>
                    </li>
                @else
                    {{-- TAUTAN ADMIN --}}
                    @if (auth()->user()->role === 'admin')
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

                    {{-- TAUTAN PETUGAS / MONITOR --}}
                    @if (auth()->user()->role === 'petugas')
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

<style>
    .nav-link {
        transition: color 0.3s ease-in-out;
    }
    .nav-link:hover {
        color: #ffc107 !important; /* Warna kuning untuk efek hover */
    }
    .dropdown-menu-dark .dropdown-item:hover {
        background-color: #343a40;
        color: #ffc107;
    }
</style>