@extends('layout.layout')

@php
    $title = 'Super Admin';
    $subTitle = 'Edit Pengguna';
@endphp

@section('content')
<div class="card shadow-sm border-0 animate-fade-in">
    <div class="card-header bg-light d-flex align-items-center">
        <i class="mdi mdi-account-edit text-primary me-2 fs-5"></i>
        <h5 class="mb-0 fw-bold text-primary">Edit Pengguna</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('superadmin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Nama</label>
                <input type="text" name="name" 
                       value="{{ old('name', $user->name) }}" 
                       class="form-control shadow-sm" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" 
                       value="{{ old('email', $user->email) }}" 
                       class="form-control shadow-sm" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Role</label>
                <select name="role" class="form-select shadow-sm" required>
                    <option value="traveler" {{ $user->role == 'traveler' ? 'selected' : '' }}>Traveler</option>
                    <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="finance" {{ $user->role == 'finance' ? 'selected' : '' }}>Finance</option>
                </select>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4 action-btn">
                    <i class="mdi mdi-content-save me-1"></i> Simpan
                </button>
                <a href="{{ route('superadmin.users.index', ['role' => $user->role]) }}" 
                   class="btn btn-secondary px-4 action-btn">
                    <i class="mdi mdi-arrow-left me-1"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tambahan CSS untuk animasi --}}
<style>
    .animate-fade-in {
        animation: fadeIn 0.6s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .action-btn {
        transition: all 0.3s ease;
    }
    .action-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
</style>
@endsection
