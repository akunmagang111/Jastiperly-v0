{{-- resources/views/refunds/show.blade.php --}}
@extends('layout.layout')

@php
    $title = 'Super Admin';
    $subTitle = 'Detail Refund';
@endphp

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h5 class="fw-bold">Detail Refund</h5>
        <p>ID Refund: {{ $refund->id }}</p>
        <p>Status: {{ $refund->status }}</p>
        <p>Alasan: {{ $refund->reason ?? '-' }}</p>
    </div>
</div>
@endsection
