@extends('layout.layout')

@php
    $title = 'Super Admin';
    $subTitle = 'Dashboard';
    $script = '<script src="' . asset('assets/js/homeOneChart.js') . '"></script>';
@endphp

@section('content')
<!-- Kartu Ringkasan -->
<div class="row row-cols-1 row-cols-md-2 gy-4 mb-4">
    <!-- Total Pengguna -->
    <div class="col">
        <div class="card shadow-none border bg-gradient-start-1 h-100">
            <div class="card-body p-20">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <p class="fw-medium text-primary-light mb-1">Total Pengguna</p>
                        <h6 class="mb-0">{{ number_format($totalUsers ?? 0) }}</h6>
                    </div>
                    <div class="w-50-px h-50-px bg-cyan rounded-circle d-flex justify-content-center align-items-center">
                        <iconify-icon icon="gridicons:multiple-users" class="text-white text-2xl"></iconify-icon>
                    </div>
                </div>
                <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center gap-1 text-success-main">
                        <iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon>
                        +{{ number_format($newUsersLast30 ?? 0) }}
                    </span>
                    Pengguna 30 hari terakhir
                </p>
            </div>
        </div>
    </div>

    <!-- Total Transaksi -->
    <div class="col">
        <div class="card shadow-none border bg-gradient-start-4 h-100">
            <div class="card-body p-20">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <p class="fw-medium text-primary-light mb-1">Total Transaksi</p>
                        <h6 class="mb-0">Rp {{ number_format($totalTransactions ?? 0, 0, ',', '.') }}</h6>
                    </div>
                    <div class="w-50-px h-50-px bg-success-main rounded-circle d-flex justify-content-center align-items-center">
                        <iconify-icon icon="solar:wallet-bold" class="text-white text-2xl"></iconify-icon>
                    </div>
                </div>
                <p class="fw-medium text-sm text-primary-light mt-12 mb-0 d-flex align-items-center gap-2">
                    <span class="d-inline-flex align-items-center gap-1 text-success-main">
                        <iconify-icon icon="bxs:up-arrow" class="text-xs"></iconify-icon>
                        +Rp {{ number_format($transactionsLast30 ?? 0, 0, ',', '.') }}
                    </span>
                    Transaksi 30 hari terakhir
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Grafik & Aktivitas -->
<div class="row gy-4">
    <!-- Grafik Total Pengguna -->
    <div class="col-12 col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="mb-3 fw-bold text-lg">Grafik Total Pengguna</h6>
                <div id="userChart" style="height:300px;"></div>
            </div>
        </div>
    </div>

    <!-- Total Aktivitas -->
    <div class="col-12 col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="mb-3 fw-bold text-lg">Total Aktivitas</h6>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Transaksi Disetujui</span>
                        <span class="fw-bold">{{ number_format($approvedTransactions ?? 0) }}</span>
                    </div>
                    <div class="progress h-6-px">
                        <div class="progress-bar bg-success"
                             style="width: {{ ($approvedTransactions ?? 0) && ($totalUsers ?? 0) ? ($approvedTransactions / max(1, $totalUsers)) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Transaksi Pending</span>
                        <span class="fw-bold">{{ number_format($pendingTransactions ?? 0) }}</span>
                    </div>
                    <div class="progress h-6-px">
                        <div class="progress-bar bg-warning"
                             style="width: {{ ($pendingTransactions ?? 0) && ($totalUsers ?? 0) ? ($pendingTransactions / max(1, $totalUsers)) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span>Transaksi Dibatalkan</span>
                        <span class="fw-bold">{{ number_format($canceledTransactions ?? 0) }}</span>
                    </div>
                    <div class="progress h-6-px">
                        <div class="progress-bar bg-danger"
                             style="width: {{ ($canceledTransactions ?? 0) && ($totalUsers ?? 0) ? ($canceledTransactions / max(1, $totalUsers)) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between">
                        <span>Titip Kirim</span>
                        <span class="fw-bold">{{ number_format($deliveryTransactions ?? 0) }}</span>
                    </div>
                    <div class="progress h-6-px">
                        <div class="progress-bar bg-info"
                             style="width: {{ ($deliveryTransactions ?? 0) && ($totalUsers ?? 0) ? ($deliveryTransactions / max(1, $totalUsers)) * 100 : 0 }}%">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Tabel Transaksi -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="mb-3 fw-bold text-lg">Transaksi Terbaru</h6>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>ID Transaksi</th>
                                <th>Nama Traveler</th>
                                <th>Nama Penitip</th>
                                <th>Total Transaksi</th>
                                <th>Layanan</th>
                                <th>Metode Pembayaran</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestTransactions ?? [] as $i => $trx)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>#TRX{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $trx->traveler->name ?? '-' }}</td>
                                    <td>{{ $trx->buyer->name ?? '-' }}</td>
                                    <td>Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                                    <td>{{ $trx->product_id ? 'Titip beli barang' : 'Titip Kirim' }}</td>
                                    <td>{{ $trx->paymentMethod->name ?? '-' }}</td>
                                    <td>
                                        @if($trx->payment_status == 'approved')
                                            <span class="badge bg-success">Disetujui</span>
                                        @elseif($trx->payment_status == 'pending')
                                            <span class="badge bg-warning">Belum Bayar</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('superadmin.transactions.show', $trx->id) }}" class="text-primary">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Belum ada transaksi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    var userData = @json(($usersByMonth ?? collect([]))->toArray());

    var months = Object.keys(userData);
    var penitip = months.map(m => userData[m].penitip);
    var traveler = months.map(m => userData[m].traveler);

    var options = {
        chart: { type: 'bar', height: 300 },
        series: [
            { name: 'Penitip', data: penitip },
            { name: 'Traveler', data: traveler }
        ],
        xaxis: {
            categories: months.map(m => moment().month(m - 1).format('MMM'))
        },
        colors: ['#00C49F', '#0088FE'],
        plotOptions: {
            bar: { columnWidth: '40%', borderRadius: 4 }
        },
        legend: { position: 'bottom' }
    };

    new ApexCharts(document.querySelector("#userChart"), options).render();
});
</script>
@endsection
