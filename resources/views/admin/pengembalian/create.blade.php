@extends('layouts.app')
@section('title', 'Catat Pengembalian')
@section('page-title', 'Catat Pengembalian')
@section('content')
<div class="row justify-content-center"><div class="col-md-8">
    <div class="card"><div class="card-body p-4">
        <form method="POST" action="{{ route('admin.pengembalian.store') }}">@csrf
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Peminjaman <span class="text-danger">*</span></label>
                    <select name="peminjaman_id" class="form-select" required>
                        <option value="">Pilih Peminjaman</option>
                        @foreach($peminjamanList as $p)<option value="{{ $p->id }}">{{ $p->user->nama }} - {{ $p->alat->nama_alat }} ({{ $p->tanggal_pinjam->format('d/m/Y') }})</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6"><label class="form-label">Tgl Kembali Aktual <span class="text-danger">*</span></label><input type="date" name="tanggal_kembali_aktual" class="form-control" value="{{ date('Y-m-d') }}" required></div>
                <div class="col-md-6"><label class="form-label">Kondisi Alat <span class="text-danger">*</span></label>
                    <select name="kondisi_alat" class="form-select" required><option value="baik">Baik</option><option value="rusak">Rusak</option><option value="hilang">Hilang</option></select>
                </div>
                <div class="col-12"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control" rows="2"></textarea></div>
            </div>
            <div class="d-flex gap-2 mt-4"><button type="submit" class="btn btn-primary">Simpan</button><a href="{{ route('admin.pengembalian.index') }}" class="btn btn-outline-secondary">Batal</a></div>
        </form>
    </div></div>
</div></div>
@endsection
