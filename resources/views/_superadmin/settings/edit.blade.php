{{-- resources/views/refunds/edit.blade.php --}}
@extends('layout.layout')

@php
    $title = 'Super Admin';
    $subTitle = 'Edit Refund';
@endphp

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h5 class="fw-bold">Edit Refund</h5>

        <form action="{{ route('superadmin.refunds.update', $refund->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label>Status</label>
                <select name="status" class="form-select">
                    <option value="Proses" {{ $refund->status == 'Proses' ? 'selected' : '' }}>Proses</option>
                    <option value="Selesai" {{ $refund->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Alasan Refund</label>
                <textarea name="reason" class="form-control">{{ $refund->reason }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@endsection
