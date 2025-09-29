@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="background-color: #f8f9fa;">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header text-white text-center py-4 rounded-top-4" style="background-image: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);">
                    <h3 class="card-title mb-0 d-flex align-items-center justify-content-center fw-bold">
                        <i class="fas fa-edit me-3"></i> Edit Loket: {{ $loket->nama_loket }}
                    </h3>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    {{-- Menampilkan Error Validasi --}}
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                        <h6 class="alert-heading fw-bold d-flex align-items-center"><i class="fas fa-exclamation-triangle me-2"></i> Ups! Ada masalah dengan input Anda.</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    
                    <form action="{{ route('lokets.update', $loket->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- BLOK 1: STATUS LOKET --}}
                        <h5 class="fw-bold text-primary mb-3 border-bottom pb-2"><i class="fas fa-signal me-2"></i> Status Operasional</h5>
                        
                        <div class="mb-4">
                            <label for="status" class="form-label fw-semibold">Status Loket Saat Ini:</label>
                            <div class="input-group input-group-lg rounded-3">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-power-off text-muted"></i></span>
                                <select name="status" id="status" class="form-select border-start-0 @error('status') is-invalid @enderror" required>
                                    <option value="aktif" {{ (old('status', $loket->status) == 'aktif') ? 'selected' : '' }}>Aktif (Buka)</option>
                                    <option value="istirahat" {{ (old('status', $loket->status) == 'istirahat') ? 'selected' : '' }}>Istirahat</option>
                                    <option value="tutup" {{ (old('status', $loket->status) == 'tutup') ? 'selected' : '' }}>Tutup</option>
                                </select>
                            </div>
                            @error('status')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info py-3 mb-5">
                            <p class="mb-0 fw-bold">Antrian Terakhir Dilayani: 
                                <span class="badge bg-primary fs-6 ms-2">{{ $loket->nomor_antrian_saat_ini ?? '0' }}</span>
                            </p>
                        </div>
                        
                        {{-- BLOK 2: DETAIL LOKET --}}
                        <h5 class="fw-bold text-primary mb-3 border-bottom pb-2"><i class="fas fa-tag me-2"></i> Detail Loket</h5>

                        <div class="mb-4">
                            <label for="nama_loket" class="form-label fw-semibold">Nama Loket:</label>
                            <div class="input-group input-group-lg rounded-3">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-bullhorn text-muted"></i></span>
                                <input 
                                    type="text" 
                                    name="nama_loket" 
                                    value="{{ old('nama_loket', $loket->nama_loket) }}" 
                                    class="form-control border-start-0 @error('nama_loket') is-invalid @enderror" 
                                    placeholder="Nama Loket" 
                                    required
                                >
                            </div>
                            @error('nama_loket')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-5">
                            <label for="deskripsi" class="form-label fw-semibold">Deskripsi:</label>
                            <textarea 
                                class="form-control rounded-3 @error('deskripsi') is-invalid @enderror" 
                                name="deskripsi" 
                                rows="5" 
                                placeholder="Deskripsi"
                            >{{ old('deskripsi', $loket->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <div class="text-danger mt-2 small">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a class="btn btn-outline-secondary rounded-pill px-4" href="{{ route('lokets.index') }}">
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