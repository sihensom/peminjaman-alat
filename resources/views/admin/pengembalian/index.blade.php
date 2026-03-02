@extends('layouts.app')
@section('title', 'Pengembalian')
@section('page-title', 'Data Pengembalian')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <form class="d-flex gap-2" method="GET">
        <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}" style="width: 220px;">
        <button class="btn btn-primary"><i class="bi bi-search"></i></button>
    </form>
    <a href="{{ route('admin.pengembalian.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Catat Pengembalian</a>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>#</th><th>Peminjam</th><th>Alat</th><th>Tgl Kembali</th><th>Kondisi</th><th>Solusi</th><th>Denda</th><th>Diterima Oleh</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($pengembalian as $i => $pg)
            <tr>
                <td>{{ $pengembalian->firstItem() + $i }}</td>
                <td>{{ $pg->peminjaman->user->nama }}</td>
                <td>{{ $pg->peminjaman->alat->nama_alat }}</td>
                <td>{{ $pg->tanggal_kembali_aktual->format('d/m/Y') }}</td>
                <td><span class="badge bg-{{ $pg->kondisi_alat == 'baik' ? 'success' : ($pg->kondisi_alat == 'rusak' ? 'danger' : 'warning') }}">{{ ucfirst($pg->kondisi_alat) }}</span></td>
                <td>{{ $pg->solusi ?? '-' }}</td>
                <td>
                    @if($pg->denda > 0)
                    <span class="text-danger fw-semibold">Rp {{ number_format($pg->denda, 0, ',', '.') }}</span>
                    @else
                    <span class="text-secondary">-</span>
                    @endif
                </td>
                <td>{{ $pg->receiver->nama ?? '-' }}</td>
                <td>
                    <form action="{{ route('admin.pengembalian.destroy', $pg) }}" method="POST" data-confirm="Yakin hapus data pengembalian ini?">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" class="text-center text-secondary py-4">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div></div>
<div class="mt-3">{{ $pengembalian->withQueryString()->links() }}</div>
@endsection
