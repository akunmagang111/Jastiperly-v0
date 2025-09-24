@extends('layout.layout')
@php
    $title = 'Edit Transaksi';
    $subTitle = 'Cryptocracy';
@endphp

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold text-primary">✏️ Edit Transaksi</h5>
            <a href="{{ route('superadmin.transactions.index') }}" class="btn btn-sm btn-secondary fw-semibold shadow-sm rounded">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <!-- Form Edit Transaksi -->
        <form action="{{ route('superadmin.transactions.updateTransaction', $transaction->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">ID Transaksi</label>
                    <input type="text" class="form-control bg-light" value="#{{ $transaction->id }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nama Penitip</label>
                    <input type="text" class="form-control bg-light" value="{{ $transaction->buyer?->name ?? '-' }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Tanggal</label>
                    <input type="text" class="form-control bg-light" value="{{ $transaction->created_at->format('d-m-Y H:i') }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="payment_status" class="form-select">
                        <option value="pending" {{ $transaction->payment_status=='pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $transaction->payment_status=='approved' ? 'selected' : '' }}>Approved</option>
                        <option value="declined" {{ $transaction->payment_status=='declined' ? 'selected' : '' }}>Declined</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Total Transaksi</label>
                    <input type="number" name="total_price" class="form-control" value="{{ $transaction->total_price }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Metode Pembayaran</label>
                    <input type="text" class="form-control bg-light" value="{{ $transaction->paymentMethod?->name ?? '-' }}" readonly>
                </div>
            </div>

            <!-- Aksi -->
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-sm btn-primary shadow-sm rounded action-btn">
                    <i class="bi bi-save me-1"></i> Simpan
                </button>
                <a href="{{ route('superadmin.transactions.index') }}" class="btn btn-sm btn-secondary shadow-sm rounded action-btn">
                    <i class="bi bi-x me-1"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

{{-- CSS Tambahan --}}
<style>
    .action-btn {
        transition: all 0.2s ease-in-out;
    }
    .action-btn:hover {
        transform: scale(1.04);
    }
    .form-control[readonly], .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endsection
