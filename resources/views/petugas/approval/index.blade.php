@extends('layouts.app')
@section('title', 'Persetujuan Peminjaman')
@section('page-title', 'Persetujuan & Pengembalian')
@section('content')

{{-- Inline styles to fix modal dark theme --}}
<style>
.modal-content {
    background: var(--card-bg, #1e2537) !important;
    border: 1px solid rgba(255,255,255,0.1) !important;
    color: var(--text-primary, #e2e8f0) !important;
}
.modal-header {
    border-bottom: 1px solid rgba(255,255,255,0.1) !important;
}
.modal-footer {
    border-top: 1px solid rgba(255,255,255,0.1) !important;
}
.modal-title { color: var(--text-primary, #e2e8f0) !important; }
.modal .form-label { color: var(--text-primary, #e2e8f0) !important; font-weight: 500; }
.modal .form-control,
.modal .form-select {
    background: rgba(255,255,255,0.07) !important;
    border: 1px solid rgba(255,255,255,0.15) !important;
    color: var(--text-primary, #e2e8f0) !important;
}
.modal .form-control:disabled,
.modal .form-select:disabled {
    background: rgba(255,255,255,0.04) !important;
    color: var(--text-secondary, #94a3b8) !important;
}
.modal .form-control::placeholder { color: var(--text-secondary, #94a3b8) !important; }
.modal .form-select option { background: #1e2537 !important; color: #e2e8f0 !important; }
.modal .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
.modal .alert-info {
    background: rgba(59,130,246,0.15) !important;
    border: 1px solid rgba(59,130,246,0.3) !important;
    color: #93c5fd !important;
}
.modal .alert-warning {
    background: rgba(245,158,11,0.15) !important;
    border: 1px solid rgba(245,158,11,0.3) !important;
    color: #fcd34d !important;
}
.denda-templates button {
    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.15);
    color: #e2e8f0;
    border-radius: 6px;
    padding: 4px 10px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.2s;
}
.denda-templates button:hover {
    background: rgba(16,185,129,0.25);
    border-color: rgba(16,185,129,0.5);
    color: #6ee7b7;
}
</style>

<form class="d-flex gap-2 mb-4" method="GET">
    <select name="status" class="form-select" style="width: 200px;">
        <option value="">Semua Status</option>
        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="diajukan_kembali" {{ request('status') == 'diajukan_kembali' ? 'selected' : '' }}>Diajukan Kembali</option>
        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
        <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
        <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
        <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
    </select>
    <button class="btn btn-primary"><i class="bi bi-filter"></i></button>
</form>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table mb-0">
        <thead><tr><th>Peminjam</th><th>Alat</th><th>Jml</th><th>Tgl Pinjam</th><th>Batas Kembali</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($peminjaman as $p)
            <tr>
                <td>{{ $p->user->nama }}</td>
                <td>{{ $p->alat->nama_alat }}</td>
                <td>{{ $p->jumlah_pinjam }}</td>
                <td>{{ $p->tanggal_pinjam->format('d/m/Y') }}</td>
                <td>{{ $p->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                <td><span class="badge-status badge-{{ $p->status }}">{{ $p->status == 'diajukan_kembali' ? 'Diajukan Kembali' : ($p->status == 'dibatalkan' ? 'Dibatalkan' : ucfirst($p->status)) }}</span></td>
                <td>
                    @if($p->status === 'pending')
                    <a href="{{ route('petugas.approval.show', $p) }}" class="btn btn-sm btn-primary"><i class="bi bi-eye me-1"></i>Review</a>
                    @elseif($p->status === 'diajukan_kembali')
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#modalTerima{{ $p->id }}">
                        <i class="bi bi-check-circle me-1"></i>Terima Pengembalian
                    </button>
                    @endif
                </td>
            </tr>

            {{-- Modal Terima Pengembalian --}}
            @if($p->status === 'diajukan_kembali')
            <div class="modal fade" id="modalTerima{{ $p->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('petugas.approval.acceptReturn', $p) }}" id="formTerima{{ $p->id }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title"><i class="bi bi-check-circle me-2 text-success"></i>Terima Pengembalian</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                {{-- Info dari peminjam --}}
                                @if($p->keterangan)
                                <div class="alert alert-info mb-3">
                                    <i class="bi bi-info-circle me-1"></i>
                                    <strong>Laporan Peminjam:</strong> {{ $p->keterangan }}
                                </div>
                                @endif

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Peminjam</label>
                                        <input type="text" class="form-control" value="{{ $p->user->nama }}" disabled>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Alat</label>
                                        <input type="text" class="form-control" value="{{ $p->alat->nama_alat }} (x{{ $p->jumlah_pinjam }})" disabled>
                                    </div>

                                    {{-- Kondisi Alat --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Kondisi Alat Saat Diterima <span class="text-danger">*</span></label>
                                        <select name="kondisi_alat" class="form-select kondisi-select" required
                                            data-modal="{{ $p->id }}"
                                            onchange="handlePetugasKondisi(this, {{ $p->id }})">
                                            <option value="baik">Baik</option>
                                            <option value="rusak">Rusak</option>
                                            <option value="hilang">Hilang</option>
                                        </select>
                                    </div>

                                    {{-- Solusi (muncul jika tidak baik) --}}
                                    <div class="col-md-6" id="solusiWrap{{ $p->id }}" style="display:none;">
                                        <label class="form-label">Solusi <span class="text-danger">*</span></label>
                                        <select name="solusi" id="solusiSel{{ $p->id }}" class="form-select">
                                            <option value="">-- Pilih Solusi --</option>
                                        </select>
                                    </div>

                                    {{-- Keterangan Petugas --}}
                                    <div class="col-12" id="keteranganWrap{{ $p->id }}" style="display:none;">
                                        <label class="form-label">Keterangan / Feedback</label>
                                        <textarea name="keterangan" class="form-control" rows="2"
                                            placeholder="Jelaskan kondisi alat secara detail..."></textarea>
                                    </div>

                                    {{-- Denda (muncul jika terlambat ATAU kondisi tidak baik) --}}
                                    <div class="col-12" id="dendaWrap{{ $p->id }}"
                                        @if(!$p->tanggal_kembali_rencana->isPast()) style="display:none;" @endif>
                                        <label class="form-label">
                                            <i class="bi bi-cash-coin me-1 text-warning"></i>
                                            Denda <span class="text-secondary">(Rupiah)</span>
                                            @if($p->tanggal_kembali_rencana->isPast())
                                            <span class="badge bg-danger ms-1">Terlambat {{ $p->tanggal_kembali_rencana->diffInDays(now()) }} hari</span>
                                            @endif
                                        </label>
                                        <div class="denda-templates d-flex flex-wrap gap-2 mb-2">
                                            @foreach(['5.000','10.000','15.000','20.000'] as $template)
                                            <button type="button" onclick="setDenda({{ $p->id }}, '{{ $template }}')">Rp {{ $template }}</button>
                                            @endforeach
                                        </div>
                                        <input type="text" id="dendaInput{{ $p->id }}" name="denda_raw" class="form-control"
                                            placeholder="Ketik nominal atau pilih template di atas"
                                            oninput="formatRupiah(this, {{ $p->id }})"
                                            autocomplete="off">
                                        <input type="hidden" name="denda" id="dendaHidden{{ $p->id }}" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success"><i class="bi bi-check-lg me-1"></i>Konfirmasi Terima</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            @empty
            <tr><td colspan="7" class="text-center text-secondary py-4">Tidak ada data</td></tr>
            @endforelse
        </tbody>
    </table>
</div></div></div>
<div class="mt-3">{{ $peminjaman->withQueryString()->links() }}</div>

<script>
const solusiOptsPerugas = {
    rusak: ['Memperbaiki', 'Mengganti Baru', 'Ganti Rugi'],
    hilang: ['Ganti Rugi', 'Beli Baru']
};

function handlePetugasKondisi(sel, id) {
    const val = sel.value;
    const solusiWrap = document.getElementById('solusiWrap' + id);
    const solusiSel = document.getElementById('solusiSel' + id);
    const keteranganWrap = document.getElementById('keteranganWrap' + id);
    const dendaWrap = document.getElementById('dendaWrap' + id);

    if (val === 'rusak' || val === 'hilang') {
        // Show solusi
        solusiWrap.style.display = 'block';
        solusiSel.innerHTML = '<option value="">-- Pilih Solusi --</option>';
        solusiOptsPerugas[val].forEach(opt => {
            const o = document.createElement('option');
            o.value = opt; o.textContent = opt;
            solusiSel.appendChild(o);
        });
        solusiSel.required = true;
        // Show keterangan
        keteranganWrap.style.display = 'block';
        // Show denda
        dendaWrap.style.display = 'block';
    } else {
        solusiWrap.style.display = 'none';
        solusiSel.required = false;
        keteranganWrap.style.display = 'none';
        // Hide denda only if not late
        // Check if already shown due to lateness - keep shown if late
        // We use data attribute
        const dendaLate = dendaWrap.dataset.late === '1';
        if (!dendaLate) dendaWrap.style.display = 'none';
    }
}

function setDenda(id, formatted) {
    const input = document.getElementById('dendaInput' + id);
    const hidden = document.getElementById('dendaHidden' + id);
    input.value = 'Rp ' + formatted;
    hidden.value = parseInt(formatted.replace(/\./g, ''));
}

function formatRupiah(input, id) {
    let raw = input.value.replace(/[^0-9]/g, '');
    const hidden = document.getElementById('dendaHidden' + id);
    if (raw === '') {
        input.value = '';
        hidden.value = 0;
        return;
    }
    const num = parseInt(raw);
    hidden.value = num;
    input.value = 'Rp ' + num.toLocaleString('id-ID');
}

// Mark late banners
document.querySelectorAll('[id^="dendaWrap"]').forEach(el => {
    if (el.style.display !== 'none') el.dataset.late = '1';
});
</script>
@endsection
