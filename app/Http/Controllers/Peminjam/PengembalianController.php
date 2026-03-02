<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Pengembalian;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index()
    {
        $pengembalian = Pengembalian::with(['peminjaman.alat'])
            ->whereHas('peminjaman', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->latest()
            ->paginate(10);

        return view('peminjam.pengembalian.index', compact('pengembalian'));
    }
}
