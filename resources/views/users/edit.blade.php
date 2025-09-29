@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="background-color: #f8f9fa;">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header text-white text-center py-4 rounded-top-4" style="background-image: linear-gradient(to right, #6a11cb 0%, #2575fc 100%);">
                    <h3 class="card-title mb-0 d-flex align-items-center justify-content-center fw-bold">
                        <i class="fas fa-user-edit me-3"></i> Edit Pengguna: {{ $user->name }}
                    </h3>
                </div>

                <div class="card-body p-4 p-md-5">
                    {{-- Menampilkan Ringkasan Error Validasi --}}
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                        <h6 class="alert-heading fw-bold d-flex align-items-center"><i class="fas fa-exclamation-triangle me-2"></i> Ups! Ada masalah dengan input Anda.</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        {{-- Nama Pengguna --}}
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Nama:</label>
                            <div class="input-group input-group-lg rounded-3">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-user text-muted"></i></span>
                                <input 
                                    type="text" 
                                    name="name" 
                                    class="form-control border-start-0 @error('name') is-invalid @enderror" 
                                    placeholder="Masukkan nama pengguna" 
                                    value="{{ old('name', $user->name) }}" 
                                    required
                                >
                            </div>
                            @error('name')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email:</label>
                            <div class="input-group input-group-lg rounded-3">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-envelope text-muted"></i></span>
                                <input 
                                    type="email" 
                                    name="email" 
                                    class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                    placeholder="Masukkan alamat email" 
                                    value="{{ old('email', $user->email) }}" 
                                    required
                                >
                            </div>
                            @error('email')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Peran (Role) --}}
                        <div class="mb-4">
                            <label for="role" class="form-label fw-semibold">Pilih Peran:</label>
                            <div class="input-group input-group-lg rounded-3">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-user-tag text-muted"></i></span>
                                <select 
                                    name="role" 
                                    class="form-select border-start-0 @error('role') is-invalid @enderror" 
                                    required
                                >
                                    @php $currentRole = old('role', $user->role); @endphp
                                    <option value="admin" {{ $currentRole == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="petugas" {{ $currentRole == 'petugas' ? 'selected' : '' }}>Petugas</option>
                                    <option value="pengguna" {{ $currentRole == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                                </select>
                            </div>
                            @error('role')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-5">

                        <p class="text-danger fw-bold mb-4 d-flex align-items-center"><i class="fas fa-exclamation-circle me-2"></i> PERUBAHAN PASSWORD (Opsional)</p>

                        {{-- Password --}}
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">Password Baru:</label>
                            <div class="input-group input-group-lg rounded-3">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-lock text-muted"></i></span>
                                <input 
                                    type="password" 
                                    name="password" 
                                    class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                    placeholder="Kosongkan jika tidak ingin mengubah password"
                                >
                            </div>
                            @error('password')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="mb-5">
                            <label for="password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru:</label>
                            <div class="input-group input-group-lg rounded-3">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-lock text-muted"></i></span>
                                <input 
                                    type="password" 
                                    name="password_confirmation" 
                                    class="form-control border-start-0" 
                                    placeholder="Ketik ulang password baru"
                                >
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a class="btn btn-outline-secondary rounded-pill px-4" href="{{ route('users.index') }}">
                                <i class="fas fa-arrow-left me-2"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection