<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PinjamController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['alat', 'pengembalian'])
            ->where('user_id', auth()->id());

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->latest()->paginate(10);
        return view('peminjam.pinjam.index', compact('peminjaman'));
    }

    public function create(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $userId = auth()->id();

        // Hitung peminjaman yang diajukan hari ini (pending/disetujui/dipinjam)
        $pinjamanHariIni = Peminjaman::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->whereNotIn('status', ['ditolak', 'dibatalkan'])
            ->count();

        $kuotaSisa = max(0, 3 - $pinjamanHariIni);

        if ($kuotaSisa <= 0) {
            return redirect()->route('peminjam.pinjam.index')
                ->with('error', 'Kuota peminjaman harian Anda sudah habis (maks. 3 per hari). Coba lagi besok.');
        }

        $alat = null;
        if ($request->filled('alat_id')) {
            $alat = Alat::findOrFail($request->alat_id);
        }

        $alatList = Alat::where('jumlah_tersedia', '>', 0)
            ->where('kondisi', 'baik')
            ->get();

        return view('peminjam.pinjam.create', compact('alatList', 'alat', 'pinjamanHariIni', 'kuotaSisa'));
    }

    public function store(Request $request)
    {
        $today = Carbon::today()->toDateString();
        $userId = auth()->id();

        // Cek kuota harian
        $pinjamanHariIni = Peminjaman::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->whereNotIn('status', ['ditolak', 'dibatalkan'])
            ->count();

        if ($pinjamanHariIni >= 3) {
            return back()->with('error', 'Kuota peminjaman harian Anda sudah habis (maks. 3 per hari).');
        }

        $request->validate([
            'alat_id' => 'required|exists:alat,id',
            'jumlah_pinjam' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'keterangan' => 'nullable|string',
        ]);

        $alat = Alat::findOrFail($request->alat_id);

        if (!$alat->isAvailable($request->jumlah_pinjam)) {
            return back()->with('error', 'Stok alat tidak mencukupi. Tersedia: ' . $alat->jumlah_tersedia)->withInput();
        }

        Peminjaman::create([
            'user_id' => auth()->id(),
            'alat_id' => $request->alat_id,
            'jumlah_pinjam' => $request->jumlah_pinjam,
            'tanggal_pinjam' => $request->tanggal_pinjam,
            'tanggal_kembali_rencana' => $request->tanggal_kembali_rencana,
            'keterangan' => $request->keterangan,
            'status' => 'pending',
        ]);

        return redirect()->route('peminjam.pinjam.index')
            ->with('success', 'Peminjaman berhasil diajukan. Menunggu persetujuan.');
    }

    public function show(Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== auth()->id()) {
            abort(403);
        }

        $peminjaman->load(['alat.kategori', 'approver', 'pengembalian']);
        return view('peminjam.pinjam.show', compact('peminjaman'));
    }

    public function cancel(Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== auth()->id()) {
            abort(403);
        }

        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Peminjaman ini tidak dapat dibatalkan. Hanya peminjaman dengan status pending yang bisa dibatalkan.');
        }

        $peminjaman->update(['status' => 'dibatalkan']);

        return redirect()->route('peminjam.pinjam.index')
            ->with('success', 'Peminjaman berhasil dibatalkan.');
    }

    public function returnForm(Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($peminjaman->status, ['disetujui', 'dipinjam'])) {
            return back()->with('error', 'Alat belum bisa dikembalikan.');
        }

        $peminjaman->load('alat');
        return view('peminjam.pinjam.return', compact('peminjaman'));
    }

    public function processReturn(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($peminjaman->status, ['disetujui', 'dipinjam'])) {
            return back()->with('error', 'Alat belum bisa dikembalikan.');
        }

        $request->validate([
            'kondisi_alat' => 'required|in:baik,rusak,hilang',
            'keterangan' => 'nullable|string',
            'solusi' => 'nullable|string|max:100',
        ]);

        $keteranganFull = 'Kondisi: ' . ucfirst($request->kondisi_alat);
        if ($request->solusi) {
            $keteranganFull .= ' | Solusi: ' . $request->solusi;
        }
        if ($request->keterangan) {
            $keteranganFull .= ' | Catatan: ' . $request->keterangan;
        }

        // Hanya ubah status menjadi 'diajukan_kembali' (menunggu petugas terima)
        $peminjaman->update([
            'status' => 'diajukan_kembali',
            'keterangan' => $keteranganFull,
        ]);

        return redirect()->route('peminjam.pinjam.index')
            ->with('success', 'Pengajuan pengembalian berhasil dikirim. Menunggu konfirmasi Petugas.');
    }
}
