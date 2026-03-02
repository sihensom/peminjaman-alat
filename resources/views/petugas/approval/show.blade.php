@extends('layouts.app')
@section('title', 'Review Peminjaman')
@section('page-title', 'Review Peminjaman #' . $peminjaman->id)
@section('content')
<div class="row g-4">
    <div class="col-md-7">
        <div class="card"><div class="card-body p-4">
            <h5 class="mb-3">Detail Peminjaman</h5>
            <table class="table table-borderless">
                <tr><td class="text-secondary" style="width:150px">Peminjam</td><td>{{ $peminjaman->user->nama }}</td></tr>
                <tr><td class="text-secondary">Email</td><td>{{ $peminjaman->user->email }}</td></tr>
                <tr><td class="text-secondary">Alat</td><td>{{ $peminjaman->alat->nama_alat }} <code>({{ $peminjaman->alat->kode_alat }})</code></td></tr>
                <tr><td class="text-secondary">Kategori</td><td>{{ $peminjaman->alat->kategori->nama_kategori }}</td></tr>
                <tr><td class="text-secondary">Jumlah</td><td>{{ $peminjaman->jumlah_pinjam }} unit</td></tr>
                <tr><td class="text-secondary">Stok Tersedia</td><td><span class="badge {{ $peminjaman->alat->jumlah_tersedia >= $peminjaman->jumlah_pinjam ? 'bg-success' : 'bg-danger' }}">{{ $peminjaman->alat->jumlah_tersedia }}</span></td></tr>
                <tr><td class="text-secondary">Tgl Pinjam</td><td>{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</td></tr>
                <tr><td class="text-secondary">Rencana Kembali</td><td>{{ $peminjaman->tanggal_kembali_rencana->format('d F Y') }}</td></tr>
                <tr><td class="text-secondary">Keterangan</td><td>{{ $peminjaman->keterangan ?? '-' }}</td></tr>
            </table>
        </div></div>
    </div>
    <div class="col-md-5">
        @if($peminjaman->status === 'pending')
        <div class="card mb-3"><div class="card-body p-4">
            <h6 class="text-success mb-3"><i class="bi bi-check-circle-fill me-1"></i>Setujui</h6>
            <form method="POST" action="{{ route('petugas.approval.approve', $peminjaman) }}" data-confirm="Yakin setujui peminjaman ini?">@csrf
                <textarea name="keterangan" class="form-control mb-3" placeholder="Keterangan (opsional)" rows="2"></textarea>
                <button class="btn btn-success w-100"><i class="bi bi-check-lg me-1"></i>Setujui Peminjaman</button>
            </form>
        </div></div>
        <div class="card"><div class="card-body p-4">
            <h6 class="text-danger mb-3"><i class="bi bi-x-circle-fill me-1"></i>Tolak</h6>
            <form method="POST" action="{{ route('petugas.approval.reject', $peminjaman) }}" data-confirm="Yakin tolak peminjaman ini?">@csrf
                <textarea name="keterangan" class="form-control mb-3" placeholder="Alasan penolakan (wajib)" rows="2" required></textarea>
                <button class="btn btn-danger w-100"><i class="bi bi-x-lg me-1"></i>Tolak Peminjaman</button>
            </form>
        </div></div>
        @else
        <div class="card"><div class="card-body p-4 text-center">
            <span class="badge-status badge-{{ $peminjaman->status }}" style="font-size: 1rem; padding: 0.5rem 1rem;">{{ ucfirst($peminjaman->status) }}</span>
            <p class="text-secondary mt-2 mb-0">Peminjaman sudah diproses</p>
        </div></div>
        @endif
    </div>
</div>
<div class="mt-3"><a href="{{ route('petugas.approval.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
@endsection
