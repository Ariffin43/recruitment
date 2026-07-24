<?php

namespace App\Services;

use App\Models\Lamaran;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class LamaranService
{
    private const LABELS = [
        'baru' => 'Baru',
        'screening_hrd' => 'Screening HRD',
        'ditolak_hrd' => 'Ditolak HRD',
        'dikirim_ke_hod' => 'Dikirim ke HOD',
        'screening_hod' => 'Screening HOD',
        'ditolak_hod' => 'Ditolak HOD',
        'menunggu_interview' => 'Menunggu Interview',
        'interview' => 'Interview',
        'selesai' => 'Selesai',
    ];

    private const STEPS = [
        'baru' => 1,
        'screening_hrd' => 2,
        'ditolak_hrd' => 2,
        'dikirim_ke_hod' => 3,
        'screening_hod' => 4,
        'ditolak_hod' => 4,
        'menunggu_interview' => 5,
        'interview' => 6,
        'selesai' => 7,
    ];

    private const DEFAULT_NOTES = [
        'baru' => 'Lamaran masuk ke sistem.',
        'screening_hrd' => 'HRD melakukan pemeriksaan awal lamaran.',
        'dikirim_ke_hod' => 'HRD menyetujui dan mengirim lamaran ke HOD.',
        'screening_hod' => 'HOD melakukan pemeriksaan kandidat.',
        'menunggu_interview' => 'Kandidat disetujui HOD dan menunggu proses interview.',
        'interview' => 'Kandidat masuk ke proses interview oleh HRD.',
        'selesai' => 'Proses rekrutmen kandidat selesai.',
        'ditolak_hrd' => 'Lamaran ditolak oleh HRD.',
        'ditolak_hod' => 'Lamaran ditolak oleh HOD.',
    ];

    private const MAIN_FLOW = [
        'baru',
        'screening_hrd',
        'dikirim_ke_hod',
        'screening_hod',
        'menunggu_interview',
        'interview',
        'selesai',
    ];

    public function getDashboardHrd(): array
    {
        $kandidat = Lamaran::with([
            'pelamar.user',
            'lowongan.fptk',
            'histories',
        ])
            ->latest('tanggal_dilamar')
            ->get()
            ->map(fn (Lamaran $lamaran) => $this->formatKandidat($lamaran, 'hrd'));

        return [
            'kandidat' => $kandidat,
            'totalKandidat' => $kandidat->count(),
            'menungguReview' => $kandidat->where('canApprove', true)->count(),
            'disetujui' => $kandidat->where('approvedByRole', true)->count(),
            'ditolak' => $kandidat->where('rejectedByRole', true)->count(),
            'tahapStyle' => $this->tahapStyle(),
        ];
    }

    public function getDashboardHod(User $user): array
    {
        $kandidat = Lamaran::with([
            'pelamar.user',
            'lowongan.fptk',
            'histories',
        ])
            ->whereHas('lowongan.fptk', function ($query) use ($user) {
                $query->where('hod_id', $user->id);
            })
            ->latest('tanggal_dilamar')
            ->get()
            ->map(fn (Lamaran $lamaran) => $this->formatKandidat($lamaran, 'hod'));

        return [
            'kandidat' => $kandidat,
            'totalKandidat' => $kandidat->count(),
            'menungguReview' => $kandidat->where('canApprove', true)->count(),
            'disetujui' => $kandidat->where('approvedByRole', true)->count(),
            'ditolak' => $kandidat->where('rejectedByRole', true)->count(),
            'tahapStyle' => $this->tahapStyle(),
        ];
    }

    private function formatKandidat(Lamaran $lamaran, string $role): array
    {
        
        $pelamar = $lamaran->pelamar;
        $user = $pelamar?->user;
        $fptk = $lamaran->lowongan?->fptk;

        $isRejected = in_array(
            $lamaran->status,
            ['ditolak_hrd', 'ditolak_hod'],
            true
        );

        if ($role === 'hrd') {
            $canApprove = in_array(
                $lamaran->status,
                ['baru', 'screening_hrd'],
                true
            );

            $approvedByRole = in_array(
                $lamaran->status,
                [
                    'dikirim_ke_hod',
                    'screening_hod',
                    'menunggu_interview',
                    'interview',
                    'selesai',
                ],
                true
            );

            $rejectedByRole = $lamaran->status === 'ditolak_hrd';
            $canSendHrd = false;
        } else {
            $canApprove = in_array(
                $lamaran->status,
                ['dikirim_ke_hod', 'screening_hod'],
                true
            );

            $approvedByRole = in_array(
                $lamaran->status,
                ['menunggu_interview', 'interview', 'selesai'],
                true
            );

            $rejectedByRole = $lamaran->status === 'ditolak_hod';
            $canSendHrd = $lamaran->status === 'menunggu_interview';
        }

        return [
            'id' => $lamaran->id,
            'nomor_lamaran' => $lamaran->nomor_lamaran ?? '-',
            'nomor_fptk' => $fptk?->nomor_fptk ?? '-',

            'nama' => $user?->nama ?? '-',
            'email' => $user?->email ?? '-',
            'no_hp' => $pelamar?->no_hp ?? '-',

            'posisi' => $fptk?->posisi_dibutuhkan ?? '-',
            'posisi_dibutuhkan' => $fptk?->posisi_dibutuhkan ?? '-',

            'tgl' => optional($lamaran->tanggal_dilamar)->format('Y-m-d'),
            'tahap' => $this->label($lamaran->status),
            'step' => $this->step($lamaran->status),

            'isApproved' => $approvedByRole,
            'isRejected' => $isRejected,
            'approvedByRole' => $approvedByRole,
            'rejectedByRole' => $rejectedByRole,

            'canApprove' => $canApprove,
            'canSendHrd' => $canSendHrd,

            'catatan_hrd' => $lamaran->catatan_hrd,
            'catatan_hod' => $lamaran->catatan_hod,
            
            'canScheduleInterview' => $role === 'hrd' && $lamaran->status === 'interview' && is_null($lamaran->tanggal_interview),
            'interviewScheduled' => !is_null($lamaran->tanggal_interview),

            'interview' => [
                'metode' => $lamaran->metode_interview,
                'tanggal' => $lamaran->tanggal_interview
                    ? Carbon::parse($lamaran->tanggal_interview)
                        ->format('Y-m-d')
                    : null,
                'jam' => $lamaran->tanggal_interview
                    ? Carbon::parse($lamaran->tanggal_interview)
                        ->format('H:i')
                    : null,
                'link' => $lamaran->link,
                'lokasi' => $lamaran->lokasi_interview,
                'catatan' => $lamaran->catatan_interview,
            ],

            'dokumen' => [
                'ktp' => $pelamar?->file_ktp,
                'kk' => $pelamar?->file_kk,
                'cv' => $pelamar?->file_cv,
                'ijazah' => $pelamar?->file_ijazah,
                'sertifikat' => $pelamar?->file_sertifikat,
            ],

            'progress' => $this->buatTimelineProgress($lamaran),
        ];
    }

    private function buatTimelineProgress(Lamaran $lamaran): array
    {
        $history = $lamaran->histories
            ->sortBy('created_at')
            ->keyBy('status_baru');

        $tanggal = [
            'baru' => $lamaran->tanggal_dilamar,

            'screening_hrd' =>
                $lamaran->tanggal_screening_hrd
                ?? $history->get('screening_hrd')?->created_at,

            'dikirim_ke_hod' =>
                $lamaran->tanggal_dikirim_ke_hod
                ?? $history->get('dikirim_ke_hod')?->created_at,

            'screening_hod' =>
                $history->get('screening_hod')?->created_at,

            'menunggu_interview' =>
                $history->get('menunggu_interview')?->created_at
                ?? $lamaran->tanggal_screening_hod,

            'interview' =>
                $history->get('interview')?->created_at
                ?? $lamaran->tanggal_interview,

            'selesai' =>
                $history->get('selesai')?->created_at,

            'ditolak_hrd' =>
                $history->get('ditolak_hrd')?->created_at
                ?? $lamaran->tanggal_screening_hrd,

            'ditolak_hod' =>
                $history->get('ditolak_hod')?->created_at
                ?? $lamaran->tanggal_screening_hod,
        ];

        $flow = match ($lamaran->status) {
            'ditolak_hrd' => [
                'baru',
                'screening_hrd',
                'ditolak_hrd',
            ],

            'ditolak_hod' => [
                'baru',
                'screening_hrd',
                'dikirim_ke_hod',
                'ditolak_hod',
            ],

            default => self::MAIN_FLOW,
        };

        $currentStep = $this->step($lamaran->status);

        return collect($flow)
            ->map(function ($status) use (
                $lamaran,
                $history,
                $tanggal,
                $currentStep
            ) {
                return [
                    'tahap' => $this->label($status),

                    'status' => $this->statusTimeline(
                        $status,
                        $lamaran->status,
                        $this->step($status),
                        $currentStep
                    ),

                    'catatan' =>
                        $history->get($status)?->catatan
                        ?? self::DEFAULT_NOTES[$status]
                        ?? null,

                    'tanggal' =>
                        $this->formatTanggal($tanggal[$status] ?? null),
                ];
            })
            ->values()
            ->toArray();
    }

    private function statusTimeline( string $status, string $currentStatus, int $step, int $currentStep ): string {
        if (
            in_array($currentStatus, ['ditolak_hrd', 'ditolak_hod'], true)
            && $status === $currentStatus
        ) {
            return 'proses';
        }

        if ($currentStatus === 'selesai' && $step <= $currentStep) {
            return 'selesai';
        }

        if ($step < $currentStep) {
            return 'selesai';
        }

        if ($step === $currentStep) {
            return 'proses';
        }

        return 'menunggu';
    }

    private function label(string $status): string
    {
        return self::LABELS[$status] ?? 'Baru';
    }

    private function step(string $status): int
    {
        return self::STEPS[$status] ?? 1;
    }

    private function formatTanggal($tanggal): ?string
    {
        return $tanggal
            ? Carbon::parse($tanggal)->translatedFormat('d F Y, H.i')
            : null;
    }

    private function tahapStyle(): array
    {
        return [
            'Baru' =>
                'bg-slate-100 text-slate-700 border border-slate-200',

            'Screening HRD' =>
                'bg-blue-50 text-blue-700 border border-blue-200',

            'Ditolak HRD' =>
                'bg-red-50 text-red-600 border border-red-200',

            'Dikirim ke HOD' =>
                'bg-orange-50 text-orange-700 border border-orange-200',

            'Screening HOD' =>
                'bg-indigo-50 text-indigo-700 border border-indigo-200',

            'Ditolak HOD' =>
                'bg-red-50 text-red-600 border border-red-200',

            'Menunggu Interview' =>
                'bg-teal-50 text-teal-700 border border-teal-200',

            'Interview' =>
                'bg-purple-50 text-purple-700 border border-purple-200',

            'Selesai' =>
                'bg-emerald-100 text-emerald-800 border border-emerald-300',
        ];
    }
}
