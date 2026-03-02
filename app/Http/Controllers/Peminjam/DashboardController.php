<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total' => Peminjaman::where('user_id', $user->id)->count(),
            'pending' => Peminjaman::where('user_id', $user->id)->where('status', 'pending')->count(),
            'dipinjam' => Peminjaman::where('user_id', $user->id)->whereIn('status', ['disetujui', 'dipinjam'])->count(),
            'dikembalikan' => Peminjaman::where('user_id', $user->id)->where('status', 'dikembalikan')->count(),
        ];

        $recentPeminjaman = Peminjaman::with('alat')
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        return view('peminjam.dashboard', compact('stats', 'recentPeminjaman'));
    }
}
