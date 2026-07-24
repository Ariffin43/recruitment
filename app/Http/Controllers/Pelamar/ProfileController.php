<?php

namespace App\Http\Controllers\Pelamar;

use App\Http\Controllers\Controller;
use App\Models\Pelamar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $pelamar = Pelamar::where('user_id', auth()->id())->first();
        return view('pelamar.pages.profile', compact('pelamar'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'jenis_kelamin'      => 'required|in:L,P',
            'no_hp'              => 'required|string|max:20',
            'alamat'             => 'required|string',
            'pendidikan_terakhir' => 'required|string|max:100',
            'pengalaman_kerja'   => 'nullable|string|max:255',
            'foto'               => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'file_cv'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_ktp'           => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_kk'            => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_ijazah'        => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'file_sertifikat'    => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'password'           => ['nullable', 'confirmed', Password::min(8)],
        ], [
            'jenis_kelamin.required'       => 'Jenis kelamin wajib dipilih.',
            'no_hp.required'               => 'No. HP wajib diisi.',
            'alamat.required'              => 'Alamat wajib diisi.',
            'pendidikan_terakhir.required' => 'Pendidikan terakhir wajib dipilih.',
            'password.confirmed'           => 'Konfirmasi password tidak cocok.',
            'password.min'                 => 'Password minimal 8 karakter.',
        ]);

        $user    = auth()->user();
        $pelamar = Pelamar::firstOrNew(['user_id' => $user->id]);

        $pelamar->jenis_kelamin       = $request->jenis_kelamin;
        $pelamar->no_hp               = $request->no_hp;
        $pelamar->alamat              = $request->alamat;
        $pelamar->pendidikan_terakhir = $request->pendidikan_terakhir;
        $pelamar->pengalaman_kerja    = $request->pengalaman_kerja;

        $fileFields = ['foto', 'file_cv', 'file_ktp', 'file_kk', 'file_ijazah', 'file_sertifikat'];

        foreach ($fileFields as $field) {
            if ($request->hasFile($field)) {
                $folder = $field === 'foto' ? 'pelamar/foto' : 'pelamar/dokumen';
                $pelamar->$field = $request->file($field)->store($folder, 'public');
            }
        }

        $pelamar->save();

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
