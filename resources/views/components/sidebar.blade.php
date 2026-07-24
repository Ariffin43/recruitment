@php
    $user = auth()->user();
    $role = $user->role ?? null;

    $config = match ($role) {
        'hrd' => ['color' => '#145D9E', 'bg' => '#EFF6FF', 'name' => 'HRD Portal', 'initial_bg' => 'bg-blue-100 text-blue-700'],
        'hod' => ['color' => '#4338CA', 'bg' => '#EEF2FF', 'name' => 'HOD Portal', 'initial_bg' => 'bg-indigo-100 text-indigo-700'],
        'gm' => ['color' => '#0F766E', 'bg' => '#F0FDFA', 'name' => 'GM Portal', 'initial_bg' => 'bg-teal-100 text-teal-700'],
        'pelamar' => ['color' => '#7C3AED', 'bg' => '#F5F3FF', 'name' => 'Pelamar Portal', 'initial_bg' => 'bg-violet-100 text-violet-700'],
        default => ['color' => '#374151', 'bg' => '#F9FAFB', 'name' => 'Portal', 'initial_bg' => 'bg-gray-100 text-gray-700'],
    };

    $brandColor = $config['color'];
    $activeBg = $config['bg'];
    $activeColor = $config['color'];
    $portalName = $config['name'];
    $userName = $user->nama ?? 'User';
    $userInitial = strtoupper(substr($userName, 0, 1));
@endphp

<aside id="sidebar"
    class="fixed lg:static inset-y-0 left-0 z-50 w-[280px] bg-white border-r border-gray-200 flex flex-col transition-all duration-200 ease-in-out -translate-x-full lg:translate-x-0">

    <div class="px-5 py-5 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-bold text-lg shrink-0"
            style="background-color: {{ $brandColor }}">
            C
        </div>
        <div class="flex-1 sidebar-text overflow-hidden">
            <div class="font-semibold text-sm leading-tight truncate">{{ $portalName }}</div>
            <div class="text-xs text-gray-400 leading-tight truncate">Cesco Offshore & Engineering</div>
        </div>
        <button id="sidebarCloseBtn" type="button"
            class="lg:hidden w-9 h-9 rounded-xl border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 shrink-0">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-2 space-y-0.5">

        @if ($role === 'hrd')

            <a href="{{ route('hrd.dashboard') }}" @class(['sidebar-link', 'sidebar-active' => request()->is('hrd/dashboard')])>
                <i class="bi bi-grid-1x2"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>

            <a href="{{ route('hrd.lowongan.index') }}" @class(['sidebar-link', 'sidebar-active' => request()->is('hrd/lowongan*')])>
                <i class="bi bi-briefcase"></i>
                <span class="sidebar-text">Lowongan</span>
            </a>

            <a href="{{ route('hrd.approval.index') }}" @class(['sidebar-link', 'sidebar-active' => request()->is('hrd/approval')])>
                <i class="bi bi-check2-square"></i>
                <span class="sidebar-text">Persetujuan FPTK</span>
            </a>

            <a href="{{ route('hrd.approval-pelamar.index') }}" @class(['sidebar-link', 'sidebar-active' => request()->is('hrd/approval-pelamar')])>
                <i class="bi bi-check2-square"></i>
                <span class="sidebar-text">Persetujuan Lamaran</span>
            </a>

            <div class="pt-4 pb-1 px-2 sidebar-text">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Master Data</span>
            </div>

            <a href="{{ route('hrd.department.index') }}" @class(['sidebar-link', 'sidebar-active' => request()->is('hrd/department*')])>
                <i class="bi bi-diagram-3"></i>
                <span class="sidebar-text">Departemen</span>
            </a>

            <a href="{{ route('hrd.kualifikasi.index') }}" @class(['sidebar-link', 'sidebar-active' => request()->is('hrd/kualifikasi*')])>
                <i class="bi bi-card-checklist"></i>
                <span class="sidebar-text">Kualifikasi</span>
            </a>

            <div class="pt-4 pb-1 px-2 sidebar-text">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Manajemen Akun</span>
            </div>

            <a href="{{ route('hrd.hod.index') }}" @class(['sidebar-link', 'sidebar-active' => request()->is('hrd/akun-hod*')])>
                <i class="bi bi-person-badge"></i>
                <span class="sidebar-text">Akun HOD</span>
            </a>

            <a href="{{ route('hrd.pelamar.index') }}" @class(['sidebar-link', 'sidebar-active' => request()->is('hrd/akun-pelamar*')])>
                <i class="bi bi-people"></i>
                <span class="sidebar-text">Akun Pelamar</span>
            </a>

        @elseif ($role === 'hod')

            <a href="{{ route('hod.dashboard') }}" @class(['sidebar-link', 'sidebar-active' => request()->is('hod/dashboard')])>
                <i class="bi bi-grid-1x2"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>

            <a href="{{ route('hod.fptk.index') }}" @class(['sidebar-link', 'sidebar-active' => request()->is('hod/fptk*')])>
                <i class="bi bi-file-earmark-text"></i>
                <span class="sidebar-text">Pengajuan FPTK</span>
            </a>

        @elseif ($role === 'gm')
        
            <a href="{{ route('gm.approval.index') }}" @class(['sidebar-link', 'sidebar-active' => request()->is('gm/approval*')])>
                <i class="bi bi-check2-square"></i>
                <span class="sidebar-text">Persetujuan FPTK</span>
            </a>

        @elseif ($role === 'pelamar')

            <a href="{{ route('pelamar.dashboard') }}" @class(['sidebar-link', 'sidebar-active' => request()->is('pelamar/dashboard')])>
                <i class="bi bi-grid-1x2"></i>
                <span class="sidebar-text">Dashboard</span>
            </a>

            <a href="{{ route('career') }}" @class(['sidebar-link', 'sidebar-active' => request()->is('careers*')])>
                <i class="bi bi-search"></i>
                <span class="sidebar-text">Cari Lowongan</span>
            </a>

            <a href="{{ route('pelamar.lamaran') }}" @class(['sidebar-link', 'sidebar-active' => request()->is('pelamar/lamaran*')])>
                <i class="bi bi-send"></i>
                <span class="sidebar-text">Lamaran Saya</span>
            </a>

            <a href="{{ route('pelamar.profile') }}" @class(['sidebar-link', 'sidebar-active' => request()->is('pelamar/profil*')])>
                <i class="bi bi-person-lines-fill"></i>
                <span class="sidebar-text">Profil Saya</span>
            </a>

        @endif

    </nav>

    <div class="px-4 py-4 border-t border-gray-100">
        <div class="flex items-center gap-3 px-2 py-2 rounded-xl sidebar-text">
            <div
                class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0 {{ $config['initial_bg'] }}">
                {{ $userInitial }}
            </div>
            <div class="flex-1 min-w-0 sidebar-text">
                <div class="text-sm font-semibold text-gray-800 truncate">{{ $userName }}</div>
                <div class="text-xs text-gray-400 truncate uppercase tracking-wide">{{ strtoupper($role ?? '-') }}</div>
            </div>
        </div>
    </div>

