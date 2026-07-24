<?php

namespace App\Http\Controllers\Hrd;

use App\Http\Controllers\Controller;
use App\Mail\InterviewScheduled;
use App\Models\Lamaran;
use App\Services\LamaranService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ApprovalPelamarController extends Controller
{
    public function __construct(
        private LamaranService $lamaranService
    ) {
    }

    public function index()
    {
        $data = $this->lamaranService->getDashboardHrd();

        return view('hrd.pages.approval-pelamar', $data);
    }

    public function setujui($id)
    {
        $lamaran = Lamaran::findOrFail($id);

        if (!in_array($lamaran->status, ['baru', 'screening_hrd'], true)) {
            return response()->json([
                'message' => 'Status lamaran tidak dapat disetujui oleh HRD.',
            ], 422);
        }

        $statusLama = $lamaran->status;

        DB::transaction(function () use ($lamaran, $statusLama) {
            $lamaran->update([
                'status' => 'dikirim_ke_hod',
                'tanggal_screening_hrd' => now(),
                'tanggal_dikirim_ke_hod' => now(),
            ]);

            $this->simpanHistory(
                $lamaran,
                $statusLama,
                'dikirim_ke_hod',
                'setujui'
            );
        });

        return response()->json([
            'message' => 'Kandidat berhasil disetujui dan dikirim ke HOD.',
        ]);
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'catatan' => ['required', 'string', 'max:500'],
        ]);

        $lamaran = Lamaran::findOrFail($id);

        if (!in_array($lamaran->status, ['baru', 'screening_hrd'], true)) {
            return response()->json([
                'message' => 'Status lamaran tidak dapat ditolak oleh HRD.',
            ], 422);
        }

        $statusLama = $lamaran->status;

        DB::transaction(function () use ($lamaran, $statusLama, $request) {
            $lamaran->update([
                'status' => 'ditolak_hrd',
                'catatan_hrd' => $request->catatan,
                'tanggal_screening_hrd' => now(),
            ]);

            $this->simpanHistory(
                $lamaran,
                $statusLama,
                'ditolak_hrd',
                'tolak',
                $request->catatan
            );
        });

        return response()->json([
            'message' => 'Kandidat berhasil ditolak oleh HRD.',
        ]);
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

    public function simpanInterview(Request $request, $id)
    {
        $lamaran = Lamaran::findOrFail($id);

        if ($lamaran->status !== 'interview') {
            return response()->json([
                'message' => 'Kandidat belum berada pada tahap interview.',
            ], 422);
        }

        $jadwalInterview = Carbon::createFromFormat(
            'Y-m-d H:i',
            $request->tanggal_interview . ' ' . substr($request->jam_interview, 0, 5)
        );

        if ($jadwalInterview->lessThanOrEqualTo(now())) {
            return response()->json([
                'message' => 'Jadwal interview harus lebih besar dari waktu sekarang.',
            ], 422);
        }

        DB::transaction(function () use ($request, $lamaran, $jadwalInterview) {
            $lamaran->update([
                'tanggal_interview' => $jadwalInterview,
                'metode_interview'  => $request->metode_interview,
                'link'              => $request->metode_interview === 'online'
                                        ? $request->link
                                        : null,
                'lokasi_interview'  => $request->metode_interview === 'offline'
                                        ? $request->lokasi_interview
                                        : null,
                'catatan_interview' => $request->catatan_interview,
                'status'            => 'selesai',
            ]);

            $lamaran->histories()->create([
                'status_lama' => 'interview',
                'status_baru' => 'selesai',
                'aksi'        => 'jadwalkan_interview',
                'catatan'     => $this->buatCatatanJadwalInterview($request, $jadwalInterview),
                'changed_by'  => auth()->id(),
            ]);

            $lamaran->refresh()->load('pelamar');
            Mail::to($lamaran->pelamar->user->email)->send(new InterviewScheduled($lamaran));
        });

        return response()->json([
            'message' => 'Jadwal interview berhasil disimpan dan email telah dikirim ke pelamar.',
        ]);
    }

    private function buatCatatanJadwalInterview(
        Request $request,
        Carbon $jadwal
    ): string {
        $detailTempat = $request->metode_interview === 'online'
            ? 'Link: ' . $request->link
            : 'Lokasi: ' . $request->lokasi_interview;

        $catatan = $request->catatan_interview
            ? ' Catatan: ' . $request->catatan_interview
            : '';

        return sprintf(
            'Interview %s dijadwalkan pada %s. %s.%s',
            ucfirst($request->metode_interview),
            $jadwal->translatedFormat('d F Y, H.i'),
            $detailTempat,
            $catatan
        );
    }
}
