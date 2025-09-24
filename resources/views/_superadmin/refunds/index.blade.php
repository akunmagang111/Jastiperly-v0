@extends('layout.layout')

@php
    $title = 'Super Admin';
    $subTitle = 'Refunds';
@endphp

@push('styles')
<style>
    .action-btn { 
        transition: all 0.3s ease-in-out; 
        border-radius: 6px !important;
    }
    .action-btn:hover { 
        transform: scale(1.05); 
        background: #f8f9fa; 
    }

    .dropdown-item { 
        transition: all 0.2s ease-in-out; 
        border-radius: 6px;
    }
    .dropdown-item:hover { 
        background-color: #f1f5f9; 
        transform: translateX(4px); 
    }

    .dropdown-menu { 
        animation: fadeIn 0.25s ease-in-out; 
        border-radius: 8px; 
    }

    @keyframes fadeIn { 
        from {opacity: 0; transform: translateY(5px);} 
        to {opacity: 1; transform: translateY(0);} 
    }

    /* Modal animasi */
    .custom-modal .modal-dialog {
        transform: translateY(-20px);
        transition: transform 0.3s ease-out;
    }
    .custom-modal.show .modal-dialog {
        transform: translateY(0);
    }
</style>
@endpush

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">

            {{-- Filter & Search --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <form method="GET" action="{{ route('superadmin.refunds.index') }}" class="d-flex gap-2">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="" {{ $status == '' ? 'selected' : '' }}>Semua</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="declined" {{ $status == 'declined' ? 'selected' : '' }}>Declined</option>
                    </select>

                    <input type="text" name="search" value="{{ $search }}" class="form-control"
                        placeholder="Cari nama pembeli..."
                        onkeydown="if(event.key==='Enter'){this.form.submit();}">
                </form>

                <a href="{{ route('superadmin.refunds.export') }}" class="btn btn-warning fw-bold shadow-sm rounded px-3">
                    <i class="bi bi-download me-1"></i> Unduh Data
                </a>
            </div>

            {{-- Table Refund --}}
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Pembeli</th>
                            <th>Produk</th>
                            <th>ID Transaksi</th>
                            <th>Tanggal Refund</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th>Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($refunds as $key => $refund)
                            <tr>
                                <td class="text-center">{{ $refunds->firstItem() + $key }}</td>
                                <td>{{ $refund->buyTransaction->buyer->name ?? '-' }}</td>
                                <td>{{ $refund->buyTransaction->product->name ?? '-' }}</td>
                                <td>#{{ $refund->buyTransaction->id ?? '-' }}</td>
                                <td>{{ $refund->created_at->format('d-m-Y') }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $refund->status == 'approved' ? 'success' : ($refund->status == 'declined' ? 'danger' : 'warning') }} px-3 py-2 rounded">
                                        {{ ucfirst($refund->status) }}
                                    </span>
                                </td>
                                <td>Rp {{ number_format($refund->buyTransaction->total_price ?? 0, 0, ',', '.') }}</td>
                                <td>{{ $refund->buyTransaction->paymentMethod->name ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="dropdown custom-dropdown">
                                        <button class="btn btn-light btn-sm rounded shadow-sm action-btn px-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-gear-fill me-1"></i> Aksi
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                                            <li>
                                                <a href="{{ route('superadmin.refunds.show', $refund->id) }}" class="dropdown-item d-flex align-items-center py-2">
                                                    <span class="badge bg-info-subtle text-info me-2 p-2 rounded"><i class="bi bi-eye"></i></span>
                                                    <span>Lihat</span>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="{{ route('superadmin.refunds.edit', $refund->id) }}" class="dropdown-item d-flex align-items-center py-2">
                                                    <span class="badge bg-warning-subtle text-warning me-2 p-2 rounded"><i class="bi bi-pencil"></i></span>
                                                    <span>Edit</span>
                                                </a>
                                            </li>
                                            <li>
                                                <!-- Trigger Modal -->
                                                <button type="button" class="dropdown-item d-flex align-items-center py-2 text-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal-{{ $refund->id }}">
                                                    <span class="badge bg-danger-subtle text-danger me-2 p-2 rounded"><i class="bi bi-trash"></i></span>
                                                    <span>Hapus</span>
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal Hapus -->
                            <div class="modal fade custom-modal" id="deleteModal-{{ $refund->id }}" tabindex="-1" aria-hidden="true">
                              <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg rounded-3">
                                  <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title">
                                        <i class="bi bi-exclamation-triangle me-2"></i> Konfirmasi Hapus
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                  </div>
                                  <div class="modal-body">
                                    Apakah Anda yakin ingin menghapus refund 
                                    <strong>#{{ $refund->id }}</strong>?<br>
                                    <span class="text-muted">Tindakan ini akan menghapus data secara permanen.</span>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-light shadow-sm" data-bs-dismiss="modal">Batal</button>
                                    <form action="{{ route('superadmin.refunds.destroy', $refund->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger shadow-sm">Ya, Hapus</button>
                                    </form>
                                  </div>
                                </div>
                              </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data refund</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $refunds->links() }}
            </div>

        </div>
    </div>
</div>
@endsection
