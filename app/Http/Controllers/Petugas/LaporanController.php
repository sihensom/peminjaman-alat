<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use App\Models\Alat;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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

    /**
     * Cetak Langsung - buka halaman print lalu trigger window.print()
     */
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

    /**
     * Unduh PDF - langsung download file PDF tanpa preview printer
     */
    public function downloadPdf(Request $request)
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

        $pdf = Pdf::loadView('petugas.laporan.pdf', compact('peminjaman', 'stats', 'startDate', 'endDate'));
        $pdf->setPaper('A4', 'portrait');

        $filename = 'Laporan_Peminjaman_' . str_replace('-', '', $startDate) . '_' . str_replace('-', '', $endDate) . '.pdf';

        return $pdf->download($filename);
    }
}
