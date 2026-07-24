<?php

namespace App\Http\Controllers\Pelamar;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Models\Pelamar;
use Illuminate\Http\Request;

class LamaranController extends Controller
{
    protected array $statusBisaDibatalkan = [
        'baru',
        'screening_hrd',
        'dikirim_ke_hod',
        'screening_hod',
        'menunggu_interview',
    ];

    public function index(Request $request)
    {
        $pelamar = Pelamar::with('user')->where('user_id', auth()->id())->firstOrFail();

        $baseQuery = Lamaran::where('pelamar_id', $pelamar->id);

        $semuaLamaran = (clone $baseQuery)->get();

        $totalLamaran     = $semuaLamaran->count();
        $lamaranBaru      = $semuaLamaran->where('status', 'baru')->count();
        $lamaranDiproses  = $semuaLamaran->whereIn('status', ['screening_hrd', 'dikirim_ke_hod', 'screening_hod'])->count();
        $lamaranInterview = $semuaLamaran->whereIn('status', ['menunggu_interview', 'interview'])->count();
        $lamaranSelesai   = $semuaLamaran->where('status', 'selesai')->count();
        $lamaranDitolak   = $semuaLamaran->whereIn('status', ['ditolak_hrd', 'ditolak_hod'])->count();

        $query = (clone $baseQuery)->with('lowongan')->latest('tanggal_dilamar');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $lamarans = $query->paginate(10)->withQueryString();

        $statusBisaDibatalkan = $this->statusBisaDibatalkan;

        return view('pelamar.pages.lamaran', compact(
            'lamarans',
            'pelamar',
            'totalLamaran',
            'lamaranBaru',
            'lamaranDiproses',
            'lamaranInterview',
            'lamaranSelesai',
            'lamaranDitolak',
            'statusBisaDibatalkan',
        ));
    }

    public function destroy($id)
    {
        $pelamar = Pelamar::where('user_id', auth()->id())->firstOrFail();

        $lamaran = Lamaran::where('pelamar_id', $pelamar->id)->findOrFail($id);

        if (!in_array($lamaran->status, $this->statusBisaDibatalkan)) {
            return back()->with('error', 'Lamaran ini sudah tidak bisa dibatalkan karena sudah memasuki tahap interview atau selesai diproses.');
        }

        $lamaran->delete();

        return back()->with('success', 'Lamaran berhasil dibatalkan.');
    }
}