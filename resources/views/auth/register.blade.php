<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - e-Recruitment</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-white">

<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

    {{-- LEFT SIDE (FORM) --}}
    <div class="flex items-center justify-center p-6 lg:p-12 bg-white">
        <div class="w-full max-w-xl">

            {{-- Header --}}
            <div class="flex items-center gap-3">
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

            {{-- FORM --}}
            <form action="{{ route('register.store') }}" method="POST" class="mt-8 space-y-5">
                @csrf

                {{-- FULL NAME --}}
                <div>
                    <label class="block text-sm font-medium text-gray-800 mb-2">
                        Nama lengkap
                    </label>

                    <input
                        type="text"
                        name="nama"
                        value="{{ old('nama') }}"
                        placeholder="Masukkan nama lengkap anda sesuai KTP"
                        class="w-full rounded-xl border px-4 py-3 text-gray-900 placeholder:text-gray-400 outline-none transition
                        {{ $errors->has('nama')
                            ? 'border-red-400 focus:ring-4 focus:ring-red-100'
                            : 'border-gray-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                        }}"
                    />

                    @error('namaname')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

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
                            ? 'border-red-400 focus:ring-4 focus:ring-red-100'
                            : 'border-gray-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                        }}"
                    />

                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- WHATSAPP --}}
                <div>
                    <label class="block text-sm font-medium text-gray-800 mb-2">
                        No Whatsapp
                    </label>

                    <input
                        id="whatsapp"
                        type="text"
                        inputmode="numeric"
                        name="no_hp"
                        value="{{ old('no_hp') }}"
                        placeholder="Contoh: 628822222222"
                        class="w-full rounded-xl border px-4 py-3 text-gray-900 placeholder:text-gray-400 outline-none transition
                        {{ $errors->has('no_hp')
                            ? 'border-red-400 focus:ring-4 focus:ring-red-100'
                            : 'border-gray-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                        }}"
                    />

                    @error('no_hp')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <p class="mt-2 text-xs text-gray-400">
                        *Masukkan nomor aktif (hanya angka, tanpa spasi).
                    </p>
                </div>


                {{-- PASSWORD + CONFIRM --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- PASSWORD --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="min. 8 karakter"
                                class="w-full rounded-xl border px-4 py-3 pr-12 text-gray-900 placeholder:text-gray-400 outline-none transition
                                {{ $errors->has('password')
                                    ? 'border-red-400 focus:ring-4 focus:ring-red-100'
                                    : 'border-gray-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
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

                    {{-- CONFIRM PASSWORD --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-2">
                            Konfirmasi Kata Sandi
                        </label>
                        <div class="relative">
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                placeholder="Masukkan ulang kata sandi"
                                class="w-full rounded-xl border px-4 py-3 pr-12 text-gray-900 placeholder:text-gray-400 outline-none transition
                                {{ $errors->has('password_confirmation')
                                    ? 'border-red-400 focus:ring-4 focus:ring-red-100'
                                    : 'border-gray-300 focus:border-blue-500 focus:ring-4 focus:ring-blue-100'
                                }}"
                            />

                            <button
                                type="button"
                                id="togglePasswordConfirm"
                                class="absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-gray-700"
                                aria-label="Toggle confirm password visibility"
                            >
                                <i class="bi bi-eye text-lg"></i>
                            </button>
                        </div>

                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- BUTTON --}}
                <button
                    type="submit"
                    class="w-full rounded-xl py-3 font-semibold text-white transition hover:opacity-95 active:opacity-90"
                    style="background: #2d3db8;"
                >
                    Daftar Sekarang
                </button>

                {{-- FOOTER LINK --}}
                <p class="text-sm text-gray-500">
                    Sudah punya akun?
                    <a href="/login" class="text-blue-600 font-medium hover:underline">
                        Masuk di sini
                    </a>
                </p>

                <p class="text-xs text-gray-400">
                    *Portal ini digunakan untuk keperluan rekrutmen resmi PT Cesco Offshore and Engineering
                </p>

            </form>
        </div>
    </div>

    {{-- RIGHT SIDE (GRADIENT PANEL) --}}
    <div class="relative hidden lg:flex items-center justify-center overflow-hidden"
         style="background: linear-gradient(135deg, #0b2c7a 0%, #0b3a95 35%, #0b4db4 100%);">

        <div class="absolute inset-0 opacity-20"
             style="background: radial-gradient(circle at 70% 40%, rgba(255,255,255,0.25), transparent 55%);">
        </div>

        <div class="relative max-w-lg px-14">
            <div class="inline-flex items-center gap-2 bg-white/15 text-white px-4 py-2 rounded-full text-sm">
                <span class="flex items-center justify-center w-7 h-7 rounded-full bg-white/20">
                    <i class="bi bi-briefcase"></i>
                </span>
                <span>Rekrutmen PT Cesco Offshore and Engineering</span>
            </div>

            <h1 class="mt-8 text-white text-5xl font-bold leading-tight">
                Daftar, Kirim lamaran <br/> dan tunggu konfirmasi.
            </h1>

            <p class="mt-5 text-white/80 text-base leading-relaxed max-w-md">
                Dengan akun ini kamu bisa melamar lebih dari satu
                lowongan dan histori tetap ada.
            </p>
        </div>
    </div>

</div>

{{-- SCRIPT: Show/Hide Password + Logic Country Code --}}
<script>
    // Toggle password
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    togglePassword.addEventListener('click', () => {
        const isPassword = password.type === 'password';
        password.type = isPassword ? 'text' : 'password';
        togglePassword.innerHTML = isPassword
            ? '<i class="bi bi-eye-slash text-lg"></i>'
            : '<i class="bi bi-eye text-lg"></i>';
    });

    // Toggle confirm password
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const passwordConfirmation = document.getElementById('password_confirmation');
    togglePasswordConfirm.addEventListener('click', () => {
        const isPassword = passwordConfirmation.type === 'password';
        passwordConfirmation.type = isPassword ? 'text' : 'password';
        togglePasswordConfirm.innerHTML = isPassword
            ? '<i class="bi bi-eye-slash text-lg"></i>'
            : '<i class="bi bi-eye text-lg"></i>';
    });

    // WhatsApp
    const whatsappInput = document.getElementById('whatsapp');

    whatsappInput.addEventListener('input', () => {
        whatsappInput.value = whatsappInput.value.replace(/\D/g, '');
    });
</script>

</body>
</html>