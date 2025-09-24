@extends('layout.layout')
@php
    $title = 'Super Admin';
    $subTitle = 'Transactions';
@endphp

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
            <h5 class="fw-bold text-primary mb-2 mb-md-0">📊 Transaksi</h5>
        </div>

        <!-- Filter -->
        <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
            <!-- Filter Jenis Transaksi -->
            <a href="{{ route('superadmin.transactions.index', array_merge(request()->except('type'), ['type' => 'beli'])) }}" 
               class="btn btn-sm {{ request('type')=='beli' ? 'btn-primary text-white' : 'btn-outline-primary' }} shadow-sm d-flex align-items-center px-3">
                Titip Beli 
                <span class="badge ms-2 {{ request('type')=='beli' ? 'bg-light text-primary' : 'bg-primary' }}">
                    {{ $countBeli ?? 0 }}
                </span>
            </a>

            <a href="{{ route('superadmin.transactions.index', array_merge(request()->except('type'), ['type' => 'kirim'])) }}" 
               class="btn btn-sm {{ request('type')=='kirim' ? 'btn-success text-white' : 'btn-outline-success' }} shadow-sm d-flex align-items-center px-3">
                Titip Kirim 
                <span class="badge ms-2 {{ request('type')=='kirim' ? 'bg-light text-success' : 'bg-success' }}">
                    {{ $countKirim ?? 0 }}
                </span>
            </a>

            <!-- Filter Lingkup -->
            <form method="GET" action="{{ route('superadmin.transactions.index') }}">
                <select name="scope" class="form-select form-select-sm shadow-sm" onchange="this.form.submit()">
                    <option value="">Lingkup</option>
                    <option value="dalam" {{ request('scope')=='dalam' ? 'selected' : '' }}>Dalam Negeri</option>
                    <option value="luar" {{ request('scope')=='luar' ? 'selected' : '' }}>Luar Negeri</option>
                </select>
            </form>

            <!-- Filter Status -->
            <form method="GET" action="{{ route('superadmin.transactions.index') }}">
                <select name="status" class="form-select form-select-sm shadow-sm" onchange="this.form.submit()">
                    <option value="">Status</option>
                    <option value="pending" {{ request('status')=='pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status')=='approved' ? 'selected' : '' }}>Approved</option>
                    <option value="declined" {{ request('status')=='declined' ? 'selected' : '' }}>Declined</option>
                </select>
            </form>

            <!-- Export Data -->
            <a href="{{ route('superadmin.transactions.export', request()->all()) }}" 
               class="btn btn-sm btn-dark fw-semibold shadow-sm d-flex align-items-center px-3">
                <i class="bi bi-download me-1"></i> Unduh Data
            </a>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle text-sm">
                <thead class="table-primary text-center">
                    <tr>
                        <th>No</th>
                        <th>Nama Penitip</th>
                        <th>ID Transaksi</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Total Transaksi</th>
                        <th>Pembayaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $index => $transaction)
                    <tr>
                        <td class="text-center">{{ $transactions->firstItem() + $index }}</td>
                        <td>{{ $transaction->buyer?->name ?? '-' }}</td>
                        <td>#{{ $transaction->id }}</td>
                        <td>{{ $transaction->created_at?->format('d-m-Y') ?? '-' }}</td>
                        <td class="text-center">
                            <span class="badge rounded-pill px-3 py-2
                                {{ $transaction->payment_status=='approved' ? 'bg-success-subtle text-success' : ($transaction->payment_status=='pending' ? 'bg-warning-subtle text-warning' : 'bg-danger-subtle text-danger') }}">
                                {{ ucfirst($transaction->payment_status ?? '-') }}
                            </span>
                        </td>
                        <td>Rp {{ number_format($transaction->total_price ?? 0, 0, ',', '.') }}</td>
                        <td>{{ $transaction->paymentMethod?->name ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('superadmin.transactions.show', $transaction->id) }}" class="btn btn-sm btn-info me-1 p-1 px-2">Detail</a>
                            <a href="{{ route('superadmin.transactions.edit', $transaction->id) }}" class="btn btn-sm btn-warning me-1 p-1 px-2">Edit</a>

                            <!-- Tombol Hapus dengan Modal -->
                            <button type="button" 
                                    class="btn btn-sm btn-danger p-1 px-2"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#deleteModal" 
                                    data-id="{{ $transaction->id }}">
                                Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Tidak ada data transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3">
            <span class="text-muted">Total {{ $transactions->total() }}</span>
            {{ $transactions->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-danger text-white">
        <h6 class="modal-title"><i class="bi bi-exclamation-triangle me-1"></i> Konfirmasi Hapus</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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

<style>
    .btn-outline-info:hover { background: #e0f7fa; }
    .btn-outline-warning:hover { background: #fff3cd; }
    .btn-outline-danger:hover { background: #fdecea; }
</style>
@endsection
