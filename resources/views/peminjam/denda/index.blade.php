@extends('layouts.app')
@section('title', 'Denda Saya')
@section('page-title', 'Denda Saya')
@section('content')

@php
    $belumBayar = $dendaList->where('denda_status', 'belum_bayar');
    $lunas = $dendaList->where('denda_status', 'lunas');
    $totalBelumBayar = $belumBayar->sum('denda');
@endphp

{{-- Summary Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div><div class="stat-value">{{ $belumBayar->count() }}</div><div class="stat-label">Belum Bayar</div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
            <div><div class="stat-value">{{ $lunas->count() }}</div><div class="stat-label">Lunas</div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-cash-stack"></i></div>
            <div><div class="stat-value">Rp {{ number_format($totalBelumBayar, 0, ',', '.') }}</div><div class="stat-label">Total Harus Dibayar</div></div>
        </div>
    </div>
</div>

{{-- Denda belum bayar --}}
@if($belumBayar->count() > 0)
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-exclamation-triangle text-danger me-2"></i>Denda Belum Dibayar</h6>
    </div>
    <div class="card-body p-0"><div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Alat</th><th>Kondisi</th><th>Solusi</th><th>Tgl Kembali</th><th>Denda</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($belumBayar as $d)
                <tr>
                    <td>{{ $d->peminjaman->alat->nama_alat ?? '-' }}</td>
                    <td><span class="badge bg-{{ $d->kondisi_alat == 'baik' ? 'success' : ($d->kondisi_alat == 'rusak' ? 'danger' : 'warning') }}">{{ ucfirst($d->kondisi_alat) }}</span></td>
                    <td>{{ $d->solusi ?? '-' }}</td>
                    <td>{{ $d->tanggal_kembali_aktual->format('d/m/Y') }}</td>
                    <td><span class="text-danger fw-bold">Rp {{ number_format($d->denda, 0, ',', '.') }}</span></td>
                    <td>
                        <a href="{{ route('peminjam.denda.pay', $d) }}" class="btn btn-sm btn-warning">
                            <i class="bi bi-wallet2 me-1"></i>Bayar
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div></div>
</div>
@endif

{{-- Riwayat denda lunas --}}
<div class="card">
    <div class="card-header">
        <h6 class="mb-0"><i class="bi bi-check-circle text-success me-2"></i>Riwayat Pembayaran</h6>
    </div>
    <div class="card-body p-0"><div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Alat</th><th>Kondisi</th><th>Denda</th><th>Metode</th><th>Tgl Bayar</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($lunas as $d)
                <tr>
                    <td>{{ $d->peminjaman->alat->nama_alat ?? '-' }}</td>
                    <td><span class="badge bg-{{ $d->kondisi_alat == 'baik' ? 'success' : ($d->kondisi_alat == 'rusak' ? 'danger' : 'warning') }}">{{ ucfirst($d->kondisi_alat) }}</span></td>
                    <td>Rp {{ number_format($d->denda, 0, ',', '.') }}</td>
                    <td>
                        @if($d->metode_bayar === 'qris')
                        <span class="badge bg-info"><i class="bi bi-qr-code me-1"></i>QRIS</span>
                        @else
                        <span class="badge bg-secondary"><i class="bi bi-cash me-1"></i>Cash</span>
                        @endif
                    </td>
                    <td>{{ $d->tanggal_bayar ? $d->tanggal_bayar->format('d/m/Y H:i') : '-' }}</td>
                    <td><span class="badge bg-success"><i class="bi bi-check-lg me-1"></i>Lunas</span></td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-secondary py-4">Belum ada riwayat pembayaran</td></tr>
                @endforelse
            </tbody>
        </table>
    </div></div>
</div>
@endsection
