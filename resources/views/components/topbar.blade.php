@props([
    'title'    => 'Dashboard',
    'subtitle' => '',
])

@php
    $user    = auth()->user();
    $role    = $user->role ?? null;

    $config = match($role) {
        'hrd'     => ['color' => '#145D9E', 'accent' => 'text-blue-600',   'ring' => 'ring-blue-200'],
        'hod'     => ['color' => '#4338CA', 'accent' => 'text-indigo-600', 'ring' => 'ring-indigo-200'],
        'gm'      => ['color' => '#0F766E', 'accent' => 'text-teal-600',   'ring' => 'ring-teal-200'],
        'pelamar' => ['color' => '#7C3AED', 'accent' => 'text-violet-600', 'ring' => 'ring-violet-200'],
        default   => ['color' => '#374151', 'accent' => 'text-gray-600',   'ring' => 'ring-gray-200'],
    };

    $brandColor  = $config['color'];
    $accentClass = $config['accent'];

    $userName    = $user->nama ?? 'User';
    $userEmail   = $user->email ?? '-';
    $userInitial = strtoupper(substr($userName, 0, 1));
    $userRole    = strtoupper($role ?? '-');

    if (empty($subtitle)) {
        $subtitle = match($role) {
            'hrd'     => 'Ringkasan rekrutmen dan aktivitas hari ini.',
            'hod'     => 'Kelola pengajuan FPTK departemen Anda.',
            'gm'      => 'Tinjau dan proses persetujuan FPTK.',
            'pelamar' => 'Pantau status lamaran Anda.',
            default   => 'Selamat datang di portal.',
        };
    }
@endphp

<header class="bg-white border-b border-gray-200 sticky top-0 z-30">
    <div class="px-4 lg:px-6 h-16 flex items-center justify-between gap-4">

        <div class="flex items-center gap-3 min-w-0">
            <button type="button" onclick="toggleSidebar()"
                class="w-10 h-10 rounded-xl border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition shrink-0">
                <i class="bi bi-list text-xl"></i>
            </button>
            <div class="min-w-0">
                <div class="text-base font-bold text-gray-900 leading-tight truncate">{{ $title }}</div>
                <div class="text-xs text-gray-400 leading-tight truncate hidden sm:block">{{ $subtitle }}</div>
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">

            <div class="relative">
                <button id="notifBtn" type="button"
                    class="w-10 h-10 rounded-xl border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition relative">
                    <i class="bi bi-bell"></i>
                    {{-- badge --}}
                    {{-- <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-red-500"></span> --}}
                </button>

                <div id="notifDropdown"
                    class="hidden absolute right-0 mt-2 w-80 max-w-[90vw] bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden z-50">
                    <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100">
                        <span class="text-sm font-bold text-gray-800">Notifikasi</span>
                        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">0 belum dibaca</span>
                    </div>
                    <div class="py-12 text-center">
                        <i class="bi bi-bell-slash text-gray-200 text-3xl"></i>
                        <div class="text-sm text-gray-400 font-medium mt-2">Belum ada notifikasi</div>
                    </div>
                </div>
            </div>

            <div class="relative">
                <button id="profileBtn" type="button"
                    class="flex items-center gap-2 h-10 px-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                    <div class="w-7 h-7 rounded-full text-white text-xs font-bold flex items-center justify-center shrink-0"
                        style="background-color: {{ $brandColor }}">
                        {{ $userInitial }}
                    </div>
                    <span class="hidden sm:block text-sm font-medium text-gray-700 max-w-[120px] truncate">{{ $userName }}</span>
                    <i class="bi bi-chevron-down text-gray-400 text-xs"></i>
                </button>

                <div id="profileDropdown"
                    class="hidden absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden z-50">

                    <div class="px-5 py-4 border-b border-gray-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full text-white text-sm font-bold flex items-center justify-center shrink-0"
                                style="background-color: {{ $brandColor }}">
                                {{ $userInitial }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-bold text-gray-800 truncate">{{ $userName }}</div>
                                <div class="text-xs text-gray-400 truncate">{{ $userEmail }}</div>
                                <span class="inline-block mt-1 text-[10px] font-semibold px-2 py-0.5 rounded-full text-white" style="background-color: {{ $brandColor }}">
                                    {{ $userRole }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="p-2">
                        <a href="#"
                            class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 text-sm font-medium text-gray-700 transition">
                            <i class="bi bi-person text-base"></i> Profil Saya
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-red-50 text-sm font-semibold text-red-600 transition">
                                <i class="bi bi-box-arrow-right text-base"></i> Keluar
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>
</header>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var notifBtn        = document.getElementById('notifBtn');
    var notifDropdown   = document.getElementById('notifDropdown');
    var profileBtn      = document.getElementById('profileBtn');
    var profileDropdown = document.getElementById('profileDropdown');

    function closeAll() {
        notifDropdown?.classList.add('hidden');
        profileDropdown?.classList.add('hidden');
    }

    function toggle(el) {
        var hidden = el.classList.contains('hidden');
        closeAll();
        if (hidden) el.classList.remove('hidden');
    }

    notifBtn?.addEventListener('click',   function (e) { e.stopPropagation(); toggle(notifDropdown); });
    profileBtn?.addEventListener('click', function (e) { e.stopPropagation(); toggle(profileDropdown); });

    document.addEventListener('click',   closeAll);
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeAll(); });
});
</script>
@endpush