<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'HOD Portal')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('head')
</head>

<body class="bg-[#F6F8FB] text-gray-900">
    <div class="h-screen flex overflow-hidden">

        {{-- Sidebar (shared component, role=hod) --}}
        <x-sidebar role="hod" />

        {{-- Main --}}
        <main class="flex-1 min-w-0 overflow-y-auto">
            <x-topbar
                role="hod"
                :title="trim($__env->yieldContent('page_title', 'Dashboard'))"
                :subtitle="trim($__env->yieldContent('page_subtitle', ''))"
            />

            <div class="px-4 lg:px-8 py-6">
                @yield('content')
            </div>
        </main>

    </div>

@stack('scripts')

</body>
</html>
