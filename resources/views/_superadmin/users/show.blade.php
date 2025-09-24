@extends('layout.layout')

@php
    $title = 'Super Admin';
    $subTitle = 'Detail Pengguna';
@endphp

@section('content')
<div class="card border-0 shadow-lg rounded-3 animate-fade-in">
    <div class="card-body p-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold text-dark m-0 d-flex align-items-center gap-2">
                <i class="bi bi-person-badge text-primary fs-4"></i>
                Detail Pengguna
            </h5>
            <a href="{{ route('superadmin.users.index', ['role' => $user->role]) }}" 
               class="btn btn-sm btn-outline-primary rounded-pill px-3 d-flex align-items-center gap-2 hover-scale">
                <i class="bi bi-arrow-left"></i>
                <span>Kembali</span>
            </a>
        </div>

        <!-- Detail Pengguna -->
        <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0">
                <tbody>
                    <tr class="detail-row">
                        <th class="text-muted fw-semibold" style="width:180px;">ID</th>
                        <td class="fw-bold text-dark">USR{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                    <tr class="detail-row">
                        <th class="text-muted fw-semibold">Nama</th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr class="detail-row">
                        <th class="text-muted fw-semibold">Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr class="detail-row">
                        <th class="text-muted fw-semibold">Role</th>
                        <td>
                            <span class="badge rounded-pill 
                                {{ $user->role === 'admin' ? 'bg-primary' : 'bg-info text-dark' }} 
                                px-3 py-2 shadow-sm">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                    </tr>
                    <tr class="detail-row">
                        <th class="text-muted fw-semibold">Status</th>
                        <td>
                            @if($user->is_online ?? false)
                                <span class="badge rounded-pill bg-success px-3 py-2 shadow-sm">Online</span>
                            @else
                                <span class="badge rounded-pill bg-secondary px-3 py-2 shadow-sm">Offline</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- Styling --}}
<style>
    /* Fade-in card */
    .animate-fade-in {
        animation: fadeInUp 0.5s ease;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Hover row detail */
    .detail-row td, .detail-row th {
        padding: 12px 0;
        border-bottom: 1px solid #f1f1f1;
    }
    .detail-row:last-child td, 
    .detail-row:last-child th {
        border-bottom: none;
    }

    /* Hover efek baris */
    .detail-row:hover {
        background-color: #f9fbff;
        transition: background-color 0.25s ease-in-out;
    }

    /* Tombol kembali */
    .hover-scale {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-scale:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
</style>
@endsection
