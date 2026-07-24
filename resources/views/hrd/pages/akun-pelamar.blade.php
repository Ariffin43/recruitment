@extends('hrd.layouts.app')
@section('title', 'Kelola Akun Pelamar')
@section('page_title', 'Akun Pelamar')
@section('page_subtitle', 'Kelola data akun registrasi calon pelamar perusahaan.')

@section('content')

    @php
        $errorModal = session('error_modal', '');
        $errorUserId = session('error_user_id', '');
    @endphp

    <div class="space-y-4">

        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">

            <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center gap-3">
                <form method="GET" action="{{ route('hrd.pelamar.index') }}" id="filterForm"
                    class="flex-1 flex flex-col sm:flex-row gap-3">
                    <input type="hidden" name="perPage" value="{{ request('perPage', 5) }}">
                    <div class="relative flex-1 max-w-sm">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                        <input id="searchInput" type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-300 text-sm outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition">
                    </div>
                    <select name="status" onchange="document.getElementById('filterForm').submit()" class="px-4 py-2.5 rounded-xl border border-gray-300 text-sm
                               outline-none bg-white focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition cursor-pointer">
                        <option value="">Semua Status</option>
                        @foreach (['pending', 'aktif', 'ditolak', 'nonaktif'] as $s)
                            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                    @if (request('search') || request('status'))
                        <a href="{{ route('hrd.pelamar.index') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-500 text-sm font-semibold hover:bg-gray-50 transition inline-flex items-center gap-1.5">
                            <i class="bi bi-x-lg text-xs"></i> Reset
                        </a>
                    @endif

                </form>

            </div>

            <div id="tableContainer" class="overflow-x-auto">
                <table class="min-w-full text-sm">

                    <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold w-12">No</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama &amp; Email</th>
                            <th class="px-4 py-3 text-left font-semibold w-28">Status</th>
                            <th class="px-4 py-3 text-left font-semibold w-32">Terdaftar</th>
                            <th class="px-4 py-3 text-center font-semibold w-20">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">
                        @forelse ($pelamar as $i => $user)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="px-4 py-3 text-gray-400 text-xs font-medium">
                                    {{ $pelamar->firstItem() + $i }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if ($user->pelamar?->foto)
                                            <img src="{{ asset('storage/' . $user->pelamar->foto) }}" alt="{{ $user->nama }}"
                                                class="w-8 h-8 rounded-full object-cover shrink-0 border border-gray-200">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-[#145D9E]/10 text-[#145D9E] text-xs font-bold flex items-center justify-center shrink-0">
                                                {{ strtoupper(substr($user->nama, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-semibold text-gray-800 text-sm">
                                                {{ $user->nama }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ $user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusMap = [
                                            'aktif' => [
                                                'bg-green-50 border-green-200 text-green-700',
                                                'bi-circle-fill',
                                                'Aktif',
                                            ],
                                            'pending' => [
                                                'bg-yellow-50 border-yellow-200 text-yellow-700',
                                                'bi-clock-fill',
                                                'Pending',
                                            ],
                                            'ditolak' => [
                                                'bg-red-50 border-red-200 text-red-600',
                                                'bi-x-circle-fill',
                                                'Ditolak',
                                            ],
                                            'nonaktif' => [
                                                'bg-gray-100 border-gray-200 text-gray-600',
                                                'bi-pause-circle-fill',
                                                'Nonaktif',
                                            ],
                                        ];

                                        [$cls, $icon, $label] = $statusMap[$user->status] ?? [
                                            'bg-gray-100 border-gray-200 text-gray-500',
                                            'bi-dash-circle',
                                            $user->status,
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-semibold {{ $cls }}">
                                        <i class="bi {{ $icon }} text-[9px]"></i>
                                        {{ $label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-400">
                                    {{ $user->created_at?->translatedFormat('d M Y') ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="inline-block" id="dropdownWrap_{{ $user->id }}">
                                        <button type="button" class="dd-trigger w-8 h-8 rounded-lg border border-gray-200
                                                   text-gray-500 flex items-center justify-center hover:bg-gray-50 hover:border-gray-300 cursor-pointer transition"
                                            data-dd-id="{{ $user->id }}" data-dd-nama="{{ $user->nama }}"
                                            data-dd-email="{{ $user->email }}"
                                            data-dd-nohp="{{ $user->pelamar?->no_hp ?? '-' }}"
                                            data-dd-alamat="{{ $user->pelamar?->alamat ?? '-' }}"
                                            data-dd-status="{{ $user->status }}"
                                            data-dd-foto="{{ $user->pelamar?->foto ?? '' }}"
                                            data-dd-ktp="{{ $user->pelamar?->file_ktp ?? '' }}"
                                            data-dd-kk="{{ $user->pelamar?->file_kk ?? '' }}"
                                            data-dd-cv="{{ $user->pelamar?->file_cv ?? '' }}"
                                            data-dd-ijazah="{{ $user->pelamar?->file_ijazah ?? '' }}"
                                            data-dd-sertifikat="{{ $user->pelamar?->file_sertifikat ?? '' }}"
                                            data-dd-tanggal="{{ $user->created_at?->translatedFormat('d M Y') ?? '-' }}"
                                            data-dd-approve="{{ $user->status === 'pending' ? '1' : '0' }}"
                                            data-dd-reject="{{ $user->status === 'pending' ? '1' : '0' }}"
                                            data-dd-nonaktif="{{ $user->status === 'aktif' ? '1' : '0' }}">
                                            <i class="bi bi-three-dots-vertical text-sm pointer-events-none"></i>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 rounded-2xl bg-gray-10 flex items-center justify-cente text-gray-300 text-2xl">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-600">
                                                Data tidak ditemukan
                                            </div>
                                            <div class="text-xs text-gray-400 mt-0.5">
                                                Coba gunakan keyword atau filter lain.
                                            </div>
                                        </div>
                                        @if (request('search') || request('status'))
                                            <a href="{{ route('hrd.pelamar.index') }}" class="mt-1 px-4 py-2 rounded-xl bg-red-600 text-white
                                                text-xs font-semibold hover:bg-red-700 transition">
                                                <i class="bi bi-arrow-counterclockwise"></i> Reset Filter
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
            <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <span>Tampilkan</span>
                    <select onchange="changePerPage(this.value)" class="px-2.5 py-1.5 rounded-lg border border-gray-200 text-xs
                               font-semibold text-gray-700 bg-white outline-none focus:ring-2 focus:ring-blue-200 cursor-pointer">
                        @foreach ([5, 10, 20, 50] as $opt)
                            <option value="{{ $opt }}" {{ request('perPage', 5) == $opt ? 'selected' : '' }}>
                                {{ $opt }}
                            </option>
                        @endforeach
                    </select>
                    <span>
                        @if ($pelamar->count() > 0)
                            menampilkan
                            <span class="font-semibold text-gray-700">{{ $pelamar->firstItem() }}</span>
                            &ndash;
                            <span class="font-semibold text-gray-700">{{ $pelamar->lastItem() }}</span>
                            dari
                            <span class="font-semibold text-gray-700">{{ $pelamar->total() }}</span>
                        @endif
                    </span>
                </div>
                @if ($pelamar->total() > 0)
                    @php
                        $currentPage = $pelamar->currentPage();
                        $lastPage = $pelamar->lastPage();
                        $paginated = $pelamar->appends(request()->query());

                        $range = [];
                        for ($p = 1; $p <= $lastPage; $p++) {
                            if ($p === 1 || $p === $lastPage || abs($p - $currentPage) <= 2) {
                                $range[] = $p;
                            }
                        }
                    @endphp

                    <div class="flex items-center gap-1">
                        @if ($paginated->onFirstPage())
                            <span class="w-8 h-8 rounded-lg border border-gray-200 text-gray-300 flex items-center justify-center cursor-not-allowed select-none">
                                <i class="bi bi-chevron-left text-xs"></i>
                            </span>
                        @else
                            <a href="{{ $paginated->previousPageUrl() }}" class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 bg-white
                                flex items-center justify-center hover:bg-blue-50 hover:border-blue-200 hover:text-[#145D9E] transition">
                                <i class="bi bi-chevron-left text-xs"></i>
                            </a>
                        @endif
                        @foreach ($range as $idx => $page)
                            @if ($idx > 0 && $page - $range[$idx - 1] > 1)
                                <span class="w-8 h-8 flex items-center justify-center text-xs text-gray-400">
                                    …
                                </span>
                            @endif

                            @if ($page === $currentPage)
                                <span class="w-8 h-8 rounded-lg text-xs font-semibold bg-[#145D9E] text-white flex items-center justify-center shadow-sm">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $paginated->url($page) }}" class="w-8 h-8 rounded-lg text-xs font-semibold border border-gray-200
                                    bg-white text-gray-600 flex items-center justify-center hover:bg-blue-50 hover:border-blue-200 hover:text-[#145D9E] transition">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                        @if ($paginated->hasMorePages())
                            <a href="{{ $paginated->nextPageUrl() }}" class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 bg-white
                                flex items-center justify-center hover:bg-blue-50 hover:border-blue-200 hover:text-[#145D9E] transition">
                                <i class="bi bi-chevron-right text-xs"></i>
                            </a>
                        @else
                            <span class="w-8 h-8 rounded-lg border border-gray-200 text-gray-300 flex items-center justify-center cursor-not-allowed select-none">
                                <i class="bi bi-chevron-right text-xs"></i>
                            </span>
                        @endif

                    </div>
                @endif

            </div>

        </div>

    </div>
    <div id="dropdownGlobal" class="hidden fixed z-[9999] bg-white border border-gray-200 rounded-xl shadow-xl py-1.5 w-44">
        <button type="button" id="ddBtnDetail" class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition text-left">
            <i class="bi bi-eye text-gray-400 text-sm"></i> Detail
        </button>
        <button type="button" id="ddBtnEdit" class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-blue-600 hover:bg-blue-50 transition text-left">
            <i class="bi bi-pencil-square text-blue-500 text-sm"></i> Edit
        </button>
        <div id="ddDividerApprove" class="my-1 border-t border-gray-100"></div>
        <button type="button" id="ddBtnApprove" class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-green-700 font-semibold hover:bg-green-50 transition text-left">
            <i class="bi bi-check-circle text-green-600 text-sm"></i> Setujui
        </button>
        <button type="button" id="ddBtnReject" class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-orange-600 font-semibold hover:bg-orange-50 transition text-left">
            <i class="bi bi-slash-circle text-orange-500 text-sm"></i> Tolak
        </button>
        <button type="button" id="ddBtnNonaktif" class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-gray-600 font-semibold hover:bg-gray-100 transition text-left">
            <i class="bi bi-person-dash text-gray-500 text-sm"></i> Nonaktifkan
        </button>

        <div class="my-1 border-t border-gray-100"></div>
        <button type="button" id="ddBtnHapus" class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-600 font-semibold hover:bg-red-50 transition text-left">
            <i class="bi bi-trash3 text-red-500 text-sm"></i> Hapus
        </button>

    </div>
    <div id="modalDetail" class="fixed inset-0 z-[60] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalDetail')"></div>
        <div
            class="relative bg-white rounded-2xl w-full max-w-xl mx-4 shadow-2xl
                    flex flex-col max-h-[90vh]">
            <div
                class="flex items-center justify-between px-6 py-4
                        border-b border-gray-100 shrink-0">
                <div>
                    <div class="text-base font-bold text-gray-800">Detail Pelamar</div>
                    <div class="text-xs text-gray-400 mt-0.5">
                        Informasi lengkap data diri calon pelamar
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalDetail')"
                    class="w-8 h-8 rounded-xl border border-gray-200 flex items-center justify-center
                           text-white bg-red-600 hover:bg-red-700 cursor-pointer transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>
            <div class="overflow-y-auto px-6 py-5 space-y-5">
                <div class="flex items-center gap-4">
                    <div id="detailAvatarWrap" class="shrink-0"></div>
                    <div>
                        <div id="detailNama" class="text-base font-bold text-gray-800"></div>
                        <div id="detailEmail" class="text-xs text-gray-400 mt-0.5"></div>
                    </div>
                </div>

                <div class="border-t border-gray-100"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">No. HP</div>
                        <div id="detailNoHp" class="text-sm font-semibold text-gray-700"></div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Status Akun</div>
                        <div id="detailStatus"></div>
                    </div>
                    <div class="col-span-2">
                        <div class="text-xs text-gray-400 font-medium mb-1">Alamat</div>
                        <div id="detailAlamat" class="text-sm text-gray-700 leading-relaxed"></div>
                    </div>
                    <div class="col-span-2">
                        <div class="text-xs text-gray-400 font-medium mb-1">Terdaftar Sejak</div>
                        <div id="detailTanggal" class="text-sm text-gray-700"></div>
                    </div>
                </div>

                <div class="border-t border-gray-100"></div>
                <div>
                    <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                        Dokumen Unggahan
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2" id="detailDokumen"></div>
                </div>

            </div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end shrink-0">
                <button type="button" onclick="closeModal('modalDetail')"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600
                           text-sm font-semibold hover:bg-gray-100 transition">
                    Tutup
                </button>
            </div>

        </div>
    </div>
    <div id="modalEdit" class="fixed inset-0 z-[60] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalEdit')"></div>
        <div
            class="relative bg-white rounded-2xl w-full max-w-lg mx-4 shadow-2xl
                    max-h-[90vh] flex flex-col">
            <div
                class="flex items-center justify-between px-6 py-4
                        border-b border-gray-100 shrink-0">
                <div>
                    <div class="text-base font-bold text-gray-800">Edit Pelamar</div>
                    <div class="text-xs text-gray-400 mt-0.5">
                        Kosongkan kolom password jika tidak ingin mengubahnya
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalEdit')"
                    class="w-8 h-8 rounded-xl border border-gray-200 flex items-center justify-center
                           text-white bg-red-600 hover:bg-red-700 cursor-pointer transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>
            <form id="formEdit" method="POST" class="overflow-y-auto">
                @csrf
                @method('PUT')

                <div class="px-6 py-5 space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input id="editNama" type="text" name="nama" value="{{ $errorModal === 'edit' ? old('nama') : '' }}"
                                class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                {{ $errors->has('nama') && $errorModal === 'edit' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @if ($errorModal === 'edit')
                            @error('nama')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input id="editEmail" type="email" name="email" value="{{ $errorModal === 'edit' ? old('email') : '' }}"
                                class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                {{ $errors->has('email') && $errorModal === 'edit' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @if ($errorModal === 'edit')
                            @error('email')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="editStatus" name="status" 
                                class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none bg-white focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                {{ $errors->has('status') && $errorModal === 'edit' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            @foreach (['pending', 'aktif', 'ditolak', 'nonaktif'] as $s)
                                <option value="{{ $s }}"
                                    {{ $errorModal === 'edit' && old('status') == $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errorModal === 'edit')
                            @error('status')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        @endif
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Password Baru
                                <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="editPassword" placeholder="Kosongkan jika tidak diubah"
                                    class="w-full px-4 py-2.5 pr-10 border rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                    {{ $errors->has('password') && $errorModal === 'edit' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                                <button type="button" onclick="togglePassword('editPassword', 'eyeEdit')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i id="eyeEdit" class="bi bi-eye text-sm"></i>
                                </button>
                            </div>
                            @if ($errorModal === 'edit')
                                @error('password')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            @endif
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Konfirmasi Password
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="editPasswordConf" placeholder="Ulangi password baru"
                                    class="w-full px-4 py-2.5 pr-10 border border-gray-300 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition">
                                <button type="button" onclick="togglePassword('editPasswordConf', 'eyeEditConf')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                    <i id="eyeEditConf" class="bi bi-eye text-sm"></i>
                                </button>
                            </div>
                        </div>

                    </div>

                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end gap-2 shrink-0">
                    <button type="button" onclick="closeModal('modalEdit')" class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-100 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#145D9E] text-white text-sm font-semibold hover:bg-[#0f4d85] transition inline-flex items-center gap-2 shadow-sm">
                        <i class="bi bi-floppy"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
    <div id="modalPreview" class="fixed inset-0 z-[70] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeModal('modalPreview')"></div>
        <div class="relative bg-white rounded-2xl w-full max-w-3xl mx-4 shadow-2xl flex flex-col max-h-[90vh]">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#145D9E]/10 text-[#145D9E] flex items-center justify-center">
                        <i class="bi bi-file-earmark-text text-sm"></i>
                    </div>
                    <div>
                        <div id="previewTitle" class="text-base font-bold text-gray-800"></div>
                        <div class="text-xs text-gray-400 mt-0.5">Pratinjau Dokumen</div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a id="previewDownload" href="#" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-gray-200 text-gray-600 text-xs font-semibold hover:bg-gray-50 transition">
                        <i class="bi bi-download text-xs"></i> Unduh
                    </a>
                    <button type="button" onclick="closeModal('modalPreview')" class="w-8 h-8 rounded-xl border border-gray-200 flex items-center justify-center text-white bg-red-600 hover:bg-red-700 cursor-pointer transition">
                        <i class="bi bi-x-lg text-xs"></i>
                    </button>
                </div>
            </div>
            <div class="overflow-y-auto flex-1 p-4 bg-gray-50 flex items-start justify-center" id="previewBody"></div>
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end shrink-0">
                <button type="button" onclick="closeModal('modalPreview')" class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-100 transition">
                    Tutup
                </button>
            </div>

        </div>
    </div>
    <form id="formApprove" method="POST" action="" style="display:none">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" value="aktif">
    </form>
    <form id="formReject" method="POST" action="" style="display:none">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" value="ditolak">
    </form>
    <form id="formNonaktif" method="POST" action="" style="display:none">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" value="nonaktif">
    </form>
    <form id="formHapus" method="POST" action="" style="display:none">
        @csrf
        @method('DELETE')
    </form>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            var _dd = {};

            function openModal(id) {
                document.getElementById(id).classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeModal(id) {
                document.getElementById(id).classList.add('hidden');
                document.body.style.overflow = '';

                if (id === 'modalPreview') {
                    document.getElementById('previewBody').innerHTML = '';
                }
            }

            var _ddEl = document.getElementById('dropdownGlobal');

            function openDropdown(triggerBtn) {
                var d = triggerBtn.dataset;
                var id = d.ddId;

                if (!_ddEl.classList.contains('hidden') && _dd.id == id) {
                    _ddEl.classList.add('hidden');
                    return;
                }

                _dd = {
                    id: id,
                    nama: d.ddNama,
                    email: d.ddEmail,
                    noHp: d.ddNohp,
                    alamat: d.ddAlamat,
                    status: d.ddStatus,
                    foto: d.ddFoto,
                    fileKtp: d.ddKtp,
                    fileKk: d.ddKk,
                    fileCv: d.ddCv,
                    fileIjazah: d.ddIjazah,
                    fileSertifikat: d.ddSertifikat,
                    tanggal: d.ddTanggal,
                    canApprove: d.ddApprove === '1',
                    canReject: d.ddReject === '1',
                    canNonaktif: d.ddNonaktif === '1',
                };

                document.getElementById('ddDividerApprove').style.display = _dd.canApprove ? '' : 'none';
                document.getElementById('ddBtnApprove').style.display = _dd.canApprove ? '' : 'none';
                document.getElementById('ddBtnReject').style.display = _dd.canReject ? '' : 'none';
                document.getElementById('ddBtnNonaktif').style.display = _dd.canNonaktif ? '' : 'none';

                var rect = triggerBtn.getBoundingClientRect();
                _ddEl.style.top = (rect.bottom + 4) + 'px';
                _ddEl.style.left = (rect.right - 176) + 'px';
                _ddEl.classList.remove('hidden');

                requestAnimationFrame(function() {
                    var r = _ddEl.getBoundingClientRect();
                    if (r.bottom > window.innerHeight - 8) {
                        _ddEl.style.top = (rect.top - r.height - 4) + 'px';
                    }
                    if (r.left < 8) {
                        _ddEl.style.left = rect.left + 'px';
                    }
                });
            }

            function closeDropdown() {
                _ddEl.classList.add('hidden');
            }

            document.addEventListener('click', function(e) {
                var trigger = e.target.closest('.dd-trigger');
                if (trigger) {
                    e.stopPropagation();
                    openDropdown(trigger);
                    return;
                }
                if (!e.target.closest('#dropdownGlobal')) closeDropdown();
            });

            window.addEventListener('scroll', closeDropdown, true);

            document.getElementById('ddBtnDetail').addEventListener('click', function() {
                closeDropdown();
                openModalDetail();
            });
            document.getElementById('ddBtnEdit').addEventListener('click', function() {
                closeDropdown();
                openModalEdit();
            });
            document.getElementById('ddBtnApprove').addEventListener('click', function() {
                closeDropdown();
                approvePelamar();
            });
            document.getElementById('ddBtnReject').addEventListener('click', function() {
                closeDropdown();
                rejectPelamar();
            });
            document.getElementById('ddBtnNonaktif').addEventListener('click', function() {
                closeDropdown();
                nonaktifPelamar();
            });
            document.getElementById('ddBtnHapus').addEventListener('click', function() {
                closeDropdown();
                hapusPelamar();
            });

            function openModalDetail() {

                var avatarWrap = document.getElementById('detailAvatarWrap');
                if (_dd.foto) {
                    avatarWrap.innerHTML =
                        '<img src="/storage/' + _dd.foto + '"' +
                        ' class="w-14 h-14 rounded-2xl object-cover border border-gray-200">';
                } else {
                    avatarWrap.innerHTML =
                        '<div class="w-14 h-14 rounded-2xl bg-[#145D9E]/10 text-[#145D9E]' +
                        ' text-xl font-bold flex items-center justify-center">' +
                        _dd.nama.charAt(0).toUpperCase() + '</div>';
                }

                document.getElementById('detailNama').textContent = _dd.nama;
                document.getElementById('detailEmail').textContent = _dd.email;
                document.getElementById('detailNoHp').textContent = _dd.noHp || '-';
                document.getElementById('detailAlamat').textContent = _dd.alamat || '-';
                document.getElementById('detailTanggal').textContent = _dd.tanggal;

                var statusMap = {
                    aktif: {
                        cls: 'bg-green-50 border-green-200 text-green-700',
                        icon: 'bi-circle-fill',
                        label: 'Aktif'
                    },
                    pending: {
                        cls: 'bg-yellow-50 border-yellow-200 text-yellow-700',
                        icon: 'bi-clock-fill',
                        label: 'Pending'
                    },
                    ditolak: {
                        cls: 'bg-red-50 border-red-200 text-red-600',
                        icon: 'bi-x-circle-fill',
                        label: 'Ditolak'
                    },
                    nonaktif: {
                        cls: 'bg-gray-100 border-gray-200 text-gray-600',
                        icon: 'bi-pause-circle-fill',
                        label: 'Nonaktif'
                    },
                };

                var s = statusMap[_dd.status] || {
                    cls: 'bg-gray-100 border-gray-200 text-gray-500',
                    icon: 'bi-dash-circle',
                    label: _dd.status
                };

                document.getElementById('detailStatus').innerHTML =
                    '<span class="inline-flex items-center gap-1.5 rounded-full border' +
                    ' px-2.5 py-1 text-xs font-semibold ' + s.cls + '">' +
                    '<i class="bi ' + s.icon + ' text-[9px]"></i>' + s.label + '</span>';

                var docs = [{
                        key: _dd.fileKtp,
                        label: 'KTP',
                        icon: 'bi-person-vcard'
                    },
                    {
                        key: _dd.fileKk,
                        label: 'Kartu Keluarga',
                        icon: 'bi-house'
                    },
                    {
                        key: _dd.fileCv,
                        label: 'CV / Resume',
                        icon: 'bi-file-person'
                    },
                    {
                        key: _dd.fileIjazah,
                        label: 'Ijazah',
                        icon: 'bi-mortarboard'
                    },
                    {
                        key: _dd.fileSertifikat,
                        label: 'Sertifikat',
                        icon: 'bi-patch-check'
                    },
                ];

                document.getElementById('detailDokumen').innerHTML = docs.map(function(doc) {
                    if (doc.key) {
                        return '<button type="button"' +
                            ' onclick="openModalPreview(\'/storage/' + doc.key + '\',\'' + doc.label + '\')"' +
                            ' class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl border border-gray-200' +
                            ' bg-white hover:bg-blue-50 hover:border-blue-200 transition group w-full text-left cursor-pointer">' +
                            '<div class="w-8 h-8 rounded-lg bg-[#145D9E]/10 text-[#145D9E] flex items-center justify-center shrink-0">' +
                            '<i class="bi ' + doc.icon + ' text-sm"></i></div>' +
                            '<div class="flex-1 min-w-0">' +
                            '<div class="text-xs font-semibold text-gray-700 truncate">' + doc.label + '</div>' +
                            '<div class="text-[10px] text-gray-400">Klik untuk pratinjau</div></div>' +
                            '<i class="bi bi-eye text-xs text-gray-300 group-hover:text-[#145D9E] transition"></i></button>';
                    }
                    return '<div class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl' +
                        ' border border-dashed border-gray-200 bg-gray-50">' +
                        '<div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-300 flex items-center justify-center shrink-0">' +
                        '<i class="bi ' + doc.icon + ' text-sm"></i></div>' +
                        '<div><div class="text-xs font-semibold text-gray-400">' + doc.label + '</div>' +
                        '<div class="text-[10px] text-gray-300">Belum diunggah</div></div></div>';
                }).join('');

                openModal('modalDetail');
            }

            function openModalEdit() {
                document.getElementById('formEdit').action =
                    '{{ url('/hrd/akun-pelamar') }}/' + _dd.id;

                document.getElementById('editNama').value = _dd.nama;
                document.getElementById('editEmail').value = _dd.email;
                document.getElementById('editStatus').value = _dd.status;

                document.getElementById('editPassword').value = '';
                document.getElementById('editPasswordConf').value = '';

                openModal('modalEdit');
            }

            function approvePelamar() {
                Swal.fire({
                    title: 'Approve Pelamar?',
                    html: 'Akun <strong>' + _dd.nama + '</strong> akan diaktifkan.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#16A34A', // hijau
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Approve!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then(function(r) {
                    if (r.isConfirmed) {
                        var f = document.getElementById('formApprove');
                        f.action = '{{ url('/hrd/akun-pelamar') }}/' + _dd.id + '/status';
                        f.submit();
                    }
                });
            }

            function rejectPelamar() {
                Swal.fire({
                    title: 'Tolak Pelamar?',
                    html: 'Akun <strong>' + _dd.nama + '</strong> akan ditolak.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EA580C',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Tolak!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then(function(r) {
                    if (r.isConfirmed) {
                        var f = document.getElementById('formReject');
                        f.action = '{{ url('/hrd/akun-pelamar') }}/' + _dd.id + '/status';
                        f.submit();
                    }
                });
            }

            function nonaktifPelamar() {
                Swal.fire({
                    title: 'Nonaktifkan Pelamar?',
                    html: 'Akun <strong>' + _dd.nama + '</strong> akan dinonaktifkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6B7280',
                    cancelButtonColor: '#9CA3AF',
                    confirmButtonText: 'Ya, Nonaktifkan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then(function(r) {
                    if (r.isConfirmed) {
                        var f = document.getElementById('formNonaktif');
                        f.action = '{{ url('/hrd/akun-pelamar') }}/' + _dd.id + '/status';
                        f.submit();
                    }
                });
            }

            function hapusPelamar() {
                Swal.fire({
                    title: 'Hapus Pelamar?',
                    text: 'Data akan dihapus secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DC2626', // merah
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then(function(r) {
                    if (r.isConfirmed) {
                        var f = document.getElementById('formHapus');
                        f.action = '{{ url('/hrd/akun-pelamar') }}/' + _dd.id;
                        f.submit();
                    }
                });
            }

            function togglePassword(inputId, iconId) {
                var input = document.getElementById(inputId);
                var icon = document.getElementById(iconId);

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('bi-eye', 'bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                }
            }

            function changePerPage(value) {
                var url = new URL(window.location.href);
                url.searchParams.set('perPage', value);
                url.searchParams.set('page', 1);
                window.location.href = url.toString();
            }

            function openModalPreview(url, label) {
                document.getElementById('previewTitle').textContent = label;
                document.getElementById('previewDownload').href = url;

                var body = document.getElementById('previewBody');

                var ext = url.split('.').pop().toLowerCase().split('?')[0];

                if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg'].indexOf(ext) !== -1) {
                    body.innerHTML =
                        '<img src="' + url + '" alt="' + label + '"' +
                        ' class="max-w-full max-h-[70vh] rounded-xl shadow-sm object-contain">';
                } else if (ext === 'pdf') {
                    body.innerHTML =
                        '<iframe src="' + url + '" frameborder="0"' +
                        ' class="w-full rounded-xl border border-gray-200 bg-white"' +
                        ' style="height:65vh"></iframe>';
                } else {
                    body.innerHTML =
                        '<div class="flex flex-col items-center gap-4 py-16 text-center">' +
                        '<div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center' +
                        ' justify-center text-gray-300 text-3xl">' +
                        '<i class="bi bi-file-earmark"></i></div>' +
                        '<div class="text-sm font-semibold text-gray-600">' +
                        'Format tidak didukung untuk pratinjau</div>' +
                        '<a href="' + url + '" target="_blank"' +
                        ' class="px-4 py-2.5 rounded-xl bg-[#145D9E] text-white text-sm' +
                        ' font-semibold hover:bg-[#0f4d85] transition inline-flex items-center gap-2">' +
                        '<i class="bi bi-download"></i> Unduh File</a></div>';
                }

                openModal('modalPreview');
            }

            var flash = document.getElementById('flashSuccess');
            if (flash) setTimeout(function() {
                flash.remove();
            }, 5000);

            var debounceTimer; document.getElementById('searchInput').addEventListener('input', function() {
                clearTimeout(debounceTimer);
                var keyword = this.value;

                debounceTimer = setTimeout(function() {
                    var params = new URLSearchParams(window.location.search);
                    params.set('search', keyword);

                    fetch('/hrd/akun-pelamar?' + params.toString(), {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(function(res) {
                            return res.text();
                        })
                        .then(function(html) {
                            var doc = new DOMParser().parseFromString(html, 'text/html');
                            var newTbody = doc.querySelector('#tableContainer tbody');
                            var curTbody = document.querySelector('#tableContainer tbody');

                            if (newTbody && curTbody) {
                                curTbody.innerHTML = newTbody.innerHTML;
                            }
                        });
                }, 300);
            });

            @if ($errors->any() && $errorModal === 'edit')
                (function() {
                    _dd.id = '{{ $errorUserId }}';
                    _dd.status = '{{ old('status') }}';
                    document.getElementById('formEdit').action = '{{ url('/hrd/akun-pelamar') }}/{{ $errorUserId }}';
                    openModal('modalEdit');
                })();
            @endif
        </script>
    @endpush

@endsection