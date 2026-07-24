@extends('hrd.layouts.app')
@section('title', 'Kelola Akun HOD')
@section('page_title', 'Head of Department')
@section('page_subtitle', 'Kelola data Head of Department (HOD) perusahaan.')

@section('content')

    @php
        $errorModal = session('error_modal', '');
        $errorHodId = session('error_hod_id', '');
    @endphp

    <div class="space-y-4">

        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">

            <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center gap-3">

                <form method="GET" action="{{ route('hrd.hod.index') }}" id="filterForm"
                    class="flex-1 flex flex-col sm:flex-row gap-3">

                    <input type="hidden" name="perPage" value="{{ request('perPage', 5) }}">

                    <div class="relative flex-1 max-w-sm">
                        <i
                            class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                        <input id="searchInput" type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama, email, atau badge..."
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-300
                            text-sm outline-none
                            focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition">
                    </div>

                    <select name="departemen" onchange="document.getElementById('filterForm').submit()"
                        class="px-4 py-2.5 rounded-xl border border-gray-300 text-sm
                            outline-none bg-white focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition cursor-pointer">
                        <option value="">Semua Departemen</option>
                        @foreach ($daftarDepartemen as $dep)
                            <option value="{{ $dep->id }}" {{ request('departemen') == $dep->id ? 'selected' : '' }}>
                                {{ $dep->kode }} — {{ $dep->nama }}
                            </option>
                        @endforeach
                    </select>

                    @if (request('search') || request('departemen'))
                        <a href="{{ route('hrd.hod.index') }}"
                            class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-500
                                text-sm font-semibold hover:bg-gray-50 transition inline-flex items-center gap-1.5">
                            <i class="bi bi-x-lg text-xs"></i> Reset
                        </a>
                    @endif

                </form>

                <button onclick="openModal('modalTambah')"
                    class="shrink-0 px-4 py-2.5 rounded-xl bg-[#145D9E] text-white text-sm font-semibold hover:bg-[#0f4d85] transition inline-flex items-center gap-2 cursor-pointer shadow-sm">
                    <i class="bi bi-plus-lg"></i>
                    Tambah
                </button>

            </div>

            <div id="tableContainer" class="overflow-x-auto">
                <table class="min-w-full text-sm">

                    <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold w-12">No</th>
                            <th class="px-4 py-3 text-left font-semibold w-32">Badge ID</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama &amp; Email</th>
                            <th class="px-4 py-3 text-left font-semibold w-40">Departemen</th>
                            <th class="px-4 py-3 text-left font-semibold w-40">Jabatan</th>
                            <th class="px-4 py-3 text-left font-semibold w-28">Status</th>
                            <th class="px-4 py-3 text-center font-semibold w-32">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($hods as $i => $hod)
                            <tr class="hover:bg-gray-50/70 transition">

                                <td class="px-4 py-3 text-gray-400 text-xs font-medium">
                                    {{ $hods->firstItem() + $i }}
                                </td>

                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center rounded-full border border-indigo-200 bg-indigo-50 text-indigo-700 px-3 py-1 text-xs font-semibold">
                                        {{ $hod->badge_id }}
                                    </span>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">

                                        <div
                                            class="w-8 h-8 rounded-full bg-[#145D9E]/10 text-[#145D9E] text-xs font-bold flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($hod->user->nama, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-800 text-sm">
                                                {{ $hod->user->nama }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                {{ $hod->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center rounded-full border border-blue-200 bg-blue-50 text-blue-700 px-3 py-1 text-xs font-semibold">
                                        {{ $hod->departemen->kode ?? '-' }}
                                    </span>
                                    <div class="text-xs text-gray-400 mt-0.5 pl-1">
                                        {{ $hod->departemen->nama ?? '' }}
                                    </div>
                                </td>

                                <td class="px-4 py-3 text-gray-700 text-sm">
                                    {{ $hod->jabatan }}
                                </td>

                                <td class="px-4 py-3">
                                    @php
                                        $statusMap = [
                                            'aktif' => [
                                                'bg-green-50 border-green-200 text-green-700',
                                                'bi-circle-fill',
                                                'Aktif',
                                            ],
                                            'nonaktif' => [
                                                'bg-gray-100 border-gray-200 text-gray-600',
                                                'bi-circle-fill',
                                                'Non-Aktif',
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
                                        ];

                                        [$cls, $icon, $label] = $statusMap[$hod->user->status] ?? [
                                            'bg-gray-100 border-gray-200 text-gray-500',
                                            'bi-dash-circle',
                                            $hod->user->status,
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-semibold {{ $cls }}">
                                        <i class="bi {{ $icon }} text-[9px]"></i>
                                        {{ $label }}
                                    </span>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-1.5">

                                        <button type="button"
                                            onclick='openModalDetail(
                                                @json($hod->user->nama),
                                                @json($hod->user->email),
                                                @json($hod->badge_id),
                                                @json($hod->departemen->kode ?? '-'),
                                                @json($hod->departemen->nama ?? '-'),
                                                @json($hod->jabatan),
                                                @json($hod->user->status),
                                                @json($hod->created_at?->translatedFormat('d M Y') ?? '-')
                                            )'
                                            title="Detail"
                                            class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 flex items-center justify-center
                                                   hover:bg-gray-50 hover:border-gray-300 cursor-pointer transition">
                                            <i class="bi bi-eye text-sm"></i>
                                        </button>

                                        <button type="button"
                                            onclick='openModalEdit(
                                                {{ $hod->id }},
                                                @json($hod->user->nama),
                                                @json($hod->user->email),
                                                @json($hod->badge_id),
                                                {{ $hod->departemen_id }},
                                                @json($hod->jabatan)
                                            )'
                                            title="Edit"
                                            class="w-8 h-8 rounded-lg border border-gray-200 text-blue-600 flex items-center justify-center
                                                   hover:bg-blue-50 hover:border-blue-200 cursor-pointer transition">
                                            <i class="bi bi-pencil-square text-sm"></i>
                                        </button>

                                        <button type="button" onclick="hapusHod({{ $hod->id }})" title="Hapus"
                                            class="w-8 h-8 rounded-lg border border-gray-200 text-red-500 flex items-center justify-center
                                                   hover:bg-red-50 hover:border-red-200 cursor-pointer transition">
                                            <i class="bi bi-trash3 text-sm"></i>
                                        </button>

                                    </div>
                                </td>

                            </tr>
                        @empty

                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div
                                            class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-300 text-2xl">
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
                                        @if (request('search') || request('departemen'))
                                            <a href="{{ route('hrd.hod.index') }}"
                                                class="mt-1 px-4 py-2 rounded-xl bg-red-600 text-white
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

            <div
                class="px-5 py-3.5 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <span>Tampilkan</span>
                    <select onchange="changePerPage(this.value)"
                        class="px-2.5 py-1.5 rounded-lg border border-gray-200 text-xs
                            font-semibold text-gray-700 bg-white outline-none
                            focus:ring-2 focus:ring-blue-200 cursor-pointer">
                        @foreach ([5, 10, 20, 50] as $opt)
                            <option value="{{ $opt }}" {{ request('perPage', 5) == $opt ? 'selected' : '' }}>
                                {{ $opt }}
                            </option>
                        @endforeach
                    </select>
                    <span>
                        @if ($hods->count() > 0)
                            menampilkan
                            <span class="font-semibold text-gray-700">{{ $hods->firstItem() }}</span>
                            &ndash;
                            <span class="font-semibold text-gray-700">{{ $hods->lastItem() }}</span>
                            dari
                            <span class="font-semibold text-gray-700">{{ $hods->total() }}</span>
                        @endif
                    </span>
                </div>

                @if ($hods->total() > 0)
                    @php
                        $currentPage = $hods->currentPage();
                        $lastPage = $hods->lastPage();
                        $paginated = $hods->appends(request()->query());

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
                            <a href="{{ $paginated->previousPageUrl() }}"
                                class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 bg-white
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
                                <span
                                    class="w-8 h-8 rounded-lg text-xs font-semibold bg-[#145D9E] text-white flex items-center justify-center shadow-sm">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $paginated->url($page) }}"
                                    class="w-8 h-8 rounded-lg text-xs font-semibold border border-gray-200
                                    bg-white text-gray-600 flex items-center justify-center hover:bg-blue-50 hover:border-blue-200 hover:text-[#145D9E] transition">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if ($paginated->hasMorePages())
                            <a href="{{ $paginated->nextPageUrl() }}"
                                class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 bg-white
                                flex items-center justify-center hover:bg-blue-50 hover:border-blue-200 hover:text-[#145D9E] transition">
                                <i class="bi bi-chevron-right text-xs"></i>
                            </a>
                        @else
                            <span
                                class="w-8 h-8 rounded-lg border border-gray-200 text-gray-300 flex items-center justify-center cursor-not-allowed select-none">
                                <i class="bi bi-chevron-right text-xs"></i>
                            </span>
                        @endif

                    </div>
                @endif

            </div>

        </div>

    </div>
    {{-- Modal Tambah --}}

    <div id="modalTambah" class="fixed inset-0 z-[60] flex items-center justify-center hidden">

        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalTambah')"></div>

        <div class="relative bg-white rounded-2xl w-full max-w-lg mx-4 shadow-2xl max-h-[90vh] flex flex-col">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
                <div class="text-base font-bold text-gray-800">Tambah HOD</div>
                <button type="button" onclick="closeModal('modalTambah')"
                    class="w-8 h-8 rounded-xl border border-gray-200 flex items-center justify-center
                           text-white bg-red-600 hover:bg-red-700 cursor-pointer transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <form action="{{ route('hrd.hod.store') }}" method="POST" class="overflow-y-auto">
                @csrf

                <div class="px-6 py-5 space-y-4">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" value="{{ old('nama') }}"
                                placeholder="Contoh: Budi Santoso"
                                class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                                       focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                       {{ $errors->has('nama') && $errorModal === 'tambah' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">

                            @if ($errorModal === 'tambah')
                                @error('nama')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Badge ID <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="badge_id" value="{{ old('badge_id') }}"
                                placeholder="Contoh: HOD-001"
                                class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                                       focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                       {{ $errors->has('badge_id') && $errorModal === 'tambah' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            @if ($errorModal === 'tambah')
                                @error('badge_id')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            @endif
                        </div>

                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            placeholder="Contoh: budi@perusahaan.com"
                            class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                                   focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                   {{ $errors->has('email') && $errorModal === 'tambah' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @if ($errorModal === 'tambah')
                            @error('email')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="tambahPassword" placeholder="Min. 8 karakter"
                                    class="w-full px-4 py-2.5 pr-10 border rounded-xl text-sm outline-none
                                           focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                           {{ $errors->has('password') && $errorModal === 'tambah' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">

                                <button type="button" onclick="togglePassword('tambahPassword', 'eyeTambah')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2
                                           text-gray-400 hover:text-gray-600">
                                    <i id="eyeTambah" class="bi bi-eye text-sm"></i>
                                </button>
                            </div>
                            @if ($errorModal === 'tambah')
                                @error('password')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="tambahPasswordConf"
                                    placeholder="Ulangi password"
                                    class="w-full px-4 py-2.5 pr-10 border border-gray-300
                                           rounded-xl text-sm outline-none
                                           focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition">
                                <button type="button" onclick="togglePassword('tambahPasswordConf', 'eyeTambahConf')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2
                                           text-gray-400 hover:text-gray-600">
                                    <i id="eyeTambahConf" class="bi bi-eye text-sm"></i>
                                </button>
                            </div>
                        </div>

                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Departemen <span class="text-red-500">*</span>
                            </label>
                            <select name="departemen_id"
                                class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none bg-white
                                       focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                       {{ $errors->has('departemen_id') && $errorModal === 'tambah' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                                <option value="">Pilih Departemen</option>
                                @foreach ($daftarDepartemen as $dep)
                                    <option value="{{ $dep->id }}"
                                        {{ old('departemen_id') == $dep->id ? 'selected' : '' }}>
                                        {{ $dep->kode }} — {{ $dep->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errorModal === 'tambah')
                                @error('departemen_id')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Jabatan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="jabatan" value="{{ old('jabatan') }}"
                                placeholder="Contoh: Head of IT"
                                class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                                       focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                       {{ $errors->has('jabatan') && $errorModal === 'tambah' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            @if ($errorModal === 'tambah')
                                @error('jabatan')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            @endif
                        </div>

                    </div>

                </div>

                <div
                    class="px-6 py-4 border-t border-gray-100 bg-gray-50/50
                            flex justify-end gap-2 shrink-0">
                    <button type="button" onclick="closeModal('modalTambah')"
                        class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600
                               text-sm font-semibold hover:bg-gray-100 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#145D9E] text-white text-sm font-semibold
                               hover:bg-[#0f4d85] transition inline-flex items-center gap-2 shadow-sm">
                        <i class="bi bi-plus-lg"></i> Simpan
                    </button>
                </div>

            </form>
        </div>
    </div>
    {{-- Modal Edit --}}

    <div id="modalEdit" class="fixed inset-0 z-[60] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalEdit')"></div>
        <div
            class="relative bg-white rounded-2xl w-full max-w-lg mx-4 shadow-2xl
                    max-h-[90vh] flex flex-col">

            <div
                class="flex items-center justify-between px-6 py-4
                        border-b border-gray-100 shrink-0">
                <div>
                    <div class="text-base font-bold text-gray-800">Edit HOD</div>
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

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>

                            <input id="editNama" type="text" name="nama"
                                value="{{ $errorModal === 'edit' ? old('nama') : '' }}"
                                class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                                       focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
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
                                Badge ID <span class="text-red-500">*</span>
                            </label>
                            <input id="editBadgeId" type="text" name="badge_id"
                                value="{{ $errorModal === 'edit' ? old('badge_id') : '' }}"
                                class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                                       focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                       {{ $errors->has('badge_id') && $errorModal === 'edit' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            @if ($errorModal === 'edit')
                                @error('badge_id')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            @endif
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input id="editEmail" type="email" name="email"
                            value="{{ $errorModal === 'edit' ? old('email') : '' }}"
                            class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                                   focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                   {{ $errors->has('email') && $errorModal === 'edit' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @if ($errorModal === 'edit')
                            @error('email')
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
                                <input type="password" name="password" id="editPassword"
                                    placeholder="Kosongkan jika tidak diubah"
                                    class="w-full px-4 py-2.5 pr-10 border rounded-xl text-sm outline-none
                                           focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                           {{ $errors->has('password') && $errorModal === 'edit' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                                <button type="button" onclick="togglePassword('editPassword', 'eyeEdit')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2
                                           text-gray-400 hover:text-gray-600">
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
                                <input type="password" name="password_confirmation" id="editPasswordConf"
                                    placeholder="Ulangi password baru"
                                    class="w-full px-4 py-2.5 pr-10 border border-gray-300
                                           rounded-xl text-sm outline-none
                                           focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition">
                                <button type="button" onclick="togglePassword('editPasswordConf', 'eyeEditConf')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2
                                           text-gray-400 hover:text-gray-600">
                                    <i id="eyeEditConf" class="bi bi-eye text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Departemen <span class="text-red-500">*</span>
                            </label>
                            <select id="editDepartemenId" name="departemen_id"
                                class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none bg-white
                                       focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                       {{ $errors->has('departemen_id') && $errorModal === 'edit' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                                <option value="">Pilih Departemen</option>
                                @foreach ($daftarDepartemen as $dep)
                                    <option value="{{ $dep->id }}"
                                        {{ $errorModal === 'edit' && old('departemen_id') == $dep->id ? 'selected' : '' }}>
                                        {{ $dep->kode }} — {{ $dep->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errorModal === 'edit')
                                @error('departemen_id')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Jabatan <span class="text-red-500">*</span>
                            </label>
                            <input id="editJabatan" type="text" name="jabatan"
                                value="{{ $errorModal === 'edit' ? old('jabatan') : '' }}"
                                class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                                       focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                       {{ $errors->has('jabatan') && $errorModal === 'edit' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            @if ($errorModal === 'edit')
                                @error('jabatan')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            @endif
                        </div>
                    </div>

                </div>

                <div
                    class="px-6 py-4 border-t border-gray-100 bg-gray-50/50
                            flex justify-end gap-2 shrink-0">
                    <button type="button" onclick="closeModal('modalEdit')"
                        class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600
                               text-sm font-semibold hover:bg-gray-100 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#145D9E] text-white text-sm font-semibold
                               hover:bg-[#0f4d85] transition inline-flex items-center gap-2 shadow-sm">
                        <i class="bi bi-floppy"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
    {{-- Modal Detail --}}

    <div id="modalDetail" class="fixed inset-0 z-[60] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalDetail')"></div>
        <div class="relative bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl flex flex-col">

            <div
                class="flex items-center justify-between px-6 py-4
                        border-b border-gray-100 shrink-0">
                <div>
                    <div class="text-base font-bold text-gray-800">Detail HOD</div>
                    <div class="text-xs text-gray-400 mt-0.5">
                        Informasi lengkap Head of Department
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalDetail')"
                    class="w-8 h-8 rounded-xl border border-gray-200 flex items-center justify-center
                           text-white bg-red-600 hover:bg-red-700 cursor-pointer transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <div class="px-6 py-5 space-y-5">

                <div class="flex items-center gap-4">

                    <div id="detailAvatar"
                        class="w-14 h-14 rounded-2xl bg-[#145D9E]/10 text-[#145D9E]
                               text-xl font-bold flex items-center justify-center shrink-0">
                    </div>
                    <div>
                        <div id="detailNama" class="text-base font-bold text-gray-800"></div>
                        <div id="detailEmail" class="text-xs text-gray-400 mt-0.5"></div>
                    </div>
                </div>

                <div class="border-t border-gray-100"></div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Badge ID</div>
                        <div id="detailBadgeId"
                            class="inline-flex items-center rounded-full border border-indigo-200
                                   bg-indigo-50 text-indigo-700 px-3 py-1 text-xs font-semibold">
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Status</div>

                        <div id="detailStatus"></div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Kode Departemen</div>
                        <div id="detailDepartemen"
                            class="inline-flex items-center rounded-full border border-blue-200
                                   bg-blue-50 text-blue-700 px-3 py-1 text-xs font-semibold">
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Jabatan</div>
                        <div id="detailJabatan" class="text-sm font-semibold text-gray-700"></div>
                    </div>
                    <div class="col-span-2">
                        <div class="text-xs text-gray-400 font-medium mb-1">Nama Departemen</div>
                        <div id="detailDepartemenNama" class="text-sm text-gray-700"></div>
                    </div>
                    <div class="col-span-2">
                        <div class="text-xs text-gray-400 font-medium mb-1">Terdaftar Sejak</div>
                        <div id="detailTanggal" class="text-sm text-gray-700"></div>
                    </div>
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

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            function openModal(id) {
                document.getElementById(id).classList.remove('hidden');
            }

            function closeModal(id) {
                document.getElementById(id).classList.add('hidden');
                resetModal(id); // bersihkan isi modal setelah ditutup
            }

            function openModalDetail(nama, email, badgeId, depKode, depNama, jabatan, status, tanggal) {

                document.getElementById('detailAvatar').textContent = nama.charAt(0).toUpperCase();
                document.getElementById('detailNama').textContent = nama;
                document.getElementById('detailEmail').textContent = email;
                document.getElementById('detailBadgeId').textContent = badgeId;
                document.getElementById('detailDepartemen').textContent = depKode;
                document.getElementById('detailDepartemenNama').textContent = depNama;
                document.getElementById('detailJabatan').textContent = jabatan;
                document.getElementById('detailTanggal').textContent = tanggal;

                const statusMap = {
                    aktif: {
                        cls: 'bg-green-50 border-green-200 text-green-700',
                        icon: 'bi-circle-fill',
                        label: 'Aktif'
                    },
                    nonaktif: {
                        cls: 'bg-gray-100 border-gray-200 text-gray-600',
                        icon: 'bi-circle-fill',
                        label: 'Non-Aktif'
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
                };

                const s = statusMap[status] ?? {
                    cls: 'bg-gray-100 border-gray-200 text-gray-500',
                    icon: 'bi-dash-circle',
                    label: status
                };

                document.getElementById('detailStatus').innerHTML =
                    `<span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-semibold ${s.cls}">
                        <i class="bi ${s.icon} text-[9px]"></i>${s.label}
                    </span>`;

                openModal('modalDetail');
            }

            function openModalEdit(id, nama, email, badgeId, departemenId, jabatan) {

                document.getElementById('formEdit').action = `/hrd/akun-hod/${id}`;

                document.getElementById('editNama').value = nama;
                document.getElementById('editEmail').value = email;
                document.getElementById('editBadgeId').value = badgeId;
                document.getElementById('editJabatan').value = jabatan;

                const select = document.getElementById('editDepartemenId');
                for (let opt of select.options) {
                    opt.selected = (opt.value == departemenId);
                }

                document.getElementById('editPassword').value = '';
                document.getElementById('editPasswordConf').value = '';

                openModal('modalEdit');
            }

            function resetModal(modalId) {
                const modal = document.getElementById(modalId);

                modal.querySelectorAll('input').forEach(input => {
                    if (!['hidden', 'submit', 'button'].includes(input.type)) {
                        input.value = '';
                    }
                });

                modal.querySelectorAll('select').forEach(select => {
                    select.selectedIndex = 0;
                });

                modal.querySelectorAll('textarea').forEach(textarea => {
                    textarea.value = '';
                });

                modal.querySelectorAll('.border-red-400').forEach(el => {
                    el.classList.remove('border-red-400', 'bg-red-50');
                    el.classList.add('border-gray-300');
                });

                modal.querySelectorAll('p.text-red-500').forEach(el => el.remove());

                modal.querySelectorAll('.bi-eye-slash').forEach(icon => {
                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                });
            }

            function hapusHod(id) {
                Swal.fire({
                    title: 'Hapus Data HOD?',
                    text: 'Akun dan data karyawan HOD ini akan dihapus secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DC2626', // merah
                    cancelButtonColor: '#6B7280', // abu-abu
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/hrd/akun-hod/${id}`;
                        form.innerHTML = `@csrf <input type="hidden" name="_method" value="DELETE">`;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            function togglePassword(inputId, iconId) {
                const input = document.getElementById(inputId);
                const icon = document.getElementById(iconId);

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('bi-eye', 'bi-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                }
            }

            function changePerPage(value) {
                const url = new URL(window.location.href);
                url.searchParams.set('perPage', value);
                url.searchParams.set('page', 1); // reset ke halaman 1
                window.location.href = url.toString();
            }

            const flash = document.getElementById('flashSuccess');
            if (flash) setTimeout(() => flash.remove(), 5000);

            const searchInput = document.getElementById('searchInput');
            let debounce;

            searchInput.addEventListener('input', function() {
                clearTimeout(debounce);
                debounce = setTimeout(async () => {
                    const keyword = this.value;
                    const url = `/hrd/akun-hod?search=${encodeURIComponent(keyword)}`;

                    const response = await fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const html = await response.text();
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTable = doc.querySelector('#tableContainer');

                    document.querySelector('#tableContainer').innerHTML = newTable.innerHTML;
                }, 300);
            });

            @if ($errors->any())
                @if ($errorModal === 'tambah')
                    openModal('modalTambah');
                @elseif ($errorModal === 'edit')
                    document.getElementById('formEdit').action = `/hrd/akun-hod/{{ $errorHodId }}`;
                    openModal('modalEdit');
                @endif
            @endif
        </script>
    @endpush

@endsection