@extends('layouts.app')
@section('title', 'Bayar Denda')
@section('page-title', 'Pembayaran Denda')
@section('content')

<div class="row g-4 justify-content-center">
    <div class="col-md-8 col-lg-6">
        {{-- Detail Denda --}}
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Detail Denda</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><td class="text-secondary" style="width:150px">Alat</td><td>{{ $pengembalian->peminjaman->alat->nama_alat }}</td></tr>
                    <tr><td class="text-secondary">Kondisi</td><td>
                        <span class="badge bg-{{ $pengembalian->kondisi_alat == 'baik' ? 'success' : ($pengembalian->kondisi_alat == 'rusak' ? 'danger' : 'warning') }}">
                            {{ ucfirst($pengembalian->kondisi_alat) }}
                        </span>
                    </td></tr>
                    @if($pengembalian->solusi)
                    <tr><td class="text-secondary">Solusi</td><td>{{ $pengembalian->solusi }}</td></tr>
                    @endif
                    <tr><td class="text-secondary">Tgl Kembali</td><td>{{ $pengembalian->tanggal_kembali_aktual->format('d F Y') }}</td></tr>
                    <tr>
                        <td class="text-secondary">Jumlah Denda</td>
                        <td><span class="text-danger fw-bold" style="font-size: 1.3rem;">Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</span></td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Form Pembayaran --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-wallet2 me-2 text-warning"></i>Pilih Metode Pembayaran</h6>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('peminjam.denda.process', $pengembalian) }}" id="paymentForm">
                    @csrf

                    {{-- Payment Method Selection --}}
                    <div class="d-flex gap-3 mb-4">
                        <label class="payment-option flex-fill" id="optCash">
                            <input type="radio" name="metode_bayar" value="cash" class="d-none" onchange="togglePayment(this.value)" required>
                            <div class="payment-card text-center p-3 rounded-3" style="border: 2px solid rgba(255,255,255,0.1); cursor: pointer; transition: all 0.3s;">
                                <i class="bi bi-cash-stack" style="font-size: 2.5rem; color: #10b981;"></i>
                                <p class="mb-0 mt-2 fw-semibold">Cash</p>
                                <small class="text-secondary">Bayar langsung ke petugas</small>
                            </div>
                        </label>
                        <label class="payment-option flex-fill" id="optQris">
                            <input type="radio" name="metode_bayar" value="qris" class="d-none" onchange="togglePayment(this.value)">
                            <div class="payment-card text-center p-3 rounded-3" style="border: 2px solid rgba(255,255,255,0.1); cursor: pointer; transition: all 0.3s;">
                                <i class="bi bi-qr-code" style="font-size: 2.5rem; color: #3b82f6;"></i>
                                <p class="mb-0 mt-2 fw-semibold">QRIS</p>
                                <small class="text-secondary">Scan QR untuk pembayaran</small>
                            </div>
                        </label>
                    </div>

                    {{-- Cash Detail --}}
                    <div id="cashDetail" style="display: none;">
                        <div class="alert" style="background: rgba(16,185,129,0.12); border: 1px solid rgba(16,185,129,0.25); color: #6ee7b7;">
                            <i class="bi bi-info-circle me-1"></i>
                            <strong>Pembayaran Cash</strong><br>
                            Silakan bayar langsung ke petugas sebesar <strong>Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</strong>.<br>
                            Klik tombol <strong>"Konfirmasi Pembayaran"</strong> setelah Anda membayar.
                        </div>
                    </div>

                    {{-- QRIS Detail --}}
                    <div id="qrisDetail" style="display: none;">
                        <div class="text-center mb-3">
                            <div class="p-3 rounded-3 d-inline-block" style="background: white;">
                                <img src="{{ asset('images/qris_dev.png') }}" alt="QRIS Code" style="max-width: 250px; height: auto;" id="qrisImg">
                            </div>
                        </div>
                        <p class="text-center text-secondary mb-2">Scan QR di atas menggunakan aplikasi e-wallet / m-banking</p>
                        <p class="text-center mb-3">
                            <span class="fw-bold text-warning" style="font-size: 1.1rem;">Total: Rp {{ number_format($pengembalian->denda, 0, ',', '.') }}</span>
                        </p>
                        <div class="text-center mb-3">
                            <a href="{{ asset('images/qris_dev.png') }}" download="QRIS_Pembayaran_Denda.png" class="btn btn-outline-info btn-sm">
                                <i class="bi bi-download me-1"></i>Download QR Code
                            </a>
                        </div>
                        <div class="alert" style="background: rgba(59,130,246,0.12); border: 1px solid rgba(59,130,246,0.25); color: #93c5fd; font-size: 0.85rem;">
                            <i class="bi bi-exclamation-circle me-1"></i>
                            <strong>Development Mode:</strong> QR code ini adalah dummy untuk pengujian. Klik "Konfirmasi Pembayaran" setelah simulasi pembayaran.
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('peminjam.denda.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
                        <button type="submit" class="btn btn-success flex-fill" id="btnSubmit" disabled>
                            <i class="bi bi-check-circle me-1"></i>Konfirmasi Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .payment-option input:checked + .payment-card {
        border-color: var(--primary) !important;
        background: rgba(99, 102, 241, 0.1) !important;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.3);
    }
</style>
@endpush

<script>
function togglePayment(method) {
    document.getElementById('cashDetail').style.display = method === 'cash' ? 'block' : 'none';
    document.getElementById('qrisDetail').style.display = method === 'qris' ? 'block' : 'none';
    document.getElementById('btnSubmit').disabled = false;
}
</script>
@endsection
