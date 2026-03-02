@extends('layouts.app')
@section('title', 'Data Peminjaman')
@section('page-title', 'Data Peminjaman')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <form class="d-flex gap-2 flex-wrap" method="GET">
        <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}" style="width: 200px;">
        <select name="status" class="form-select" style="width: 160px;">
            <option value="">Semua Status</option>
            @foreach(['pending','disetujui','ditolak','dipinjam','dikembalikan'] as $s)
            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button class="btn btn-primary"><i class="bi bi-search"></i></button>
    </form>
    <a href="{{ route('admin.peminjaman.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah</a>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>#</th><th>Peminjam</th><th>Alat</th><th>Jml</th><th>Tgl Pinjam</th><th>Tgl Kembali</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($peminjaman as $i => $p)
                    <tr>
                        <td>{{ $peminjaman->firstItem() + $i }}</td>
                        <td>{{ $p->user->nama }}</td>
                        <td>{{ $p->alat->nama_alat }}</td>
                        <td>{{ $p->jumlah_pinjam }}</td>
                        <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td>{{ $p->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                        <td><span class="badge-status badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.peminjaman.show', $p) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                <form action="{{ route('admin.peminjaman.destroy', $p) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-secondary py-4">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $peminjaman->withQueryString()->links() }}</div>
@endsection
