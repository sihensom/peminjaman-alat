@extends('layouts.app')
@section('title', 'Daftar Alat')
@section('page-title', 'Daftar Alat Tersedia')
@section('content')
<form class="d-flex gap-2 mb-4 flex-wrap" method="GET">
    <input type="text" name="search" class="form-control" placeholder="Cari alat..." value="{{ request('search') }}" style="width: 220px;">
    <select name="kategori_id" class="form-select" style="width: 180px;">
        <option value="">Semua Kategori</option>
        @foreach($kategoriList as $k)
        <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
        @endforeach
    </select>
    <button class="btn btn-primary"><i class="bi bi-search"></i></button>
</form>

<div class="row g-3">
    @forelse($alat as $a)
    <div class="col-md-4 col-sm-6">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h6 class="mb-0">{{ $a->nama_alat }}</h6>
                    <span class="badge bg-{{ $a->kondisi == 'baik' ? 'success' : 'danger' }}">{{ ucfirst($a->kondisi) }}</span>
                </div>
                <small class="text-secondary d-block mb-2"><code>{{ $a->kode_alat }}</code> • {{ $a->kategori->nama_kategori }}</small>
                <p class="text-secondary mb-3" style="font-size: 0.85rem;">{{ Str::limit($a->deskripsi, 80) ?? 'Tidak ada deskripsi' }}</p>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="badge {{ $a->jumlah_tersedia > 0 ? 'bg-success' : 'bg-danger' }}">
                        {{ $a->jumlah_tersedia > 0 ? "Tersedia: {$a->jumlah_tersedia}" : 'Habis' }}
                    </span>
                    @if($a->jumlah_tersedia > 0 && $a->kondisi == 'baik')
                    <a href="{{ route('peminjam.pinjam.create', ['alat_id' => $a->id]) }}" class="btn btn-sm btn-primary"><i class="bi bi-cart-plus me-1"></i>Pinjam</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="empty-state">
            <i class="bi bi-box-seam"></i>
            <h6>Tidak ada alat ditemukan</h6>
            <p>Coba ubah filter pencarian Anda</p>
        </div>
    </div>
    @endforelse
</div>
<div class="mt-3">{{ $alat->withQueryString()->links() }}</div>
@endsection
