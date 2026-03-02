@extends('layouts.app')
@section('title', 'Proses Pengembalian')
@section('page-title', 'Proses Pengembalian')
@section('content')
<div class="row justify-content-center"><div class="col-md-8">
    <div class="card mb-3"><div class="card-body p-4">
        <h6 class="mb-3">Informasi Peminjaman</h6>
        <table class="table table-borderless">
            <tr><td class="text-secondary" style="width:150px">Peminjam</td><td>{{ $peminjaman->user->nama }}</td></tr>
            <tr><td class="text-secondary">Alat</td><td>{{ $peminjaman->alat->nama_alat }}</td></tr>
            <tr><td class="text-secondary">Jumlah</td><td>{{ $peminjaman->jumlah_pinjam }} unit</td></tr>
            <tr><td class="text-secondary">Tgl Pinjam</td><td>{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</td></tr>
            <tr><td class="text-secondary">Batas Kembali</td><td>{{ $peminjaman->tanggal_kembali_rencana->format('d F Y') }}</td></tr>
        </table>
    </div></div>
    <div class="card"><div class="card-body p-4">
        <h6 class="mb-3">Form Pengembalian</h6>
        <form method="POST" action="{{ route('petugas.monitor.processReturn', $peminjaman) }}">@csrf
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Tgl Kembali Aktual</label><input type="date" name="tanggal_kembali_aktual" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                <div class="col-md-6"><label class="form-label">Kondisi Alat</label>
                    <select name="kondisi_alat" class="form-select" required><option value="baik">Baik</option><option value="rusak">Rusak</option><option value="hilang">Hilang</option></select>
                </div>
                <div class="col-12"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control" rows="2"></textarea></div>
            </div>
            <div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Proses Pengembalian</button><a href="{{ route('petugas.monitor.index') }}" class="btn btn-outline-secondary">Batal</a></div>
        </form>
    </div></div>
</div></div>
@endsection
