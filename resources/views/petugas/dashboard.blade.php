@extends('layouts.app')
@section('title', 'Dashboard Petugas')
@section('page-title', 'Dashboard Petugas')
@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-hourglass-split"></i></div>
            <div><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-label">Menunggu Persetujuan</div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-arrow-left-right"></i></div>
            <div><div class="stat-value">{{ $stats['dipinjam'] }}</div><div class="stat-label">Sedang Dipinjam</div></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
            <div><div class="stat-value">{{ $stats['dikembalikan'] }}</div><div class="stat-label">Dikembalikan</div></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-bell-fill me-2"></i>Peminjaman Menunggu Persetujuan</h6>
        <a href="{{ route('petugas.approval.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
    </div>
    <div class="card-body p-0"><div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Peminjam</th><th>Alat</th><th>Jumlah</th><th>Tgl Pinjam</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($pendingList as $p)
                <tr>
                    <td>{{ $p->user->nama }}</td>
                    <td>{{ $p->alat->nama_alat }}</td>
                    <td>{{ $p->jumlah_pinjam }}</td>
                    <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                    <td><a href="{{ route('petugas.approval.show', $p) }}" class="btn btn-sm btn-primary">Review</a></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-secondary py-4">Tidak ada peminjaman pending</td></tr>
                @endforelse
            </tbody>
        </table>
    </div></div>
</div>
@endsection
