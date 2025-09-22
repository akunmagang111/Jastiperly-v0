@extends('layout.layout')
@php
    $title='Dashboard';
    $subTitle = 'Investment';
    $script = ' <script src="' . asset('assets/js/homeFiveChart.js') . '"></script>';
@endphp

@section('content')

            <div class="row gy-4">

                <div class="col-xxl-6">
                    <div class="card h-100">
                        <div class="card-body p-24">
                            <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                                <h6 class="mb-2 fw-bold text-lg mb-0">Daftar Refund</h6>
                                <a  href="javascript:void(0)" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                                    View All
                                    <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                                </a>
                            </div>
                            <div>
    <form method="GET">
        <div class="d-flex gap-2 mb-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search" class="form-control">
            <button type="submit" class="btn btn-primary">Cari</button>
        </div>

        <div class="btn-group mb-3" role="group">
            <input type="radio" class="btn-check" name="status" id="btnradio1" value="" {{ request('status') == '' ? 'checked' : '' }} onchange="this.form.submit()">
            <label class="btn btn-outline-primary-600 px-20 py-11 radius-8" for="btnradio1">Semua</label>

            <input type="radio" class="btn-check" name="status" id="btnradio2" value="pending" {{ request('status') == 'pending' ? 'checked' : '' }} onchange="this.form.submit()">
            <label class="btn btn-outline-warning-600 px-20 py-11 radius-8" for="btnradio2">Pending</label>

            <input type="radio" class="btn-check" name="status" id="btnradio3" value="approved" {{ request('status') == 'approved' ? 'checked' : '' }} onchange="this.form.submit()">
            <label class="btn btn-outline-success-600 px-20 py-11 radius-8" for="btnradio3">Approved</label>

            <input type="radio" class="btn-check" name="status" id="btnradio4" value="declined" {{ request('status') == 'declined' ? 'checked' : '' }} onchange="this.form.submit()">
            <label class="btn btn-outline-danger-600 px-20 py-11 radius-8" for="btnradio4">Declined</label>
        </div>
    </form>

    <div class="table-responsive scroll-sm">
        <table class="table bordered-table mb-0 table-hover">
            <thead>
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
            @forelse($refunds as $index => $refund)
                @php
                    $trx = $refund->buyTransaction;
                    $buyer = $trx->buyer;
                @endphp
                <tr>
                    <td>{{ $refunds->firstItem() + $index }}</td>
                    <td>{{ $buyer?->detail?->name ?? $buyer?->name ?? $buyer?->email ?? '-' }}</td>
                    <td>#{{ $trx->id }}</td>
                    <td>{{ $trx->created_at->format('d M Y') }}</td>
                    <td>
                        <span class="badge bg-{{ $refund->status == 'approved' ? 'success' : ($refund->status == 'pending' ? 'warning' : 'danger') }}">
                            {{ ucfirst($refund->status) }}
                        </span>
                    </td>
                    <td>Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                    <td>{{ $trx->paymentMethod->name ?? '-' }}</td>
                    <td>
                        <a href="javascript:void(0);"
                        class="me-2 view-refund"
                        data-image="{{ $buyer?->detail?->account_image ? asset('storage/'.$buyer->detail->account_image) : asset('assets/images/user-placeholder.png') }}"
                        data-buyer="{{ $buyer?->detail?->name ?? $buyer?->name ?? $buyer?->email ?? '-' }}"
                        data-product="{{ $trx->product->name ?? '-' }}"
                        data-trxid="{{ $trx->id }}"
                        data-date="{{ $trx->created_at->format('d M Y') }}"
                        data-status="{{ ucfirst($refund->status) }}"
                        data-total="Rp {{ number_format($trx->total_price, 0, ',', '.') }}"
                        data-payment="{{ $trx->paymentMethod->name ?? '-' }}">
                            <iconify-icon icon="mdi:eye" class="text-primary" style="font-size: 32px;"></iconify-icon>
                        </a>

                        <a href="javascript:void(0);"
                        class="edit-refund"
                        data-id="{{ $refund->id }}"
                        data-status="{{ $refund->status }}">
                            <iconify-icon icon="mdi:pencil" class="text-warning" style="font-size: 32px;"></iconify-icon>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data refund</td>
                </tr>
            @endforelse
        </tbody>
        </table>
        <div class="mt-3">
            {{ $refunds->links() }}
        </div>
    </div>
    <!-- Modal Detail Refund -->
<div class="modal fade" id="refundDetailModal" tabindex="-1" aria-labelledby="refundDetailModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="refundDetailModalLabel">Detail Refund</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <!-- Foto Traveler -->
        <img id="detailImage" src="{{ asset('assets/images/user-placeholder.png') }}" 
            alt="Foto Traveler" class="rounded-circle mb-3" width="100" height="100">

        <h5 id="detailBuyer" class="fw-bold mb-3"></h5>

        <div class="text-start">
            <h6 class="fw-bold">Produk</h6>
            <p id="detailProduct"></p>

            <h6 class="fw-bold">ID Transaksi</h6>
            <p id="detailTrxId"></p>

            <h6 class="fw-bold">Tanggal</h6>
            <p id="detailDate"></p>

            <h6 class="fw-bold">Status</h6>
            <p id="detailStatus"></p>

            <h6 class="fw-bold">Total Harga</h6>
            <p id="detailTotal"></p>

            <h6 class="fw-bold">Metode Pembayaran</h6>
            <p id="detailPayment"></p>
        </div>
    </div>
    </div>
  </div>
</div>

<!-- Modal Edit Refund -->
<div class="modal fade" id="refundEditModal" tabindex="-1" aria-labelledby="refundEditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <form method="POST" id="editRefundForm">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title fw-bold" id="refundEditModalLabel">Edit Refund</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="refund_id" id="editRefundId">

            <div class="mb-3">
                <label for="status" class="form-label fw-bold">Status</label>
                <select name="status" id="editStatus" class="form-select">
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="declined">Declined</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
                    </div>
                </div>

                

            </div>
        </div>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    /// Detail Refund
    document.querySelectorAll(".view-refund").forEach(btn => {
        btn.addEventListener("click", function() {
            document.getElementById("detailImage").src = this.dataset.image;
            document.getElementById("detailBuyer").innerText = this.dataset.buyer;
            document.getElementById("detailProduct").innerText = this.dataset.product;
            document.getElementById("detailTrxId").innerText = "#" + this.dataset.trxid;
            document.getElementById("detailDate").innerText = this.dataset.date;
            document.getElementById("detailStatus").innerText = this.dataset.status;
            document.getElementById("detailTotal").innerText = this.dataset.total;
            document.getElementById("detailPayment").innerText = this.dataset.payment;

            new bootstrap.Modal(document.getElementById("refundDetailModal")).show();
        });
    });

    // Edit Refund
    document.querySelectorAll(".edit-refund").forEach(btn => {
        btn.addEventListener("click", function() {
            document.getElementById("editRefundId").value = this.dataset.id;
            document.getElementById("editStatus").value = this.dataset.status;

            // set action ke route update refund
            document.getElementById("editRefundForm").action = "/finance/refunds/" + this.dataset.id;

            new bootstrap.Modal(document.getElementById("refundEditModal")).show();
        });
    });
});
</script>
@endpush