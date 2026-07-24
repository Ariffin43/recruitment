<?php

namespace App\Http\Controllers\Hrd;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Departemen::latest();

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode', 'like', '%' . $request->search . '%')
                  ->orWhere('nama', 'like', '%' . $request->search . '%');
            });
        }

        $allowedPerPage = [5, 10, 20, 50];
        $perPage = (int) $request->get('perPage', 5);

        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 5;
        }

        $departemen = $query->paginate($perPage)->withQueryString();

        return view('hrd.pages.department', compact('departemen'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => ['required', 'string', 'max:255', 'unique:departemen,kode'],
            'nama' => ['required', 'string', 'max:255'],
        ], [
            'kode.required' => 'Kode departemen wajib diisi.',
            'kode.unique' => 'Kode departemen sudah digunakan.',
            'nama.required' => 'Nama departemen wajib diisi.',
        ]);

        Departemen::create([
            'kode' => strtoupper(trim($request->kode)),
            'nama' => $request->nama,
        ]);

        return redirect()
            ->route('hrd.department.index')
            ->with('success', 'Departemen berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $department = Departemen::findOrFail($id);

        $request->validate([
            'kode' => ['required', 'string', 'max:255', 'unique:departemen,kode,' . $department->id],
            'nama' => ['required', 'string', 'max:255'],
        ], [
            'kode.required' => 'Kode departemen wajib diisi.',
            'kode.unique' => 'Kode departemen sudah digunakan.',
            'nama.required' => 'Nama departemen wajib diisi.',
        ]);

        $department->update([
            'kode' => strtoupper(trim($request->kode)),
            'nama' => $request->nama,
        ]);

        return redirect()
            ->route('hrd.department.index')
            ->with('success', 'Departemen berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Departemen::findOrFail($id)->delete();

        return redirect()
            ->route('hrd.department.index')
            ->with('success', 'Departemen berhasil dihapus.');
    }
}