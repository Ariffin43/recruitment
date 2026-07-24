<?php

namespace App\Http\Controllers\Pelamar;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Lowongan;
use App\Models\Pelamar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $user    = auth()->user();
        $pelamar = Pelamar::where('user_id', $user->id)->first();

        $profileLengkap = $pelamar
            && $pelamar->jenis_kelamin
            && $pelamar->no_hp
            && $pelamar->alamat
            && $pelamar->pendidikan_terakhir
            && $pelamar->file_cv
            && $pelamar->file_ktp
            && $pelamar->file_kk;

        $totalLowonganAktif = Lowongan::where('status', 'dibuka')
            ->where('tanggal_ditutup', '>=', now()->toDateString())
            ->count();

        $lowongans = Lowongan::where('status', 'dibuka')
            ->where('tanggal_ditutup', '>=', now()->toDateString())
            ->latest()
            ->limit(5)
            ->get();

        $sudahDilamarIds = $pelamar
            ? Lamaran::where('pelamar_id', $pelamar->id)->pluck('lowongan_id')
            : collect();

        $riwayatLamaran = $pelamar
            ? Lamaran::with('lowongan')
                ->where('pelamar_id', $pelamar->id)
                ->latest('tanggal_dilamar')
                ->get()
            : collect();

        $riwayatTerbaru = $riwayatLamaran->take(5);

        $totalLamaran     = $riwayatLamaran->count();
        $lamaranBaru      = $riwayatLamaran->where('status', 'baru')->count();
        $lamaranDiproses  = $riwayatLamaran->whereIn('status', ['screening_hrd', 'dikirim_ke_hod', 'screening_hod'])->count();
        $lamaranInterview = $riwayatLamaran->whereIn('status', ['menunggu_interview', 'interview'])->count();
        $lamaranSelesai   = $riwayatLamaran->where('status', 'selesai')->count();
        $lamaranDitolak   = $riwayatLamaran->whereIn('status', ['ditolak_hrd', 'ditolak_hod'])->count();

        $ringkasanStatus = [
            ['label' => 'Baru',      'total' => $lamaranBaru,      'dot' => 'bg-slate-400'],
            ['label' => 'Diproses',  'total' => $lamaranDiproses,  'dot' => 'bg-blue-500'],
            ['label' => 'Interview', 'total' => $lamaranInterview, 'dot' => 'bg-violet-500'],
            ['label' => 'Diterima',  'total' => $lamaranSelesai,   'dot' => 'bg-emerald-500'],
            ['label' => 'Ditolak',   'total' => $lamaranDitolak,   'dot' => 'bg-red-400'],
        ];

        return view('pelamar.pages.dashboard', compact(
            'profileLengkap',
            'lowongans',
            'totalLowonganAktif',
            'sudahDilamarIds',
            'riwayatLamaran',
            'riwayatTerbaru',
            'totalLamaran',
            'lamaranDiproses',
            'lamaranInterview',
            'lamaranSelesai',
            'lamaranDitolak',
            'ringkasanStatus',
        ));
    }

    public function lamar(Request $request, $lowonganId)
    {
        $user    = auth()->user();
        $pelamar = Pelamar::where('user_id', $user->id)->first();

        if (!$pelamar || !$pelamar->jenis_kelamin || !$pelamar->no_hp || !$pelamar->alamat || !$pelamar->pendidikan_terakhir || !$pelamar->file_cv || !$pelamar->file_kk || !$pelamar->file_ktp) {
            return back()->with('error', 'Lengkapi profil kamu terlebih dahulu sebelum melamar.');
        }

        $lowongan = Lowongan::where('id', $lowonganId)
        ->where('status', 'dibuka')
        ->firstOrFail();

        $masihAdaLamaranAktif = Lamaran::where('lowongan_id', $lowongan->id)
        ->where('pelamar_id', $pelamar->id)
        ->whereIn('status', [
            'baru',
            'screening_hrd',
            'dikirim_ke_hod',
            'screening_hod',
            'menunggu_interview',
            'interview',
        ])
        ->exists();

        if ($masihAdaLamaranAktif) {
            return back()->with('error', 'Kamu sudah melamar pada lowongan ini.');
        }

        Lamaran::create([
            'nomor_lamaran'   => 'LAM-' . now()->format('Y') . '-' . strtoupper(Str::random(6)),
            'lowongan_id'     => $lowongan->id,
            'pelamar_id'      => $pelamar->id,
            'status'          => 'baru',
            'tanggal_dilamar' => now(),
            'metode_interview'=> 'offline',
        ]);

        return back()->with('success', 'Lamaran berhasil dikirim! Pantau statusnya di riwayat lamaran.');
    }
}