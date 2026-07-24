<?php

namespace App\Http\Controllers;

use App\Models\Fptk;
use Illuminate\Http\Request;

class FptkApprovalController extends Controller
{
    private function getRingkasanStatus(): array
    {
        return Fptk::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
    }

    public function indexGm(Request $request)
    {
        $query = Fptk::with(['departemen', 'hod']);

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('nomor_fptk', 'like', "%{$keyword}%")
                  ->orWhere('posisi_dibutuhkan', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $fptks = $query->latest()
            ->paginate($request->get('perPage', 10))
            ->withQueryString();

        return view('gm.pages.approval', [
            'fptks'   => $fptks,
            'summary' => $this->getRingkasanStatus(),
        ]);
    }

    public function approveGm(Request $request, $id)
    {
        $fptk = Fptk::findOrFail($id);

        if (!in_array($fptk->status, ['pending_gm', 'revisi_gm'])) {
            return back()->with('error', 'Status tidak valid untuk disetujui GM.');
        }

        $request->validate([
            'catatan_gm' => 'nullable|string|max:1000',
        ]);

        $fptk->update([
            'status'         => 'approved_gm',
            'catatan_gm'     => $request->catatan_gm,
            'gm_approved_at' => now(),
        ]);

        return back()->with('success', 'FPTK berhasil disetujui GM.');
    }

    public function revisiGm(Request $request, $id)
    {
        $fptk = Fptk::findOrFail($id);

        if (!in_array($fptk->status, ['pending_gm', 'revisi_gm'])) {
            return back()->with('error', 'Status tidak valid untuk diminta revisi oleh GM.');
        }

        $request->validate([
            'catatan_gm' => 'required|string|max:1000',
        ]);

        $fptk->update([
            'status'     => 'revisi_gm',
            'catatan_gm' => $request->catatan_gm,
        ]);

        return back()->with('success', 'FPTK dikembalikan untuk revisi oleh HOD.');
    }

    public function tolakGm(Request $request, $id)
    {
        $fptk = Fptk::findOrFail($id);

        if (!in_array($fptk->status, ['pending_gm', 'revisi_gm'])) {
            return back()->with('error', 'Status tidak valid untuk ditolak GM.');
        }

        $request->validate([
            'catatan_gm' => 'required|string|max:1000',
        ]);

        $fptk->update([
            'status'     => 'ditolak',
            'catatan_gm' => $request->catatan_gm,
        ]);

        return back()->with('success', 'FPTK berhasil ditolak oleh GM.');
    }

    public function indexHrd(Request $request)
    {
        $query = Fptk::with(['departemen', 'hod']);

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->where(function ($q) use ($keyword) {
                $q->where('nomor_fptk', 'like', "%{$keyword}%")
                  ->orWhere('posisi_dibutuhkan', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $fptks = $query->latest()
            ->paginate($request->get('perPage', 10))
            ->withQueryString();

        return view('hrd.pages.approval', [
            'fptks'   => $fptks,
            'summary' => $this->getRingkasanStatus(),
        ]);
    }

    public function approveHrd(Request $request, $id)
    {
        $fptk = Fptk::findOrFail($id);

        if (!in_array($fptk->status, ['approved_gm', 'revisi_hrd'])) {
            return back()->with('error', 'Status tidak valid untuk disetujui HRD.');
        }

        $request->validate([
            'catatan_hrd' => 'nullable|string|max:1000',
        ]);

        $fptk->update([
            'status'          => 'approved_hrd',
            'catatan_hrd'     => $request->catatan_hrd,
            'hrd_approved_at' => now(),
        ]);

        return back()->with('success', 'FPTK berhasil disetujui HRD.');
    }

    public function revisiHrd(Request $request, $id)
    {
        $fptk = Fptk::findOrFail($id);

        if (!in_array($fptk->status, ['approved_gm', 'revisi_hrd'])) {
            return back()->with('error', 'Status tidak valid untuk diminta revisi oleh HRD.');
        }

        $request->validate([
            'catatan_hrd' => 'required|string|max:1000',
        ]);

        $fptk->update([
            'status'      => 'revisi_hrd',
            'catatan_hrd' => $request->catatan_hrd,
        ]);

        return back()->with('success', 'FPTK dikembalikan untuk revisi oleh HOD.');
    }

    public function batalkanHrd(Request $request, $id)
    {
        $fptk = Fptk::findOrFail($id);

        if (!in_array($fptk->status, ['approved_gm', 'revisi_hrd'])) {
            return back()->with('error', 'Status tidak valid untuk ditolak HRD.');
        }

        $request->validate([
            'catatan_hrd' => 'required|string|max:1000',
        ]);

        $fptk->update([
            'status'      => 'ditolak',
            'catatan_hrd' => $request->catatan_hrd,
        ]);

        return back()->with('success', 'FPTK berhasil ditolak oleh HRD.');
    }
}