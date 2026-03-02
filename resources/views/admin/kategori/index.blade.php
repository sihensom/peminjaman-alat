@extends('layouts.app')
@section('title', 'Kategori')
@section('page-title', 'Kategori Alat')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <form class="d-flex gap-2" method="GET">
        <input type="text" name="search" class="form-control" placeholder="Cari kategori..." value="{{ request('search') }}" style="width: 220px;">
        <button class="btn btn-primary"><i class="bi bi-search"></i></button>
    </form>
    <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Kategori</a>
</div>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead><tr><th>#</th><th>Nama Kategori</th><th>Deskripsi</th><th>Jumlah Alat</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($kategori as $i => $k)
                    <tr>
                        <td>{{ $kategori->firstItem() + $i }}</td>
                        <td>{{ $k->nama_kategori }}</td>
                        <td>{{ Str::limit($k->deskripsi, 50) ?? '-' }}</td>
                        <td><span class="badge bg-info">{{ $k->alat_count }}</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.kategori.edit', $k) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.kategori.destroy', $k) }}" method="POST" data-confirm="Yakin hapus kategori ini?">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button></form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-secondary py-4">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $kategori->withQueryString()->links() }}</div>
@endsection
