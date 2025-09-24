@extends('layout.layout')

@php
    $title = 'Super Admin';
    $subTitle = 'Detail Produk';
@endphp

@section('content')
<div class="card shadow-sm border-0 rounded-3 animate-fadeIn">
    <div class="card-body">
        <h5 class="mb-4 fw-bold text-primary">Detail Produk</h5>

        <div class="row g-4">
            <!-- Gambar Produk -->
            <div class="col-md-4 text-center">
                <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/no-image.png') }}"
                     alt="Produk"
                     class="img-fluid rounded shadow-sm product-img-detail">
            </div>

            <!-- Detail Produk -->
            <div class="col-md-8">
                <table class="table table-borderless align-middle">
                    <tr>
                        <th style="width: 160px;">Nama Produk</th>
                        <td>{{ $product->name }}</td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $product->description ?: '-' }}</td>
                    </tr>
                    <tr>
                        <th>Harga</th>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Nama Pemilik</th>
                        <td>{{ $product->submiter->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Role Submitter</th>
                        <td>
                            @if($product->submiter?->role == 'traveler')
                                <span class="badge bg-info">Traveler</span>
                            @elseif($product->submiter?->role == 'customer')
                                <span class="badge bg-primary">Customer</span>
                            @else
                                <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td>{{ $product->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Terakhir Update</th>
                        <td>{{ $product->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Status Approval</th>
                        <td>
                            <span class="badge
                                @if($product->approval == 'pending') bg-warning text-dark
                                @elseif($product->approval == 'approved') bg-success
                                @elseif($product->approval == 'declined') bg-danger
                                @else bg-secondary
                                @endif">
                                {{ ucfirst($product->approval) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <hr class="my-4">

        <!-- Tombol Aksi -->
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('superadmin.products.index') }}" class="btn btn-secondary shadow-sm btn-animate">
                <i class="mdi mdi-arrow-left"></i> Kembali
            </a>

            <div class="d-flex gap-2">
                <!-- Trigger Modal Setujui -->
                <button type="button" class="btn btn-success shadow-sm btn-animate" data-bs-toggle="modal" data-bs-target="#approveModal">
                    <i class="mdi mdi-check"></i> Setujui
                </button>

                <!-- Trigger Modal Tolak -->
                <button type="button" class="btn btn-danger shadow-sm btn-animate" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="mdi mdi-close"></i> Tolak
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Setujui -->
<div class="modal fade custom-modal" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="approveModalLabel">Konfirmasi Persetujuan</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin <strong>MENYETUJUI</strong> produk <em>{{ $product->name }}</em>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light shadow-sm" data-bs-dismiss="modal">Batal</button>
        <form action="{{ route('superadmin.products.validate', $product->id) }}" method="POST">
            @csrf
            <input type="hidden" name="approval" value="approved">
            <button type="submit" class="btn btn-success shadow-sm">Ya, Setujui</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tolak -->
<div class="modal fade custom-modal" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-3">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="rejectModalLabel">Konfirmasi Penolakan</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin <strong>MENOLAK</strong> produk <em>{{ $product->name }}</em>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light shadow-sm" data-bs-dismiss="modal">Batal</button>
        <form action="{{ route('superadmin.products.validate', $product->id) }}" method="POST">
            @csrf
            <input type="hidden" name="approval" value="declined">
            <button type="submit" class="btn btn-danger shadow-sm">Ya, Tolak</button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection

@push('styles')
<style>
    /* Animasi fade in card */
    .animate-fadeIn {
        animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Gambar produk */
    .product-img-detail {
        max-height: 220px;
        object-fit: cover;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-img-detail:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 18px rgba(0,0,0,0.15);
    }

    /* Tombol animasi */
    .btn-animate {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .btn-animate:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Modal animasi lebih halus */
    .custom-modal .modal-dialog {
        transform: translateY(-20px);
        transition: transform 0.3s ease-out;
    }
    .custom-modal.show .modal-dialog {
        transform: translateY(0);
    }
</style>
@endpush
