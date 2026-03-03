<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use Illuminate\Http\Request;

class DendaController extends Controller
{
    /**
     * Tampilkan daftar denda yang belum dibayar
     */
    public function index()
    {
        $userId = auth()->id();

        // Ambil semua pengembalian dimana user punya denda > 0
        $dendaList = Pengembalian::with(['peminjaman.alat', 'peminjaman.user'])
            ->whereHas('peminjaman', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->where('denda', '>', 0)
            ->latest()
            ->get();

        return view('peminjam.denda.index', compact('dendaList'));
    }

    /**
     * Form pembayaran denda
     */
    public function pay(Pengembalian $pengembalian)
    {
        // Pastikan ini milik user yang login
        if ($pengembalian->peminjaman->user_id !== auth()->id()) {
            abort(403);
        }

        if ($pengembalian->denda_status === 'lunas') {
            return back()->with('info', 'Denda ini sudah lunas.');
        }

        $pengembalian->load(['peminjaman.alat']);

        return view('peminjam.denda.pay', compact('pengembalian'));
    }

    /**
     * Proses pembayaran denda
     */
    public function processPayment(Request $request, Pengembalian $pengembalian)
    {
        // Pastikan ini milik user yang login
        if ($pengembalian->peminjaman->user_id !== auth()->id()) {
            abort(403);
        }

        if ($pengembalian->denda_status === 'lunas') {
            return back()->with('info', 'Denda ini sudah lunas.');
        }

        $request->validate([
            'metode_bayar' => 'required|in:cash,qris',
        ]);

        $pengembalian->update([
            'denda_status' => 'lunas',
            'metode_bayar' => $request->metode_bayar,
            'tanggal_bayar' => now(),
        ]);

        return redirect()->route('peminjam.denda.index')
            ->with('success', 'Pembayaran denda berhasil! Terima kasih.');
    }
}
