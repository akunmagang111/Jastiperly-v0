@extends('layout.layout')

@php
    $title='Dashboard';
    $subTitle = 'AI';
    $script= '<script src="' . asset('assets/js/homeOneChart.js') . '"></script>';
@endphp

@section('content')

            <div class="row row-cols-xxxl-5 row-cols-lg-3 row-cols-sm-2 row-cols-1 gy-4">

                <!-- Total Pendapatan Hari Ini -->
                <div class="col">
                    <div class="card shadow-none border bg-gradient-start-4 h-100">
                        <div class="card-body p-20">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div>
                                    <p class="fw-medium text-primary-light mb-1">Total Pendapatan Hari ini</p>
                                    <h6 class="mb-0">Rp {{ number_format($todayIncome, 0, ',', '.') }}</h6>
                                </div>
                                <div class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center">
                                    <iconify-icon icon="solar:wallet-bold" class="text-white text-2xl mb-0"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Pendapatan Bulan Ini -->
                <div class="col">
                    <div class="card shadow-none border bg-gradient-start-5 h-100">
                        <div class="card-body p-20">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <div>
                                    <p class="fw-medium text-primary-light mb-1">Total Pendapatan Bulan Ini</p>
                                    <h6 class="mb-0">Rp {{ number_format($monthIncome, 0, ',', '.') }}</h6>
                                </div>
                                <div class="w-50-px h-50-px bg-red rounded-circle d-flex justify-content-center align-items-center">
                                    <iconify-icon icon="fa6-solid:file-invoice-dollar" class="text-white text-2xl mb-0"></iconify-icon>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row gy-4 mt-1">

                <div class="col-xxl-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                <h6 class="mb-2 fw-bold text-lg mb-0">Aktivitas</h6>
                                <select class="form-select form-select-sm w-auto bg-base border text-secondary-light"
                                    onchange="location = this.value;">
                                <option value="{{ route('finance.index', ['range' => 'daily']) }}" {{ $range=='daily' ? 'selected' : '' }}>Daily</option>
                                <option value="{{ route('finance.index', ['range' => 'weekly']) }}" {{ $range=='weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="{{ route('finance.index', ['range' => 'monthly']) }}" {{ $range=='monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="{{ route('finance.index', ['range' => 'yearly']) }}" {{ $range=='yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                            </div>

                            <ul class="d-flex flex-wrap align-items-center mt-3 gap-3">
                                <li class="d-flex align-items-center gap-2">
                                    <span class="w-12-px h-12-px rounded-circle bg-primary-600"></span>
                                    <span class="text-secondary-light text-sm fw-semibold">
                                        Titip Kirim Barang:
                                        <span class="text-primary-light fw-bold">{{ $sendCount }}</span>
                                    </span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <span class="w-12-px h-12-px rounded-circle bg-yellow"></span>
                                    <span class="text-secondary-light text-sm fw-semibold">
                                        Titip Beli Barang:
                                        <span class="text-primary-light fw-bold">{{ $buyCount }}</span>
                                        
                                    </span>
                                </li>
                            </ul>

                            <div class="mt-40">
                                <div id="paymentStatusChart" class="margin-16-minus"></div>
                            </div>

                        </div>
                    </div>
                </div>


                <div class="col-xxl-9 col-xl-12">
                    <div class="card h-100">
                        <div class="card-body p-24">

                            <div class="d-flex flex-wrap align-items-center gap-1 justify-content-between mb-16">
                                <ul class="nav border-gradient-tab nav-pills mb-0" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link d-flex align-items-center active" id="pills-to-do-list-tab" data-bs-toggle="pill" data-bs-target="#pills-to-do-list" type="button" role="tab" aria-controls="pills-to-do-list" aria-selected="true">
                                            Pengguna Teraktif
                                            
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link d-flex align-items-center" id="pills-recent-leads-tab" data-bs-toggle="pill" data-bs-target="#pills-recent-leads" type="button" role="tab" aria-controls="pills-recent-leads" aria-selected="false" tabindex="-1">
                                            Transaksi Terbaru
                                            <span class="text-sm fw-semibold py-6 px-12 bg-neutral-500 rounded-pill text-white line-height-1 ms-12 notification-alert">{{ $todayTransactionsCount ?? 0 }}</span>
                                        </button>
                                    </li>
                                </ul>
                                <a  href="javascript:void(0)" class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                                    View All
                                    <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                                </a>
                            </div>

                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-to-do-list" role="tabpanel" aria-labelledby="pills-to-do-list-tab" tabindex="0">
                                    <div class="table-responsive scroll-sm">
                                        <table class="table bordered-table sm-table mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col">No </th>
                                                    <th scope="col">Email </th>
                                                    <th scope="col">Nama</th>
                                                    <th scope="col">Total Transaksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($topUsers as $index => $user)
                                                <tr>
                                                    <td>{{ $topUsers->firstItem() + $index }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->total_buy + $user->total_send }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        </table>

                                        <div class="mt-3">
                                            {{ $topUsers->links() }}
                                        </div>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-recent-leads" role="tabpanel" aria-labelledby="pills-recent-leads-tab" tabindex="0">
                                    <div class="table-responsive scroll-sm">
                                        <table class="table bordered-table sm-table mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col">No </th>
                                                    <th scope="col">ID Transaksi</th>
                                                    <th scope="col">Nama Traveler</th>
                                                    <th scope="col" class="text-center">Nama Penitip</th>
                                                    <th scope="col" class="text-center">Total Transaksi</th>
                                                    <th scope="col" class="text-center">Layanan</th>
                                                    <th scope="col" class="text-center">Metode Pembayaran</th>
                                                    <th scope="col" class="text-center">Status</th>
                                                    <th scope="col" class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($todayTransactions as $index => $trx)
                                                <tr>
                                                    <td>{{ $todayTransactions->firstItem() + $index }}</td>
                                                    <td>{{ $trx->transaction_id }}</td>
                                                    <td>{{ $trx->traveler_name }}</td>
                                                    <td class="text-center">{{ $trx->buyer_name }}</td>
                                                    <td class="text-center">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                                                    <td class="text-center">{{ $trx->service }}</td>
                                                    <td class="text-center">{{ ucfirst($trx->payment_method) }}</td>
                                                    <td class="text-center">
                                                        @if($trx->status == 'approved')
                                                            <span class="bg-success-focus text-success-main px-24 py-4 rounded-pill fw-medium text-sm">Approved</span>
                                                        @elseif($trx->status == 'pending')
                                                            <span class="bg-warning-focus text-warning-main px-24 py-4 rounded-pill fw-medium text-sm">Pending</span>
                                                        @else
                                                            <span class="bg-danger-focus text-danger-main px-24 py-4 rounded-pill fw-medium text-sm">Declined</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="javascript:void(0);" 
                                                            class="btn btn-sm btn-primary view-trx"
                                                            data-id="{{ $trx->transaction_id }}"
                                                            data-traveler="{{ $trx->traveler_name }}"
                                                            data-buyer="{{ $trx->buyer_name }}"
                                                            data-total="Rp {{ number_format($trx->total_price, 0, ',', '.') }}"
                                                            data-service="{{ $trx->service }}"
                                                            data-payment="{{ ucfirst($trx->payment_method) }}"
                                                            data-status="{{ ucfirst($trx->status) }}"
                                                            data-date="{{ \Carbon\Carbon::parse($trx->created_at)->format('d M Y H:i') }}"
                                                            data-image="{{ $trx->buyer_image ? asset('storage/'.$trx->buyer_image) : asset('assets/images/user-placeholder.png') }}">
                                                            Detail
                                                        </a>
                                                        </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        </table>

                                        <div class="mt-3">
                                            {{ $todayTransactions->links() }}
                                        </div>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<!-- Modal Detail Transaksi -->
<div class="modal fade" id="trxDetailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Detail Transaksi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body text-center">
        <!-- Foto Traveler -->
        <img id="trxImage" src="{{ asset('assets/images/user-placeholder.png') }}"
             alt="Foto Traveler" class="rounded-circle mb-3" width="100" height="100">

        <h5 id="trxTraveler" class="fw-bold mb-2"></h5>
        <p class="text-muted" id="trxBuyer"></p>

        <div class="text-start mt-3">
          <h6 class="fw-bold">ID Transaksi</h6>
          <p id="trxId"></p>

          <h6 class="fw-bold">Tanggal</h6>
          <p id="trxDate"></p>

          <h6 class="fw-bold">Status</h6>
          <p id="trxStatus"></p>

          <h6 class="fw-bold">Total Harga</h6>
          <p id="trxTotal"></p>

          <h6 class="fw-bold">Layanan</h6>
          <p id="trxService"></p>

          <h6 class="fw-bold">Metode Pembayaran</h6>
          <p id="trxPayment"></p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

{{-- taruh script chart di bawah --}}
@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    var options = {
        chart: { type: 'line', height: 350 },
        series: [
            { name: 'Titip Kirim', data: @json($chartSendData) },
            { name: 'Titip Beli', data: @json($chartBuyData) }
        ],
        xaxis: { categories: @json($chartLabels) }
    };

    if (window.chart) {
        window.chart.destroy();
    }
    window.chart = new ApexCharts(document.querySelector("#paymentStatusChart"), options);
    window.chart.render();
});

document.addEventListener("DOMContentLoaded", function() {
    // Chart existing code...
    var options = {
        chart: { type: 'line', height: 350 },
        series: [
            { name: 'Titip Kirim', data: @json($chartSendData) },
            { name: 'Titip Beli', data: @json($chartBuyData) }
        ],
        xaxis: { categories: @json($chartLabels) }
    };
    if (window.chart) { window.chart.destroy(); }
    window.chart = new ApexCharts(document.querySelector("#paymentStatusChart"), options);
    window.chart.render();

    // Popup detail transaksi
    document.querySelectorAll(".view-trx").forEach(btn => {
        btn.addEventListener("click", function() {
            document.getElementById("trxId").innerText = "#" + this.dataset.id;
            document.getElementById("trxTraveler").innerText = this.dataset.traveler;
            document.getElementById("trxBuyer").innerText = "Pembeli: " + this.dataset.buyer;
            document.getElementById("trxTotal").innerText = this.dataset.total;
            document.getElementById("trxService").innerText = this.dataset.service;
            document.getElementById("trxPayment").innerText = this.dataset.payment;
            document.getElementById("trxStatus").innerText = this.dataset.status;
            document.getElementById("trxDate").innerText = this.dataset.date;
            document.getElementById("trxImage").src = this.dataset.image;

            new bootstrap.Modal(document.getElementById("trxDetailModal")).show();
        });
    });
});
</script>
@endpush