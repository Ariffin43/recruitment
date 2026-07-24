@extends('pelamar.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Selamat datang, ' . auth()->user()->nama . '. Temukan lowongan kerja yang sesuai untukmu.')

@section('content')

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
            <p class="text-2xl font-bold text-violet-600 mt-1">{{ $lamaranInterview }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Diterima</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $lamaranSelesai }}</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-4 col-span-2 sm:col-span-1">
            <p class="text-xs font-medium text-slate-400 uppercase tracking-wide">Ditolak</p>
            <p class="text-2xl font-bold text-red-500 mt-1">{{ $lamaranDitolak }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom Utama --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Lowongan Tersedia --}}
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                    <h2 class="font-semibold text-slate-700 flex items-center gap-2">
                        <i class="bi bi-briefcase text-blue-500"></i>
                        Lowongan Tersedia
                    </h2>
                    <span class="text-xs text-slate-400">{{ $totalLowonganAktif }} aktif</span>
                </div>

                @if ($lowongans->isEmpty())
                    <div class="py-16 text-center text-slate-400">
                        <i class="bi bi-inbox text-4xl block mb-2"></i>
                        <p class="text-sm">Belum ada lowongan yang tersedia saat ini.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs text-slate-400 uppercase tracking-wide border-b border-slate-100">
                                    <th class="px-6 py-3 font-medium w-10">No</th>
                                    <th class="px-4 py-3 font-medium">Posisi</th>
                                    <th class="px-4 py-3 font-medium hidden md:table-cell">Lokasi</th>
                                    <th class="px-4 py-3 font-medium hidden sm:table-cell">Tipe</th>
                                    <th class="px-4 py-3 font-medium">Tutup</th>
                                    <th class="px-6 py-3 font-medium text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($lowongans as $lowongan)
                                    @php
                                        $sudahDilamar = $sudahDilamarIds->contains($lowongan->id);
                                        $deptName = $lowongan->fptk?->departemen?->nama;

                                        $tipeClass = match (strtolower($lowongan->tipe_kerja ?? '')) {
                                            'fulltime' => 'bg-blue-50 text-blue-600',
                                            'kontrak' => 'bg-amber-50 text-amber-600',
                                            'magang' => 'bg-violet-50 text-violet-600',
                                            default => 'bg-slate-100 text-slate-500',
                                        };

                                        $tanggalTutup = \Carbon\Carbon::parse($lowongan->tanggal_ditutup);
                                        $sisaHari = (int) now()->startOfDay()->diffInDays($tanggalTutup->copy()->startOfDay(), false);
                                        $isUrgent = $sisaHari >= 0 && $sisaHari <= 7;
                                    @endphp
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="px-6 py-4 text-slate-400 text-xs align-top">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-4">
                                            <p class="font-medium text-slate-800">{{ $lowongan->judul }}</p>
                                            <p class="text-xs text-slate-400 mt-0.5 flex items-center gap-1">
                                                @if ($deptName)
                                                    <i class="bi bi-diagram-3"></i> {{ $deptName }}
                                                @endif
                                                <span class="sm:hidden">
                                                    &middot; {{ ucfirst($lowongan->tipe_kerja) }}
                                                </span>
                                            </p>
                                        </td>
                                        <td class="px-4 py-4 text-slate-500 hidden md:table-cell">
                                            <i class="bi bi-geo-alt text-slate-400"></i> {{ $lowongan->lokasi ?? '-' }}
                                        </td>
                                        <td class="px-4 py-4 hidden sm:table-cell">
                                            <span class="inline-flex px-2 py-0.5 rounded text-xs font-medium {{ $tipeClass }}">
                                                {{ ucfirst($lowongan->tipe_kerja) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-xs {{ $isUrgent ? 'text-red-500 font-semibold' : 'text-slate-500' }}">
                                            {{ $tanggalTutup->translatedFormat('d M Y') }}
                                            @if ($isUrgent)
                                                <span class="block">{{ $sisaHari === 0 ? 'Hari ini' : $sisaHari . ' hari lagi' }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if ($sudahDilamar)
                                                <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 text-xs font-medium whitespace-nowrap">
                                                    <i class="bi bi-check-circle-fill"></i> Dilamar
                                                </span>
                                            @elseif (!$profileLengkap)
                                                <button onclick="alertProfilKurang()"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-100 text-slate-400 text-xs font-medium cursor-not-allowed whitespace-nowrap">
                                                    <i class="bi bi-lock-fill"></i> Lamar
                                                </button>
                                            @else
                                                <button onclick="konfirmasiLamar({{ $lowongan->id }}, '{{ addslashes($lowongan->judul) }}')"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium transition-colors whitespace-nowrap">
                                                    <i class="bi bi-send"></i> Lamar
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Teks "lihat lebih banyak" — selalu tampil --}}
                <div class="px-6 py-4 border-t border-slate-100 text-center">
                    <a href="{{ route('career') }}" class="text-xs font-medium text-blue-600 hover:text-blue-700">
                        Lihat Lebih Banyak Lowongan &rarr;
                    </a>
                </div>
            </div>

            {{-- Riwayat Lamaran Terbaru --}}
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                    <h2 class="font-semibold text-slate-700 flex items-center gap-2">
                        <i class="bi bi-clock-history text-violet-500"></i>
                        Riwayat Lamaran Terbaru
                    </h2>
                </div>

                @if ($riwayatTerbaru->isEmpty())
                    <div class="py-16 text-center text-slate-400">
                        <i class="bi bi-file-earmark-x text-4xl block mb-2"></i>
                        <p class="text-sm">Kamu belum pernah melamar lowongan.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="text-left text-xs text-slate-400 uppercase tracking-wide border-b border-slate-100">
                                    <th class="px-6 py-3 font-medium w-10">No</th>
                                    <th class="px-4 py-3 font-medium">Posisi</th>
                                    <th class="px-4 py-3 font-medium hidden sm:table-cell">Tanggal</th>
                                    <th class="px-4 py-3 font-medium">Progress</th>
                                    <th class="px-6 py-3 font-medium text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($riwayatTerbaru as $lamaran)
                                    @php
                                        $statusConfig = [
                                            'baru' => ['bg-slate-100 text-slate-500', 'Baru', 1],
                                            'screening_hrd' => ['bg-blue-50 text-blue-600', 'Screening HRD', 2],
                                            'ditolak_hrd' => ['bg-red-50 text-red-500', 'Ditolak HRD', 2],
                                            'dikirim_ke_hod' => ['bg-indigo-50 text-indigo-600', 'Dikirim ke HOD', 3],
                                            'screening_hod' => ['bg-purple-50 text-purple-600', 'Screening HOD', 4],
                                            'ditolak_hod' => ['bg-red-50 text-red-500', 'Ditolak HOD', 4],
                                            'menunggu_interview' => ['bg-amber-50 text-amber-600', 'Menunggu Interview', 5],
                                            'interview' => ['bg-violet-50 text-violet-600', 'Interview', 6],
                                            'selesai' => ['bg-emerald-50 text-emerald-600', 'Selesai', 7],
                                        ];
                                        [$badgeClass, $badgeLabel, $stepAt] = $statusConfig[$lamaran->status] ?? ['bg-slate-100 text-slate-500', ucfirst($lamaran->status), 1];
                                        $isRejected = in_array($lamaran->status, ['ditolak_hrd', 'ditolak_hod']);
                                        $barColor = $isRejected ? 'bg-red-400' : ($lamaran->status === 'selesai' ? 'bg-emerald-400' : 'bg-blue-400');
                                        $percent = round(($stepAt / 7) * 100);
                                    @endphp
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="px-6 py-4 text-slate-400 text-xs align-top">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-4">
                                            <p class="font-medium text-slate-800 truncate max-w-[220px]">{{ $lamaran->lowongan->judul }}</p>
                                            <p class="text-xs text-slate-400 mt-0.5">No. {{ $lamaran->nomor_lamaran }}</p>
                                        </td>
                                        <td class="px-4 py-4 text-slate-500 text-xs hidden sm:table-cell whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($lamaran->tanggal_dilamar)->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="px-4 py-4 w-32">
                                            <div class="h-1.5 rounded-full bg-slate-100 overflow-hidden">
                                                <div class="h-full {{ $barColor }} rounded-full" style="width: {{ $percent }}%"></div>
                                            </div>
                                            @if (in_array($lamaran->status, ['menunggu_interview', 'interview']) && $lamaran->metode_interview)
                                                <p class="text-[11px] text-violet-600 mt-1">{{ ucfirst($lamaran->metode_interview) }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium whitespace-nowrap {{ $badgeClass }}">
                                                {{ $badgeLabel }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                {{-- Teks "lihat lebih banyak" — selalu tampil --}}
                <div class="px-6 py-4 border-t border-slate-100 text-center">
                    <a href="{{ route('pelamar.lamaran') }}" class="text-xs font-medium text-blue-600 hover:text-blue-700">
                        Lihat Lebih Banyak Riwayat Lamaran &rarr;
                    </a>
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Profil --}}
            <div class="bg-white rounded-xl border border-slate-100 shadow-sm p-5">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold text-sm shrink-0">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 2)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-semibold text-slate-800 truncate">{{ auth()->user()->nama }}</p>
                        @if ($profileLengkap)
                            <span class="inline-flex items-center gap-1 text-xs text-emerald-600 font-medium mt-0.5">
                                <i class="bi bi-check-circle-fill"></i> Profil Lengkap
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs text-amber-600 font-medium mt-0.5">
                                <i class="bi bi-exclamation-triangle-fill"></i> Profil Belum Lengkap
                            </span>
                        @endif
                    </div>
                </div>

                @unless ($profileLengkap)
                    <p class="text-xs text-slate-500 mt-3 leading-relaxed">
                        Lengkapi data diri dan dokumen wajib supaya bisa melamar lowongan.
                    </p>
                @endunless

                <a href="{{ route('pelamar.profile') }}"
                    class="mt-4 flex items-center justify-center gap-1.5 w-full py-2 rounded-lg text-xs font-medium transition-colors
                    {{ $profileLengkap ? 'bg-slate-100 text-slate-600 hover:bg-slate-200' : 'bg-amber-500 text-white hover:bg-amber-600' }}">
                    <i class="bi bi-person-gear"></i>
                    {{ $profileLengkap ? 'Kelola Profil' : 'Lengkapi Sekarang' }}
                </a>
            </div>
        </div>
    </div>

    {{-- Form tersembunyi untuk submit lamaran --}}
    <form id="formLamar" method="POST" action="" class="hidden">
        @csrf
    </form>

@endsection

@push('scripts')
    <script>
        const LAMAR_URL_TEMPLATE = "{{ route('pelamar.lamaran.store', ['lowonganId' => '__ID__']) }}";

        function alertProfilKurang() {
            Swal.fire({
                icon: 'warning',
                title: 'Profil Belum Lengkap',
                text: 'Lengkapi profil kamu terlebih dahulu sebelum melamar lowongan.',
                confirmButtonText: 'Lengkapi Profil',
                showCancelButton: true,
                cancelButtonText: 'Nanti',
                confirmButtonColor: '#2563eb',
            }).then(result => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('pelamar.profile') }}";
                }
            });
        }

        function konfirmasiLamar(lowonganId, judulLowongan) {
            Swal.fire({
                icon: 'question',
                title: 'Lamar Posisi Ini?',
                html: `Kamu akan melamar ke posisi <strong>${judulLowongan}</strong>.`,
                confirmButtonText: 'Ya, Lamar!',
                showCancelButton: true,
                cancelButtonText: 'Batal',
                confirmButtonColor: '#2563eb',
            }).then(result => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengirim Lamaran...',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => Swal.showLoading(),
                    });
                    const form = document.getElementById('formLamar');
                    form.action = LAMAR_URL_TEMPLATE.replace('__ID__', lowonganId);
                    form.submit();
                }
            });
        }

        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', confirmButtonColor: '#2563eb' });
        @endif

        @if (session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal', text: '{{ session('error') }}', confirmButtonColor: '#2563eb' });
        @endif
    </script>
@endpush