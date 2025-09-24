@extends('layout.layout')

@php
    $title = 'Super Admin';
    $subTitle = 'Edit Refund';
@endphp

@section('content')
<div class="card shadow-sm border-0 rounded-3 animate-fadeIn">
    <div class="card-body">
        <h5 class="fw-bold mb-3 text-primary">
            <i class="bi bi-pencil-square me-2"></i> Edit Refund
        </h5>

        <form action="{{ route('superadmin.refunds.update', $refund->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Status -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select shadow-sm rounded-3" required>
                    <option value="pending"  {{ $refund->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $refund->status == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $refund->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <!-- Alasan -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Alasan Refund</label>
                <textarea name="reason" class="form-control shadow-sm rounded-3" rows="3">{{ old('reason', $refund->reason) }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="mt-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm btn-animate rounded-3">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
                <a href="{{ route('superadmin.refunds.show', $refund->id) }}" class="btn btn-outline-secondary px-4 shadow-sm btn-animate rounded-3">
                    <i class="bi bi-x-circle me-1"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Animasi Custom --}}
<style>
    /* Fade In Card */
    .animate-fadeIn {
        animation: fadeIn 0.6s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Button Hover Animasi */
    .btn-animate {
        transition: all 0.25s ease-in-out;
    }
    .btn-animate:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0,0,0,0.15);
    }

    /* Konsistensi input & tombol kotak */
    .form-control, .form-select, .btn {
        border-radius: 0.4rem !important; /* kotak tapi sedikit rounded */
    }
</style>
@endsection
