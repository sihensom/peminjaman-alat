@extends('layouts.app')
@section('title', 'Kelola Alat')
@section('page-title', 'Kelola Alat')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <form class="d-flex gap-2 flex-wrap" method="GET">
        <input type="text" name="search" class="form-control" placeholder="Cari alat..." value="{{ request('search') }}" style="width: 200px;">
        <select name="kategori_id" class="form-select" style="width: 160px;">
            <option value="">Semua Kategori</option>
            @foreach($kategoriList as $k)
            <option value="{{ $k->id }}" {{ request('kategori_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
            @endforeach
        </select>
        <select name="kondisi" class="form-select" style="width: 140px;">
            <option value="">Semua Kondisi</option>
            <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
            <option value="rusak" {{ request('kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
            <option value="maintenance" {{ request('kondisi') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
        </select>
        <button class="btn btn-primary"><i class="bi bi-search"></i></button>
    </form>
    <a href="{{ route('admin.alat.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Tambah Alat</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Alat</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Kondisi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alat as $i => $a)
                    <tr>
                        <td>{{ $alat->firstItem() + $i }}</td>
                        <td><code>{{ $a->kode_alat }}</code></td>
                        <td>{{ $a->nama_alat }}</td>
                        <td>{{ $a->kategori->nama_kategori }}</td>
                        <td><span class="badge {{ $a->jumlah_tersedia > 0 ? 'bg-success' : 'bg-danger' }}">{{ $a->jumlah_tersedia }}/{{ $a->jumlah_total }}</span></td>
                        <td><span class="badge bg-{{ $a->kondisi == 'baik' ? 'success' : ($a->kondisi == 'rusak' ? 'danger' : 'warning') }}">{{ ucfirst($a->kondisi) }}</span></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.alat.show', $a) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.alat.edit', $a) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                                <form action="{{ route('admin.alat.destroy', $a) }}" method="POST" onsubmit="return confirm('Yakin hapus alat ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-secondary py-4">Tidak ada data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $alat->withQueryString()->links() }}</div>
@endsection
