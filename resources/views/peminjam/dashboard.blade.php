@extends('layouts.app')
@section('title', 'Dashboard Peminjam')
@section('page-title', 'Dashboard Peminjam')
@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-clipboard2-check-fill"></i></div>
            <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total Peminjaman</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-hourglass-split"></i></div>
            <div><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-label">Menunggu</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-arrow-left-right"></i></div>
            <div><div class="stat-value">{{ $stats['dipinjam'] }}</div><div class="stat-label">Sedang Dipinjam</div></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
            <div><div class="stat-value">{{ $stats['dikembalikan'] }}</div><div class="stat-label">Dikembalikan</div></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Peminjaman Terakhir</h6>
        <a href="{{ route('peminjam.pinjam.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
    </div>
    <div class="card-body p-0"><div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Alat</th><th>Jumlah</th><th>Tgl Pinjam</th><th>Status</th></tr></thead>
            <tbody>
                @forelse($recentPeminjaman as $p)
                <tr>
                    <td>{{ $p->alat->nama_alat }}</td>
                    <td>{{ $p->jumlah_pinjam }}</td>
                    <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td><span class="badge-status badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-secondary py-4">Belum ada peminjaman</td></tr>
                @endforelse
            </tbody>
        </table>
    </div></div>
</div>
@endsection
