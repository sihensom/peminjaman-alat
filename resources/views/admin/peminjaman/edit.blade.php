@extends('layouts.app')
@section('title', 'Edit Peminjaman')
@section('page-title', 'Edit Peminjaman')
@section('content')
<div class="row justify-content-center"><div class="col-md-6">
    <div class="card"><div class="card-body p-4">
        <form method="POST" action="{{ route('admin.peminjaman.update', $peminjaman) }}">@csrf @method('PUT')
            <div class="mb-3"><label class="form-label">Status</label>
                <select name="status" class="form-select">
                    @foreach(['pending','disetujui','ditolak','dipinjam','dikembalikan'] as $s)
                    <option value="{{ $s }}" {{ $peminjaman->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control" rows="2">{{ $peminjaman->keterangan }}</textarea></div>
            <div class="d-flex gap-2"><button type="submit" class="btn btn-primary">Simpan</button><a href="{{ route('admin.peminjaman.index') }}" class="btn btn-outline-secondary">Batal</a></div>
        </form>
    </div></div>
</div></div>
@endsection
