@extends('layout.layout')
@php
    $title='Super Admin';
    $subTitle = 'Manajemen Pengguna';
@endphp

@section('content')
<div class="card h-100 shadow-sm rounded-3 animate-fade-in">
    <div class="card-body">

        <!-- Tabs Role + Export -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link {{ $role == 'traveler' ? 'active' : '' }}" 
                       href="{{ route('superadmin.users.index', ['role' => 'traveler']) }}">
                        Traveler
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $role == 'customer' ? 'active' : '' }}" 
                       href="{{ route('superadmin.users.index', ['role' => 'customer']) }}">
                        Penitip
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $role == 'admin' ? 'active' : '' }}" 
                       href="{{ route('superadmin.users.index', ['role' => 'admin']) }}">
                        Admin
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $role == 'finance' ? 'active' : '' }}" 
                       href="{{ route('superadmin.users.index', ['role' => 'finance']) }}">
                        Finance
                    </a>
                </li>
            </ul>

            <a href="{{ route('superadmin.users.export', ['role' => $role]) }}" 
                class="btn btn-warning btn-sm d-inline-flex align-items-center gap-2 shadow-sm rounded-2 px-3 py-2">
                <iconify-icon icon="mdi:download-circle-outline" width="18" height="18"></iconify-icon>
                <span class="fw-semibold">Unduh Data</span>
            </a>
        </div>

        <!-- Table -->
        <div class="table-responsive">
            <table class="table table-hover align-middle text-sm">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td>USR{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                <img src="{{ $user->profile_photo_url ?? 'https://i.pravatar.cc/40?u='.$user->id }}" 
                                     class="rounded-circle shadow-sm" width="40" height="40">
                            </td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->is_online ?? false)
                                    <span class="badge bg-success">Online</span>
                                @else
                                    <span class="badge bg-secondary">Offline</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('superadmin.users.show', $user) }}" class="btn btn-sm btn-info shadow-sm" title="View">
                                    <iconify-icon icon="mdi:eye"></iconify-icon>
                                </a>
                                <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-sm btn-primary shadow-sm" title="Edit">
                                    <iconify-icon icon="mdi:pencil"></iconify-icon>
                                </a>
                                <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger shadow-sm btn-delete" title="Hapus">
                                        <iconify-icon icon="mdi:delete"></iconify-icon>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada pengguna</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="d-flex justify-content-between align-items-center mt-2">
            <span>Total {{ $users->total() }}</span>
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- SweetAlert2 Script --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".btn-delete").forEach(button => {
        button.addEventListener("click", function () {
            let form = this.closest("form");

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data pengguna ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endsection
