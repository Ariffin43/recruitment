<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
        ]);

        if (! Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau kata sandi salah.',
            ])->withInput();
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->status !== 'aktif') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $message = match ($user->status) {
                'pending' => 'Akun kamu masih di proses. Silakan menunggu persetujuan.',
                'nonaktif' => 'Akun kamu sedang nonaktif.',
                'ditolak' => 'Akun kamu ditolak.',
                default => 'Akun kamu belum aktif.',
            };

            return back()->withErrors([
                'email' => $message,
            ])->withInput();
        }

        return match ($user->role) {
            'pelamar' => redirect()->intended('pelamar/dashboard'),
            'hrd'     => redirect()->intended('hrd/dashboard'),
            'hod'     => redirect()->intended('hod/dashboard'),
            'gm'      => redirect()->intended('gm/approval'),
            default   => redirect()->intended('/dashboard'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout.');
    }
}