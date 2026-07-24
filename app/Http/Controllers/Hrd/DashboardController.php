<?php

namespace App\Http\Controllers\Hrd;

use App\Http\Controllers\Controller;
use App\Models\Departemen;
use App\Models\Fptk;
use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $akunPelamarMenunggu = User::where('role', 'pelamar')
            ->where('status', 'pending')
            ->count();

        $fptkMenungguHrd = Fptk::where('status', 'approved_gm')
            ->count();

        $lamaranMasuk = Lamaran::whereIn('status', ['baru', 'screening_hrd'])
            ->count();

        $lowonganAktif = Lowongan::where('status', 'dibuka')
            ->count();

        $totalDepartemen = Departemen::count();

        $totalHod = User::where('role', 'hod')
            ->where('status', 'aktif')
            ->count();

        $antrianFptk = Fptk::where('status', 'approved_gm')
            ->latest('gm_approved_at')
            ->limit(5)
            ->get();

        $antrianLamaran = Lamaran::with([
            'pelamar.user',
            'lowongan.fptk',
        ])
            ->whereIn('status', ['baru', 'screening_hrd'])
            ->latest('tanggal_dilamar')
            ->limit(5)
            ->get();

        $antrianAkunPelamar = User::where('role', 'pelamar')
            ->where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        $fptkSudahAdaLowongan = Lowongan::pluck('fptk_id')
            ->filter()
            ->unique();

        $fptkSiapLowongan = Fptk::where('status', 'approved_hrd')
            ->whereNotIn('id', $fptkSudahAdaLowongan)
            ->latest('hrd_approved_at')
            ->limit(5)
            ->get();

        return view('hrd.pages.dashboard', compact(
            'akunPelamarMenunggu',
            'fptkMenungguHrd',
            'lamaranMasuk',
            'lowonganAktif',
            'totalDepartemen',
            'totalHod',
            'antrianFptk',
            'antrianLamaran',
            'antrianAkunPelamar',
            'fptkSiapLowongan'
        ));
    }
}
