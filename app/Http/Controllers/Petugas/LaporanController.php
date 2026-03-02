<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Alat;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('dari', now()->startOfMonth()->toDateString());
        $endDate = $request->input('sampai', now()->toDateString());

        $allPeminjaman = Peminjaman::with(['user', 'alat', 'pengembalian'])
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->get();

        $stats = [
            'total' => $allPeminjaman->count(),
            'disetujui' => $allPeminjaman->where('status', 'disetujui')->count(),
            'ditolak' => $allPeminjaman->where('status', 'ditolak')->count(),
            'dikembalikan' => $allPeminjaman->where('status', 'dikembalikan')->count(),
            'pending' => $allPeminjaman->where('status', 'pending')->count(),
        ];

        $peminjaman = Peminjaman::with(['user', 'alat', 'pengembalian'])
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->latest()
            ->paginate(10);

        $alatPopuler = Peminjaman::selectRaw('alat_id, COUNT(*) as total')
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->groupBy('alat_id')
            ->orderByDesc('total')
            ->limit(5)
            ->with('alat')
            ->get();

        return view('petugas.laporan.index', compact('peminjaman', 'stats', 'alatPopuler', 'startDate', 'endDate'));
    }

    public function print(Request $request)
    {
        $startDate = $request->input('dari', now()->startOfMonth()->toDateString());
        $endDate = $request->input('sampai', now()->toDateString());

        $peminjaman = Peminjaman::with(['user', 'alat', 'pengembalian'])
            ->whereBetween('tanggal_pinjam', [$startDate, $endDate])
            ->get();

        $stats = [
            'total' => $peminjaman->count(),
            'disetujui' => $peminjaman->where('status', 'disetujui')->count(),
            'ditolak' => $peminjaman->where('status', 'ditolak')->count(),
            'dikembalikan' => $peminjaman->where('status', 'dikembalikan')->count(),
        ];

        return view('petugas.laporan.print', compact('peminjaman', 'stats', 'startDate', 'endDate'));
    }
}
