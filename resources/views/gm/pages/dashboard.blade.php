@extends('gm.layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Selamat datang, ' . auth()->user()->nama . '. Berikut ringkasan aktivitas persetujuan FPTK Anda.')

@section('content')

    @php
        $nama = auth()->user()->nama ?? 'GM';
        $jam = now()->hour;
        $salam = $jam < 11 ? 'Selamat Pagi' : ($jam < 15 ? 'Selamat Siang' : ($jam < 18 ? 'Selamat Sore' : 'Selamat Malam'));

        $statCards = 
        [
            [
                'label' => 'Menunggu Persetujuan',
                'value' => $summary['pending_gm'] ?? 0,
                'icon' => 'bi-hourglass-split',
                'color' => 'text-amber-600',
                'bg' => 'bg-amber-50',
                'border' => 'border-amber-200',
                'dot' => 'bg-amber-400',
                'urgent' => ($summary['pending_gm'] ?? 0) > 0,
            ],
            [
                'label' => 'Dikembalikan untuk Revisi',
                'value' => $summary['revisi_gm'] ?? 0,
                'icon' => 'bi-arrow-repeat',
                'color' => 'text-orange-600',
                'bg' => 'bg-orange-50',
                'border' => 'border-orange-200',
                'dot' => 'bg-orange-400',
                'urgent' => false,
            ],
            [
                'label' => 'Telah Disetujui GM',
                'value' => $summary['approved_gm'] ?? 0,
                'icon' => 'bi-check-circle-fill',
                'color' => 'text-blue-600',
                'bg' => 'bg-blue-50',
                'border' => 'border-blue-200',
                'dot' => 'bg-blue-400',
                'urgent' => false,
            ],
            [
                'label' => 'Ditolak',
                'value' => $summary['ditolak'] ?? 0,
                'icon' => 'bi-x-circle-fill',
                'color' => 'text-red-500',
                'bg' => 'bg-red-50',
                'border' => 'border-red-200',
                'dot' => 'bg-red-400',
                'urgent' => false,
            ],
        ];

        $pendingCount = $summary['pending_gm'] ?? 0;
        $revisiCount = $summary['revisi_gm'] ?? 0;
        $needAction = $pendingCount + $revisiCount;
    @endphp

    <div class="space-y-6">

        {{-- Greeting Banner --}}
        <div class="rounded-2xl bg-[#0F766E] overflow-hidden relative">
            <div class="absolute inset-0 opacity-10" style="background-image: url(\" data:image/svg+xml,%3Csvg width='60'
                height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg' %3E%3Cg fill='none' fill-rule='evenodd'
                %3E%3Cg fill='%23ffffff' fill-opacity='1' %3E%3Cpath
                d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'
                /%3E%3C/g%3E%3C/g%3E%3C/svg%3E\");">
            </div>
            <div class="relative px-6 py-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="text-white/70 text-xs font-medium mb-1">{{ $salam }},</div>
                    <div class="text-white text-xl font-bold leading-tight">{{ $nama }}</div>
                    <div class="text-white/60 text-xs mt-1.5">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </div>
                </div>

                @if ($needAction > 0)
                    <a href="{{ route('gm.approval.index') }}"
                        class="shrink-0 inline-flex items-center gap-2 bg-white text-[#0F766E] px-5 py-2.5 rounded-xl text-sm font-bold shadow hover:bg-blue-50 transition">
                        <span
                            class="w-5 h-5 rounded-full bg-amber-400 text-white text-[10px] font-bold flex items-center justify-center shrink-0">
                            {{ $needAction > 99 ? '99+' : $needAction }}
                        </span>
                        Tinjau Sekarang
                        <i class="bi bi-arrow-right text-xs"></i>
                    </a>
                @else
                    <div
                        class="shrink-0 inline-flex items-center gap-2 bg-white/15 text-white px-5 py-2.5 rounded-xl text-sm font-semibold">
                        <i class="bi bi-check2-all text-emerald-300"></i>
                        Semua sudah ditinjau
                    </div>
                @endif
            </div>
        </div>

        {{-- Stat Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($statCards as $card)
                <div
                    class="bg-white rounded-2xl border {{ $card['border'] }} p-5 flex flex-col gap-3 shadow-sm relative overflow-hidden">
                    @if ($card['urgent'])
                        <span class="absolute top-3 right-3 flex h-2.5 w-2.5">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $card['dot'] }} opacity-60"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 {{ $card['dot'] }}"></span>
                        </span>
                    @endif
                    <div class="w-10 h-10 rounded-xl {{ $card['bg'] }} flex items-center justify-center shrink-0">
                        <i class="bi {{ $card['icon'] }} {{ $card['color'] }} text-lg"></i>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-800">{{ $card['value'] }}</div>
                        <div class="text-xs text-gray-400 mt-0.5 leading-snug">{{ $card['label'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid lg:grid-cols-3 gap-6">

            {{-- FPTK Terbaru --}}
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <div class="text-sm font-bold text-gray-800">FPTK Terbaru</div>
                        <div class="text-xs text-gray-400 mt-0.5">5 pengajuan FPTK paling terakhir masuk</div>
                    </div>
                    <a href="{{ route('gm.approval.index') }}"
                        class="text-xs font-semibold text-[#0F766E] hover:underline inline-flex items-center gap-1">
                        Lihat Semua <i class="bi bi-arrow-right text-[10px]"></i>
                    </a>
                </div>

                @php
                    $statusMap = [
                        'pending_gm' => ['bg-amber-50 border-amber-200 text-amber-700', 'bi-clock-fill', 'Pending GM'],
                        'revisi_gm' => ['bg-orange-50 border-orange-200 text-orange-700', 'bi-arrow-repeat', 'Revisi GM'],
                        'approved_gm' => ['bg-blue-50 border-blue-200 text-blue-700', 'bi-check-circle-fill', 'Disetujui GM'],
                        'approved_hrd' => ['bg-green-50 border-green-200 text-green-700', 'bi-check-circle-fill', 'Disetujui HRD'],
                        'revisi_hrd' => ['bg-purple-50 border-purple-200 text-purple-700', 'bi-arrow-repeat', 'Revisi HRD'],
                        'ditolak' => ['bg-red-50 border-red-200 text-red-600', 'bi-x-circle-fill', 'Ditolak'],
                    ];
                @endphp

                <div class="divide-y divide-gray-100">
                    @forelse ($recentFptks as $fptk)
                        @php [$cls, $icon, $label] = $statusMap[$fptk->status] ?? ['bg-gray-100 border-gray-200 text-gray-500', 'bi-dash-circle', $fptk->status]; @endphp
                        <div class="px-5 py-3.5 flex items-center gap-4 hover:bg-gray-50/60 transition">
                            <div
                                class="w-8 h-8 rounded-lg bg-[#0F766E]/10 text-[#0F766E] text-xs font-bold flex items-center justify-center shrink-0">
                                {{ strtoupper(substr($fptk->hod->nama ?? '-', 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-gray-800 truncate">{{ $fptk->posisi_dibutuhkan }}</div>
                                <div class="text-xs text-gray-400 mt-0.5 flex items-center gap-2">
                                    <span class="font-mono">{{ $fptk->nomor_fptk }}</span>
                                    <span class="w-1 h-1 rounded-full bg-gray-300 inline-block"></span>
                                    <span>{{ $fptk->hod->nama ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="shrink-0 flex flex-col items-end gap-1.5">
                                <span
                                    class="inline-flex items-center gap-1 rounded-full border px-2 py-0.5 text-[10px] font-semibold {{ $cls }}">
                                    <i class="bi {{ $icon }} text-[8px]"></i> {{ $label }}
                                </span>
                                <span class="text-[10px] text-gray-400">
                                    {{ $fptk->created_at?->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="px-5 py-12 text-center">
                            <div
                                class="w-12 h-12 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-300 text-xl mx-auto mb-3">
                                <i class="bi bi-folder2-open"></i>
                            </div>
                            <div class="text-sm font-semibold text-gray-500">Belum ada FPTK masuk</div>
                            <div class="text-xs text-gray-400 mt-1">FPTK yang diajukan HOD akan muncul di sini.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Panel Kanan --}}
            <div class="space-y-4">

                {{-- Alur Persetujuan --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <div class="text-sm font-bold text-gray-800">Alur Persetujuan FPTK</div>
                        <div class="text-xs text-gray-400 mt-0.5">Posisi Anda dalam proses ini</div>
                    </div>
                    <div class="px-5 py-4">
                        <ol class="space-y-0 relative ml-3">

                            @php
                                $steps = [
                                    ['label' => 'HOD mengajukan FPTK', 'sub' => 'Inisiasi kebutuhan SDM', 'done' => true, 'active' => false],
                                    ['label' => 'Persetujuan GM', 'sub' => 'Anda meninjau di tahap ini', 'done' => false, 'active' => true],
                                    ['label' => 'Persetujuan HRD', 'sub' => 'Finalisasi & proses rekrutmen', 'done' => false, 'active' => false],
                                    ['label' => 'Rekrutmen Dibuka', 'sub' => 'Lowongan aktif di portal', 'done' => false, 'active' => false],
                                ];
                            @endphp

                            @foreach ($steps as $step)
                                <li class="relative flex gap-3.5 pb-5 last:pb-0">
                                    <div class="flex flex-col items-center">
                                        <div @class([
                                            'w-6 h-6 rounded-full flex items-center justify-center shrink-0 z-10 border-2',
                                            'bg-[#0F766E] border-[#0F766E]' => $step['active'],
                                            'bg-emerald-500 border-emerald-500' => $step['done'],
                                            'bg-white border-gray-300' => !$step['active'] && !$step['done'],
                                        ])>
                                            @if ($step['done'])
                                                <i class="bi bi-check-lg text-white text-[10px] font-bold"></i>
                                            @elseif ($step['active'])
                                                <span class="w-2 h-2 rounded-full bg-white"></span>
                                            @else
                                                <span class="w-2 h-2 rounded-full bg-gray-300"></span>
                                            @endif
                                        </div>
                                        <div @class([
                                            'w-px flex-1 mt-1',
                                            'bg-emerald-200' => $step['done'],
                                            'bg-[#0F766E]/30' => $step['active'],
                                            'bg-gray-100' => !$step['active'] && !$step['done'],
                                            'hidden' => $loop->last,
                                        ])></div>
                                    </div>
                                    <div class="flex-1 pt-0.5 pb-1">
                                        <div @class([
                                            'text-xs font-semibold',
                                            'text-[#0F766E]' => $step['active'],
                                            'text-gray-800' => $step['done'],
                                            'text-gray-400' => !$step['active'] && !$step['done'],
                                        ])>{{ $step['label'] }}</div>
                                        <div @class([
                                            'text-[11px] mt-0.5',
                                            'text-[#0F766E]/70' => $step['active'],
                                            'text-gray-400' => !$step['active'],
                                        ])>{{ $step['sub'] }}</div>
                                    </div>
                                </li>
                            @endforeach

                        </ol>
                    </div>
                </div>

                {{-- Aksi Cepat --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <div class="text-sm font-bold text-gray-800">Aksi Cepat</div>
                    </div>
                    <div class="p-3 space-y-1.5">
                        <a href="{{ route('gm.approval.index') }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-[#0F766E]/5 transition group">
                            <div
                                class="w-8 h-8 rounded-lg bg-[#0F766E]/10 flex items-center justify-center shrink-0 group-hover:bg-[#0F766E]/20 transition">
                                <i class="bi bi-file-earmark-check text-[#0F766E] text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-gray-700">Semua Pengajuan FPTK</div>
                                <div class="text-xs text-gray-400">Tinjau seluruh daftar</div>
                            </div>
                            <i class="bi bi-chevron-right text-gray-300 text-xs group-hover:text-[#0F766E] transition"></i>
                        </a>

                        <a href="{{ route('gm.approval.index', ['status' => 'pending_gm']) }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-amber-50 transition group">
                            <div
                                class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center shrink-0 group-hover:bg-amber-100 transition">
                                <i class="bi bi-hourglass-split text-amber-500 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-gray-700">FPTK Menunggu</div>
                                <div class="text-xs text-gray-400">Filter status pending GM</div>
                            </div>
                            @if ($pendingCount > 0)
                                <span
                                    class="shrink-0 px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-[10px] font-bold">
                                    {{ $pendingCount }}
                                </span>
                            @else
                                <i class="bi bi-chevron-right text-gray-300 text-xs group-hover:text-amber-500 transition"></i>
                            @endif
                        </a>

                        <a href="{{ route('gm.approval.index', ['status' => 'revisi_gm']) }}"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-orange-50 transition group">
                            <div
                                class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center shrink-0 group-hover:bg-orange-100 transition">
                                <i class="bi bi-arrow-repeat text-orange-500 text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-gray-700">Dalam Revisi</div>
                                <div class="text-xs text-gray-400">Filter status revisi GM</div>
                            </div>
                            @if ($revisiCount > 0)
                                <span
                                    class="shrink-0 px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 text-[10px] font-bold">
                                    {{ $revisiCount }}
                                </span>
                            @else
                                <i class="bi bi-chevron-right text-gray-300 text-xs group-hover:text-orange-500 transition"></i>
                            @endif
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection