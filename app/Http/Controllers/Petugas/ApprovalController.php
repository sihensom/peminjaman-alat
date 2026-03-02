<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Alat;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'alat', 'pengembalian']);

        $allowedStatuses = ['pending', 'disetujui', 'dipinjam', 'dikembalikan', 'ditolak', 'diajukan_kembali', 'dibatalkan'];
        if ($request->filled('status') && in_array($request->status, $allowedStatuses)) {
            $query->where('status', $request->status);
        } else {
            $query->whereIn('status', ['pending', 'disetujui', 'dipinjam', 'diajukan_kembali', 'dikembalikan']);
        }

        $peminjaman = $query->latest()->paginate(10);
        return view('petugas.approval.index', compact('peminjaman'));
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['user', 'alat.kategori']);
        return view('petugas.approval.show', compact('peminjaman'));
    }

    public function approve(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Peminjaman sudah diproses.');
        }

        $alat = $peminjaman->alat;
        if (!$alat->isAvailable($peminjaman->jumlah_pinjam)) {
            return back()->with('error', 'Stok alat tidak mencukupi.');
        }

        $peminjaman->update([
            'status' => 'disetujui',
            'approved_by' => auth()->id(),
            'keterangan' => $request->keterangan,
        ]);

        $alat->decrement('jumlah_tersedia', $peminjaman->jumlah_pinjam);

        return redirect()->route('petugas.approval.index')
            ->with('success', 'Peminjaman berhasil disetujui.');
    }

    public function reject(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Peminjaman sudah diproses.');
        }

        $request->validate(['keterangan' => 'required|string']);

        $peminjaman->update([
            'status' => 'ditolak',
            'approved_by' => auth()->id(),
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('petugas.approval.index')
            ->with('success', 'Peminjaman berhasil ditolak.');
    }

    /**
     * Terima pengembalian alat dari peminjam (status: diajukan_kembali)
     */
    public function acceptReturn(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'diajukan_kembali') {
            return back()->with('error', 'Peminjaman ini tidak sedang mengajukan pengembalian.');
        }

        $request->validate([
            'kondisi_alat' => 'required|in:baik,rusak,hilang',
            'solusi' => 'nullable|string|max:100',
            'keterangan' => 'nullable|string',
            'denda' => 'nullable|integer|min:0',
        ]);

        // Buat record pengembalian
        Pengembalian::create([
            'peminjaman_id' => $peminjaman->id,
            'tanggal_kembali_aktual' => now()->toDateString(),
            'kondisi_alat' => $request->kondisi_alat,
            'solusi' => $request->solusi,
            'keterangan' => $request->keterangan,
            'denda' => $request->denda ?? 0,
            'received_by' => auth()->id(),
        ]);

        // Update status peminjaman
        $peminjaman->update(['status' => 'dikembalikan']);

        // Kembalikan stok alat
        $alat = $peminjaman->alat;
        $alat->increment('jumlah_tersedia', $peminjaman->jumlah_pinjam);

        // Update kondisi alat jika rusak/hilang
        if (in_array($request->kondisi_alat, ['rusak', 'hilang'])) {
            $alat->update(['kondisi' => 'rusak']);
        }

        return redirect()->route('petugas.approval.index')
            ->with('success', 'Pengembalian alat berhasil diterima.');
    }
}
