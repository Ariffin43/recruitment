<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lowongan Kerja | PT. Cesco Offshore and Engineering</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Rajdhani:wght@500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        display: ['Rajdhani', 'sans-serif'],
                    },
                    colors: {
                        cesco: { blue: '#003366', orange: '#FF6600' }
                    },
                    keyframes: {
                        modalIn: {
                            '0%': { opacity: '0', transform: 'scale(0.95) translateY(10px)' },
                            '100%': { opacity: '1', transform: 'scale(1) translateY(0)' },
                        }
                    },
                    animation: { 'modal-in': 'modalIn 0.3s ease-out forwards' }
                }
            }
        }
    </script>

    <style>
        .hero-pattern {
            background-color: #003366;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="font-sans text-slate-800 bg-slate-50 flex flex-col min-h-screen">

    @php
        $user = auth()->user();
        $isLoggedIn = auth()->check();
        $isProfileComplete = isset($isProfileComplete) ? $isProfileComplete : false;
        $userRole = $isLoggedIn ? $user->role : null;

        $dashboardRoute = match ($userRole) {
            'hrd', 'gm' => route('hrd.dashboard'),
            'hod' => route('hod.dashboard'),
            'pelamar' => route('pelamar.dashboard'),
            default => url('/'),
        };

        $canApply = !$isLoggedIn || $userRole === 'pelamar';
    @endphp

    <nav class="fixed w-full z-50 bg-cesco-blue shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ url('/') }}" class="flex items-center gap-3 shrink-0">
                    <div class="w-9 h-9 bg-cesco-orange rounded-lg flex items-center justify-center shadow-md">
                        <i class="bi bi-briefcase-fill text-white text-lg"></i>
                    </div>
                    <div class="flex flex-col leading-none">
                        <span class="font-display font-bold text-xl text-white tracking-wide">
                            CESCO <span class="text-cesco-orange">CAREERS</span>
                        </span>
                        <span class="hidden sm:block text-[9px] font-semibold tracking-widest text-slate-300 uppercase">
                            Offshore & Engineering Portal
                        </span>
                    </div>
                </a>

                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ url('/') }}"
                        class="px-4 py-2 rounded-md text-slate-200 font-medium hover:text-cesco-orange transition-colors text-sm">Beranda</a>
                    <a href="{{ route('career') }}"
                        class="px-4 py-2 rounded-md text-white font-medium hover:text-cesco-orange transition-colors text-sm">Lowongan</a>
                    <a href="{{ url('/') }}#about"
                        class="px-4 py-2 rounded-md text-slate-200 font-medium hover:text-cesco-orange transition-colors text-sm">Tentang
                        Kami</a>
                    <a href="{{ url('/') }}#culture"
                        class="px-4 py-2 rounded-md text-slate-200 font-medium hover:text-cesco-orange transition-colors text-sm">Budaya
                        Kerja</a>
                    <span class="h-6 w-px bg-slate-600 mx-2"></span>

                    @if ($isLoggedIn)
                        <a href="{{ $dashboardRoute }}"
                            class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-2 rounded-md font-semibold transition-all shadow-md flex items-center gap-1.5 text-sm">
                            <i class="bi bi-grid-fill"></i> Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="ml-2 px-4 py-2 rounded-md text-slate-300 hover:text-red-500 font-medium transition-colors text-sm flex items-center gap-1.5">
                                <i class="bi bi-box-arrow-right"></i> Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-cesco-orange hover:bg-orange-600 text-white px-5 py-2 rounded-md font-semibold transition-all shadow-md flex items-center gap-1.5 text-sm">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    @endif
                </div>

                <button id="mobile-menu-btn"
                    class="md:hidden text-white hover:text-cesco-orange p-2 rounded-md transition-colors"
                    onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                    <i class="bi bi-list text-3xl"></i>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-slate-900 border-t border-slate-800">
            <div class="px-4 pt-2 pb-5 space-y-1">
                <a href="{{ url('/') }}"
                    class="block px-4 py-3 text-slate-300 font-medium hover:bg-slate-800 rounded-md transition-colors">Beranda</a>
                <a href="{{ route('career') }}"
                    class="block px-4 py-3 text-white font-medium bg-slate-800 rounded-md">Lowongan</a>
                <a href="{{ url('/') }}#about"
                    class="block px-4 py-3 text-slate-300 font-medium hover:bg-slate-800 rounded-md transition-colors">Tentang
                    Kami</a>
                <a href="{{ url('/') }}#culture"
                    class="block px-4 py-3 text-slate-300 font-medium hover:bg-slate-800 rounded-md transition-colors">Budaya
                    Kerja</a>
                <div class="pt-3 border-t border-slate-800 space-y-2">
                    @if ($isLoggedIn)
                        <a href="{{ $dashboardRoute }}"
                            class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white text-center py-3 rounded-md font-bold transition-colors">
                            <i class="bi bi-grid-fill mr-2"></i> Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="block w-full text-slate-300 hover:bg-slate-800 text-center py-3 rounded-md font-medium transition-colors">
                                <i class="bi bi-box-arrow-right mr-2"></i> Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="block w-full bg-cesco-orange hover:bg-orange-600 text-white text-center py-3 rounded-md font-bold transition-colors">
                            <i class="bi bi-box-arrow-in-right mr-2"></i> Login
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow pt-16">
        <section class="hero-pattern py-10 md:py-14 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-cesco-blue/90 to-transparent"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <ol class="flex items-center gap-2 text-sm text-slate-300 mb-5">
                    <li>
                        <a href="{{ url('/') }}"
                            class="hover:text-cesco-orange flex items-center gap-1 transition-colors">
                            <i class="bi bi-house-door-fill"></i> Beranda
                        </a>
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="bi bi-chevron-right text-[10px]"></i>
                        <span class="text-white font-semibold">Daftar Lowongan</span>
                    </li>
                </ol>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-display font-bold text-white">Mulai Karir Lepas Pantai
                    Anda</h1>
                <p class="mt-3 text-slate-200 text-sm md:text-base font-light max-w-2xl leading-relaxed">
                    Jelajahi posisi teknis dan manajemen yang tersedia di Galangan Batam maupun Kantor Pusat Jakarta.
                </p>
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid lg:grid-cols-12 gap-8 items-start">

                <aside class="lg:col-span-4 bg-white border border-slate-200 rounded-xl p-6 space-y-6 shadow-sm lg:sticky lg:top-24">
                    <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                        <span class="font-bold text-lg text-slate-800">
                            <i class="bi bi-sliders2 mr-2 text-cesco-blue"></i>
                            Filter Pencarian
                        </span>

                        @if (request()->hasAny(['search', 'departemen', 'tipe_kerja', 'status']))
                            <a href="{{ route('career') }}"
                                class="text-sm font-medium text-cesco-orange hover:text-orange-600 transition-colors inline-flex items-center gap-1">
                                <i class="bi bi-arrow-counterclockwise"></i>
                                Reset Filter
                            </a>
                        @endif
                    </div>

                    <form method="GET" action="{{ route('career') }}" id="filter-form" class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Kata Kunci</label>
                            <div class="relative">
                                <i class="bi bi-search absolute left-3 top-3.5 text-slate-400 text-sm"></i>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Contoh: Welder, Engineer..."
                                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-cesco-blue/30 text-sm transition-all">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Departemen</label>
                            <select name="departemen" onchange="document.getElementById('filter-form').submit()"
                                class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-cesco-blue/30 text-sm bg-white transition-all cursor-pointer">
                                <option value="">Semua Departemen</option>
                                @if (isset($departemens))
                                    @foreach ($departemens as $dept)
                                        <option value="{{ $dept->id }}" @selected(request('departemen') == $dept->id)>
                                            {{ $dept->nama }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Tipe Kerja</label>
                            <select name="tipe_kerja" onchange="document.getElementById('filter-form').submit()"
                                class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-cesco-blue/30 text-sm bg-white transition-all cursor-pointer">
                                <option value="">Semua Tipe</option>
                                <option value="fulltime" @selected(request('tipe_kerja') == 'fulltime')>Fulltime</option>
                                <option value="kontrak" @selected(request('tipe_kerja') == 'kontrak')>Kontrak</option>
                                <option value="magang" @selected(request('tipe_kerja') == 'magang')>Magang</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Status
                                Lowongan</label>
                            <select name="status" onchange="document.getElementById('filter-form').submit()"
                                class="w-full px-3 py-2.5 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-cesco-blue/30 text-sm bg-white transition-all cursor-pointer">
                                <option value="">Semua Status</option>
                                <option value="dibuka" @selected(request('status') == 'dibuka')>Dibuka</option>
                                <option value="ditutup" @selected(request('status') == 'ditutup')>Ditutup</option>
                                <option value="draft" @selected(request('status') == 'draft')>Draft</option>
                            </select>
                        </div>
                    </form>
                </aside>

                <section class="lg:col-span-8 space-y-6">
                    <div class="flex justify-between items-center pb-4 border-b border-slate-200">
                        <span class="text-sm font-semibold text-slate-500" id="jobs-count-text">
                            Menampilkan {{ $totalLowongan }} Lowongan Aktif
                        </span>
                    </div>

                    <div id="jobs-wrapper" class="space-y-4">
                        @if (isset($lowongans))
                            @forelse($lowongans as $lowongan)
                                @php
                                    $deptName = $lowongan->fptk?->departemen?->nama ?? 'Umum';
                                    $lokasi = $lowongan->lokasi ?? 'Batam Yard';
                                    $tipeKerja = ucfirst($lowongan->tipe_kerja ?? 'Fulltime');
                                    $tanggalDibuat = \Carbon\Carbon::parse($lowongan->tanggal_dibuka)->format('d M Y');
                                    $statusLowongan = strtolower($lowongan->status ?? 'dibuka');

                                    $jumlahKebutuhan = $lowongan->fptk?->jumlah_kebutuhan;
                                    $posisiDibutuhkan = $lowongan->fptk?->posisi_dibutuhkan;

                                    $tanggalTutup = \Carbon\Carbon::parse($lowongan->tanggal_ditutup ?? now()->addMonth());
                                    $sisaHari = (int) now()->startOfDay()->diffInDays($tanggalTutup->copy()->startOfDay(), false);
                                    $isUrgent = $statusLowongan === 'dibuka' && $sisaHari >= 0 && $sisaHari <= 7;

                                    $sudahMelamar = isset($appliedLowonganIds) && $appliedLowonganIds->contains($lowongan->id);

                                    $tipeClass = match (strtolower($lowongan->tipe_kerja)) {
                                        'fulltime' => 'bg-blue-50 text-cesco-blue border-blue-100',
                                        'kontrak' => 'bg-orange-50 text-cesco-orange border-orange-100',
                                        'magang' => 'bg-sky-50 text-sky-700 border-sky-100',
                                        default => 'bg-slate-50 text-slate-600 border-slate-100',
                                    };

                                    $statusClass = match ($statusLowongan) {
                                        'dibuka' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        'ditutup' => 'bg-rose-100 text-rose-800 border-rose-200',
                                        'draft' => 'bg-amber-100 text-amber-800 border-amber-200',
                                        default => 'bg-slate-100 text-slate-800 border-slate-200',
                                    };

                                    $allKualifikasi = collect();
                                    foreach ($lowongan->fptk?->departemen?->kualifikasi ?? collect() as $kualifikasi) {
                                        $items = collect(preg_split("/\r\n|\r|\n/", $kualifikasi->nama_kualifikasi ?? ''))
                                            ->map(fn($i) => trim($i))
                                            ->filter();
                                        $allKualifikasi = $allKualifikasi->merge($items);
                                    }
                                @endphp

                                <div class="bg-white border border-slate-200 rounded-xl p-5 sm:p-6 shadow-sm hover:-translate-y-1 hover:shadow-lg hover:border-cesco-blue/40 transition-all duration-300 cursor-pointer group flex flex-col justify-between gap-5 relative"
                                    onclick="openJobModal(this)">

                                    <div
                                        class="absolute right-5 top-5 text-slate-300 group-hover:text-cesco-orange transition-colors duration-300 hidden sm:block">
                                        <i class="bi bi-arrow-up-right-circle text-2xl"></i>
                                    </div>

                                    <div class="space-y-4 pr-0 sm:pr-8">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="px-3 py-1.5 border rounded-md text-xs font-bold uppercase tracking-wider {{ $statusClass }}">{{ $statusLowongan }}</span>
                                            <span class="px-3 py-1.5 border rounded-md text-xs font-semibold bg-slate-50 text-slate-700 border-slate-200 flex items-center gap-1">
                                                <i class="bi bi-person-workspace"></i> {{ $tipeKerja }}
                                            </span>
                                            @if ($posisiDibutuhkan)
                                                <span class="px-3 py-1.5 border rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700 border-indigo-100 flex items-center gap-1">
                                                    <i class="bi bi-diagram-3"></i> Posisi: <span class="font-bold">{{ $posisiDibutuhkan }}</span>
                                                </span>
                                            @endif
                                            {{-- Badge jumlah pelamar --}}
                                            <span class="px-3 py-1.5 border rounded-md text-xs font-semibold bg-purple-50 text-purple-700 border-purple-100 flex items-center gap-1">
                                                <i class="bi bi-people"></i> {{ $lowongan->lamaran_count }} Orang Telah Melamar
                                            </span>
                                            @if ($isUrgent)
                                                <span class="px-3 py-1.5 border rounded-md text-xs font-bold uppercase tracking-wider bg-red-50 text-red-600 border-red-200 flex items-center gap-1 animate-pulse">
                                                    <i class="bi bi-alarm-fill"></i>
                                                    {{ $sisaHari === 0 ? 'Ditutup Hari Ini' : 'Segera Ditutup · ' . $sisaHari . ' Hari Lagi' }}
                                                </span>
                                            @endif
                                        </div>

                                        <div>
                                            <h3 class="text-xl sm:text-2xl font-bold text-slate-900 group-hover:text-cesco-blue transition-colors leading-tight">
                                                {{ $lowongan->judul }}</h3>
                                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1.5 text-sm text-slate-500 mt-2.5 font-medium">
                                                <span class="flex items-center gap-1.5"><i class="bi bi-geo-alt text-cesco-orange"></i>{{ $lokasi }}</span>
                                                <span class="hidden sm:block w-1 h-1 bg-slate-300 rounded-full"></span>
                                                @if ($jumlahKebutuhan)
                                                    <span class="flex items-center gap-1.5"><i class="bi bi-people-fill text-cesco-orange"></i>Dibutuhkan {{ $jumlahKebutuhan }} Orang</span>
                                                    <span class="hidden sm:block w-1 h-1 bg-slate-300 rounded-full"></span>
                                                @endif
                                                <span class="flex items-center gap-1.5 {{ $isUrgent ? 'text-red-600 font-semibold' : '' }}"><i class="bi bi-calendar3"></i> Ditutup:
                                                    {{ $tanggalTutup->format('d M Y') }}</span>
                                            </div>
                                        </div>

                                        <div class="bg-slate-50 p-4 rounded-lg border border-slate-100">
                                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">
                                                Persyaratan Utama:</h4>
                                            <div class="space-y-2">
                                                @foreach ($allKualifikasi->take(3) as $item)
                                                    <div class="flex items-start gap-2.5 text-sm text-slate-600">
                                                        <i class="bi bi-check2-square text-cesco-blue mt-0.5 shrink-0"></i>
                                                        <span class="leading-relaxed">{{ $item }}</span>
                                                    </div>
                                                @endforeach
                                                @if ($allKualifikasi->count() > 3)
                                                    <div
                                                        class="text-xs text-cesco-orange font-semibold mt-2 pl-6 flex items-center gap-1.5">
                                                        <i class="bi bi-plus-circle"></i> {{ $allKualifikasi->count() - 3 }}
                                                        kualifikasi lainnya
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="border-t border-slate-100 pt-5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                                        <span class="text-xs text-slate-400 font-medium flex items-center">
                                            <i class="bi bi-clock-history mr-1.5"></i> Diposting
                                            {{ \Carbon\Carbon::parse($lowongan->tanggal_dibuka)->diffForHumans() }}
                                        </span>
                                        @if ($canApply)
                                            @if ($sudahMelamar)
                                                <button disabled onclick="event.stopPropagation();"
                                                    class="w-full sm:w-auto bg-emerald-50 text-emerald-700 border border-emerald-200 px-6 py-2.5 rounded-lg font-semibold text-sm cursor-not-allowed flex items-center justify-center gap-2">
                                                    <i class="bi bi-check-circle-fill"></i> Lamaran Sudah Dikirim
                                                </button>
                                            @elseif ($statusLowongan === 'dibuka')
                                                <button onclick="event.stopPropagation(); triggerApplyProcess('{{ $lowongan->id }}', '{{ addslashes($lowongan->judul) }}')"
                                                    class="w-full sm:w-auto bg-cesco-blue hover:bg-slate-800 text-white px-6 py-2.5 rounded-lg font-semibold text-sm transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow-md">
                                                    Lamar Sekarang <i class="bi bi-send"></i>
                                                </button>
                                            @else
                                                <button disabled onclick="event.stopPropagation();"
                                                    class="w-full sm:w-auto bg-slate-200 text-slate-400 px-6 py-2.5 rounded-lg font-semibold text-sm cursor-not-allowed flex items-center justify-center gap-2">
                                                    <i class="bi bi-lock-fill"></i> Lowongan {{ ucfirst($statusLowongan) }}
                                                </button>
                                            @endif
                                        @endif
                                    </div>

                                    <div class="hidden modal-data" data-id="{{ $lowongan->id }}"
                                        data-title="{{ $lowongan->judul }}" data-dept="{{ $deptName }}"
                                        data-type="{{ $tipeKerja }}" data-location="{{ $lokasi }}"
                                        data-date="{{ $tanggalDibuat }}" data-status="{{ $statusLowongan }}"
                                        data-kuota="{{ $jumlahKebutuhan }}" data-posisi="{{ $posisiDibutuhkan }}"
                                        data-pelamar="{{ $lowongan->lamaran_count }}"
                                        data-applied="{{ $sudahMelamar ? 'true' : 'false' }}">
                                        <div class="modal-requirements">
                                            @if ($allKualifikasi->count())
                                                @foreach ($allKualifikasi as $item)
                                                    <div
                                                        class="flex items-start gap-3 bg-slate-50 p-3.5 rounded-lg border border-slate-100">
                                                        <i
                                                            class="bi bi-check-circle-fill text-emerald-500 mt-0.5 shrink-0 text-base"></i>
                                                        <span class="text-slate-700 text-sm leading-relaxed">{{ $item }}</span>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div
                                                    class="text-slate-500 italic col-span-full py-2 bg-slate-50 p-4 rounded-lg text-center">
                                                    Belum ada persyaratan khusus yang dicantumkan.</div>
                                            @endif
                                        </div>
                                        <div class="modal-description text-sm text-slate-600 leading-relaxed text-justify">
                                            {{ $lowongan->deskripsi ?? 'Silakan lengkapi profil Anda dan penuhi seluruh persyaratan kualifikasi teknis yang tertera untuk melamar posisi ini.' }}
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div id="no-jobs-fallback"
                                    class="text-center py-16 bg-white border border-slate-200 rounded-xl shadow-sm">
                                    <div
                                        class="w-20 h-20 mx-auto bg-slate-50 rounded-full flex items-center justify-center text-slate-300 text-4xl mb-5 border border-slate-100">
                                        <i class="bi bi-inbox"></i>
                                    </div>
                                    <h3 class="font-bold text-slate-800 text-xl">Belum Ada Lowongan Aktif</h3>
                                    <p class="text-slate-500 text-sm mt-2 max-w-sm mx-auto">Saat ini tidak ada lowongan yang
                                        sesuai dengan kriteria pencarian Anda.</p>
                                    <a href="{{ route('career') }}"
                                        class="inline-block mt-5 text-sm bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg font-medium transition-colors">Reset
                                        Filter</a>
                                </div>
                            @endforelse
                        @endif
                    </div>

                    @if (isset($lowongans) && $lowongans->hasPages())
                        <div class="mt-8 border-t border-slate-200 pt-6">{{ $lowongans->links() }}</div>
                    @endif
                </section>
            </div>
        </div>
    </main>

    <div id="jobDetailModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 sm:p-6" role="dialog"
        aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeJobModal()"></div>
        <div
            class="relative bg-white rounded-2xl w-full max-w-3xl max-h-[90vh] mx-auto shadow-2xl flex flex-col animate-modal-in overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-start bg-slate-50">
                <div class="space-y-3 pr-4">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span id="modalDept"
                            class="px-2.5 py-1 rounded bg-blue-50 text-cesco-blue text-xs font-bold border border-blue-100"></span>
                        <span id="modalType"
                            class="px-2.5 py-1 rounded text-xs font-bold uppercase tracking-wider border"></span>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-bold text-slate-900 leading-tight" id="modalTitle"></h2>
                    <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500 font-medium">
                        <span class="flex items-center gap-1.5"><i
                                class="bi bi-geo-alt-fill text-cesco-orange"></i><span id="modalLocation"></span></span>
                        <span class="flex items-center gap-1.5"><i class="bi bi-people-fill text-cesco-orange"></i><span id="modalPelamar"></span> Orang Telah Melamar</span>
                        <span class="flex items-center gap-1.5"><i
                                class="bi bi-calendar2-check text-cesco-orange"></i>Dibuka: <span
                                id="modalDate"></span></span>
                    </div>
                </div>
                <button onclick="closeJobModal()"
                    class="text-slate-400 hover:text-slate-800 hover:bg-slate-200 p-2 rounded-full transition-colors shrink-0">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>

            <div class="p-6 md:p-8 overflow-y-auto custom-scrollbar flex-grow">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 mb-4 border-l-4 border-cesco-blue pl-3">Kualifikasi &
                        Persyaratan</h3>
                    <div id="modalReqs" class="space-y-3 text-sm grid sm:grid-cols-2 gap-x-4"></div>
                </div>
            </div>

            <div class="px-6 py-5 border-t border-slate-100 bg-slate-50 flex flex-col sm:flex-row justify-between items-center gap-4">
                @if ($canApply)
                    <p class="text-xs text-slate-500 flex items-center gap-2">
                        <i class="bi bi-info-circle text-cesco-blue"></i> Pastikan profil Anda lengkap 100% sebelum mengirim lamaran.
                    </p>
                @else
                    <p class="text-xs text-slate-500 flex items-center gap-2">
                        <i class="bi bi-eye text-cesco-blue"></i> Anda masuk sebagai <span class="font-semibold uppercase">{{ $userRole }}</span> — hanya dapat melihat informasi lowongan.
                    </p>
                @endif
                <div class="flex gap-3 w-full sm:w-auto">
                    <button onclick="closeJobModal()"
                        class="px-5 py-2.5 rounded-lg font-semibold text-sm text-slate-600 bg-white border border-slate-300 hover:bg-slate-100 transition-colors w-full sm:w-auto">Tutup</button>
                    <button id="modalApplyBtn"
                        class="px-6 py-2.5 rounded-lg font-semibold text-sm text-white bg-cesco-orange hover:bg-orange-600 shadow-md transition-all w-full sm:w-auto flex items-center justify-center gap-2">
                        Kirim Lamaran <i class="bi bi-send-check"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form id="applyForm" method="POST" action="" class="hidden">
        @csrf
    </form>

    <footer class="bg-cesco-blue text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-10">
                <div>
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 bg-cesco-orange rounded-lg flex items-center justify-center shrink-0">
                            <i class="bi bi-briefcase-fill text-white text-lg"></i>
                        </div>
                        <span class="font-display font-bold text-xl tracking-wide">CESCO <span
                                class="text-cesco-orange">CAREERS</span></span>
                    </div>
                    <p class="text-slate-300 text-sm leading-relaxed">Portal rekrutmen resmi PT. Cesco Offshore and
                        Engineering.</p>
                </div>

                <div>
                    <h4 class="font-bold text-sm uppercase tracking-wider text-slate-400 mb-5">Tautan Cepat</h4>
                    <ul class="space-y-3 text-sm text-slate-300">
                        <li><a href="{{ url('/') }}"
                                class="hover:text-cesco-orange transition-colors flex items-center gap-2"><i
                                    class="bi bi-chevron-right text-[10px]"></i> Beranda</a></li>
                        <li><a href="{{ route('career') }}"
                                class="hover:text-cesco-orange transition-colors flex items-center gap-2"><i
                                    class="bi bi-chevron-right text-[10px]"></i> Semua Lowongan</a></li>
                        <li><a href="{{ url('/') }}#about"
                                class="hover:text-cesco-orange transition-colors flex items-center gap-2"><i
                                    class="bi bi-chevron-right text-[10px]"></i> Tentang Kami</a></li>
                        @if (!$isLoggedIn)
                            <li><a href="{{ route('login') }}"
                                    class="hover:text-cesco-orange transition-colors flex items-center gap-2"><i
                                        class="bi bi-chevron-right text-[10px]"></i> Login Pelamar</a></li>
                        @endif
                    </ul>
                </div>

                <div>
                    <h4 class="font-bold text-sm uppercase tracking-wider text-slate-400 mb-5">Hubungi HR Kami</h4>
                    <ul class="space-y-4 text-sm text-slate-300">
                        <li class="flex items-center gap-3"><i
                                class="bi bi-envelope-fill text-cesco-orange text-lg shrink-0"></i>
                            recruitment@cesco.co.id</li>
                        <li class="flex items-center gap-3"><i
                                class="bi bi-telephone-fill text-cesco-orange text-lg shrink-0"></i> +62 778 xxx xxxx
                        </li>
                        <li class="flex items-center gap-3"><i
                                class="bi bi-geo-alt-fill text-cesco-orange text-lg shrink-0"></i> Batam Yard, Kepulauan
                            Riau</li>
                    </ul>
                </div>
            </div>

            <div
                class="mt-12 pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-400">
                <span>© {{ date('Y') }} PT. Cesco Offshore and Engineering. All rights reserved.</span>
                @if ($isLoggedIn)
                    <a href="{{ $dashboardRoute }}" class="hover:text-white transition-colors flex items-center gap-1">
                        Pergi ke Dashboard <i class="bi bi-arrow-right"></i>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hover:text-white transition-colors flex items-center gap-1">
                        Login Portal Pelamar <i class="bi bi-arrow-right"></i>
                    </a>
                @endif
            </div>
        </div>
    </footer>

    <script>
        const IS_LOGGED_IN = @json($isLoggedIn);
        const IS_PROFILE_COMPLETE = @json($isProfileComplete);
        const USER_ROLE = @json($userRole);
        const APPLY_URL_TEMPLATE = "{{ route('pelamar.lamaran.store', ['lowonganId' => '__ID__']) }}";

        function openJobModal(cardElement) {
            const d = cardElement.querySelector('.modal-data');
            const jobId = d.dataset.id;
            const jobStatus = d.dataset.status;
            const isApplied = d.dataset.applied === 'true';

            document.getElementById('modalTitle').textContent = d.dataset.title;
            document.getElementById('modalDept').textContent = d.dataset.dept;
            document.getElementById('modalLocation').textContent = d.dataset.location;
            document.getElementById('modalDate').textContent = d.dataset.date;
            document.getElementById('modalPelamar').textContent = d.dataset.pelamar;
            document.getElementById('modalReqs').innerHTML = d.querySelector('.modal-requirements').innerHTML;

            const typeBadge = document.getElementById('modalType');
            typeBadge.className = 'px-2.5 py-1 rounded text-xs font-bold uppercase tracking-wider border';
            const badgeMap = {
                dibuka: ['bg-emerald-100', 'text-emerald-800', 'border-emerald-200', 'DIBUKA'],
                ditutup: ['bg-rose-100', 'text-rose-800', 'border-rose-200', 'DITUTUP'],
                draft: ['bg-amber-100', 'text-amber-800', 'border-amber-200', 'DRAFT'],
            };
            const [bg, text, border, label] = badgeMap[jobStatus] ?? ['bg-slate-100', 'text-slate-800', 'border-slate-200', jobStatus.toUpperCase()];
            typeBadge.classList.add(bg, text, border);
            typeBadge.textContent = label;

            const tombolLamar  = document.getElementById('modalApplyBtn');
            if (isApplied) {
                tombolLamar.classList.remove('hidden');
                tombolLamar.disabled = true;
                tombolLamar.className = 'px-6 py-2.5 rounded-lg font-semibold text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 cursor-not-allowed w-full sm:w-auto flex items-center justify-center gap-2';
                tombolLamar.innerHTML = '<i class="bi bi-check-circle-fill"></i> Lamaran Sudah Dikirim';
                tombolLamar.onclick = null;
            } else if (jobStatus === 'dibuka' && USER_ROLE === 'pelamar') {
                tombolLamar.classList.remove('hidden');
                tombolLamar.disabled = false;
                tombolLamar.className = 'px-6 py-2.5 rounded-lg font-semibold text-sm text-white bg-cesco-orange hover:bg-orange-600 shadow-md transition-all w-full sm:w-auto flex items-center justify-center gap-2';
                tombolLamar.innerHTML = 'Kirim Lamaran <i class="bi bi-send-check"></i>';
                tombolLamar.onclick = () => { closeJobModal(); setTimeout(() => triggerApplyProcess(jobId, d.dataset.title), 300); };
            } else if (jobStatus !== 'dibuka') {
                tombolLamar.classList.remove('hidden');
                tombolLamar.disabled  = true;
                tombolLamar.className = 'px-6 py-2.5 rounded-lg font-semibold text-sm text-slate-400 bg-slate-200 cursor-not-allowed w-full sm:w-auto flex items-center justify-center gap-2';
                tombolLamar.innerHTML = '<i class="bi bi-lock-fill"></i> Tidak Tersedia';
                tombolLamar.onclick   = null;
 
            } else {
                tombolLamar.classList.add('hidden');
            }

            const modal = document.getElementById('jobDetailModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeJobModal() {
            const modal = document.getElementById('jobDetailModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeJobModal(); });

        function submitApplyForm(jobId) {
            const form = document.getElementById('applyForm');
            form.action = APPLY_URL_TEMPLATE.replace('__ID__', jobId);
            form.submit();
        }

        function triggerApplyProcess(jobId, jobTitle) {
            if (!IS_LOGGED_IN) {
                Swal.fire({
                    title: 'Autentikasi Diperlukan',
                    html: `<div class="text-left space-y-3 text-sm mt-2">
                        <p class="font-semibold text-slate-700">Anda ingin melamar posisi:</p>
                        <div class="p-3 bg-slate-50 border border-slate-200 rounded-lg text-cesco-blue font-bold text-center">${jobTitle}</div>
                        <p class="text-slate-500 text-xs">Silakan masuk atau daftar terlebih dahulu untuk melanjutkan.</p>
                    </div>`,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#003366',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Masuk / Daftar',
                    cancelButtonText: 'Batal',
                    customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-lg px-6', cancelButton: 'rounded-lg px-6' }
                }).then(r => { if (r.isConfirmed) window.location.href = "{{ route('login') }}"; });
                return;
            }

            if (!IS_PROFILE_COMPLETE) {
                Swal.fire({
                    title: 'Profil Belum Lengkap!',
                    html: `<div class="text-sm text-slate-600 text-left space-y-3 mt-2">
                        <p>Mohon lengkapi data profil Anda terlebih dahulu, meliputi:</p>
                        <div class="bg-orange-50 border border-orange-100 p-3 rounded-lg">
                            <ul class="list-disc list-inside font-semibold text-xs text-cesco-orange space-y-1">
                                <li>Data Diri (Kelamin, No HP, Alamat)</li>
                                <li>Riwayat Pendidikan & Pengalaman</li>
                                <li>Dokumen Wajib (Foto, KTP, KK, CV, Ijazah)</li>
                            </ul>
                        </div>
                    </div>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#FF6600',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: 'Lengkapi Profil',
                    cancelButtonText: 'Nanti',
                    customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-lg px-6', cancelButton: 'rounded-lg px-6' }
                }).then(r => { if (r.isConfirmed) window.location.href = "{{ route('pelamar.profile') }}"; });
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Lamaran',
                text: `Apakah Anda yakin ingin mengirim lamaran untuk posisi ${jobTitle}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#003366',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Kirim Lamaran',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-lg px-6', cancelButton: 'rounded-lg px-6' }
            }).then(r => {
                if (r.isConfirmed) {
                    Swal.fire({
                        title: 'Mengirim Lamaran...',
                        html: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => Swal.showLoading(),
                        customClass: { popup: 'rounded-2xl' }
                    });
                    submitApplyForm(jobId);
                }
            });
        }

        @if (session('success'))
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    title: 'Berhasil!',
                    text: @json(session('success')),
                    icon: 'success',
                    confirmButtonColor: '#003366',
                    confirmButtonText: 'Oke',
                    customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-lg px-6' }
                });
            });
        @elseif (session('error'))
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    title: 'Gagal Mengirim Lamaran',
                    text: @json(session('error')),
                    icon: 'error',
                    confirmButtonColor: '#003366',
                    confirmButtonText: 'Oke',
                    customClass: { popup: 'rounded-2xl', confirmButton: 'rounded-lg px-6' }
                });
            });
        @endif
    </script>
</body>

</html>