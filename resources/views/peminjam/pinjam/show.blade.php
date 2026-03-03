@extends('layouts.app')
@section('title', 'Detail Peminjaman')
@section('page-title', 'Detail Peminjaman #' . $peminjaman->id)
@section('content')
<div class="row g-4">
    <div class="col-md-8">
        <div class="card"><div class="card-body p-4">
            <div class="d-flex justify-content-between mb-3">
                <h5>Detail Peminjaman</h5>
                <span class="badge-status badge-{{ $peminjaman->status }}" style="font-size: 0.9rem; padding: 0.4rem 0.8rem;">
                    {{ match($peminjaman->status) {
                        'diajukan_kembali' => 'Menunggu Konfirmasi',
                        'dibatalkan' => 'Dibatalkan',
                        default => ucfirst($peminjaman->status)
                    } }}
                </span>
            </div>
            <table class="table table-borderless">
                <tr><td class="text-secondary" style="width:160px">Alat</td><td>{{ $peminjaman->alat->nama_alat }} <code>({{ $peminjaman->alat->kode_alat }})</code></td></tr>
                <tr><td class="text-secondary">Kategori</td><td>{{ $peminjaman->alat->kategori->nama_kategori }}</td></tr>
                <tr><td class="text-secondary">Jumlah</td><td>{{ $peminjaman->jumlah_pinjam }} unit</td></tr>
                <tr><td class="text-secondary">Tgl Pinjam</td><td>{{ $peminjaman->tanggal_pinjam->format('d F Y') }}</td></tr>
                <tr><td class="text-secondary">Rencana Kembali</td><td>{{ $peminjaman->tanggal_kembali_rencana->format('d F Y') }}</td></tr>
                <tr><td class="text-secondary">Disetujui Oleh</td><td>{{ $peminjaman->approver->nama ?? '-' }}</td></tr>
            </table>

            {{-- Feedback / Keterangan dari Petugas --}}
            @if($peminjaman->keterangan)
            <div class="alert mt-3 mb-0" style="background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.25); color: #93c5fd;">
                <div class="d-flex align-items-start gap-2">
                    @if(str_starts_with($peminjaman->keterangan, 'Pengembalian ditolak:'))
                    <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size: 1.2rem; margin-top: 2px;"></i>
                    @else
                    <i class="bi bi-chat-left-text-fill text-info" style="font-size: 1.1rem; margin-top: 2px;"></i>
                    @endif
                    <div>
                        <strong class="d-block mb-1">
                            @if(str_starts_with($peminjaman->keterangan, 'Pengembalian ditolak:'))
                            <span class="text-danger">Pengembalian Ditolak</span>
                            @else
                            Catatan Petugas
                            @endif
                        </strong>
                        <span style="color: #e2e8f0;">{{ $peminjaman->keterangan }}</span>
                    </div>
                </div>
            </div>
            @endif

            {{-- Data Pengembalian (jika sudah dikembalikan) --}}
            @if($peminjaman->pengembalian)
            <hr style="border-color: var(--border);">
            <h6 class="mb-3"><i class="bi bi-arrow-return-left me-1"></i>Data Pengembalian</h6>
            <table class="table table-borderless">
                <tr><td class="text-secondary" style="width:160px">Tgl Kembali</td><td>{{ $peminjaman->pengembalian->tanggal_kembali_aktual->format('d F Y') }}</td></tr>
                <tr><td class="text-secondary">Kondisi</td><td><span class="badge bg-{{ $peminjaman->pengembalian->kondisi_alat == 'baik' ? 'success' : ($peminjaman->pengembalian->kondisi_alat == 'rusak' ? 'danger' : 'warning') }}">{{ ucfirst($peminjaman->pengembalian->kondisi_alat) }}</span></td></tr>
                @if($peminjaman->pengembalian->solusi)
                <tr><td class="text-secondary">Solusi</td><td>{{ $peminjaman->pengembalian->solusi }}</td></tr>
                @endif
                @if($peminjaman->pengembalian->keterangan)
                <tr><td class="text-secondary">Keterangan</td><td>{{ $peminjaman->pengembalian->keterangan }}</td></tr>
                @endif
                @if($peminjaman->pengembalian->denda > 0)
                <tr><td class="text-secondary">Denda</td><td><span class="text-danger fw-semibold">Rp {{ number_format($peminjaman->pengembalian->denda, 0, ',', '.') }}</span></td></tr>
                @endif
                <tr><td class="text-secondary">Diterima Oleh</td><td>{{ $peminjaman->pengembalian->receiver->nama ?? '-' }}</td></tr>
            </table>
            @endif

            @if(in_array($peminjaman->status, ['disetujui','dipinjam']))
            <a href="{{ route('peminjam.pinjam.return', $peminjaman) }}" class="btn btn-success mt-3"><i class="bi bi-arrow-return-left me-1"></i>Kembalikan Alat</a>
            @endif
        </div></div>
    </div>
</div>
<div class="mt-3"><a href="{{ route('peminjam.pinjam.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a></div>
@endsection
