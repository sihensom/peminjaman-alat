@extends('layouts.app')
@section('title', 'Detail Alat')
@section('page-title', 'Detail Alat')

@section('content')
<div class="row g-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between mb-3">
                    <h5>{{ $alat->nama_alat }}</h5>
                    <span class="badge bg-{{ $alat->kondisi == 'baik' ? 'success' : ($alat->kondisi == 'rusak' ? 'danger' : 'warning') }}">{{ ucfirst($alat->kondisi) }}</span>
                </div>
                <table class="table table-borderless">
                    <tr><td class="text-secondary" style="width:150px">Kode Alat</td><td><code>{{ $alat->kode_alat }}</code></td></tr>
                    <tr><td class="text-secondary">Kategori</td><td>{{ $alat->kategori->nama_kategori }}</td></tr>
                    <tr><td class="text-secondary">Stok Total</td><td>{{ $alat->jumlah_total }}</td></tr>
                    <tr><td class="text-secondary">Stok Tersedia</td><td><span class="badge {{ $alat->jumlah_tersedia > 0 ? 'bg-success' : 'bg-danger' }}">{{ $alat->jumlah_tersedia }}</span></td></tr>
                    <tr><td class="text-secondary">Deskripsi</td><td>{{ $alat->deskripsi ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Riwayat Peminjaman</h6></div>
            <div class="card-body p-0">
                @forelse($alat->peminjaman->take(10) as $p)
                <div class="d-flex justify-content-between align-items-center p-3 border-bottom" style="border-color: var(--border) !important;">
                    <div>
                        <small class="d-block">{{ $p->user->nama }}</small>
                        <small class="text-secondary">{{ $p->tanggal_pinjam->format('d/m/Y') }}</small>
                    </div>
                    <span class="badge-status badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span>
                </div>
                @empty
                <div class="text-center text-secondary py-3">Belum ada riwayat</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
<div class="mt-3"><a href="{{ route('admin.alat.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
@endsection
