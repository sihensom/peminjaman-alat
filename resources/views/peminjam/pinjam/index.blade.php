@extends('layouts.app')
@section('title', 'Peminjaman Saya')
@section('page-title', 'Peminjaman Saya')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <form class="d-flex gap-2" method="GET">
        <select name="status" class="form-select" style="width: 185px;">
            <option value="">Semua Status</option>
            @foreach(['pending','disetujui','diajukan_kembali','dikembalikan','dibatalkan','ditolak'] as $s)
            <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                {{ match($s) {
                    'diajukan_kembali' => 'Menunggu Konfirmasi',
                    'dibatalkan' => 'Dibatalkan',
                    default => ucfirst($s)
                } }}
            </option>
            @endforeach
        </select>
        <button class="btn btn-primary"><i class="bi bi-filter"></i></button>
    </form>
    <div class="d-flex align-items-center gap-3">
        {{-- Kuota harian --}}
        @php
            $today = \Carbon\Carbon::today()->toDateString();
            $used = \App\Models\Peminjaman::where('user_id', auth()->id())
                ->whereDate('created_at', $today)
                ->whereNotIn('status', ['ditolak', 'dibatalkan'])
                ->count();
            $sisa = max(0, 3 - $used);
        @endphp
        <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-3" style="background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.1);">
            <i class="bi bi-calendar-check text-info"></i>
            <span class="text-secondary" style="font-size:.85rem;">Kuota pinjam hari ini:</span>
            <strong class="{{ $used >= 3 ? 'text-danger' : ($used >= 2 ? 'text-warning' : 'text-success') }}" style="font-size:.95rem;">{{ $used }}/3</strong>
        </div>
        @if($sisa > 0)
        <a href="{{ route('peminjam.pinjam.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Ajukan Peminjaman</a>
        @else
        <button class="btn btn-primary" disabled title="Kuota habis, coba besok"><i class="bi bi-plus-lg me-1"></i>Ajukan Peminjaman</button>
        @endif
    </div>
</div>

<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>#</th><th>Alat</th><th>Jml</th><th>Tgl Pinjam</th><th>Batas Kembali</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($peminjaman as $i => $p)
            <tr>
                <td>{{ $peminjaman->firstItem() + $i }}</td>
                <td>{{ $p->alat->nama_alat }}</td>
                <td>{{ $p->jumlah_pinjam }}</td>
                <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                <td>
                    {{ $p->tanggal_kembali_rencana->format('d/m/Y') }}
                    @if(\Carbon\Carbon::today()->greaterThan($p->tanggal_kembali_rencana->startOfDay()) && in_array($p->status, ['disetujui','dipinjam']))
                    <span class="badge bg-danger ms-1">Terlambat</span>
                    @endif
                </td>
                <td>
                    <span class="badge-status badge-{{ $p->status }}">
                        {{ match($p->status) {
                            'diajukan_kembali' => 'Menunggu Konfirmasi',
                            'dibatalkan' => 'Dibatalkan',
                            default => ucfirst($p->status)
                        } }}
                    </span>
                </td>
                <td class="d-flex gap-1">
                    <a href="{{ route('peminjam.pinjam.show', $p) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                    @if(in_array($p->status, ['disetujui','dipinjam']))
                    <a href="{{ route('peminjam.pinjam.return', $p) }}" class="btn btn-sm btn-success"><i class="bi bi-arrow-return-left"></i></a>
                    @endif
                    @if($p->status === 'pending')
                    <form action="{{ route('peminjam.pinjam.cancel', $p) }}" method="POST" data-confirm="Yakin batalkan peminjaman ini?">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Batalkan Peminjaman">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-secondary py-4">Belum ada peminjaman</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div></div>
<div class="mt-3">{{ $peminjaman->withQueryString()->links() }}</div>
@endsection
