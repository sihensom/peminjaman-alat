@extends('layouts.app')
@section('title', 'Riwayat Pengembalian')
@section('page-title', 'Riwayat Pengembalian Alat')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0"><i class="bi bi-arrow-return-left me-2"></i>Riwayat Pengembalian Saya</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Alat</th>
                        <th>Jumlah</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Kondisi</th>
                        <th>Solusi</th>
                        <th>Keterangan</th>
                        <th>Denda</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengembalian as $i => $pg)
                    <tr>
                        <td>{{ $pengembalian->firstItem() + $i }}</td>
                        <td>{{ $pg->peminjaman->alat->nama_alat ?? '-' }}</td>
                        <td>{{ $pg->peminjaman->jumlah_pinjam }}</td>
                        <td>{{ $pg->peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                        <td>{{ $pg->tanggal_kembali_aktual->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $pg->kondisi_alat == 'baik' ? 'success' : ($pg->kondisi_alat == 'rusak' ? 'danger' : 'warning') }}">
                                {{ ucfirst($pg->kondisi_alat) }}
                            </span>
                        </td>
                        <td>{{ $pg->solusi ?? '-' }}</td>
                        <td>{{ $pg->keterangan ?? '-' }}</td>
                        <td>
                            @if($pg->denda > 0)
                            <span class="text-danger fw-semibold">Rp {{ number_format($pg->denda, 0, ',', '.') }}</span>
                            @else
                            <span class="text-secondary">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-secondary py-4">
                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0">Belum ada riwayat pengembalian</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $pengembalian->links() }}</div>
@endsection
