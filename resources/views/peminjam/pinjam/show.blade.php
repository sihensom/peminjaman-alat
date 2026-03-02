@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('page-title', 'Detail Peminjaman #' . $peminjaman->id)
@section('content')
<div class="row g-4">
    <div class="col-md-8">
        <div class="card"><div class="card-body p-4">
            <div class="d-flex justify-content-between mb-3">
                <h5>Detail Peminjaman</h5>
                <span class="badge-status badge-{{ $peminjaman->status }}" style="font-size: 0.9rem; padding: 0.4rem 0.8rem;">{{ ucfirst($peminjaman->status) }}</span>
            </div>
            <table class="table table-borderless">
                <tr><td class="text-secondary" style="width:160px">Alat</td><td>{{ $peminjaman->alat->nama_alat }} <code>({{ $peminjaman->alat->kode_alat }})</code></td></tr>
                <tr><td class="text-secondary">Kategori</td><td>{{ $peminjaman->alat->kategori->nama_kategori }}</td></tr>
                <tr><td class="text-secondary">Jumlah</td><td>{{ $peminjaman->jumlah_pinjam }} unit</td></tr>
                <tr><td class="text-secondary">Tgl Pinjam</td><td>{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</td></tr>
                <tr><td class="text-secondary">Rencana Kembali</td><td>{{ $peminjaman->tanggal_kembali_rencana->format('d F Y') }}</td></tr>
                <tr><td class="text-secondary">Disetujui Oleh</td><td>{{ $peminjaman->approver->nama ?? '-' }}</td></tr>
                <tr><td class="text-secondary">Keterangan</td><td>{{ $peminjaman->keterangan ?? '-' }}</td></tr>
            </table>
            @if($peminjaman->pengembalian)
            <hr style="border-color: var(--border);">
            <h6 class="mb-3">Data Pengembalian</h6>
            <table class="table table-borderless">
                <tr><td class="text-secondary" style="width:160px">Tgl Kembali</td><td>{{ $peminjaman->pengembalian->tanggal_kembali_aktual->format('d F Y') }}</td></tr>
                <tr><td class="text-secondary">Kondisi</td><td><span class="badge bg-{{ $peminjaman->pengembalian->kondisi_alat == 'baik' ? 'success' : ($peminjaman->pengembalian->kondisi_alat == 'rusak' ? 'danger' : 'warning') }}">{{ ucfirst($peminjaman->pengembalian->kondisi_alat) }}</span></td></tr>
            </table>
            @endif

            @if(in_array($peminjaman->status, ['disetujui','dipinjam']))
            <a href="{{ route('peminjam.pinjam.return', $peminjaman) }}" class="btn btn-success mt-3"><i class="bi bi-arrow-return-left me-1"></i>Kembalikan Alat</a>
            @endif
        </div></div>
    </div>
</div>
<div class="mt-3"><a href="{{ route('peminjam.pinjam.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
@endsection
