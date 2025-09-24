@extends('layout.layout')

@php
    $title = 'Super Admin';
    $subTitle = 'Edit Produk';
@endphp

@section('content')
<div class="card shadow-sm border-0 animate__animated animate__fadeIn">
    <div class="card-body">
        <h5 class="mb-4 fw-bold">Edit Produk</h5>

        <form action="{{ route('superadmin.products.update', $product->id) }}" 
              method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <!-- Hidden Submitter ID -->
            <input type="hidden" name="submiter_id" value="{{ old('submiter_id', $product->submiter_id) }}">

            <!-- Role Submitter -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Role Submitter</label>
                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="traveler" {{ old('role', $product->role) == 'traveler' ? 'selected' : '' }}>Traveler</option>
                    <option value="customer" {{ old('role', $product->role) == 'customer' ? 'selected' : '' }}>Customer</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <small class="text-muted d-block mt-2">Nama pengguna baru (opsional):</small>
                <input type="text" name="new_submitter_name" class="form-control mt-1"
                       placeholder="Masukkan nama pengguna baru"
                       value="{{ old('new_submitter_name') }}">
                @error('new_submitter_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Nama Produk -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Produk</label>
                <input type="text" name="name" 
                       class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name', $product->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi Produk</label>
                <textarea name="description" rows="3" 
                          class="form-control @error('description') is-invalid @enderror"
                          required>{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Harga -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Harga</label>
                <input type="number" step="0.01" name="price" 
                       class="form-control @error('price') is-invalid @enderror" 
                       value="{{ old('price', $product->price) }}" required>
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Hidden Approval -->
            <input type="hidden" name="approval" 
                   value="{{ old('approval', $product->approval ?? 'pending') }}">

            <!-- Kategori -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Kategori</label>
                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" 
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Foto Produk -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Foto Produk</label>
                <input type="file" name="image" 
                       class="form-control @error('image') is-invalid @enderror" 
                       accept="image/*" onchange="previewImage(event)">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <div class="mt-3">
                    @if($product->image)
                        <img id="preview" src="{{ asset('storage/'.$product->image) }}" 
                             alt="Foto Produk" class="rounded shadow-sm preview-img">
                    @else
                        <img id="preview" style="display:none;" class="rounded shadow-sm preview-img" alt="Foto Produk">
                    @endif
                </div>
            </div>

            <!-- Tombol -->
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('superadmin.products.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-warning px-4">
                    <i class="mdi mdi-content-save-edit-outline me-1"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .preview-img {
        width: 160px;
        height: auto;
        max-height: 200px;
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
