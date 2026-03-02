<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = LogAktivitas::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('aksi', 'like', "%{$search}%")
                  ->orWhere('detail', 'like', "%{$search}%")
                  ->orWhere('tabel', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tabel')) {
            $query->where('tabel', $request->tabel);
        }

        $logs = $query->latest('created_at')->paginate(20);
        return view('admin.log.index', compact('logs'));
    }
}
