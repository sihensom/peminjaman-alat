@extends('layouts.app')
@section('title', 'Laporan')
@section('page-title', 'Laporan Peminjaman')
@section('content')
<form class="d-flex gap-2 mb-4 flex-wrap align-items-end" method="GET">
    <div><label class="form-label">Dari</label><input type="date" name="dari" class="form-control" value="{{ request('dari', now()->startOfMonth()->format('Y-m-d')) }}"></div>
    <div><label class="form-label">Sampai</label><input type="date" name="sampai" class="form-control" value="{{ request('sampai', now()->format('Y-m-d')) }}"></div>
    <button class="btn btn-primary"><i class="bi bi-filter me-1"></i>Filter</button>
    <a href="{{ route('petugas.laporan.print', array_merge(request()->query(), ['mode' => 'print'])) }}" class="btn btn-outline-secondary" target="_blank"><i class="bi bi-printer me-1"></i>Cetak Langsung</a>
    <a href="{{ route('petugas.laporan.print', array_merge(request()->query(), ['mode' => 'pdf'])) }}" class="btn btn-outline-danger" target="_blank"><i class="bi bi-file-earmark-pdf me-1"></i>Unduh PDF</a>
</form>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stat-card"><div class="stat-icon blue"><i class="bi bi-clipboard2-check-fill"></i></div><div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total Peminjaman</div></div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div><div><div class="stat-value">{{ $stats['disetujui'] }}</div><div class="stat-label">Disetujui</div></div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="stat-icon red"><i class="bi bi-x-circle-fill"></i></div><div><div class="stat-value">{{ $stats['ditolak'] }}</div><div class="stat-label">Ditolak</div></div></div></div>
    <div class="col-md-3"><div class="stat-card"><div class="stat-icon green"><i class="bi bi-arrow-return-left"></i></div><div><div class="stat-value">{{ $stats['dikembalikan'] }}</div><div class="stat-label">Dikembalikan</div></div></div></div>
</div>

<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>Peminjam</th><th>Alat</th><th>Jml</th><th>Tgl Pinjam</th><th>Status</th></tr></thead>
        <tbody>
            @forelse($peminjaman as $p)
            <tr>
                <td>{{ $p->user->nama }}</td>
                <td>{{ $p->alat->nama_alat }}</td>
                <td>{{ $p->jumlah_pinjam }}</td>
                <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                <td><span class="badge-status badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-secondary py-4">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div></div>
<div class="mt-3">{{ $peminjaman->withQueryString()->links() }}</div>
@endsection
