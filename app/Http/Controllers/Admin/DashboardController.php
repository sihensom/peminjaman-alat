<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_alat' => Alat::count(),
            'total_peminjaman' => Peminjaman::count(),
            'peminjaman_aktif' => Peminjaman::whereIn('status', ['disetujui', 'dipinjam'])->count(),
            'peminjaman_pending' => Peminjaman::where('status', 'pending')->count(),
            'pengembalian' => Pengembalian::count(),
        ];

        $recentPeminjaman = Peminjaman::with(['user', 'alat'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPeminjaman'));
    }
}
