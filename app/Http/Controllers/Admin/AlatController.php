<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AlatController extends Controller
{
    public function index(Request $request)
    {
        $query = Alat::with('kategori');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_alat', 'like', "%{$search}%")
                  ->orWhere('kode_alat', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('kondisi')) {
            $query->where('kondisi', $request->kondisi);
        }

        $alat = $query->latest()->paginate(10);
        $kategoriList = Kategori::all();
        return view('admin.alat.index', compact('alat', 'kategoriList'));
    }

    public function create()
    {
        $kategoriList = Kategori::all();
        return view('admin.alat.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'kode_alat' => 'required|string|max:50|unique:alat,kode_alat',
            'nama_alat' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'jumlah_total' => 'required|integer|min:1',
            'kondisi' => 'required|in:baik,rusak,maintenance',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['kategori_id', 'kode_alat', 'nama_alat', 'deskripsi', 'jumlah_total', 'kondisi']);
        $data['jumlah_tersedia'] = $request->jumlah_total;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('alat', 'public');
        }

        Alat::create($data);

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil ditambahkan.');
    }

    public function show(Alat $alat)
    {
        $alat->load(['kategori', 'peminjaman.user']);
        return view('admin.alat.show', compact('alat'));
    }

    public function edit(Alat $alat)
    {
        $kategoriList = Kategori::all();
        return view('admin.alat.edit', compact('alat', 'kategoriList'));
    }

    public function update(Request $request, Alat $alat)
    {
        $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'kode_alat' => ['required', 'string', 'max:50', Rule::unique('alat')->ignore($alat->id)],
            'nama_alat' => 'required|string|max:150',
            'deskripsi' => 'nullable|string',
            'jumlah_total' => 'required|integer|min:1',
            'kondisi' => 'required|in:baik,rusak,maintenance',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['kategori_id', 'kode_alat', 'nama_alat', 'deskripsi', 'jumlah_total', 'kondisi']);

        // Recalculate available stock
        $dipinjam = $alat->peminjaman()->whereIn('status', ['disetujui', 'dipinjam'])->sum('jumlah_pinjam');
        $data['jumlah_tersedia'] = $request->jumlah_total - $dipinjam;

        if ($request->hasFile('gambar')) {
            if ($alat->gambar) {
                Storage::disk('public')->delete($alat->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('alat', 'public');
        }

        $alat->update($data);

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil diperbarui.');
    }

    public function destroy(Alat $alat)
    {
        if ($alat->gambar) {
            Storage::disk('public')->delete($alat->gambar);
        }

        $alat->delete();

        return redirect()->route('admin.alat.index')
            ->with('success', 'Alat berhasil dihapus.');
    }
}
