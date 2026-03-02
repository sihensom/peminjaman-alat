@extends('layouts.app')
@section('title', isset($kategori) ? 'Edit Kategori' : 'Tambah Kategori')
@section('page-title', isset($kategori) ? 'Edit Kategori' : 'Tambah Kategori')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ isset($kategori) ? route('admin.kategori.update', $kategori) : route('admin.kategori.store') }}">
                    @csrf
                    @if(isset($kategori)) @method('PUT') @endif
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kategori" class="form-control" value="{{ old('nama_kategori', $kategori->nama_kategori ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi', $kategori->deskripsi ?? '') }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Simpan</button>
                        <a href="{{ route('admin.kategori.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
