<?php

namespace App\Http\Controllers\Hod;

use App\Http\Controllers\Controller;
use App\Models\Lamaran;
use App\Services\LamaranService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private LamaranService $lamaranService
    ) {
    }

    public function index()
    {
        $data = $this->lamaranService->getDashboardHod(auth()->user());

        return view('hod.pages.dashboard', $data);
    }

    public function setujui($id)
    {
        $lamaran = $this->cariLamaranHod($id);

        if (!in_array($lamaran->status, ['dikirim_ke_hod', 'screening_hod'])) {
            return response()->json([
                'message' => 'Status lamaran tidak dapat disetujui.',
            ], 422);
        }

        $statusLama = $lamaran->status;

        $lamaran->update([
            'status' => 'menunggu_interview',
            'tanggal_screening_hod' => now(),
        ]);

        $this->simpanHistory(
            $lamaran,
            $statusLama,
            'menunggu_interview',
            'setujui'
        );

        return response()->json([
            'message' => 'Kandidat berhasil disetujui.',
        ]);
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'catatan' => ['required', 'string', 'max:500'],
        ]);

        $lamaran = $this->cariLamaranHod($id);

        if (!in_array($lamaran->status, ['dikirim_ke_hod', 'screening_hod'])) {
            return response()->json([
                'message' => 'Status lamaran tidak dapat ditolak.',
            ], 422);
        }

        $statusLama = $lamaran->status;

        $lamaran->update([
            'status' => 'ditolak_hod',
            'catatan_hod' => $request->catatan,
            'tanggal_screening_hod' => now(),
        ]);

        $this->simpanHistory(
            $lamaran,
            $statusLama,
            'ditolak_hod',
            'tolak',
            $request->catatan
        );

        return response()->json([
            'message' => 'Kandidat berhasil ditolak.',
        ]);
    }

    public function kirimKeHrd($id)
    {
        $lamaran = $this->cariLamaranHod($id);

        if ($lamaran->status !== 'menunggu_interview') {
            return response()->json([
                'message' => 'Kandidat belum dapat dikirim ke HRD.',
            ], 422);
        }

        $statusLama = $lamaran->status;

        $lamaran->update([
            'status' => 'interview',
        ]);

        $this->simpanHistory(
            $lamaran,
            $statusLama,
            'interview',
            'kirim_hrd'
        );

        return response()->json([
            'message' => 'Kandidat berhasil dikirim ke HRD.',
        ]);
    }

    private function cariLamaranHod($id): Lamaran
    {
        return Lamaran::where('id', $id)
            ->whereHas('lowongan.fptk', function ($query) {
                $query->where('hod_id', auth()->id());
            })
            ->firstOrFail();
    }

    private function simpanHistory(
        Lamaran $lamaran,
        string $statusLama,
        string $statusBaru,
        string $aksi,
        ?string $catatan = null
    ): void {
        $lamaran->histories()->create([
            'status_lama' => $statusLama,
            'status_baru' => $statusBaru,
            'aksi' => $aksi,
            'catatan' => $catatan,
            'changed_by' => auth()->id(),
        ]);
    }
}