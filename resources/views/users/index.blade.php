@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="background-color: #f8f9fa;">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h1 class="mb-0 text-primary fw-bold display-5">
                    <i class="fas fa-users-cog me-3 text-secondary"></i>Kelola Pengguna
                </h1>
                <a class="btn btn-primary btn-lg rounded-pill shadow-sm d-flex align-items-center px-4" href="{{ route('users.create') }}">
                    <i class="fas fa-user-plus me-2"></i>Tambah Pengguna Baru
                </a>
            </div>

            {{-- Notifikasi Sukses --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
                    <p class="mb-0 fw-semibold">{{ session('success') }}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="text-white rounded-top-4" style="background-image: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);">
                                <tr>
                                    <th scope="col" style="width: 5%;">ID</th>
                                    <th scope="col" style="width: 25%;">Nama</th>
                                    <th scope="col" style="width: 30%;">Email</th>
                                    <th scope="col" style="width: 15%;">Peran</th>
                                    <th scope="col" class="text-center" style="width: 25%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($users as $user)
                                <tr>
                                    <td class="align-middle">{{ $user->id }}</td>
                                    <td class="align-middle fw-semibold">{{ $user->name }}</td>
                                    <td class="align-middle text-muted">{{ $user->email }}</td>
                                    <td class="align-middle">
                                        {{-- Tampilan peran dengan badge yang elegan --}}
                                        @if ($user->role === 'admin')
                                            <span class="badge bg-danger px-3 py-2 rounded-pill fw-normal">{{ ucfirst($user->role) }}</span>
                                        @elseif ($user->role === 'petugas')
                                            <span class="badge bg-info text-dark px-3 py-2 rounded-pill fw-normal">{{ ucfirst($user->role) }}</span>
                                        @else
                                            <span class="badge bg-secondary px-3 py-2 rounded-pill fw-normal">{{ ucfirst($user->role) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="d-flex justify-content-center gap-2">
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm rounded-circle shadow-sm" data-bs-toggle="tooltip" title="Edit Pengguna">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm rounded-circle shadow-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}?');" data-bs-toggle="tooltip" title="Hapus Pengguna">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                        <p class="mb-0 text-muted fs-5">Tidak ada data pengguna yang terdaftar.</p>
                                        <p class="text-muted">Silakan tambahkan pengguna baru melalui tombol di atas.</p>
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

<script>
    // Inisialisasi Tooltips Bootstrap agar tombol aksi memiliki label saat di-hover
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection