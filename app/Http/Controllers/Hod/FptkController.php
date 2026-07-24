<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use App\Models\Fptk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class FptkController extends Controller
{
    public function index(Request $request)
    {
        $query = Fptk::query()
            ->with('departemen')
            ->where('hod_id', Auth::id());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_fptk', 'like', "%{$search}%")
                    ->orWhere('posisi_dibutuhkan', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $allowedSort = ['nomor_fptk', 'posisi_dibutuhkan', 'status', 'created_at'];

        if ($request->filled('sort') && in_array($request->sort, $allowedSort)) {
            $direction = $request->direction === 'desc' ? 'desc' : 'asc';
            $query->orderBy($request->sort, $direction);
        } else {
            $query->latest();
        }

        $allowedPerPage = [5, 10, 20, 50];
        $perPage = (int) $request->get('perPage', 5);

        if (! in_array($perPage, $allowedPerPage)) {
            $perPage = 5;
        }

        $fptk = $query->paginate($perPage)->withQueryString();
        $kualifikasi = \App\Models\Kualifikasi::where(
            'departemen_id',
            Auth::user()->karyawan->departemen_id
        )->first();

        return view('hod.pages.pengajuan-fptk', compact('fptk', 'kualifikasi'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'posisi_dibutuhkan'           => ['required', 'string', 'max:150'],
                'jumlah_kebutuhan'  => ['required', 'integer', 'min:1'],
                'tanggal_dibutuhkan'=> ['required', 'date'],
                'alasan'            => ['required', 'string'],
                'catatan_tambahan'  => ['nullable', 'string'],
            ], [
                'posisi_dibutuhkan.required'            => 'posisi_dibutuhkan wajib diisi.',
                'posisi_dibutuhkan.max'                 => 'posisi_dibutuhkan maksimal 150 karakter.',
                'jumlah_kebutuhan.required'   => 'Jumlah kebutuhan wajib diisi.',
                'jumlah_kebutuhan.integer'    => 'Jumlah kebutuhan harus berupa angka.',
                'jumlah_kebutuhan.min'        => 'Jumlah kebutuhan minimal 1.',
                'tanggal_dibutuhkan.required' => 'Tanggal dibutuhkan wajib diisi.',
                'tanggal_dibutuhkan.date'     => 'Format tanggal tidak valid.',
                'alasan.required'             => 'Alasan pengajuan wajib diisi.',
            ]);

        } catch (ValidationException $e) {

            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error_modal', 'fptk');
        }
        $departemenId = Auth::user()->karyawan->departemen_id;
        $lastId    = (Fptk::max('id') ?? 0) + 1;
        $nomorFptk = 'FPTK-' . now()->format('Y') . '-' . str_pad($lastId, 4, '0', STR_PAD_LEFT);

        Fptk::create([
            'nomor_fptk'         => $nomorFptk,
            'hod_id'             => Auth::id(),
            'departemen_id'      => $departemenId,
            'posisi_dibutuhkan'            => $request->posisi_dibutuhkan,
            'jumlah_kebutuhan'   => $request->jumlah_kebutuhan,
            'tanggal_dibutuhkan' => $request->tanggal_dibutuhkan,
            'alasan'             => $request->alasan,
            'catatan_tambahan'   => $request->catatan_tambahan,
            'status'             => 'pending_gm',
        ]);

        return redirect()->route('hod.fptk.index')->with('success', 'Pengajuan FPTK berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $fptk = Fptk::where('id', $id)
            ->where('hod_id', Auth::id())
            ->firstOrFail();

        if (! in_array($fptk->status, ['revisi_gm', 'revisi_hrd'])) {
            return back()->with('error', 'FPTK ini tidak dapat diubah.');
        }

        try {
            $request->validate([
                'posisi_dibutuhkan'            => ['required', 'string', 'max:150'],
                'jumlah_kebutuhan'   => ['required', 'integer', 'min:1'],
                'tanggal_dibutuhkan' => ['required', 'date'],
                'alasan'             => ['required', 'string'],
                'catatan_tambahan'   => ['nullable', 'string'],
            ], [
                'posisi_dibutuhkan.required'            => 'posisi_dibutuhkan wajib diisi.',
                'posisi_dibutuhkan.max'                 => 'posisi_dibutuhkan maksimal 150 karakter.',
                'jumlah_kebutuhan.required'   => 'Jumlah kebutuhan wajib diisi.',
                'jumlah_kebutuhan.integer'    => 'Jumlah kebutuhan harus berupa angka.',
                'jumlah_kebutuhan.min'        => 'Jumlah kebutuhan minimal 1.',
                'tanggal_dibutuhkan.required' => 'Tanggal dibutuhkan wajib diisi.',
                'tanggal_dibutuhkan.date'     => 'Format tanggal tidak valid.',
                'alasan.required'             => 'Alasan pengajuan wajib diisi.',
            ]);

        } catch (ValidationException $e) {

            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error_modal', 'fptk')
                ->with('error_fptk_id', $id);
        }

        $fptk->update([
            'posisi_dibutuhkan'            => $request->posisi_dibutuhkan,
            'jumlah_kebutuhan'   => $request->jumlah_kebutuhan,
            'tanggal_dibutuhkan' => $request->tanggal_dibutuhkan,
            'alasan'             => $request->alasan,
            'catatan_tambahan'   => $request->catatan_tambahan,
            'status' => match ($fptk->status) {
                'revisi_gm'  => 'pending_gm',
                'revisi_hrd' => 'approved_gm',
                default      => $fptk->status,
            },
        ]);

        return back()->with('success', 'Pengajuan FPTK berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $fptk = Fptk::where('id', $id)
            ->where('hod_id', Auth::id())
            ->whereIn('status', ['pending_gm', 'revisi_gm', 'revisi_hrd'])
            ->firstOrFail();

        $fptk->delete();

        return back()->with('success', 'Pengajuan FPTK berhasil dihapus.');
    }
}