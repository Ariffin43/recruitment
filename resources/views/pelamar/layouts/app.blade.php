<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'HRD Portal')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @stack('head')
</head>

<body class="bg-[#F6F8FB] text-gray-900">
    <div class="h-screen flex overflow-hidden">

        <x-sidebar role="pelamar" />

        <main class="flex-1 min-w-0 overflow-y-auto">
            <x-topbar role="pelamar" :title="trim($__env->yieldContent('page_title', 'Dashboard'))" :subtitle="trim($__env->yieldContent('page_subtitle', ''))" />
            <div class="px-4 lg:px-8 py-6">
                @if (session('success'))
                    <div id="flashSuccess" class="flex items-center gap-3 px-4 py-3 mb-3 rounded-xl bg-green-50 border border-green-200
                       text-green-700 text-sm font-semibold shadow-sm">
                        <i class="bi bi-check-circle-fill text-green-500 text-base"></i>
                        <span>{{ session('success') }}</span>
                        <button onclick="document.getElementById('flashSuccess').remove()"
                            class="ml-auto text-green-400 hover:text-green-600 transition">
                            <i class="bi bi-x-lg text-xs"></i>
                        </button>
                    </div>
                @endif
                @yield('content')
            </div>
        </main>

    </div>

     @if (session('notifikasi'))
     <script>
         Swal.fire({
             icon: '{{ session('type') }}', 
             title: '{{ session('title') }}',
             text: '{{ session('notifikasi') }}',
             confirmButtonColor: '#145D9E',
             timer: 2500, timerProgressBar: true, showConfirmButton: false,
         });
     </script>
    @endif
    @stack('scripts')
</body>

</html>
