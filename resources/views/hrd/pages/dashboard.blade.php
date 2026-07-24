@extends('hrd.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Pusat operasional rekrutmen — pantau semua proses dalam satu tampilan.')

@section('content')
    @php
        $antrianFptkData = collect($antrianFptk ?? []);
        $antrianLamaranData = collect($antrianLamaran ?? []);
        $antrianAkunPelamarData = collect($antrianAkunPelamar ?? []);
        $fptkSiapLowonganData = collect($fptkSiapLowongan ?? []);
    @endphp

    <div class="space-y-6">
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="bg-[#4338CA] rounded-2xl p-5 text-white shadow-sm">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-white/70 leading-snug">Akun
                            Pelamar<br>Menunggu</p>
                        <p class="text-4xl font-bold mt-2">{{ $akunPelamarMenunggu ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center">
                        <i class="bi bi-person-check-fill"></i>
                    </div>
                </div>
                <a href="{{ route('hrd.pelamar.index') }}"
                    class="text-xs font-semibold text-white/90 hover:text-white inline-flex items-center gap-1">
                    Review akun <i class="bi bi-arrow-right-short"></i>
                </a>
            </div>

            <div class="bg-white border border-amber-100 rounded-2xl p-5 shadow-sm">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 leading-snug">
                            FPTK<br>Menunggu HRD</p>
                        <p class="text-4xl font-bold text-amber-600 mt-2">{{ $fptkMenungguHrd ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center">
                        <i class="bi bi-file-earmark-check-fill"></i>
                    </div>
                </div>
                <a href="{{ route('hrd.approval.index') }}"
                    class="text-xs font-semibold text-amber-600 hover:text-amber-700 inline-flex items-center gap-1">
                    Cek persetujuan <i class="bi bi-arrow-right-short"></i>
                </a>
            </div>

            <div class="bg-white border border-blue-100 rounded-2xl p-5 shadow-sm">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 leading-snug">
                            Lamaran<br>Masuk</p>
                        <p class="text-4xl font-bold text-blue-700 mt-2">{{ $lamaranMasuk ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="bi bi-inbox-fill"></i>
                    </div>
                </div>
                <a href="{{ route('hrd.approval.index', ['tab' => 'pelamar']) }}"
                    class="text-xs font-semibold text-blue-600 hover:text-blue-700 inline-flex items-center gap-1">
                    Cek persetujuan <i class="bi bi-arrow-right-short"></i>
                </a>
            </div>

            <div class="bg-white border border-emerald-100 rounded-2xl p-5 shadow-sm">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 leading-snug">
                            Lowongan<br>Aktif</p>
                        <p class="text-4xl font-bold text-emerald-700 mt-2">{{ $lowonganAktif ?? 0 }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                </div>
                <a href="{{ route('hrd.lowongan.index') }}"
                    class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 inline-flex items-center gap-1">
                    Kelola lowongan <i class="bi bi-arrow-right-short"></i>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                    <div>
                        <h3 class="font-bold text-slate-800">Antrian Prioritas HRD</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Data yang membutuhkan tindakan segera.</p>
                    </div>
                    <i class="bi bi-list-task text-slate-400 text-xl"></i>
                </div>

                <div class="divide-y divide-slate-100">
                    <div class="p-5">
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <div>
                                <h4 class="text-sm font-bold text-slate-700">Persetujuan FPTK</h4>
                                <p class="text-xs text-slate-400">Pengajuan tenaga kerja yang perlu keputusan HRD.</p>
                            </div>
                            <a href="{{ route('hrd.approval.index') }}"
                                class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100">
                                Lihat Semua
                            </a>
                        </div>

                        <div class="space-y-2">
                            @forelse ($antrianFptkData as $item)
                                <div
                                    class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 px-4 py-3 hover:bg-slate-50 transition">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-800 truncate">
                                            {{ $item->posisi_dibutuhkan ?? '-' }}</p>
                                        <p class="text-xs text-slate-400">{{ $item->nomor_fptk ?? '-' }} ·
                                            {{ $item->jumlah_kebutuhan ?? 0 }} orang</p>
                                    </div>
                                    <a href="{{ route('hrd.approval.index', $item->id) }}" class="shrink-0 text-xs font-semibold text-amber-700 border border-amber-200 px-2.5 py-1 rounded-full">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            @empty
                                <div
                                    class="rounded-xl border border-dashed border-slate-200 p-5 text-center text-sm text-slate-400">
                                    Tidak ada FPTK yang menunggu persetujuan HRD.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <div>
                                <h4 class="text-sm font-bold text-slate-700">Lamaran Masuk</h4>
                                <p class="text-xs text-slate-400">Kandidat yang perlu screening awal oleh HRD.</p>
                            </div>
                            <a href="{{ route('hrd.approval.index', ['tab' => 'pelamar']) }}"
                                class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100">
                                Lihat Semua
                            </a>
                        </div>

                        <div class="space-y-2">
                            @forelse ($antrianLamaranData as $item)
                                <div
                                    class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 px-4 py-3 hover:bg-slate-50 transition">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-800 truncate">
                                            {{ $item->pelamar->user->nama ?? '-' }}</p>
                                        <p class="text-xs text-slate-400">{{ $item->lowongan->judul ?? '-' }} ·
                                            {{ optional($item->tanggal_dilamar)->format('d M Y') }}</p>
                                    </div>
                                    <a href="{{ route('hrd.approval.index', ['tab' => 'pelamar', 'lamaran_id' => $item->id]) }}"
                                        class="shrink-0 text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200 px-2.5 py-1 rounded-full hover:bg-blue-100">
                                        Detail
                                    </a>
                                </div>
                            @empty
                                <div
                                    class="rounded-xl border border-dashed border-slate-200 p-5 text-center text-sm text-slate-400">
                                    Tidak ada lamaran baru.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="flex items-center justify-between gap-3 mb-3">
                            <div>
                                <h4 class="text-sm font-bold text-slate-700">Persetujuan Akun Pelamar</h4>
                                <p class="text-xs text-slate-400">Akun pelamar yang perlu diverifikasi sebelum aktif.</p>
                            </div>
                            <a href="{{ route('hrd.pelamar.index') }}"
                                class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 hover:bg-indigo-100">
                                Lihat Semua
                            </a>
                        </div>

                        <div class="space-y-2">
                            @forelse ($antrianAkunPelamarData as $item)
                                <div
                                    class="flex items-center justify-between gap-3 rounded-xl border border-slate-100 px-4 py-3 hover:bg-slate-50 transition">
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-slate-800 truncate">{{ $item->nama ?? '-' }}</p>
                                        <p class="text-xs text-slate-400">{{ $item->email ?? '-' }}</p>
                                    </div>
                                    <a href="{{ route('hrd.pelamar.index') }}"
                                        class="shrink-0 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 px-2.5 py-1 rounded-full hover:bg-indigo-100">
                                        Verifikasi
                                    </a>
                                </div>
                            @empty
                                <div
                                    class="rounded-xl border border-dashed border-slate-200 p-5 text-center text-sm text-slate-400">
                                    Tidak ada akun pelamar yang menunggu verifikasi.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                        <h3 class="font-bold text-slate-800">FPTK Siap Dibuka</h3>
                        <p class="text-xs text-slate-400 mt-0.5">FPTK approved yang belum menjadi lowongan.</p>
                    </div>

                    <div class="p-4 space-y-3">
                        @forelse ($fptkSiapLowonganData as $item)
                            <div class="rounded-xl border border-slate-100 p-4">
                                <p class="text-sm font-semibold text-slate-800">{{ $item->posisi_dibutuhkan ?? '-' }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $item->nomor_fptk ?? '-' }} ·
                                    {{ $item->jumlah_kebutuhan ?? 0 }} orang</p>
                                <a href="{{ route('hrd.lowongan.create', ['fptk_id' => $item->id]) }}"
                                    class="mt-3 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 text-white hover:bg-emerald-700 transition">
                                    <i class="bi bi-plus-lg"></i>
                                    Buka Lowongan
                                </a>
                            </div>
                        @empty
                            <div
                                class="rounded-xl border border-dashed border-slate-200 p-5 text-center text-sm text-slate-400">
                                Tidak ada FPTK yang siap dibuka.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection