@extends('layouts.app')
@section('title', 'Monitor Peminjaman')
@section('page-title', 'Monitor Peminjaman')
@section('content')
<form class="d-flex gap-2 mb-4" method="GET">
    <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}" style="width: 220px;">
    <button class="btn btn-primary"><i class="bi bi-search"></i></button>
</form>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>Peminjam</th><th>Alat</th><th>Jml</th><th>Tgl Pinjam</th><th>Batas Kembali</th><th>Status</th></tr></thead>
        <tbody>
            @forelse($peminjaman as $p)
            <tr>
                <td>{{ $p->user->nama }}</td>
                <td>{{ $p->alat->nama_alat }}</td>
                <td>{{ $p->jumlah_pinjam }}</td>
                <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                <td>
                    {{ $p->tanggal_kembali_rencana->format('d/m/Y') }}
                    @if(\Carbon\Carbon::today()->greaterThan($p->tanggal_kembali_rencana->startOfDay()) && !in_array($p->status, ['dikembalikan', 'ditolak']))
                    <span class="badge bg-danger ms-1">Terlambat</span>
                    @endif
                </td>
                <td><span class="badge-status badge-{{ $p->status }}">{{ $p->status == 'diajukan_kembali' ? 'Diajukan Kembali' : ucfirst($p->status) }}</span></td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-secondary py-4">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div></div>
<div class="mt-3">{{ $peminjaman->withQueryString()->links() }}</div>
@endsection
