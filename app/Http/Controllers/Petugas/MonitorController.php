<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'alat', 'pengembalian'])
            ->whereIn('status', ['disetujui', 'dipinjam', 'dikembalikan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->latest()->paginate(10);
        return view('petugas.monitor.index', compact('peminjaman'));
    }

    public function returnForm(Peminjaman $peminjaman)
    {
        if (!in_array($peminjaman->status, ['disetujui', 'dipinjam'])) {
            return back()->with('error', 'Alat sudah dikembalikan atau belum disetujui.');
        }

        $peminjaman->load(['user', 'alat']);
        return view('petugas.monitor.return', compact('peminjaman'));
    }

    public function processReturn(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'tanggal_kembali_aktual' => 'required|date',
            'kondisi_alat' => 'required|in:baik,rusak,hilang',
            'keterangan' => 'nullable|string',
        ]);

        Pengembalian::create([
            'peminjaman_id' => $peminjaman->id,
            'tanggal_kembali_aktual' => $request->tanggal_kembali_aktual,
            'kondisi_alat' => $request->kondisi_alat,
            'keterangan' => $request->keterangan,
            'received_by' => auth()->id(),
        ]);

        $peminjaman->update(['status' => 'dikembalikan']);

        $alat = $peminjaman->alat;
        $alat->increment('jumlah_tersedia', $peminjaman->jumlah_pinjam);

        if (in_array($request->kondisi_alat, ['rusak', 'hilang'])) {
            $alat->update(['kondisi' => 'rusak']);
        }

        return redirect()->route('petugas.monitor.index')
            ->with('success', 'Pengembalian berhasil diproses.');
    }
}
