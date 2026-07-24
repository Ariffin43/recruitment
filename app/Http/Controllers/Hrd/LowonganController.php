<?php

namespace App\Http\Controllers\Hrd;

use App\Http\Controllers\Controller;
use App\Models\Fptk;
use App\Models\Lowongan;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LowonganController extends Controller
{
    public function index(Request $request)
    {
        Lowongan::updateExpiredStatus();

        $query = Lowongan::with(['fptk.departemen']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tipe_kerja')) {
            $query->where('tipe_kerja', $request->tipe_kerja);
        }

        $query->latest();

        $allowedPerPage = [5, 10, 20, 50];
        $perPage = (int) $request->get('perPage', 5);
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 5;
        }

        $lowongans = $query->paginate($perPage)->withQueryString();
        $fptkSudahDipakai = Lowongan::pluck('fptk_id')->toArray();

        $approvedFptk = Fptk::with('departemen.kualifikasi')
            ->where('status', 'approved_hrd')
            ->whereNotIn('id', $fptkSudahDipakai)
            ->latest()
            ->get();

        $totalPelamar = 0;

        return view('hrd.pages.lowongan', compact('lowongans', 'approvedFptk', 'totalPelamar'));
    }

    public function store(Request $request)
    {
        // Validasi input
        try {
            $request->validate([
                'fptk_id'         => ['required', 'exists:fptk,id'],
                'judul'           => ['required', 'string', 'max:150'],
                'lokasi'          => ['required', 'string', 'max:150'],
                'tipe_kerja'      => ['required', 'in:fulltime,kontrak,magang'],
                'tanggal_dibuka'  => ['required', 'date'],
                'tanggal_ditutup' => ['required', 'date', 'after_or_equal:tanggal_dibuka'],
            ], [
                'fptk_id.required'              => 'FPTK wajib dipilih.',
                'fptk_id.exists'                => 'FPTK tidak valid.',
                'judul.required'                => 'Judul lowongan wajib diisi.',
                'lokasi.required'               => 'Lokasi wajib diisi.',
                'tipe_kerja.required'           => 'Tipe kerja wajib dipilih.',
                'tanggal_dibuka.required'       => 'Tanggal dibuka wajib diisi.',
                'tanggal_ditutup.required'      => 'Tanggal ditutup wajib diisi.',
                'tanggal_ditutup.after_or_equal'=> 'Tanggal ditutup tidak boleh kurang dari tanggal dibuka.',
            ]);
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error_modal', 'create-lowongan');
        }

        $fptk = Fptk::findOrFail($request->fptk_id);

        if ($fptk->status !== 'approved_hrd') {
            return back()->with('error', 'FPTK belum disetujui HRD.');
        }

        $sudahAda = Lowongan::where('fptk_id', $fptk->id)->exists();
        if ($sudahAda) {
            return back()->with('error', 'FPTK ini sudah memiliki lowongan.');
        }

        $lowongan = Lowongan::create([
            'fptk_id'         => $fptk->id,
            'judul'           => $request->judul,
            'lokasi'          => $request->lokasi,
            'tipe_kerja'      => $request->tipe_kerja,
            'status'          => 'dibuka',
            'tanggal_dibuka'  => $request->tanggal_dibuka,
            'tanggal_ditutup' => $request->tanggal_ditutup,
        ]);

        return redirect()
            ->route('hrd.lowongan.index')
            ->with('success', "Lowongan \"{$lowongan->judul}\" berhasil dibuat.");
    }

    public function update(Request $request, $id)
    {
        $lowongan = Lowongan::findOrFail($id);

        try {
            $request->validate([
                'judul'           => ['required', 'string', 'max:150'],
                'lokasi'          => ['required', 'string', 'max:150'],
                'tipe_kerja'      => ['required', 'in:fulltime,kontrak,magang'],
                'status'          => ['required', 'in:dibuka,ditutup'],
                'tanggal_dibuka'  => ['required', 'date'],
                'tanggal_ditutup' => ['required', 'date', 'after_or_equal:tanggal_dibuka'],
            ]);
        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error_modal', 'edit-lowongan')
                ->with('error_lowongan_id', $id);
        }

        $lowongan->update([
            'judul'           => $request->judul,
            'lokasi'          => $request->lokasi,
            'tipe_kerja'      => $request->tipe_kerja,
            'status'          => $request->status,
            'tanggal_dibuka'  => $request->tanggal_dibuka,
            'tanggal_ditutup' => $request->tanggal_ditutup,
        ]);

        return back()->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $lowongan = Lowongan::findOrFail($id);
        $judul    = $lowongan->judul;
        $lowongan->delete();

        return back()->with('success', "Lowongan \"{$judul}\" berhasil dihapus.");
    }
}