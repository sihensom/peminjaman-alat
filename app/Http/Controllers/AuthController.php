<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LogAktivitas;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->remember)) {
            $request->session()->regenerate();

            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aksi' => 'LOGIN',
                'tabel' => 'users',
                'record_id' => Auth::id(),
                'detail' => 'User login: ' . Auth::user()->nama,
                'ip_address' => $request->ip(),
                'created_at' => now(),
            ]);

            return $this->redirectByRole();
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        LogAktivitas::create([
            'user_id' => Auth::id(),
            'aksi' => 'LOGOUT',
            'tabel' => 'users',
            'record_id' => Auth::id(),
            'detail' => 'User logout: ' . Auth::user()->nama,
            'ip_address' => $request->ip(),
            'created_at' => now(),
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }

    protected function redirectByRole()
    {
        $role = Auth::user()->role;

        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'petugas' => redirect()->route('petugas.dashboard'),
            'peminjam' => redirect()->route('peminjam.dashboard'),
            default => redirect()->route('login'),
        };
    }
}
