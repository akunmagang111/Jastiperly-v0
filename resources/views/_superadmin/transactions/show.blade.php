@extends('layout.layout')
@php
    $title='Detail Transaksi';
    $subTitle = 'Super Admin';
@endphp

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold text-primary">📄 Detail Transaksi</h5>
            <a href="{{ route('superadmin.transactions.index') }}" class="btn btn-sm btn-secondary fw-semibold shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <!-- Transaction Info -->
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label fw-semibold">ID Transaksi</label>
                <div class="p-2 border rounded bg-light">#{{ $transaction->id }}</div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Nama Penitip</label>
                <div class="p-2 border rounded bg-light">{{ $transaction->buyer?->name ?? '-' }}</div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Tanggal</label>
                <div class="p-2 border rounded bg-light">{{ $transaction->created_at->format('d-m-Y H:i') }}</div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Status</label>
                @php
                    $statusClass = match($transaction->payment_status) {
                        'approved' => 'bg-success text-white',
                        'pending' => 'bg-warning text-dark',
                        'declined' => 'bg-danger text-white',
                        default => 'bg-secondary text-white',
                    };
                @endphp
                <div class="p-2 border rounded text-center {{ $statusClass }}">
                    {{ ucfirst($transaction->payment_status ?? '-') }}
                </div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Total Transaksi</label>
                <div class="p-2 border rounded bg-light">Rp {{ number_format($transaction->total_price ?? 0, 0, ',', '.') }}</div>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Metode Pembayaran</label>
                <div class="p-2 border rounded bg-light">{{ $transaction->paymentMethod?->name ?? '-' }}</div>
            </div>
        </div>

        <!-- Optional Notes -->
        @if(!empty($transaction->notes))
        <div class="mb-4">
            <label class="form-label fw-semibold">Catatan</label>
            <div class="p-3 border rounded bg-light">{{ $transaction->notes }}</div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="d-flex gap-2">
            <a href="{{ route('superadmin.transactions.edit', $transaction->id) }}" 
               class="btn btn-sm btn-warning fw-semibold shadow-sm px-3">
                <i class="bi bi-pencil-square me-1"></i> Edit
            </a>

            <!-- Tombol Hapus trigger modal -->
            <button type="button" 
                    class="btn btn-sm btn-danger fw-semibold shadow-sm px-3"
                    data-bs-toggle="modal" 
                    data-bs-target="#deleteModal" 
                    data-id="{{ $transaction->id }}">
                <i class="bi bi-trash me-1"></i> Hapus
            </button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-danger text-white">
        <h6 class="modal-title"><i class="bi bi-exclamation-triangle me-1"></i> Konfirmasi Hapus</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin menghapus transaksi ini? Tindakan ini akan menghapus data secara permanen.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
        <form id="deleteForm" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger px-3">Hapus</button>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Script Modal --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var transactionId = button.getAttribute('data-id');
        var form = document.getElementById('deleteForm');
        form.action = '/superadmin/transactions/' + transactionId;
    });
});
</script>

{{-- CSS Tambahan --}}
<style>
    .btn {
        border-radius: 6px !important; /* konsisten kotak */
        transition: all 0.25s ease-in-out;
    }
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0,0,0,0.15);
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endsection
