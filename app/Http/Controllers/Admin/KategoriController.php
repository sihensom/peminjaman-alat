<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $query = Kategori::withCount('alat');

        if ($request->filled('search')) {
            $query->where('nama_kategori', 'like', "%{$request->search}%");
        }

        $kategori = $query->latest()->paginate(10);
        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori,nama_kategori',
            'deskripsi' => 'nullable|string',
        ]);

        Kategori::create($request->only(['nama_kategori', 'deskripsi']));

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama_kategori' => ['required', 'string', 'max:100', Rule::unique('kategori')->ignore($kategori->id)],
            'deskripsi' => 'nullable|string',
        ]);

        $kategori->update($request->only(['nama_kategori', 'deskripsi']));

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->alat()->count() > 0) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki alat.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
