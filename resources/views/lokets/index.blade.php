@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="background-color: #f8f9fa;">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <h1 class="mb-0 text-primary fw-bold display-5">
                    <i class="fas fa-list-alt me-3 text-secondary"></i>Kelola Loket
                </h1>
                <a class="btn btn-primary btn-lg rounded-pill shadow-sm d-flex align-items-center px-4" href="{{ route('lokets.create') }}">
                    <i class="fas fa-plus me-2"></i>Tambah Loket Baru
                </a>
            </div>

            @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
                <p class="mb-0 fw-semibold">{{ $message }}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="text-white rounded-top-4" style="background-image: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);">
                                <tr>
                                    <th scope="col" class="py-3" style="width: 5%;">No</th>
                                    <th scope="col" class="py-3" style="width: 20%;">Nama Loket</th>
                                    <th scope="col" class="py-3" style="width: 15%;">Status</th> {{-- Kolom Status Baru --}}
                                    <th scope="col" class="py-3">Deskripsi</th>
                                    <th scope="col" class="text-center py-3" style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($lokets as $loket)
                                <tr>
                                    <td class="align-middle">{{ $loop->iteration }}</td>
                                    <td class="align-middle fw-bold text-primary">{{ $loket->nama_loket }}</td>
                                    
                                    {{-- TAMPILAN STATUS LOKET --}}
                                    <td class="align-middle">
                                        @php
                                            $badgeClass = '';
                                            if ($loket->status === 'aktif') {
                                                $badgeClass = 'bg-success';
                                            } elseif ($loket->status === 'istirahat') {
                                                $badgeClass = 'bg-warning text-dark';
                                            } else {
                                                $badgeClass = 'bg-danger';
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill fw-normal">
                                            {{ ucfirst($loket->status) }}
                                        </span>
                                    </td>
                                    
                                    <td class="align-middle text-muted" style="max-width: 300px;">
                                        <div class="text-truncate">{{ $loket->deskripsi }}</div>
                                    </td>
                                    <td class="text-center align-middle">
                                        <form action="{{ route('lokets.destroy', $loket->id) }}" method="POST" class="d-flex justify-content-center gap-2">
                                            
                                            <a class="btn btn-primary btn-sm rounded-circle shadow-sm" href="{{ route('lokets.edit', $loket->id) }}" data-bs-toggle="tooltip" title="Edit/Ubah Status">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm rounded-circle shadow-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus loket {{ $loket->nama_loket }}?')" data-bs-toggle="tooltip" title="Hapus Loket">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5"> {{-- colspan diubah menjadi 5 --}}
                                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                        <p class="mb-0 text-muted fs-5">Tidak ada loket yang terdaftar saat ini.</p>
                                        <p class="text-muted">Silakan tambahkan loket baru untuk memulai layanan.</p>
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
    // Inisialisasi Tooltips
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection