<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\User;
use App\Models\Alat;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'alat', 'approver']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', fn($q) => $q->where('nama', 'like', "%{$search}%"))
                  ->orWhereHas('alat', fn($q) => $q->where('nama_alat', 'like', "%{$search}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $peminjaman = $query->latest()->paginate(10);
        return view('admin.peminjaman.index', compact('peminjaman'));
    }

    public function create()
    {
        $users = User::where('role', 'peminjam')->get();
        $alatList = Alat::where('jumlah_tersedia', '>', 0)->where('kondisi', 'baik')->get();
        return view('admin.peminjaman.create', compact('users', 'alatList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'alat_id' => 'required|exists:alat,id',
            'jumlah_pinjam' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'keterangan' => 'nullable|string',
        ]);

        $alat = Alat::findOrFail($request->alat_id);
        if (!$alat->isAvailable($request->jumlah_pinjam)) {
            return back()->with('error', 'Stok alat tidak mencukupi.')->withInput();
        }

        Peminjaman::create($request->only([
            'user_id', 'alat_id', 'jumlah_pinjam', 'tanggal_pinjam', 'tanggal_kembali_rencana', 'keterangan'
        ]));

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman berhasil ditambahkan.');
    }

    public function show(Peminjaman $peminjaman)
    {
        $peminjaman->load(['user', 'alat.kategori', 'approver', 'pengembalian']);
        return view('admin.peminjaman.show', compact('peminjaman'));
    }

    public function edit(Peminjaman $peminjaman)
    {
        $users = User::where('role', 'peminjam')->get();
        $alatList = Alat::all();
        return view('admin.peminjaman.edit', compact('peminjaman', 'users', 'alatList'));
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak,dipinjam,dikembalikan',
            'keterangan' => 'nullable|string',
        ]);

        $peminjaman->update($request->only(['status', 'keterangan']));

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman berhasil diperbarui.');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        if (in_array($peminjaman->status, ['disetujui', 'dipinjam'])) {
            // Kembalikan stok
            $alat = $peminjaman->alat;
            $alat->increment('jumlah_tersedia', $peminjaman->jumlah_pinjam);
        }

        $peminjaman->delete();

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Peminjaman berhasil dihapus.');
    }
}
