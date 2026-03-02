@extends('layouts.app')
@section('title', 'Ajukan Peminjaman')
@section('page-title', 'Ajukan Peminjaman')
@section('content')
<div class="row justify-content-center"><div class="col-md-8">
    <div class="card"><div class="card-body p-4">
        <form method="POST" action="{{ route('peminjam.pinjam.store') }}">@csrf
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Pilih Alat <span class="text-danger">*</span></label>
                    <select name="alat_id" class="form-select" required>
                        <option value="">Pilih Alat</option>
                        @foreach($alatList as $a)
                        <option value="{{ $a->id }}" {{ old('alat_id', request('alat_id')) == $a->id ? 'selected' : '' }}>{{ $a->nama_alat }} ({{ $a->jumlah_tersedia }} tersedia)</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4"><label class="form-label">Jumlah <span class="text-danger">*</span></label><input type="number" name="jumlah_pinjam" class="form-control" value="{{ old('jumlah_pinjam', 1) }}" min="1" required></div>
                <div class="col-md-4"><label class="form-label">Tgl Pinjam <span class="text-danger">*</span></label><input type="date" name="tanggal_pinjam" class="form-control" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required></div>
                <div class="col-md-4"><label class="form-label">Tgl Kembali <span class="text-danger">*</span></label><input type="date" name="tanggal_kembali_rencana" class="form-control" value="{{ old('tanggal_kembali_rencana') }}" required></div>
                <div class="col-12"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control" rows="2" placeholder="Tujuan peminjaman...">{{ old('keterangan') }}</textarea></div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-send me-1"></i>Ajukan Peminjaman</button>
                <a href="{{ route('peminjam.pinjam.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div></div>
</div></div>
@endsection
