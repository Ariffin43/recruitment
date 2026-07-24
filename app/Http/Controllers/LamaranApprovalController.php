<?php

namespace App\Http\Controllers;

use App\Models\Lamaran;
use App\Models\LaporanHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LamaranApprovalController extends Controller
{
    private const SEMUA_STATUS = [
        'baru', 'screening_hrd', 'ditolak_hrd',
        'dikirim_ke_hod', 'screening_hod', 'ditolak_hod',
        'menunggu_interview', 'interview', 'selesai',
    ];

    public static function summaryLamaran(): array
    {
        return Lamaran::selectRaw('status, COUNT(*) as total')
            ->whereIn('status', self::SEMUA_STATUS)
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();
    }

    public static function countPendingHrd(): int
    {
        return Lamaran::whereIn('status', ['baru', 'screening_hrd', 'menunggu_interview', 'interview'])
            ->count();
    }

    public function advance(Request $request, $id)
    {
        $lamaran = Lamaran::with(['pelamar.user', 'lowongan.fptk.departemen'])->findOrFail($id);

        $transisi = [
            'baru'               => 'screening_hrd',
            'screening_hrd'      => 'dikirim_ke_hod',
            'menunggu_interview' => 'interview',
            'interview'          => 'selesai',
        ];

        if (!array_key_exists($lamaran->status, $transisi)) {
            return response()->json([
                'message' => 'Lamaran ini tidak dapat dimajukan dari status: ' . $lamaran->status,
            ], 422);
        }

        $validasi = ['catatan' => ['nullable', 'string', 'max:1000']];

        // Jika advance ke interview, wajib isi jadwal
        if ($lamaran->status === 'menunggu_interview') {
            $validasi['metode_interview']  = ['required', 'in:offline,online'];
            $validasi['lokasi_interview']  = ['nullable', 'string', 'max:255'];
            $validasi['link']              = ['nullable', 'url', 'max:255'];
            $validasi['tanggal_interview'] = ['required', 'date'];
        }

        $request->validate($validasi);

        $statusBaru  = $transisi[$lamaran->status];
        $statusLama  = $lamaran->status;
        $updateData  = ['status' => $statusBaru];

        // Catat timestamp per tahap
        $tsCols = [
            'screening_hrd'      => 'tanggal_screening_hrd',
            'dikirim_ke_hod'     => 'tanggal_dikirim_ke_hod',
            'interview'          => 'tanggal_interview',
        ];
        if (isset($tsCols[$statusBaru])) {
            $updateData[$tsCols[$statusBaru]] = now();
        }

        // Data tambahan untuk jadwal interview
        if ($lamaran->status === 'menunggu_interview') {
            $updateData['metode_interview']  = $request->metode_interview;
            $updateData['lokasi_interview']  = $request->lokasi_interview;
            $updateData['link']              = $request->link;
            $updateData['catatan_interview'] = $request->catatan;
        } elseif ($lamaran->status === 'screening_hrd') {
            $updateData['catatan_hrd'] = $request->catatan;
        }

        if ($lamaran->status === 'interview') {
            $validasiHasil = ['hasil_akhir' => ['required', 'in:diterima,ditolak,cadangan']];
            $request->validate($validasiHasil);
            $updateData['hasil_akhir']        = $request->hasil_akhir;
            $updateData['catatan_interview']  = $request->catatan;
        }

        $lamaran->update($updateData);

        // Catat history
        $this->catatHistory($lamaran->id, $statusLama, $statusBaru, 'advance', $request->catatan);

        $pesanMap = [
            'screening_hrd'  => "Lamaran {$lamaran->pelamar->user->nama} mulai diproses.",
            'dikirim_ke_hod' => "Lamaran {$lamaran->pelamar->user->nama} berhasil dikirim ke HOD.",
            'interview'      => "Jadwal interview {$lamaran->pelamar->user->nama} berhasil disimpan.",
            'selesai'        => "Proses rekrutmen {$lamaran->pelamar->user->nama} telah diselesaikan.",
        ];

        return response()->json([
            'message'    => $pesanMap[$statusBaru] ?? 'Status lamaran berhasil diperbarui.',
            'status_baru' => $statusBaru,
        ]);
    }

    /* ──────────────────────────────────────────────────────
       Tolak lamaran — dipanggil via fetch() dari blade
       POST /hrd/lamaran-approval/{id}/tolak
    ────────────────────────────────────────────────────── */
    public function tolak(Request $request, $id)
    {
        $lamaran = Lamaran::with(['pelamar.user'])->findOrFail($id);

        $bisaDitolakHrd = ['baru', 'screening_hrd', 'interview'];

        if (!in_array($lamaran->status, $bisaDitolakHrd)) {
            return response()->json([
                'message' => 'Lamaran ini tidak dapat ditolak HRD dari status: ' . $lamaran->status,
            ], 422);
        }

        $request->validate([
            'catatan' => ['required', 'string', 'max:1000'],
        ], [
            'catatan.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $statusLama = $lamaran->status;

        $lamaran->update([
            'status'      => 'ditolak_hrd',
            'catatan_hrd' => $request->catatan,
            'hasil_akhir' => 'ditolak',
        ]);

        $this->catatHistory($lamaran->id, $statusLama, 'ditolak_hrd', 'tolak', $request->catatan);

        return response()->json([
            'message' => "Lamaran {$lamaran->pelamar->user->nama} telah ditolak oleh HRD.",
        ]);
    }

    /* ──────────────────────────────────────────────────────
       Helper: catat ke lamaran_histories
    ────────────────────────────────────────────────────── */
    private function catatHistory(int $lamaranId, string $statusLama, string $statusBaru, string $aksi, ?string $catatan): void
    {
        \DB::table('lamaran_histories')->insert([
            'lamaran_id'  => $lamaranId,
            'status_lama' => $statusLama,
            'status_baru' => $statusBaru,
            'aksi'        => $aksi,
            'catatan'     => $catatan,
            'changed_by'  => Auth::id(),
            'created_at'  => now(),
        ]);
    }
}