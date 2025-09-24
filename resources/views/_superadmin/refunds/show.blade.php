@extends('layout.layout')

@php
    $title = 'Super Admin';
    $subTitle = 'Detail Refund';
@endphp

@section('content')
<div class="card shadow-sm border-0 animate-fadeIn">
    <div class="card-body">
        <h5 class="fw-bold mb-3 text-primary">
            <i class="bi bi-receipt-cutoff me-2"></i> Detail Refund
        </h5>

        <div class="row g-3">
            <div class="col-md-6">
                <p class="mb-1"><strong>ID Refund:</strong><br> {{ $refund->id }}</p>
            </div>
            <div class="col-md-6">
                <p class="mb-1"><strong>Status:</strong><br> 
                    <span class="badge 
                        @if($refund->status == 'pending') bg-warning text-dark
                        @elseif($refund->status == 'approved') bg-success
                        @else bg-danger
                        @endif">
                        {{ ucfirst($refund->status) }}
                    </span>
                </p>
            </div>
            <div class="col-md-6">
                <p class="mb-1"><strong>Pembeli:</strong><br> {{ $refund->buyTransaction->buyer->name ?? '-' }}</p>
            </div>
            <div class="col-md-6">
                <p class="mb-1"><strong>Traveler:</strong><br> {{ $refund->buyTransaction->traveler->name ?? '-' }}</p>
            </div>
            <div class="col-md-6">
                <p class="mb-1"><strong>Produk:</strong><br> {{ $refund->buyTransaction->product->name ?? '-' }}</p>
            </div>
            <div class="col-md-6">
                <p class="mb-1"><strong>Metode Pembayaran:</strong><br> {{ $refund->buyTransaction->paymentMethod->name ?? '-' }}</p>
            </div>
            <div class="col-md-12">
                <p class="mb-1"><strong>Alasan Refund:</strong><br> {{ $refund->reason ?? '-' }}</p>
            </div>
            <div class="col-md-12">
                <p class="mb-1"><strong>Tanggal Dibuat:</strong><br> {{ $refund->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mt-4 d-flex flex-wrap gap-2">
            <a href="{{ route('superadmin.refunds.edit', $refund->id) }}" 
               class="btn btn-warning px-3 fw-semibold shadow-sm btn-animate">
                <i class="bi bi-pencil-square me-1"></i> Edit
            </a>

            <!-- Trigger Modal Hapus -->
            <button type="button" class="btn btn-danger px-3 fw-semibold shadow-sm btn-animate" 
                    data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="bi bi-trash me-1"></i> Hapus
            </button>

            <a href="{{ route('superadmin.refunds.index') }}" 
               class="btn btn-outline-secondary px-3 shadow-sm btn-animate">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

<!-- Modal Hapus -->
<div class="modal fade custom-modal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin <strong>MENGHAPUS</strong> refund <em>#{{ $refund->id }}</em>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light shadow-sm" data-bs-dismiss="modal">Batal</button>
        <form action="{{ route('superadmin.refunds.destroy', $refund->id) }}" method="POST" class="m-0">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger shadow-sm">Ya, Hapus</button>
        </form>
      </div>
    </div>
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

    /* Modal Animasi */
    .custom-modal .modal-dialog {
        transform: translateY(-20px);
        transition: transform 0.3s ease-out;
    }
    .custom-modal.show .modal-dialog {
        transform: translateY(0);
    }
</style>
@endsection
