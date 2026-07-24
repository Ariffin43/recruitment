<?php

namespace App\Http\Controllers;

use App\Models\Departemen;
use App\Models\Lamaran;
use App\Models\Lowongan;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index(Request $request)
    {
        Lowongan::updateExpiredStatus();

        $query = Lowongan::with(['fptk.departemen.kualifikasi'])
        ->withCount('lamaran')
        ->orderByRaw("CASE
            WHEN status = 'dibuka' THEN 0
            ELSE 1
        END")
        ->latest('tanggal_dibuka');

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('tipe_kerja')) {
            $query->where('tipe_kerja', $request->tipe_kerja);
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('departemen')) {
            $query->whereHas('fptk.departemen', function ($q) use ($request) {
                $q->where('id', $request->departemen);
            });
        }

        // Update Pagination menjadi 10
        $lowongans = $query->paginate(10)->withQueryString();

        $departemens = Departemen::orderBy('nama')->get();
        $totalLowongan = Lowongan::where('status', 'dibuka')->count();
        $totalDepartemen = Departemen::count();

        $previewLowongan = Lowongan::with(['fptk.departemen'])
            ->withCount('lamaran')
            ->where('status', 'dibuka')
            ->latest('tanggal_dibuka')
            ->take(3)
            ->get();

        $isProfileComplete = false;
        $appliedLowonganIds = collect();

        if (auth()->check() && auth()->user()->pelamar) {
            $p = auth()->user()->pelamar;

            $isProfileComplete =
                filled($p->jenis_kelamin) &&
                filled($p->no_hp) &&
                filled($p->alamat) &&
                filled($p->pendidikan_terakhir) &&
                filled($p->pengalaman_kerja) &&
                filled($p->foto) &&
                filled($p->file_ktp) &&
                filled($p->file_kk) &&
                filled($p->file_cv) &&
                filled($p->file_ijazah);
                $appliedLowonganIds = Lamaran::where('pelamar_id', $p->id)
                ->whereIn('status', [
                    'baru',
                    'screening_hrd',
                    'dikirim_ke_hod',
                    'screening_hod',
                    'menunggu_interview',
                    'interview',
                ])
                ->pluck('lowongan_id');
        }

        return view('careers', compact(
            'lowongans',
            'departemens',
            'totalLowongan',
            'totalDepartemen',
            'previewLowongan',
            'isProfileComplete',
            'appliedLowonganIds'
        ));
    }
}