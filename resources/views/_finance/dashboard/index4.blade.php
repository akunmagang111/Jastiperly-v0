@extends('layout.layout')
@php
    $title='Dashboard';
    $subTitle = 'Cryptocracy';
    $script = ' <script src="' . asset('assets/js/homeFourChart.js') . '"></script>';
@endphp

@section('content')

<div class="col-xxl-6">
    <div class="card h-100">
        <div class="card-body p-24">
            <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                <h6 class="mb-2 fw-bold text-lg mb-0">Daftar Penitip</h6>
                <button type="button" class="btn btn-outline-success-600 radius-8 px-20 py-8 d-flex align-items-center gap-2">
                    Unduh Data <iconify-icon icon="mdi:download" class="text-xl"></iconify-icon>
                </button>
            </div>

            <form method="GET">
                <div class="d-flex gap-2 mb-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search" class="form-control">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </form>

            <div class="table-responsive scroll-sm">
                <table class="table bordered-table mb-0 table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Penitip</th>
                            <th>Username</th>
                            <th>Tanggal</th>
                            <th>No. Rekening</th>
                            <th>Total Transaksi</th>
                            <th>Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($transactions as $i => $trx)
                        <tr>
                            <td>{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/images/product/product-img1.png') }}" class="flex-shrink-0 me-12 radius-8" width="40">
                                    <div class="flex-grow-1">
                                        <h6 class="text-md mb-0 fw-normal">{{ $trx['name'] }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $trx['email'] }}</td>
                            <td>{{ $trx['latest_with_type'] }}</td>
                            <td>{{ $trx['accounts'] }}</td>
                            <td>{{ $trx['total_trx'] }}</td>
                            <td>{{ $trx['payments'] }}</td>
                            <td class="text-center">
                                <a href="javascript:void(0);" 
                                class="me-2 view-detail" 
                                data-name="{{ $trx['name'] }}"
                                data-email="{{ $trx['email'] }}"
                                data-image="{{ $trx['userdetails']?->account_image ? asset('storage/'.$trx['userdetails']->account_image) : asset('assets/images/default-avatar.png') }}"
                                data-accounts='@json($trx['accounts_raw'])'
                                data-payments='@json($trx['payments_raw'])'
                                data-total="{{ $trx['total_trx'] }}"
                                data-latest="{{ $trx['latest_with_type'] }}">
                                    <iconify-icon icon="mdi:eye" class="text-primary" style="font-size: 32px;"></iconify-icon>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $transactions->appends(request()->query())->links('vendor.pagination.custom') }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Detail --}}
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Traveler</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="d-flex align-items-center mb-3">
            <img id="travelerImage" src="" class="rounded me-3" width="80" height="80" style="object-fit: cover;">
            <div>
                <h6 id="travelerName" class="mb-0 fw-bold"></h6>
                <small id="travelerEmail" class="text-muted"></small>
            </div>
        </div>

        <h6 class="fw-bold">No. Rekening</h6>
        <ul id="accountList" class="list-group mb-3"></ul>

        <h6 class="fw-bold">Pembayaran</h6>
        <ul id="paymentList" class="list-group mb-3"></ul>

        <h6 class="fw-bold">Total Transaksi</h6>
        <p id="totalTrx" class="mb-3"></p>

        <h6 class="fw-bold">Transaksi Terakhir</h6>
        <p id="latestTrx" class="mb-0"></p>
    </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".view-detail").forEach(el => {
        el.addEventListener("click", function() {
            let name = this.dataset.name;
            let email = this.dataset.email;
            let image = this.dataset.image;
            let accounts = JSON.parse(this.dataset.accounts);
            let payments = JSON.parse(this.dataset.payments);
            let totalTrx = this.dataset.total;
            let latestTrx = this.dataset.latest;

            document.getElementById("totalTrx").innerText = totalTrx;
            document.getElementById("latestTrx").innerText = latestTrx;

            // Isi modal
            document.getElementById("travelerName").innerText = name;
            document.getElementById("travelerEmail").innerText = email;
            document.getElementById("travelerImage").src = image;

            // Isi list accounts
            let accList = document.getElementById("accountList");
            accList.innerHTML = "";
            if(Array.isArray(accounts)) {
                accounts.forEach(acc => {
                    accList.innerHTML += `<li class="list-group-item">${acc}</li>`;
                });
            } else {
                accList.innerHTML = `<li class="list-group-item">${accounts}</li>`;
            }

            // Isi list payments
            let payList = document.getElementById("paymentList");
            payList.innerHTML = "";
            if(Array.isArray(payments)) {
                payments.forEach(pay => {
                    payList.innerHTML += `<li class="list-group-item">${pay}</li>`;
                });
            } else {
                payList.innerHTML = `<li class="list-group-item">${payments}</li>`;
            }

            // Show modal
            new bootstrap.Modal(document.getElementById("detailModal")).show();
        });
    });
});
</script>
@endpush