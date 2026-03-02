<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'pending' => Peminjaman::where('status', 'pending')->count(),
            'dipinjam' => Peminjaman::whereIn('status', ['disetujui', 'dipinjam'])->count(),
            'dikembalikan' => Peminjaman::where('status', 'dikembalikan')->count(),
        ];

        $pendingList = Peminjaman::with(['user', 'alat'])
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        return view('petugas.dashboard', compact('stats', 'pendingList'));
    }
}
