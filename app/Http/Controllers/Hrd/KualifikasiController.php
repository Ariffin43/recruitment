<?php

namespace App\Http\Controllers\Hrd;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use App\Models\Kualifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KualifikasiController extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->input('search');
        $filterDep  = $request->input('departemen');
        $perPage    = $request->input('perPage', 5);

        $kualifikasi = Kualifikasi::with('departemen')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('departemen', function ($q2) use ($search) {
                    $q2->where('nama', 'like', "%{$search}%")
                       ->orWhere('kode', 'like', "%{$search}%");
                });
            })
            ->when($filterDep, function ($q) use ($filterDep) {
                $q->where('departemen_id', $filterDep);
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $departemen = Departemen::orderBy('nama')->get();

        $depTerpakai = Kualifikasi::pluck('departemen_id')->toArray();

        return view('hrd.pages.kualifikasi', compact(
            'kualifikasi',
            'departemen',
            'depTerpakai'
        ));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'departemen_id'    => 'required|exists:departemen,id|unique:kualifikasi,departemen_id',
            'nama_kualifikasi' => 'required|string',
        ], [
            'departemen_id.required' => 'Departemen wajib dipilih.',
            'departemen_id.unique'   => 'Departemen ini sudah memiliki kualifikasi.',
            'nama_kualifikasi.required' => 'Kualifikasi wajib diisi.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('openModal', 'modalTambah');
        }

        $items = preg_split("/\r\n|\r|\n/", $request->nama_kualifikasi);
        $items = array_values(array_filter(array_map('trim', $items)));

        Kualifikasi::create([
            'departemen_id'    => $request->departemen_id,
            'nama_kualifikasi' => implode("\n", $items),
        ]);

        return back()->with('success', 'Kualifikasi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'departemen_id'    => "required|exists:departemen,id|unique:kualifikasi,departemen_id,{$id}",
            'nama_kualifikasi' => 'required|string',
        ], [
            'departemen_id.required' => 'Departemen wajib dipilih.',
            'departemen_id.unique'   => 'Departemen ini sudah memiliki kualifikasi.',
            'nama_kualifikasi.required' => 'Kualifikasi wajib diisi.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('openModal', 'modalEdit')
                ->with('editId', $id);
        }

        $kualifikasi = Kualifikasi::findOrFail($id);

        $items = preg_split("/\r\n|\r|\n/", $request->nama_kualifikasi);
        $items = array_values(array_filter(array_map('trim', $items)));

        $kualifikasi->update([
            'departemen_id'    => $request->departemen_id,
            'nama_kualifikasi' => implode("\n", $items),
        ]);

        return back()->with('success', 'Kualifikasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Kualifikasi::findOrFail($id)->delete();

        return back()->with('success', 'Kualifikasi berhasil dihapus.');
    }
}