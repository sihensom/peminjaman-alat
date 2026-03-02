@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-people-fill"></i></div>
            <div>
                <div class="stat-value">{{ $stats['total_users'] }}</div>
                <div class="stat-label">Total User</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-box-seam-fill"></i></div>
            <div>
                <div class="stat-value">{{ $stats['total_alat'] }}</div>
                <div class="stat-label">Total Alat</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon purple"><i class="bi bi-clipboard2-check-fill"></i></div>
            <div>
                <div class="stat-value">{{ $stats['total_peminjaman'] }}</div>
                <div class="stat-label">Total Peminjaman</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-hourglass-split"></i></div>
            <div>
                <div class="stat-value">{{ $stats['peminjaman_pending'] }}</div>
                <div class="stat-label">Menunggu Persetujuan</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-arrow-left-right"></i></div>
            <div>
                <div class="stat-value">{{ $stats['peminjaman_aktif'] }}</div>
                <div class="stat-label">Sedang Dipinjam</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-arrow-return-left"></i></div>
            <div>
                <div class="stat-value">{{ $stats['pengembalian'] }}</div>
                <div class="stat-label">Pengembalian</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Peminjaman Terbaru</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Tanggal Pinjam</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPeminjaman as $p)
                    <tr>
                        <td>{{ $p->user->nama }}</td>
                        <td>{{ $p->alat->nama_alat }}</td>
                        <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td><span class="badge-status badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-secondary py-4">Belum ada data peminjaman</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
