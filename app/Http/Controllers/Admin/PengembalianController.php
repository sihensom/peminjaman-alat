<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengembalian::with(['peminjaman.user', 'peminjaman.alat', 'receiver']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('peminjaman.user', fn($q) => $q->where('nama', 'like', "%{$search}%"))
                  ->orWhereHas('peminjaman.alat', fn($q) => $q->where('nama_alat', 'like', "%{$search}%"));
        }

        $pengembalian = $query->latest()->paginate(10);
        return view('admin.pengembalian.index', compact('pengembalian'));
    }

    public function create()
    {
        $peminjamanList = Peminjaman::with(['user', 'alat'])
            ->whereIn('status', ['disetujui', 'dipinjam'])
            ->doesntHave('pengembalian')
            ->get();
        return view('admin.pengembalian.create', compact('peminjamanList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'tanggal_kembali_aktual' => 'required|date',
            'kondisi_alat' => 'required|in:baik,rusak,hilang',
            'keterangan' => 'nullable|string',
        ]);

        $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);

        Pengembalian::create([
            'peminjaman_id' => $request->peminjaman_id,
            'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual,
            'kondisi_alat' => $request->kondisi_alat,
            'keterangan' => $request->keterangan,
            'received_by' => auth()->id(),
        ]);

        // Update status peminjaman
        $peminjaman->update(['status' => 'dikembalikan']);

        // Kembalikan stok alat
        $alat = $peminjaman->alat;
        $alat->increment('jumlah_tersedia', $peminjaman->jumlah_pinjam);

        if (in_array($request->kondisi_alat, ['rusak', 'hilang'])) {
            $alat->update(['kondisi' => 'rusak']);
        }

        return redirect()->route('admin.pengembalian.index')
            ->with('success', 'Pengembalian berhasil dicatat.');
    }

    public function destroy(Pengembalian $pengembalian)
    {
        $pengembalian->delete();

        return redirect()->route('admin.pengembalian.index')
            ->with('success', 'Data pengembalian berhasil dihapus.');
    }
}
