<?php

namespace App\Http\Controllers\Hrd;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class PelamarController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()
            ->where('role', 'pelamar')
            ->with('pelamar');

        // SEARCH
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // FILTER STATUS
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // SORT
        if ($request->filled('sort')) {
            $direction = $request->direction === 'desc' ? 'desc' : 'asc';

            if (in_array($request->sort, ['nama', 'status', 'email', 'created_at'])) {
                $query->orderBy($request->sort, $direction);
            }
        } else {
            $query->latest();
        }

        // PER PAGE
        $allowedPerPage = [5, 10, 20, 50];
        $perPage        = (int) $request->get('perPage', 5);

        if (! in_array($perPage, $allowedPerPage)) {
            $perPage = 5;
        }

        $pelamar = $query->paginate($perPage)->withQueryString();

        return view('hrd.pages.akun-pelamar', compact('pelamar'));
    }
    
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        try {
            $request->validate([
                'nama'     => 'required|string|max:255',
                'email'    => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|min:8|confirmed',
                'status'   => 'required|in:pending,aktif,nonaktif,ditolak',
            ], [
                'nama.required'      => 'Nama wajib diisi.',
                'email.required'     => 'Email wajib diisi.',
                'email.email'        => 'Format email tidak valid.',
                'email.unique'       => 'Email sudah digunakan.',
                'password.min'       => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
                'status.required'    => 'Status wajib dipilih.',
                'status.in'          => 'Status tidak valid.',
            ]);
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error_modal', 'edit')
                ->with('error_user_id', $id);
        }

        $data = [
            'nama'   => $request->nama,
            'email'  => $request->email,
            'status' => $request->status,
            'role'   => 'pelamar',
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Akun pelamar berhasil diperbarui.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'in:aktif,ditolak,nonaktif'],
        ], [
            'status.required' => 'Status wajib diisi.',
            'status.in'       => 'Status tidak valid.',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'status' => $request->status
        ]);

        $labelMap = [
            'aktif'     => 'disetujui',
            'ditolak'   => 'ditolak',
            'nonaktif'  => 'dinonaktifkan',
        ];

        $label = $labelMap[$request->status] ?? 'diperbarui';

        return redirect()
            ->route('hrd.pelamar.index')
            ->with(
                'success',
                "Akun {$user->nama} berhasil {$label}."
            );
    }
    public function destroy($id)
    {
        $user = User::with('pelamar')->findOrFail($id);

        // Hapus data pelamar terlebih dahulu (jika ada)
        if ($user->pelamar) {
            $user->pelamar->delete();
        }

        $user->delete();

        return back()->with('success', 'Akun pelamar berhasil dihapus.');
    }
}