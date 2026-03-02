@extends('layouts.app')
@section('title', 'Detail Alat')
@section('page-title', 'Detail Alat')
@section('content')
<div class="row g-4">
    <div class="col-md-8">
        <div class="card"><div class="card-body p-4">
            <div class="d-flex justify-content-between mb-3">
                <h5>{{ $alat->nama_alat }}</h5>
                <span class="badge bg-{{ $alat->kondisi == 'baik' ? 'success' : 'danger' }}">{{ ucfirst($alat->kondisi) }}</span>
            </div>
            <table class="table table-borderless">
                <tr><td class="text-secondary" style="width:150px">Kode</td><td><code>{{ $alat->kode_alat }}</code></td></tr>
                <tr><td class="text-secondary">Kategori</td><td>{{ $alat->kategori->nama_kategori }}</td></tr>
                <tr><td class="text-secondary">Stok Tersedia</td><td><span class="badge {{ $alat->jumlah_tersedia > 0 ? 'bg-success' : 'bg-danger' }}">{{ $alat->jumlah_tersedia }} / {{ $alat->jumlah_total }}</span></td></tr>
                <tr><td class="text-secondary">Deskripsi</td><td>{{ $alat->deskripsi ?? '-' }}</td></tr>
            </table>
            @if($alat->jumlah_tersedia > 0 && $alat->kondisi == 'baik')
            <a href="{{ route('peminjam.pinjam.create', ['alat_id' => $alat->id]) }}" class="btn btn-primary"><i class="bi bi-cart-plus me-1"></i>Pinjam Alat Ini</a>
            @endif
        </div></div>
    </div>
</div>
<div class="mt-3"><a href="{{ route('peminjam.alat.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
@endsection
