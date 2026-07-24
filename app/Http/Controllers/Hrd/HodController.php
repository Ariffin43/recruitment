<?php

namespace App\Http\Controllers\Hrd;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class HodController extends Controller
{
    public function index(Request $request)
    {
        $search       = $request->input('search');
        $departemenId = $request->input('departemen');
        $perPage      = (int) $request->input('perPage', 5);

        if (!in_array($perPage, [5, 10, 20, 50])) {
            $perPage = 5;
        }

        $hods = Karyawan::with(['user', 'departemen'])
            ->whereHas('user', function ($query) {
                $query->where('role', 'hod');
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('badge_id', 'like', "%{$search}%")
                        ->orWhere('jabatan', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($u) use ($search) {
                            $u->where('nama', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($departemenId, function ($query) use ($departemenId) {
                $query->where('departemen_id', $departemenId);
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $daftarDepartemen = Departemen::orderBy('kode')->get();

        return view('hrd.pages.akun-hod', compact('hods', 'daftarDepartemen'));
    }

    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'nama'          => ['required', 'string', 'max:255'],
                'email'         => ['required', 'email', 'max:255', 'unique:users,email'],
                'password'      => ['required', 'string', 'min:4', 'confirmed'],
                'badge_id'      => ['required', 'string', 'max:50', 'unique:karyawan,badge_id'],
                'departemen_id' => ['required', 'exists:departemen,id'],
                'jabatan'       => ['required', 'string', 'max:100'],
            ], [
                'nama.required'          => 'Nama wajib diisi.',
                'email.required'         => 'Email wajib diisi.',
                'email.email'            => 'Format email tidak valid.',
                'email.unique'           => 'Email sudah digunakan.',
                'password.required'      => 'Password wajib diisi.',
                'password.min'           => 'Password minimal 4 karakter.',
                'password.confirmed'     => 'Konfirmasi password tidak cocok.',
                'badge_id.required'      => 'Badge ID wajib diisi.',
                'badge_id.unique'        => 'Badge ID sudah digunakan.',
                'departemen_id.required' => 'Departemen wajib dipilih.',
                'jabatan.required'       => 'Jabatan wajib diisi.',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error_modal', 'tambah');
        }

        DB::transaction(function () use ($request) {

            $user = User::create([
                'nama'     => $request->nama,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'hod',
                'status'   => 'aktif',
            ]);

            Karyawan::create([
                'user_id'       => $user->id,
                'departemen_id' => $request->departemen_id,
                'badge_id'      => $request->badge_id,
                'jabatan'       => $request->jabatan,
            ]);
        });

        return redirect()
            ->route('hrd.hod.index')
            ->with('success', 'Data HOD berhasil ditambahkan.');
    }

    public function update(Request $request, Karyawan $hod)
    {
        try {

            $validated = $request->validate([
                'nama'          => ['required', 'string', 'max:255'],
                'email'         => ['required','email','max:255', Rule::unique('users', 'email')->ignore($hod->user_id)],
                'password'      => ['nullable', 'string', 'min:4', 'confirmed'],
                'badge_id'      => [ 'required', 'string', 'max:50', Rule::unique('karyawan', 'badge_id')->ignore($hod->id)],
                'departemen_id' => ['required', 'exists:departemen,id'],
                'jabatan'       => ['required', 'string', 'max:100'],
            ], [
                'nama.required'          => 'Nama wajib diisi.',
                'email.required'         => 'Email wajib diisi.',
                'email.email'            => 'Format email tidak valid.',
                'email.unique'           => 'Email sudah digunakan.',
                'password.min'           => 'Password minimal 4 karakter.',
                'password.confirmed'     => 'Konfirmasi password tidak cocok.',
                'badge_id.required'      => 'Badge ID wajib diisi.',
                'badge_id.unique'        => 'Badge ID sudah digunakan.',
                'departemen_id.required' => 'Departemen wajib dipilih.',
                'jabatan.required'       => 'Jabatan wajib diisi.',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            return redirect()
                ->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error_modal', 'edit')
                ->with('error_hod_id', $hod->id);
        }

        DB::transaction(function () use ($request, $hod) {

            $userData = [
                'nama'  => $request->nama,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $hod->user->update($userData);

            $hod->update([
                'departemen_id' => $request->departemen_id,
                'badge_id'      => $request->badge_id,
                'jabatan'       => $request->jabatan,
            ]);
        });

        return redirect()
            ->route('hrd.hod.index')
            ->with('success', 'Data HOD berhasil diperbarui.');
    }

    public function destroy(Karyawan $hod)
    {
        DB::transaction(function () use ($hod) {
            $userId = $hod->user_id;
            $hod->delete();
            User::destroy($userId);
        });

        return redirect()
            ->route('hrd.hod.index')
            ->with('success', 'Data HOD berhasil dihapus.');
    }
}