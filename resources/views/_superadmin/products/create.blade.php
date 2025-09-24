@extends('layout.layout')

@php
    $title = 'Super Admin';
    $subTitle = 'Tambah Produk';
@endphp

@section('content')
<div class="card shadow-sm border-0 rounded-3 mb-4 animate__animated animate__fadeIn">
    <div class="card-body p-4">
        <!-- Header -->
        <h4 class="mb-4 fw-bold text-primary d-flex align-items-center">
            <i class="mdi mdi-plus-box-outline me-2"></i>
            Tambah Produk Baru
        </h4>

        <!-- Form -->
        <form action="{{ route('superadmin.products.store') }}" 
              method="POST" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
            @csrf

            <!-- Nama Produk -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                <input type="text" name="name"
                       value="{{ old('name') }}"
                       class="form-control @error('name') is-invalid @enderror"
                       placeholder="Masukkan nama produk" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Harga -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Harga <span class="text-danger">*</span></label>
                <input type="number" name="price"
                       value="{{ old('price') }}"
                       class="form-control @error('price') is-invalid @enderror"
                       placeholder="Contoh: 150000" required>
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="col-12">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="description" rows="3"
                          class="form-control @error('description') is-invalid @enderror"
                          placeholder="Tulis deskripsi produk...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Role & Submitter -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Role Submitter <span class="text-danger">*</span></label>
                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="traveler" {{ old('role') == 'traveler' ? 'selected' : '' }}>Traveler</option>
                    <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <small class="text-muted d-block mt-2">Nama pengguna baru:</small>
                <input type="text" name="new_submitter_name" 
                       class="form-control mt-1 @error('new_submitter_name') is-invalid @enderror"
                       placeholder="Masukkan nama pengguna baru" 
                       value="{{ old('new_submitter_name') }}">
                @error('new_submitter_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Kategori -->
            <div class="col-md-6">
                <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id')==$category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Foto Produk -->
            <div class="col-12">
                <label class="form-label fw-semibold">Foto Produk</label>
                <input type="file" name="image"
                       class="form-control @error('image') is-invalid @enderror"
                       accept="image/*" onchange="previewImage(event)">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="mt-3">
                    <img id="preview" 
                         src="{{ asset('images/no-image.png') }}"
                         alt="Preview"
                         class="preview-img rounded shadow-sm border"
                         style="display:none;">
                </div>
            </div>

            <!-- Tombol -->
            <div class="col-12 d-flex justify-content-between mt-4">
                <a href="{{ route('superadmin.products.index') }}" class="btn btn-light border px-3">
                    <i class="mdi mdi-arrow-left me-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="mdi mdi-content-save-outline me-1"></i> Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Style tambahan --}}
<style>
    .preview-img {
        max-height: 200px;
        width: auto;
        object-fit: cover;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .preview-img:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(0,0,0,0.2);
    }
    .btn {
        transition: all 0.2s ease;
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
</style>

{{-- Script preview gambar --}}
<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('preview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}
</script>
@endsection
