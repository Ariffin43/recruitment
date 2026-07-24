<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - e-Recruitment</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white">

<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

    {{-- LEFT SIDE --}}
    <div class="relative hidden lg:flex items-center justify-center overflow-hidden"
         style="background: linear-gradient(135deg, #0b2c7a 0%, #0b3a95 35%, #0b4db4 100%);">

        <div class="absolute inset-0 opacity-20"
             style="background: radial-gradient(circle at 70% 40%, rgba(255,255,255,0.25), transparent 55%);">
        </div>

        <div class="relative max-w-lg px-14">
            <div class="inline-flex items-center gap-2 bg-white/15 text-white px-4 py-2 rounded-full text-sm">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-white/20">
                    <i class="bi bi-shield-lock"></i>
                </span>
                <span>Akses kandidat & user internal</span>
            </div>

            <h1 class="mt-8 text-white text-5xl font-bold leading-tight">
                Portal perekrutan <br/> karyawan
            </h1>

            <p class="mt-5 text-white/80 text-base leading-relaxed max-w-md">
                Lihat status lamaran, jadwal interview dan permintaan
                dokumen langsung di satu tempat
            </p>
        </div>
    </div>

    {{-- RIGHT SIDE --}}
    <div class="flex items-center justify-center p-6 lg:p-12 bg-white">
        <div class="w-full max-w-xl">

            {{-- Back Button --}}
            <a href="/"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-gray-200 text-sm text-gray-700 hover:bg-gray-50 transition">
                <i class="bi bi-arrow-left"></i>
                Kembali ke beranda
            </a>

            {{-- Header --}}
            <div class="mt-6 flex items-center gap-3">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold"
                     style="background: var(--brand-blue);">
                    e
                </div>
                <div>
                    <div class="text-lg font-semibold text-gray-900">e-Recruitment</div>
                    <div class="text-sm text-gray-500">PT Cesco Offshore and Engineering</div>
                </div>
            </div>

            <h2 class="mt-5 text-4xl font-bold text-gray-900">
                Masuk ke portal rekrutmen
            </h2>
            <p class="mt-2 text-gray-500">
                Gunakan email dan kata sandi yang sudah terdaftar.
            </p>

            @if(session('success'))
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            text: "{{ session('success') }}",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    });
                </script>
            @endif

            {{-- FORM --}}
            <form action="{{ route('login.post') }}" method="POST" class="mt-8 space-y-5">
                @csrf
                {{-- EMAIL --}}
                <div>
                    <label class="block text-sm font-medium text-gray-800 mb-2">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email anda yang aktif"
                        class="w-full rounded-xl border px-4 py-3 text-gray-900 placeholder:text-gray-400 outline-none transition
                        {{ $errors->has('email')
                            ? 'border-red-400 focus:ring-red-100'
                            : 'border-gray-300 focus:border-blue-500 focus:ring-blue-100'
                        }}"
                    />

                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-800">
                            Kata Sandi
                        </label>
                    </div>

                    <div class="relative">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            placeholder="Masukkan kata sandi"
                            class="w-full rounded-xl border px-4 py-3 pr-12 text-gray-900 placeholder:text-gray-400 outline-none transition
                            {{ $errors->has('password')
                                ? 'border-red-400 focus:ring-red-100'
                                : 'border-gray-300 focus:border-blue-500 focus:ring-blue-100'
                            }}"
                        />

                        <button
                            type="button"
                            id="togglePassword"
                            class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700"
                            aria-label="Toggle password visibility"
                        >
                            <i class="bi bi-eye text-lg"></i>
                        </button>
                    </div>

                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- BUTTON --}}
                <button
                    type="submit"
                    class="w-full rounded-xl py-3 font-semibold text-white transition
                           hover:opacity-95 active:opacity-90"
                    style="background: var(--brand-blue);"
                >
                    Masuk
                </button>

                {{-- FOOTER LINK --}}
                <p class="text-sm text-gray-500">
                    Belum punya akun?
                    <a href="/register" class="text-blue-600 font-medium hover:underline">
                        Daftar dulu
                    </a>
                </p>

                <p class="text-xs text-gray-400">
                    *Portal ini digunakan untuk keperluan rekrutmen resmi PT Cesco Offshore and Engineering
                </p>
            </form>
        </div>
    </div>

</div>

{{-- SCRIPT SHOW/HIDE PASSWORD --}}
<script>
    const toggle = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    toggle.addEventListener('click', () => {
        const isPassword = password.type === 'password';
        password.type = isPassword ? 'text' : 'password';
        toggle.innerHTML = isPassword
            ? '<i class="bi bi-eye-slash text-lg"></i>'
            : '<i class="bi bi-eye text-lg"></i>';
    });
</script>

</body>
</html>