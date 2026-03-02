@extends('layouts.app')
@section('title', 'Kembalikan Alat')
@section('page-title', 'Kembalikan Alat')
@section('content')
<div class="row justify-content-center"><div class="col-md-8">
    <div class="card mb-3"><div class="card-body p-4">
        <h6 class="mb-3">Informasi Peminjaman</h6>
        <table class="table table-borderless">
            <tr><td class="text-secondary" style="width:150px">Alat</td><td>{{ $peminjaman->alat->nama_alat }}</td></tr>
            <tr><td class="text-secondary">Jumlah</td><td>{{ $peminjaman->jumlah_pinjam }} unit</td></tr>
            <tr><td class="text-secondary">Tgl Pinjam</td><td>{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</td></tr>
            <tr><td class="text-secondary">Batas Kembali</td><td>
                {{ $peminjaman->tanggal_kembali_rencana->format('d F Y') }}
                @if($peminjaman->tanggal_kembali_rencana->isPast())
                <span class="badge bg-danger ms-1">Terlambat</span>
                @endif
            </td></tr>
        </table>
    </div></div>
    <div class="card"><div class="card-body p-4">
        <h6 class="mb-3">Form Pengembalian</h6>
        <form method="POST" action="{{ route('peminjam.pinjam.processReturn', $peminjaman) }}" data-confirm="Yakin kembalikan alat ini?">@csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Kondisi Alat <span class="text-danger">*</span></label>
                    <select name="kondisi_alat" id="kondisiAlatSelect" class="form-select" required onchange="handleKondisiChange(this.value)">
                        <option value="baik">Baik</option>
                        <option value="rusak">Rusak</option>
                        <option value="hilang">Hilang</option>
                    </select>
                </div>

                {{-- Dropdown solusi (muncul jika rusak/hilang) --}}
                <div class="col-md-6" id="solusiWrapper" style="display:none;">
                    <label class="form-label">Solusi <span class="text-danger">*</span></label>
                    <select name="solusi" id="solusiSelect" class="form-select">
                        <option value="">-- Pilih Solusi --</option>
                    </select>
                </div>

                {{-- Keterangan (muncul jika rusak/hilang) --}}
                <div class="col-12" id="keteranganWrapper">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan pengembalian (opsional)"></textarea>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Kembalikan</button>
                <a href="{{ route('peminjam.pinjam.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div></div>
</div></div>

<script>
const solusiOptions = {
    rusak: ['Memperbaiki', 'Mengganti Baru', 'Ganti Rugi'],
    hilang: ['Ganti Rugi', 'Beli Baru']
};

function handleKondisiChange(val) {
    const wrapper = document.getElementById('solusiWrapper');
    const select = document.getElementById('solusiSelect');

    if (val === 'rusak' || val === 'hilang') {
        wrapper.style.display = 'block';
        select.innerHTML = '<option value="">-- Pilih Solusi --</option>';
        solusiOptions[val].forEach(opt => {
            const o = document.createElement('option');
            o.value = opt;
            o.textContent = opt;
            select.appendChild(o);
        });
        select.required = true;
    } else {
        wrapper.style.display = 'none';
        select.required = false;
        select.value = '';
    }
}
</script>
@endsection
