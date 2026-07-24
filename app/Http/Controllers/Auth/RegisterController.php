<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pelamar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'min:3', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'no_hp' => ['required', 'regex:/^[0-9]{9,15}$/'],
            'password' => ['required', 'min:8', 'confirmed'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.min' => 'Nama lengkap minimal 3 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'no_hp.required' => 'Nomor WhatsApp wajib diisi.',
            'no_hp.regex' => 'Nomor WhatsApp harus angka dan panjang 9-15 digit.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sama.',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'pelamar',
                'status' => 'pending',
            ]);

            Pelamar::create([
                'user_id' => $user->id,
                'jenis_kelamin' => null,
                'no_hp' => $validated['no_hp'],
                'alamat' => null,
                'pendidikan_terakhir' => null,
                'pengalaman_kerja' => null,
                'foto' => null,
                'file_ktp' => null,
                'file_kk' => null,
                'file_cv' => null,
                'file_ijazah' => null,
                'file_sertifikat' => null,
            ]);
        });

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Akun kamu menunggu persetujuan.');
    }
}