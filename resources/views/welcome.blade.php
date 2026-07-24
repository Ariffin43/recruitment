<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cesco Careers | PT. Cesco Offshore and Engineering</title>
    <meta name="description"
        content="Portal karir resmi PT. Cesco Offshore and Engineering. Temukan peluang karir terbaik di bidang teknik maritim, petrokimia, dan industri konstruksi.">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
                        cesco: {
                            blue: '#003366',
                            orange: '#FF6600',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        .hero-pattern {
            background-color: #003366;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>

<body class="font-sans text-slate-800 bg-white flex flex-col min-h-screen">
    @php
        $user = auth()->user();
        $isLoggedIn = auth()->check();
        $isProfileComplete = isset($isProfileComplete) ? $isProfileComplete : false;
        $userRole = $isLoggedIn ? $user->role : null;

        $dashboardRoute = match($userRole) {
            'hrd', 'gm' => route('hrd.dashboard'),
            'hod'       => route('hod.dashboard'),
            'pelamar'   => route('pelamar.dashboard'),
            default     => url('/'),
        };

    @endphp

    {{-- NAVBAR --}}
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
                    <a href="{{ url('/') }}" class="px-4 py-2 rounded-md text-slate-200 font-medium hover:text-cesco-orange transition-colors text-sm">Beranda</a>
                    <a href="{{ route('career') }}" class="px-4 py-2 rounded-md text-white font-medium hover:text-cesco-orange transition-colors text-sm">Lowongan</a>
                    <a href="{{ url('/') }}#about" class="px-4 py-2 rounded-md text-slate-200 font-medium hover:text-cesco-orange transition-colors text-sm">Tentang Kami</a>
                    <a href="{{ url('/') }}#culture" class="px-4 py-2 rounded-md text-slate-200 font-medium hover:text-cesco-orange transition-colors text-sm">Budaya Kerja</a>
                    <span class="h-6 w-px bg-slate-600 mx-2"></span>

                    @if ($isLoggedIn)
                        <a href="{{ $dashboardRoute }}" class="bg-orange-600 hover:bg-orange-700 text-white px-5 py-2 rounded-md font-semibold transition-all shadow-md flex items-center gap-1.5 text-sm">
                            <i class="bi bi-grid-fill"></i> Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="ml-2 px-4 py-2 rounded-md text-slate-300 hover:text-red-500 font-medium transition-colors text-sm flex items-center gap-1.5">
                                <i class="bi bi-box-arrow-right"></i> Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="bg-cesco-orange hover:bg-orange-600 text-white px-5 py-2 rounded-md font-semibold transition-all shadow-md flex items-center gap-1.5 text-sm">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </a>
                    @endif
                </div>

                <button id="mobile-menu-btn" class="md:hidden text-white hover:text-cesco-orange p-2 rounded-md transition-colors" onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                    <i class="bi bi-list text-3xl"></i>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="hidden md:hidden bg-slate-900 border-t border-slate-800">
            <div class="px-4 pt-2 pb-5 space-y-1">
                <a href="{{ url('/') }}" class="block px-4 py-3 text-slate-300 font-medium hover:bg-slate-800 rounded-md transition-colors">Beranda</a>
                <a href="{{ route('career') }}" class="block px-4 py-3 text-white font-medium bg-slate-800 rounded-md">Lowongan</a>
                <a href="{{ url('/') }}#about" class="block px-4 py-3 text-slate-300 font-medium hover:bg-slate-800 rounded-md transition-colors">Tentang Kami</a>
                <a href="{{ url('/') }}#culture" class="block px-4 py-3 text-slate-300 font-medium hover:bg-slate-800 rounded-md transition-colors">Budaya Kerja</a>
                <div class="pt-3 border-t border-slate-800 space-y-2">
                    @if ($isLoggedIn)
                        <a href="{{ $dashboardRoute }}" class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white text-center py-3 rounded-md font-bold transition-colors">
                            <i class="bi bi-grid-fill mr-2"></i> Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-slate-300 hover:bg-slate-800 text-center py-3 rounded-md font-medium transition-colors">
                                <i class="bi bi-box-arrow-right mr-2"></i> Keluar
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block w-full bg-cesco-orange hover:bg-orange-600 text-white text-center py-3 rounded-md font-bold transition-colors">
                            <i class="bi bi-box-arrow-in-right mr-2"></i> Login
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>


    <main class="flex-grow pt-16">

        {{-- HERO SECTION --}}
        <section class="hero-pattern py-20 lg:py-32 relative overflow-hidden text-white">
            <div class="absolute inset-0 bg-gradient-to-r from-cesco-blue/95 via-cesco-blue/80 to-transparent"></div>
            <div class="absolute -right-1/4 -top-1/4 w-1/2 h-1/2 rounded-full bg-cesco-orange opacity-10 blur-3xl">
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="grid lg:grid-cols-12 gap-12 items-center">
                    <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                        <div
                            class="inline-flex items-center px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-orange-300 text-xs font-semibold uppercase tracking-wider">
                            <i class="bi bi-patch-check-fill text-cesco-orange mr-2"></i>
                            Portal Rekrutmen Resmi PT. Cesco
                        </div>

                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-display font-bold leading-tight">
                            Mulai Karir Anda di<br>
                            <span class="text-cesco-orange">Kancah Lepas Pantai Global</span>
                        </h1>

                        <p
                            class="text-base sm:text-lg text-slate-200 max-w-xl mx-auto lg:mx-0 font-light leading-relaxed">
                            PT. Cesco Offshore and Engineering adalah penyedia layanan rekayasa mekanikal, konstruksi,
                            pra-komisi kelautan dan petrokimia terkemuka di Batam. Bergabunglah dengan tim profesional
                            kami!
                        </p>

                        <div class="pt-2 flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                            <a href="{{ route('career') }}"
                                class="bg-cesco-orange hover:bg-orange-600 text-white px-8 py-3.5 rounded-md font-bold text-base transition-all shadow-lg flex items-center justify-center gap-1">
                                Cari Lowongan <i class="bi bi-arrow-right-short text-2xl"></i>
                            </a>
                            <a href="#about"
                                class="border border-white/30 text-white hover:bg-white/10 px-8 py-3.5 rounded-md font-bold text-base transition-all flex items-center justify-center">
                                Pelajari Tentang Kami
                            </a>
                        </div>
                    </div>

                    <div class="lg:col-span-5 hidden lg:block">
                        <div
                            class="bg-slate-900/60 backdrop-blur-md rounded-2xl p-6 border border-white/10 shadow-2xl relative">
                            <div
                                class="absolute -top-4 -left-4 w-12 h-12 bg-cesco-orange rounded-xl flex items-center justify-center shadow-lg">
                                <i class="bi bi-gear-fill text-white text-2xl animate-spin"
                                    style="animation-duration:10s"></i>
                            </div>

                            <h3 class="text-xl font-display font-bold text-white pl-10 mb-4 uppercase tracking-wider">
                                Keunggulan Bersama Cesco
                            </h3>

                            <div class="space-y-3">
                                <div class="flex items-start gap-3 bg-white/5 p-3 rounded-lg border border-white/5">
                                    <i class="bi bi-shield-fill-check text-green-400 text-lg mt-0.5"></i>
                                    <div>
                                        <h4 class="font-bold text-sm text-white">Lingkungan Kerja Aman (HSE)</h4>
                                        <p class="text-xs text-slate-300 mt-0.5">Prioritas keselamatan kerja dengan
                                            standar industri kelautan & petrokimia terkini.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 bg-white/5 p-3 rounded-lg border border-white/5">
                                    <i class="bi bi-globe2 text-blue-400 text-lg mt-0.5"></i>
                                    <div>
                                        <h4 class="font-bold text-sm text-white">Layanan Konstruksi & Rekayasa</h4>
                                        <p class="text-xs text-slate-300 mt-0.5">Jasa pra-komisi mekanik dan rekayasa
                                            terkemuka untuk klien skala internasional.</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 bg-white/5 p-3 rounded-lg border border-white/5">
                                    <i class="bi bi-award-fill text-orange-400 text-lg mt-0.5"></i>
                                    <div>
                                        <h4 class="font-bold text-sm text-white">Pengembangan Kompetensi</h4>
                                        <p class="text-xs text-slate-300 mt-0.5">Program kerja berbasis target industri
                                            yang terus mengasah keahlian teknis Anda.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- STATS COUNTER --}}
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 relative z-20">
            <div
                class="bg-white rounded-xl shadow-xl border border-slate-100 p-8 grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="p-4">
                    <p class="text-4xl font-extrabold text-cesco-blue"><i class="bi bi-gear-wide-connected"></i></p>
                    <p class="text-sm font-semibold text-slate-500 mt-1">Pre-Commissioning</p>
                </div>
                <div class="p-4 border-l border-slate-100">
                    <p class="text-4xl font-extrabold text-cesco-orange"><i class="bi bi-droplet-half"></i></p>
                    <p class="text-sm font-semibold text-slate-500 mt-1">Chemical Cleaning</p>
                </div>
                <div class="p-4 border-l border-slate-100">
                    <p class="text-4xl font-extrabold text-cesco-blue"><i class="bi bi-tools"></i></p>
                    <p class="text-sm font-semibold text-slate-500 mt-1">Mechanical & Engineering</p>
                </div>
                <div class="p-4 border-l border-slate-100">
                    <p class="text-4xl font-extrabold text-cesco-orange"><i class="bi bi-buildings"></i></p>
                    <p class="text-sm font-semibold text-slate-500 mt-1">General Construction</p>
                </div>
            </div>
        </section>

        {{-- TENTANG KAMI --}}
        <section id="about" class="py-20 bg-white scroll-mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid lg:grid-cols-2 gap-14 items-center">
                    <div>
                        <span class="text-sm font-bold text-cesco-orange tracking-widest uppercase">Mengenal
                            Perusahaan</span>
                        <h2 class="text-3xl sm:text-4xl font-display font-bold text-cesco-blue mt-2 mb-6">
                            PT. Cesco Offshore and Engineering
                        </h2>
                        <p class="text-slate-600 mb-4 leading-relaxed">
                            PT Cesco Offshore and Engineering adalah perusahaan penyedia layanan penyelesaian
                            (completion) dan pra-komisi mekanik terkemuka. Kami berfokus untuk melayani industri
                            kelautan dan petrokimia, serta menawarkan layanan rekayasa konstruksi untuk industri secara
                            umum.
                        </p>
                        <p class="text-slate-600 mb-8 leading-relaxed">
                            Bermarkas strategis di Kawasan Intan Industrial Park, Batu Ampar, Kota Batam, kami membekali
                            setiap proyek klien dengan metodologi pengerjaan mutakhir, baik pada tahapan chemical
                            cleaning, evaluasi CPM (Critical Path Method), hingga jaminan standar keselamatan kerja
                            (HSE) yang prima.
                        </p>

                        <div class="grid grid-cols-2 gap-4">
                            @foreach (['Pre-commissioning Mekanik', 'Industri Petrokimia & Marine', 'Rekayasa & Konstruksi', 'Perawatan & Pembersihan Kimia'] as $item)
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-cesco-blue shrink-0">
                                        <i class="bi bi-check-lg font-bold"></i>
                                    </div>
                                    <span class="font-semibold text-sm">{{ $item }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="relative">
                        <div
                            class="bg-slate-200 rounded-2xl overflow-hidden shadow-xl aspect-video border-4 border-white">
                            <img src="https://placehold.co/800x450/003366/ffffff?text=Cesco+Yard+Batam"
                                alt="Cesco Batam Yard" class="w-full h-full object-cover">
                        </div>
                        <div
                            class="absolute -bottom-6 -right-6 bg-cesco-blue p-4 rounded-xl text-white shadow-xl max-w-xs hidden sm:block">
                            <p class="text-xs font-semibold text-orange-400 uppercase tracking-widest">Pusat
                                Operasional Utama</p>
                            <p class="text-sm font-bold mt-1">Kawasan Intan Industrial Park Blok B No. 1, Batu Ampar,
                                Batam</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- BUDAYA KERJA --}}
        <section id="culture" class="py-20 bg-white scroll-mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-14">
                    <span class="text-sm font-bold text-cesco-orange tracking-widest uppercase">Budaya Kerja
                        Kami</span>
                    <h2 class="text-3xl md:text-4xl font-display font-bold text-cesco-blue mt-2">
                        Bekerja dengan Nilai, Presisi, & Keamanan
                    </h2>
                    <div class="w-20 h-1 bg-cesco-orange mx-auto mt-4 rounded"></div>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="p-6 text-center space-y-4">
                        <div
                            class="w-16 h-16 mx-auto bg-orange-50 rounded-full flex items-center justify-center text-cesco-orange text-3xl">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <h3 class="text-xl font-bold">Zero Accident Culture</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Di Cesco, tidak ada pekerjaan yang terlalu mendesak sehingga tidak dapat dikerjakan dengan
                            aman. Kami bangga atas rekam jejak nihil kecelakaan kerja kami.
                        </p>
                    </div>

                    <div class="p-6 text-center space-y-4 border-y md:border-y-0 md:border-x border-slate-100">
                        <div
                            class="w-16 h-16 mx-auto bg-blue-50 rounded-full flex items-center justify-center text-cesco-blue text-3xl">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h3 class="text-xl font-bold">Kolaborasi Ahli</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Tim kami terdiri dari para ahli teknikal yang merencanakan ketepatan waktu dengan evaluasi
                            matang, memastikan kelancaran operasional pra-komisi bagi seluruh pelanggan.
                        </p>
                    </div>

                    <div class="p-6 text-center space-y-4">
                        <div
                            class="w-16 h-16 mx-auto bg-green-50 rounded-full flex items-center justify-center text-emerald-600 text-3xl">
                            <i class="bi bi-lightbulb-fill"></i>
                        </div>
                        <h3 class="text-xl font-bold">Inovasi Berkelanjutan</h3>
                        <p class="text-slate-600 text-sm leading-relaxed">
                            Mengadopsi pendekatan terstruktur dan rekayasa mutakhir untuk meminimalkan risiko biaya
                            sembari mempertahankan kualitas penyelesaian proyek tingkat tinggi.
                        </p>
                    </div>
                </div>
            </div>
        </section>

    </main>

    {{-- FOOTER --}}
    <footer class="bg-cesco-blue text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid md:grid-cols-3 gap-8">
                {{-- Brand --}}
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 bg-cesco-orange rounded-lg flex items-center justify-center">
                            <i class="bi bi-briefcase-fill text-white"></i>
                        </div>
                        <span class="font-display font-bold text-xl tracking-wide">
                            CESCO <span class="text-cesco-orange">CAREERS</span>
                        </span>
                    </div>
                    <p class="text-slate-300 text-sm leading-relaxed">
                        Portal rekrutmen resmi PT. Cesco Offshore and Engineering.
                    </p>
                </div>

                {{-- Navigasi --}}
                <div>
                    <h4 class="font-bold text-sm uppercase tracking-wider text-slate-400 mb-4">Navigasi</h4>
                    <ul class="space-y-2 text-sm text-slate-300">
                        <li><a href="{{ url('/') }}"
                                class="hover:text-cesco-orange transition-colors">Beranda</a></li>
                        <li><a href="{{ route('career') }}"
                                class="hover:text-cesco-orange transition-colors">Lowongan Kerja</a></li>
                        <li><a href="#about" class="hover:text-cesco-orange transition-colors">Tentang Kami</a></li>
                        <li><a href="#culture" class="hover:text-cesco-orange transition-colors">Budaya Kerja</a></li>
                    </ul>
                </div>

                {{-- Kontak --}}
                <div>
                    <h4 class="font-bold text-sm uppercase tracking-wider text-slate-400 mb-4">Kontak HR</h4>
                    <ul class="space-y-3 text-sm text-slate-300">
                        <li class="flex items-center gap-2">
                            <i class="bi bi-envelope-fill text-cesco-orange"></i> salesmarketing@cesco.co.id
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="bi bi-envelope-fill text-cesco-orange"></i> salesmarketing2@cesco.co.id
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="bi bi-telephone-fill text-cesco-orange mt-0.5"></i>
                            <div>(0778) 5502088</div>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="bi bi-geo-alt-fill text-cesco-orange mt-0.5"></i>
                            <div>Kawasan Intan Industrial Park Blok B No.1, Tj. Sengkuang, Kec. Batu Ampar, Kota Batam
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div
                class="mt-10 pt-6 border-t border-white/10 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-slate-400">
                <span>© {{ date('Y') }} PT. Cesco Offshore and Engineering. All rights reserved.</span>
                <a href="{{ route('login') }}" class="hover:text-cesco-orange transition-colors">
                    Login Portal Pelamar →
                </a>
            </div>
        </div>
    </footer>

</body>

</html>