</aside>

<div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden"></div>

<style>
    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.6rem 0.75rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        transition: background-color 0.15s, color 0.15s;
        white-space: nowrap;
    }

    .sidebar-link:hover {
        background-color:
            {{ $activeBg }}
        ;
        color:
            {{ $activeColor }}
        ;
    }

    .sidebar-link.sidebar-active {
        background-color:
            {{ $activeBg }}
        ;
        color:
            {{ $activeColor }}
        ;
        font-weight: 600;
    }

    .sidebar-link i {
        font-size: 1.1rem;
        width: 1.25rem;
        text-align: center;
        shrink: 0;
    }
</style>

<script>
    (function () {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const closeBtn = document.getElementById('sidebarCloseBtn');

        let desktopCollapsed = false;

        function isMobile() {
            return window.innerWidth < 1024;
        }

        function openMobile() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeMobile() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function collapseDesktop() {
            sidebar.style.width = '72px';
            sidebar.querySelectorAll('.sidebar-text').forEach(el => el.classList.add('hidden'));
            desktopCollapsed = true;
        }

        function expandDesktop() {
            sidebar.style.width = '280px';
            sidebar.querySelectorAll('.sidebar-text').forEach(el => el.classList.remove('hidden'));
            desktopCollapsed = false;
        }

        closeBtn?.addEventListener('click', closeMobile);
        overlay?.addEventListener('click', closeMobile);

        window.addEventListener('resize', () => {
            if (!isMobile()) {
                closeMobile();
            }
        });

        window.toggleSidebar = function () {
            if (isMobile()) {
                sidebar.classList.contains('-translate-x-full') ? openMobile() : closeMobile();
            } else {
                desktopCollapsed ? expandDesktop() : collapseDesktop();
            }
        };
    })();
</script>