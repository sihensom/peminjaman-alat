<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    public function index(Request $request)
    {
        $query = Alat::with('kategori')->where('kondisi', 'baik');

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

        $alat = $query->paginate(12);
        $kategoriList = Kategori::all();

        return view('peminjam.alat.index', compact('alat', 'kategoriList'));
    }

    public function show(Alat $alat)
    {
        $alat->load('kategori');
        return view('peminjam.alat.show', compact('alat'));
    }
}
