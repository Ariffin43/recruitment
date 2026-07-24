@extends('pelamar.layouts.app')

@section('title', 'Lamaran Saya')
@section('page_title', 'Lamaran Saya')
@section('page_subtitle', 'Pantau status dan perkembangan setiap lamaran yang telah kamu kirim.')

@section('content')

    @include('components.modal-detail')

    @php
        $statusConfig = [
            'baru'               => ['bg-slate-100 text-slate-500', 'Baru', 1],
            'screening_hrd'      => ['bg-blue-50 text-blue-600', 'Screening HRD', 2],
            'ditolak_hrd'        => ['bg-red-50 text-red-500', 'Ditolak HRD', 2],
            'dikirim_ke_hod'     => ['bg-indigo-50 text-indigo-600', 'Dikirim ke HOD', 3],
            'screening_hod'      => ['bg-purple-50 text-purple-600', 'Screening HOD', 4],
            'ditolak_hod'        => ['bg-red-50 text-red-500', 'Ditolak HOD', 4],
            'menunggu_interview' => ['bg-amber-50 text-amber-600', 'Menunggu Interview', 5],
            'interview'          => ['bg-[#7C3AED]/10 text-[#7C3AED]', 'Interview', 6],
            'selesai'            => ['bg-emerald-50 text-emerald-600', 'Selesai', 7],
        ];

        $hasilConfig = [
            'diterima' => 'Diterima',
            'ditolak'  => 'Ditolak',
            'cadangan' => 'Cadangan',
        ];

        $filterOptions = [
            ''                   => 'Semua Status',
            'baru'               => 'Baru',
            'screening_hrd'      => 'Screening HRD',
            'dikirim_ke_hod'     => 'Dikirim ke HOD',
            'screening_hod'      => 'Screening HOD',
            'menunggu_interview' => 'Menunggu Interview',
            'interview'          => 'Interview',
            'selesai'            => 'Selesai',
            'ditolak_hrd'        => 'Ditolak',
        ];

        $stageOrder = [
            1 => 'Lamaran Dikirim',
            2 => 'Screening HRD',
            3 => 'Dikirim ke HOD',
            4 => 'Screening HOD',
            5 => 'Menunggu Interview',
            6 => 'Interview',
            7 => 'Selesai',
        ];

        // Bangun data untuk modal detail (dipakai oleh components.modal-detail)
        $kandidatData = $lamarans->getCollection()->mapWithKeys(function ($lamaran) use ($statusConfig, $hasilConfig, $stageOrder, $pelamar) {
            $cfg = $statusConfig[$lamaran->status] ?? ['bg-slate-100 text-slate-500', ucfirst($lamaran->status), 1];
            [, $badgeLabel, $stepAt] = $cfg;

            $rejectedAt = $lamaran->status === 'ditolak_hrd' ? 2 : ($lamaran->status === 'ditolak_hod' ? 4 : null);

            $hasilLabel = ($lamaran->hasil_akhir && isset($hasilConfig[$lamaran->hasil_akhir]))
                ? $hasilConfig[$lamaran->hasil_akhir]
                : null;

            // Susun array progress sesuai kontrak renderTimeline() di components/timeline-progress
            $progress = [];
            foreach ($stageOrder as $num => $label) {
                if ($rejectedAt !== null && $num > $rejectedAt) {
                    break;
                }

                $catatan = null;
                $tanggal = null;

                if ($num === 1) {
                    $tanggal = \Carbon\Carbon::parse($lamaran->tanggal_dilamar)->translatedFormat('d M Y');
                } elseif ($num === 2) {
                    $catatan = $lamaran->catatan_hrd;
                } elseif ($num === 4) {
                    $catatan = $lamaran->catatan_hod;
                } elseif ($num === 6) {
                    $catatan = $lamaran->catatan_interview;
                    $tanggal = $lamaran->tanggal_interview
                        ? \Carbon\Carbon::parse($lamaran->tanggal_interview)->translatedFormat('d M Y, H:i')
                            . ($lamaran->metode_interview ? ' · ' . ucfirst($lamaran->metode_interview) : '')
                        : null;
                } elseif ($num === 7) {
                    $catatan = $hasilLabel
                        ? 'Hasil akhir: ' . $hasilLabel
                        : ($lamaran->status === 'selesai' ? 'Proses rekrutmen telah selesai.' : null);
                }

                if ($rejectedAt !== null && $num === $rejectedAt) {
                    $label = 'Ditolak ' . ($rejectedAt === 2 ? 'HRD' : 'HOD');
                    $status = 'selesai';
                } elseif  ($lamaran->status === 'selesai') {
                    $status = 'selesai';
                }
                else {
                    $status = $num < $stepAt ? 'selesai' : ($num === $stepAt ? 'proses' : 'menunggu');
                }

                $progress[] = [
                    'tahap'   => $label,
                    'status'  => $status,
                    'catatan' => $catatan,
                    'tanggal' => $tanggal,
                ];
            }

            return [
                $lamaran->id => [
                    'id'                => $lamaran->id,
                    'nama'              => $pelamar->user->nama ?? '-',
                    'email'             => $pelamar->user->email ?? '-',
                    'no_hp'             => $pelamar->no_hp,
                    'posisi_dibutuhkan' => $lamaran->lowongan->judul,
                    'tahap'             => $badgeLabel,
                    'step'              => $stepAt,
                    'tgl'               => $lamaran->tanggal_dilamar,
                    'dokumen'           => [
                        'ktp' => $pelamar->file_ktp,
                        'kk'  => $pelamar->file_kk,
                        'cv'  => $pelamar->file_cv,
                        'ijazah'     => $pelamar->file_ijazah,
                        'sertifikat' => $pelamar->file_sertifikat,
                    ],
                    'progress' => $progress,
                    'viewOnly' => true,
                ],
            ];
        });
    @endphp

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Total Lamaran</p>
            <p class="text-2xl font-bold text-slate-800 mt-1">{{ $totalLamaran }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Diproses</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $lamaranDiproses }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Interview</p>
            <p class="text-2xl font-bold mt-1" style="color:#7C3AED">{{ $lamaranInterview }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Selesai</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $lamaranSelesai }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4 col-span-2 sm:col-span-1">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Ditolak</p>
            <p class="text-2xl font-bold text-red-500 mt-1">{{ $lamaranDitolak }}</p>
        </div>
    </div>

    {{-- Filter Status (dropdown) --}}
    <div class="flex items-center justify-between mb-6">
        <div class="relative w-full sm:w-64">
            <select id="filterStatus"
                onchange="if (this.value) { window.location.href = this.value } else { window.location.href = '{{ route('pelamar.lamaran') }}' }"
                class="w-full appearance-none bg-white border border-slate-200 rounded-lg pl-4 pr-10 py-2.5 text-sm font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-[#7C3AED]/30 focus:border-[#7C3AED] transition-colors cursor-pointer">
                @foreach ($filterOptions as $value => $label)
                    <option value="{{ route('pelamar.lamaran', $value ? ['status' => $value] : []) }}"
                        {{ request('status', '') === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            <i class="bi bi-funnel absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none"></i>
        </div>

        <span class="hidden sm:block text-xs text-slate-400">
            Menampilkan {{ $lamarans->count() }} dari {{ $lamarans->total() }} lamaran
        </span>
    </div>

    {{-- Tabel Lamaran --}}
    <div class="bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden">

        @if ($lamarans->isEmpty())
            <div class="py-20 text-center text-slate-400">
                <i class="bi bi-inbox text-5xl block mb-3"></i>
                <p class="text-sm font-medium">Belum ada lamaran</p>
                <p class="text-xs mt-1">Kamu belum pernah melamar, atau tidak ada lamaran dengan filter ini.</p>
                <a href="{{ route('pelamar.dashboard') }}"
                    class="inline-flex items-center gap-1.5 mt-4 px-4 py-2 text-white text-xs font-medium rounded-lg transition-colors"
                    style="background-color:#7C3AED">
                    <i class="bi bi-briefcase"></i> Lihat Lowongan
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="text-left text-xs text-slate-400 uppercase tracking-wide border-b border-slate-100 bg-slate-50/60">
                            <th class="px-6 py-3 font-medium w-10 border-r border-slate-100">No</th>
                            <th class="px-4 py-3 font-medium border-r border-slate-100">Posisi</th>
                            <th class="px-4 py-3 font-medium hidden sm:table-cell border-r border-slate-100">Tanggal</th>
                            <th class="px-4 py-3 font-medium border-r border-slate-100">Status</th>
                            <th class="px-4 py-3 font-medium border-r border-slate-100">Jadwal Interview</th>
                            <th class="px-6 py-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach ($lamarans as $index => $lamaran)
                            @php
                                [$badgeClass, $badgeLabel] = $statusConfig[$lamaran->status] ?? ['bg-slate-100 text-slate-500', ucfirst($lamaran->status)];
                                $bisaDibatalkan = in_array($lamaran->status, $statusBisaDibatalkan);
                                $nomorUrut = $lamarans->firstItem() + $index;

                                $tipeIcon = match (strtolower($lamaran->lowongan->tipe_kerja ?? '')) {
                                    'fulltime' => 'bi-briefcase-fill',
                                    'kontrak' => 'bi-file-earmark-text-fill',
                                    'magang' => 'bi-mortarboard-fill',
                                    default => 'bi-briefcase-fill',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-6 py-4 text-slate-400 text-xs align-top border-r border-slate-50">{{ $nomorUrut }}</td>
                                <td class="px-4 py-4 border-r border-slate-50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 text-[#7C3AED]" style="background:#f5f3ff">
                                            <i class="bi {{ $tipeIcon }} text-sm"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-medium text-slate-800 truncate max-w-[220px]">{{ $lamaran->lowongan->judul }}</p>
                                            <p class="text-xs text-slate-400 mt-0.5">No. {{ $lamaran->nomor_lamaran }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-slate-500 text-xs hidden sm:table-cell whitespace-nowrap border-r border-slate-50">
                                    {{ \Carbon\Carbon::parse($lamaran->tanggal_dilamar)->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-4 py-4 border-r border-slate-50">
                                    <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium whitespace-nowrap {{ $badgeClass }}">
                                        {{ $badgeLabel }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 border-r border-slate-50">
                                    @if ($lamaran->tanggal_interview)
                                        @php
                                            $metodeIcon = $lamaran->metode_interview === 'online' ? 'bi-camera-video-fill' : 'bi-geo-alt-fill';
                                            $metodeLabel = $lamaran->metode_interview === 'online' ? 'Online' : 'Offline';
                                        @endphp
                                        <div class="flex items-center gap-1.5">
                                            <div class="text-xs">
                                                <p class="font-medium text-slate-700 whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($lamaran->tanggal_interview)->translatedFormat('d M Y, H:i') }}
                                                </p>
                                                <p class="text-slate-400 flex items-center gap-1 mt-0.5">
                                                    <i class="bi {{ $metodeIcon }}"></i> {{ $metodeLabel }}
                                                </p>
                                            </div>
                                            <i class="bi bi-info-circle text-slate-400 hover:text-cesco-blue cursor-pointer text-sm"
                                                onclick="showInfo()">
                                            </i>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Belum dijadwalkan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <button onclick="openDetailKandidat({{ $lamaran->id }})"
                                        class="inline-flex border border-gray-300 items-center gap-1 px-3 py-1.5 rounded-lg text-gray-500 text-xs font-medium transition-colors mr-1 hover:opacity-90">
                                        <i class="bi bi-eye"></i> Detail
                                    </button>
                                    @if ($bisaDibatalkan)
                                        <button onclick="batalkanLamaran({{ $lamaran->id }}, '{{ addslashes($lamaran->lowongan->judul) }}')"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium transition-colors">
                                            <i class="bi bi-x-circle"></i> Batalkan
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($lamarans->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $lamarans->links() }}
                </div>
            @endif
        @endif

    </div>

    {{-- Form tersembunyi untuk submit pembatalan lamaran --}}
    <form id="formBatalkan" method="POST" action="" class="hidden">
        @csrf
        @method('DELETE')
    </form>

@endsection

@push('scripts')
    <script>
        window.kandidatData = @json($kandidatData);
        const BATALKAN_URL_TEMPLATE = "{{ route('pelamar.lamaran.destroy', ['id' => '__ID__']) }}";

        function batalkanLamaran(id, judulLowongan) {
            Swal.fire({
                icon: 'warning',
                title: 'Batalkan Lamaran?',
                html: `Lamaran untuk posisi <strong>${judulLowongan}</strong> akan dihapus permanen dan tidak bisa dikembalikan.`,
                confirmButtonText: 'Ya, Batalkan',
                showCancelButton: true,
                cancelButtonText: 'Tidak',
                confirmButtonColor: '#7C3AED',
                cancelButtonColor: '#94a3b8',
            }).then(result => {
                if (result.isConfirmed) {
                    const form = document.getElementById('formBatalkan');
                    form.action = BATALKAN_URL_TEMPLATE.replace('__ID__', id);
                    form.submit();
                }
            });
        }

        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', confirmButtonColor: '#7C3AED' });
        @endif

        @if (session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal', text: '{{ session('error') }}', confirmButtonColor: '#7C3AED' });
        @endif

        function showInfo() {
            Swal.fire({
                icon: 'info',
                title: 'Informasi Interview',
                text: 'Cek email kamu untuk jadwal interview lebih lengkapnya. Jika jadwal tidak ada di inbox, coba cek folder spam.',
                confirmButtonText: 'Mengerti'
            });
        }
    </script>
@endpush