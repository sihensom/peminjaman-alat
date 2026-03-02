@extends('layouts.app')
@section('title', 'Edit Alat')
@section('page-title', 'Edit Alat')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.alat.update', $alat) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kode Alat <span class="text-danger">*</span></label>
                            <input type="text" name="kode_alat" class="form-control" value="{{ old('kode_alat', $alat->kode_alat) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Alat <span class="text-danger">*</span></label>
                            <input type="text" name="nama_alat" class="form-control" value="{{ old('nama_alat', $alat->nama_alat) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori_id" class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                @foreach($kategoriList as $k)
                                <option value="{{ $k->id }}" {{ old('kategori_id', $alat->kategori_id) == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Jumlah Total <span class="text-danger">*</span></label>
                            <input type="number" name="jumlah_total" class="form-control" value="{{ old('jumlah_total', $alat->jumlah_total) }}" min="1" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kondisi <span class="text-danger">*</span></label>
                            <select name="kondisi" class="form-select" required>
                                <option value="baik" {{ old('kondisi', $alat->kondisi) == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak" {{ old('kondisi', $alat->kondisi) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                <option value="maintenance" {{ old('kondisi', $alat->kondisi) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="2">{{ old('deskripsi', $alat->deskripsi) }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Gambar</label>
                            <input type="file" name="gambar" class="form-control" accept="image/*">
                            @if($alat->gambar)
                            <small class="text-secondary">Gambar saat ini ada. Upload baru untuk mengganti.</small>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan Perubahan</button>
                        <a href="{{ route('admin.alat.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
