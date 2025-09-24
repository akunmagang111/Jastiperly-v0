@extends('layout.layout')
@php
    $title = 'Super Admin';
    $subTitle = 'Products';
@endphp

@section('content')
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div class="d-flex gap-2 flex-wrap">
                @foreach(['traveler'=>'Traveler','customer'=>'Customer'] as $r=>$label)
                    @php
                        $count = \App\Models\Product::with('submiter')
                                ->whereHas('submiter', fn($q) => $q->where('role', $r))
                                ->count();
                    @endphp
                    <a href="{{ route('superadmin.products.index', ['role'=>$r]) }}"
                       class="btn btn-sm {{ request('role')==$r ? 'btn-primary text-white' : 'btn-outline-primary' }} rounded-2 shadow-sm">
                        {{ $label }}
                        <span class="badge bg-light text-dark ms-1">{{ $count }}</span>
                    </a>
                @endforeach
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <form method="GET" class="d-flex">
                    <select name="approval" class="form-select form-select-sm rounded-2 shadow-sm" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        @foreach(['pending'=>'Validasi','approved'=>'Disetujui','declined'=>'Ditolak'] as $s=>$label)
                            <option value="{{ $s }}" {{ request('approval')==$s ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </form>

                <a href="{{ route('superadmin.products.export') }}" 
                   class="btn btn-warning btn-sm d-flex align-items-center gap-2 shadow-sm rounded-2 px-3"
                   style="min-width: 120px;">
                    <iconify-icon icon="mdi:download" width="18" height="18"></iconify-icon>
                    <span>Unduh</span>
                </a>

                <a href="{{ route('superadmin.products.create') }}" 
                   class="btn btn-success btn-sm d-flex align-items-center gap-2 shadow-sm rounded-2 px-3"
                   style="min-width: 120px;">
                    <iconify-icon icon="mdi:plus" width="18" height="18"></iconify-icon>
                    <span>Tambah</span>
                </a>
            </div>
        </div>

        <!-- Produk List -->
        <div class="table-responsive">
            <table class="table align-middle table-hover shadow-sm rounded">
                <thead class="table-light">
                    <tr>
                        <th style="width:140px;">Foto</th>
                        <th>Detail Produk</th>
                        <th class="text-end" style="width:240px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr class="table-row">
                            <!-- Foto -->
                            <td>
                                <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://via.placeholder.com/120x120' }}"
                                     class="rounded-2 shadow-sm product-img"
                                     alt="{{ $product->name }}">
                            </td>

                            <!-- Detail Produk -->
                            <td>
                                <div><strong>Nama Barang:</strong> {{ $product->name }}</div>
                                <div><strong>Deskripsi:</strong> {{ $product->description }}</div>
                                <div><strong>Harga:</strong> Rp {{ number_format($product->price,0,',','.') }}</div>
                                <div><strong>Pemilik:</strong> {{ $product->submiter->name ?? '-' }}</div>
                                <div><strong>Status:</strong>
                                    @if($product->approval == 'pending')
                                        <span class="badge bg-warning text-dark">Validasi</span>
                                    @elseif($product->approval == 'approved')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($product->approval == 'declined')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $product->approval }}</span>
                                    @endif
                                </div>
                            </td>

                            <!-- Aksi -->
                            <td class="text-end">
                                <div class="d-flex flex-column align-items-end gap-2">
                                    <a href="{{ route('superadmin.products.show', $product->id) }}" 
                                    class="btn btn-sm btn-primary shadow-sm rounded-2 action-btn">Validasi</a>
                                    <a href="{{ route('superadmin.products.edit', $product->id) }}" 
                                    class="btn btn-sm btn-warning shadow-sm rounded-2 action-btn">Edit</a>

                                    <!-- Tombol trigger modal -->
                                    <button type="button" 
                                            class="btn btn-sm btn-danger shadow-sm rounded-2 action-btn" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal{{ $product->id }}">
                                        Hapus
                                    </button>
                                </div>
                            </td>

                            <!-- Modal Konfirmasi -->
                            <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content shadow-lg rounded-3 border-0">
                                        <div class="modal-header bg-danger text-white">
                                            <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p class="mb-0">Apakah Anda yakin ingin menghapus produk 
                                                <strong>{{ $product->name }}</strong>?
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary rounded-2" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('superadmin.products.destroy', $product->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger rounded-2">Ya, Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Belum ada produk</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- CSS tambahan --}}
<style>
    /* Foto produk lebih rapi */
    .product-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .product-img:hover {
        transform: scale(1.04);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    /* Efek hover baris tabel */
    .table-row {
        transition: background-color 0.2s ease;
    }
    .table-row:hover {
        background-color: #f8f9fa;
    }

    /* Tombol aksi seragam */
    .action-btn {
        min-width: 100px;
        text-align: center;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
</style>
@endsection
