@extends('hrd.layouts.app')

@section('title', 'Lowongan Kerja')
@section('page_title', 'Lowongan')
@section('page_subtitle', 'Kelola lowongan kerja yang dibuka berdasarkan FPTK yang disetujui.')

@section('content')
    @php
        $errorModal      = session('error_modal', '');
        $errorLowonganId = session('error_lowongan_id', '');
    @endphp

    <div class="space-y-4">

        {{-- ===== KPI CARDS ===== --}}
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-4">

            {{-- Total Lowongan --}}
            <div class="col-span-2 xl:col-span-1 rounded-2xl shadow-sm overflow-hidden relative"
                style="background: linear-gradient(135deg, #145D9E 0%, #001E56 100%);">
                <div class="p-5 relative z-10">
                    <div class="text-xs sm:text-sm font-semibold text-white/80">TOTAL LOWONGAN</div>
                    <div class="mt-1 text-3xl sm:text-4xl font-extrabold text-white">{{ $lowongans->total() }}</div>
                    <div class="mt-1 text-xs sm:text-sm text-white/70">Seluruh periode</div>
                </div>
                <div class="absolute -top-6 -right-6 w-28 h-28 rounded-full bg-white/5"></div>
            </div>

            {{-- Aktif --}}
            <div class="rounded-2xl border border-emerald-200 bg-white shadow-sm p-4 sm:p-5">
                <div class="text-xs font-semibold text-gray-500">AKTIF</div>
                <div class="mt-1 text-3xl sm:text-4xl font-extrabold text-emerald-700">
                    {{ $lowongans->getCollection()->where('status', 'dibuka')->count() }}
                </div>
                <div class="mt-1 text-xs sm:text-sm text-gray-500">Sedang dibuka</div>
            </div>

            {{-- Tutup --}}
            <div class="rounded-2xl border border-red-200 bg-white shadow-sm p-4 sm:p-5">
                <div class="text-xs font-semibold text-gray-500">TUTUP</div>
                <div class="mt-1 text-3xl sm:text-4xl font-extrabold text-red-600">
                    {{ $lowongans->getCollection()->where('status', 'ditutup')->count() }}
                </div>
                <div class="mt-1 text-xs sm:text-sm text-gray-500">Sudah ditutup</div>
            </div>

            {{-- Total Pelamar (siap dipakai saat tabel lamaran sudah ada) --}}
            <div class="rounded-2xl border border-amber-200 bg-white shadow-sm p-4 sm:p-5">
                <div class="text-xs font-semibold text-gray-500">TOTAL PELAMAR</div>
                <div class="mt-1 text-3xl sm:text-4xl font-extrabold text-amber-600">{{ $totalPelamar }}</div>
                <div class="mt-1 text-xs sm:text-sm text-gray-500">Seluruh lowongan</div>
            </div>

        </div>

        {{-- ===== MAIN CARD ===== --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">

            {{-- FILTER + TOMBOL TAMBAH --}}
            <div class="px-4 sm:px-5 py-4 border-b border-gray-100 flex flex-col gap-3">

                <form method="GET" action="{{ route('hrd.lowongan.index') }}" id="filterForm"
                    class="flex flex-col sm:flex-row gap-3">

                    <input type="hidden" name="perPage" value="{{ request('perPage', 5) }}">

                    {{-- Search --}}
                    <div class="relative flex-1">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari judul atau lokasi..."
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-300 text-sm outline-none
                                   focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition">
                    </div>

                    <div class="flex flex-wrap gap-2 items-center">

                        {{-- Filter Status --}}
                        <select name="status" onchange="document.getElementById('filterForm').submit()"
                            class="px-3 py-2.5 rounded-xl border border-gray-300 text-sm outline-none bg-white
                                   focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition cursor-pointer">
                            <option value="">Semua Status</option>
                            <option value="dibuka"  {{ request('status') === 'dibuka'  ? 'selected' : '' }}>Aktif</option>
                            <option value="ditutup" {{ request('status') === 'ditutup' ? 'selected' : '' }}>Tutup</option>
                        </select>

                        {{-- Filter Tipe Kerja --}}
                        <select name="tipe_kerja" onchange="document.getElementById('filterForm').submit()"
                            class="px-3 py-2.5 rounded-xl border border-gray-300 text-sm outline-none bg-white
                                   focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition cursor-pointer">
                            <option value="">Semua Tipe</option>
                            <option value="fulltime" {{ request('tipe_kerja') === 'fulltime' ? 'selected' : '' }}>Full-time</option>
                            <option value="kontrak"  {{ request('tipe_kerja') === 'kontrak'  ? 'selected' : '' }}>Kontrak</option>
                            <option value="magang"   {{ request('tipe_kerja') === 'magang'   ? 'selected' : '' }}>Magang</option>
                        </select>

                        {{-- Tombol Reset --}}
                        @if (request('search') || request('status') || request('tipe_kerja'))
                            <a href="{{ route('hrd.lowongan.index') }}"
                                class="px-3 py-2.5 rounded-xl border border-gray-200 text-gray-500 text-sm font-semibold
                                       hover:bg-gray-50 transition inline-flex items-center gap-1.5 shrink-0">
                                <i class="bi bi-x-lg text-xs"></i>
                                <span class="hidden sm:inline">Reset</span>
                            </a>
                        @endif

                        {{-- Tombol Buka Lowongan Baru --}}
                        <button type="button" onclick="openModal('modalTambah')"
                            class="ml-auto shrink-0 px-4 py-2.5 rounded-xl bg-[#145D9E] text-white text-sm font-semibold
                                   hover:bg-[#0f4d85] transition inline-flex items-center gap-2 cursor-pointer shadow-sm">
                            <i class="bi bi-plus-lg"></i>
                            <span>Buka Lowongan</span>
                        </button>

                    </div>

                </form>
            </div>

            {{-- TABEL --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">

                    <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold w-10">No</th>
                            <th class="px-4 py-3 text-left font-semibold w-37">FPTK</th>
                            <th class="px-4 py-3 text-left font-semibold">Judul & Lokasi</th>
                            <th class="px-4 py-3 text-left font-semibold w-30">Departemen</th>
                            <th class="px-4 py-3 text-left font-semibold w-27">Posisi</th>
                            <th class="px-4 py-3 text-left font-semibold w-20">Kebutuhan</th>
                            <th class="px-4 py-3 text-left font-semibold w-26">Tanggal Ditutup</th>
                            <th class="px-4 py-3 text-left font-semibold w-20">Tipe</th>
                            <th class="px-4 py-3 text-left font-semibold w-18">Status</th>
                            <th class="px-4 py-3 text-center font-semibold w-22">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($lowongans as $i => $lowongan)
                            <tr class="hover:bg-gray-50/70 transition">

                                <td class="px-4 py-3 text-gray-400 text-xs font-medium">
                                    {{ $lowongans->firstItem() + $i }}
                                </td>

                                {{-- Nomor FPTK --}}
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-full border border-indigo-200
                                                 bg-indigo-50 text-indigo-700 px-2.5 py-1 text-xs font-semibold">
                                        {{ $lowongan->fptk->nomor_fptk ?? '-' }}
                                    </span>
                                </td>

                                {{-- Judul & Lokasi --}}
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-sm">{{ $lowongan->judul }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">
                                        <i class="bi bi-geo-alt text-[10px]"></i> {{ $lowongan->lokasi }}
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="text-sm font-semibold">
                                        {{ $lowongan->fptk->departemen->nama ?? '-' }}
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="text-sm font-semibold">
                                        {{ $lowongan->fptk->posisi_dibutuhkan ?? '-' }}
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="text-sm font-semibold">
                                        {{ $lowongan->fptk->jumlah_kebutuhan ?? 0 }} orang
                                    </div>
                                </td>

                                {{-- Tanggal Ditutup --}}
                                <td class="px-4 py-3 text-gray-500 text-xs">
                                    {{ $lowongan->tanggal_ditutup?->format('d M Y') ?? '-' }}
                                </td>

                                {{-- Tipe Kerja --}}
                                <td class="px-4 py-3">
                                    @php
                                        $tipeMap = [
                                            'fulltime' => ['bg-sky-50 border-sky-200 text-sky-700',           'Full-time'],
                                            'kontrak'  => ['bg-orange-50 border-orange-200 text-orange-700',  'Kontrak'],
                                            'magang'   => ['bg-purple-50 border-purple-200 text-purple-700',  'Magang'],
                                        ];
                                        [$tipeCls, $tipeLabel] = $tipeMap[$lowongan->tipe_kerja]
                                            ?? ['bg-gray-100 border-gray-200 text-gray-600', $lowongan->tipe_kerja];
                                    @endphp
                                    <span class="inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold {{ $tipeCls }}">
                                        {{ $tipeLabel }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-3">
                                    @if ($lowongan->status === 'dibuka')
                                        <span class="inline-flex items-center gap-1 rounded-full border border-green-200
                                                     bg-green-50 text-green-700 px-2.5 py-1 text-xs font-semibold">
                                            <i class="bi bi-circle-fill text-[8px]"></i> Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full border border-red-200
                                                     bg-red-50 text-red-600 px-2.5 py-1 text-xs font-semibold">
                                            <i class="bi bi-x-circle-fill text-[8px]"></i> Tutup
                                        </span>
                                    @endif
                                </td>

                                {{-- Tombol Aksi --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-1.5">

                                        {{-- Detail --}}
                                        <button type="button"
                                            onclick='openModalDetail(
                                                @json($lowongan->judul),
                                                @json($lowongan->fptk->departemen->nama ?? "-"),
                                                @json($lowongan->fptk->departemen->kode ?? "-"),
                                                @json($lowongan->fptk->nomor_fptk ?? "-"),
                                                @json($lowongan->fptk->posisi_dibutuhkan ?? "-"),
                                                @json($lowongan->fptk->jumlah_kebutuhan ?? 0),
                                                @json($lowongan->lokasi),
                                                @json($lowongan->tipe_kerja),
                                                @json($lowongan->status),
                                                @json($lowongan->tanggal_dibuka?->format("d M Y") ?? "-"),
                                                @json($lowongan->tanggal_ditutup?->format("d M Y") ?? "-")
                                            )'
                                            title="Detail"
                                            class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 flex items-center
                                                   justify-center hover:bg-gray-50 hover:border-gray-300 cursor-pointer transition">
                                            <i class="bi bi-eye text-sm"></i>
                                        </button>

                                        {{-- Edit --}}
                                        <button type="button"
                                            onclick='openModalEdit(
                                                {{ $lowongan->id }},
                                                @json($lowongan->judul),
                                                @json($lowongan->lokasi),
                                                @json($lowongan->tipe_kerja),
                                                @json($lowongan->status),
                                                @json($lowongan->tanggal_dibuka?->format("Y-m-d") ?? ""),
                                                @json($lowongan->tanggal_ditutup?->format("Y-m-d") ?? "")
                                            )'
                                            title="Edit"
                                            class="w-8 h-8 rounded-lg border border-gray-200 text-blue-600 flex items-center
                                                   justify-center hover:bg-blue-50 hover:border-blue-200 cursor-pointer transition">
                                            <i class="bi bi-pencil-square text-sm"></i>
                                        </button>

                                        {{-- Hapus --}}
                                        <button type="button"
                                            onclick="hapusLowongan({{ $lowongan->id }})"
                                            title="Hapus"
                                            class="w-8 h-8 rounded-lg border border-gray-200 text-red-500 flex items-center
                                                   justify-center hover:bg-red-50 hover:border-red-200 cursor-pointer transition">
                                            <i class="bi bi-trash3 text-sm"></i>
                                        </button>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-300 text-2xl">
                                            <i class="bi bi-briefcase"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-600">Data tidak ditemukan</div>
                                            <div class="text-xs text-gray-400 mt-0.5">Coba gunakan keyword atau filter lain.</div>
                                        </div>
                                        @if (request('search') || request('status') || request('tipe_kerja'))
                                            <a href="{{ route('hrd.lowongan.index') }}"
                                                class="mt-1 px-4 py-2 rounded-xl bg-red-600 text-white text-xs font-semibold hover:bg-red-700 transition">
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

            {{-- SHOW ENTRIES + PAGINATION --}}
            <div class="px-4 sm:px-5 py-3.5 border-t border-gray-100 bg-gray-50/50
                        flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <span>Tampilkan</span>
                    <select onchange="changePerPage(this.value)"
                        class="px-2.5 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700
                               bg-white outline-none focus:ring-2 focus:ring-blue-200 cursor-pointer">
                        @foreach ([5, 10, 20, 50] as $opt)
                            <option value="{{ $opt }}" {{ request('perPage', 5) == $opt ? 'selected' : '' }}>
                                {{ $opt }}
                            </option>
                        @endforeach
                    </select>
                    @if ($lowongans->count() > 0)
                        <span>
                            menampilkan
                            <span class="font-semibold text-gray-700">{{ $lowongans->firstItem() }}</span>
                            &ndash;
                            <span class="font-semibold text-gray-700">{{ $lowongans->lastItem() }}</span>
                            dari
                            <span class="font-semibold text-gray-700">{{ $lowongans->total() }}</span>
                        </span>
                    @endif
                </div>

                @if ($lowongans->lastPage() > 1)
                    @php
                        $currentPage = $lowongans->currentPage();
                        $lastPage    = $lowongans->lastPage();
                        $paginated   = $lowongans->appends(request()->query());
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
                                class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 bg-white flex items-center
                                       justify-center hover:bg-blue-50 hover:border-blue-200 hover:text-[#145D9E] transition">
                                <i class="bi bi-chevron-left text-xs"></i>
                            </a>
                        @endif

                        @foreach ($range as $idx => $page)
                            @if ($idx > 0 && $page - $range[$idx - 1] > 1)
                                <span class="w-8 h-8 flex items-center justify-center text-xs text-gray-400">…</span>
                            @endif

                            @if ($page === $currentPage)
                                <span class="w-8 h-8 rounded-lg text-xs font-semibold bg-[#145D9E] text-white flex items-center justify-center shadow-sm">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $paginated->url($page) }}"
                                    class="w-8 h-8 rounded-lg text-xs font-semibold border border-gray-200 bg-white text-gray-600
                                           hover:bg-blue-50 hover:border-blue-200 hover:text-[#145D9E] flex items-center justify-center transition">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if ($paginated->hasMorePages())
                            <a href="{{ $paginated->nextPageUrl() }}"
                                class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 bg-white flex items-center
                                       justify-center hover:bg-blue-50 hover:border-blue-200 hover:text-[#145D9E] transition">
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

    {{-- MODAL TAMBAH LOWONGAN --}}
    <div id="modalTambah" class="fixed inset-0 z-[60] flex items-end sm:items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalTambah')"></div>
        <div class="relative bg-white rounded-t-2xl sm:rounded-2xl w-full sm:max-w-lg mx-0 sm:mx-4
                    shadow-2xl max-h-[95vh] sm:max-h-[90vh] flex flex-col">

            <div class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-gray-100 shrink-0">
                <div>
                    <div class="text-base font-bold text-gray-800">Buka Lowongan Baru</div>
                    <div class="text-xs text-gray-400 mt-0.5">
                        Field bertanda <span class="text-red-500">*</span> wajib diisi
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalTambah')"
                    class="w-8 h-8 rounded-xl border border-gray-200 flex items-center justify-center
                           text-white bg-red-600 hover:bg-red-700 cursor-pointer transition shrink-0">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <form action="{{ route('hrd.lowongan.store') }}" method="POST" class="overflow-y-auto">
            @csrf

            <div class="px-5 sm:px-6 py-5 space-y-4">

                {{-- Pilih FPTK --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Ref. FPTK <span class="text-red-500">*</span>
                    </label>
                    <select name="fptk_id" id="selectFptk" onchange="previewFptk(this)"
                        class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none bg-white focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                            {{ $errors->has('fptk_id') && $errorModal === 'create-lowongan' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        <option value="">-- Pilih FPTK yang sudah disetujui --</option>
                        @foreach ($approvedFptk as $fptk)
                            <option value="{{ $fptk->id }}"
                                data-dept="{{ $fptk->departemen->nama ?? '-' }}"
                                data-posisi="{{ $fptk->posisi_dibutuhkan }}"
                                data-jumlah="{{ $fptk->jumlah_kebutuhan }}"
                                data-kualifikasi="{{ $fptk->departemen->kualifikasi->first()->nama_kualifikasi ?? '' }}"
                                {{ old('fptk_id') == $fptk->id ? 'selected' : '' }}>
                                {{ $fptk->nomor_fptk }} — {{ $fptk->departemen->nama ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errorModal === 'create-lowongan')
                        @error('fptk_id')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    @endif
                </div>

                {{-- Preview info dari FPTK yang dipilih --}}
                <div id="previewFptkBox" class="hidden rounded-xl bg-blue-50 border border-blue-200 px-4 py-3 space-y-1">
                    <div class="text-xs font-semibold text-blue-700 mb-2">
                        <i class="bi bi-info-circle"></i> Info dari FPTK yang dipilih
                    </div>
                    <div class="text-xs text-gray-600">
                        Departemen: <span id="previewDept" class="font-semibold text-gray-800"></span>
                    </div>
                    <div class="text-xs text-gray-600">
                        Posisi Dibutuhkan: <span id="previewPosisi" class="font-semibold text-gray-800"></span>
                    </div>
                    <div class="text-xs text-gray-600">
                        Jumlah Kebutuhan: <span id="previewJumlah" class="font-semibold text-gray-800"></span> orang
                    </div>
                </div>

                <div id="kualifikasiBox" class="hidden">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Kualifikasi
                    </label>

                    <div class="w-full px-4 py-3 rounded-xl border border-amber-200 bg-amber-50/60">
                        <ul id="previewKualifikasi" class="space-y-1.5"></ul>
                    </div>

                    <p class="mt-1.5 text-xs text-gray-400">
                        <i class="bi bi-lock text-[10px]"></i>
                        Field ini tidak bisa diedit, mengikuti kualifikasi yang sudah ditentukan.
                    </p>
                </div>

                {{-- Judul Lowongan --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Judul Lowongan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul"
                        value="{{ old('judul') }}"
                        placeholder="Contoh: NOW HIRING — IT Support"
                        class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                            focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                            {{ $errors->has('judul') && $errorModal === 'create-lowongan' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @if ($errorModal === 'create-lowongan')
                        @error('judul')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    @endif
                </div>

                {{-- Tipe Kerja + Lokasi --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Tipe Kerja <span class="text-red-500">*</span>
                        </label>
                        <select name="tipe_kerja"
                            class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none bg-white
                                focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                {{ $errors->has('tipe_kerja') && $errorModal === 'create-lowongan' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            <option value="">Pilih tipe kerja</option>
                            <option value="fulltime" {{ old('tipe_kerja') === 'fulltime' ? 'selected' : '' }}>Full-time</option>
                            <option value="kontrak"  {{ old('tipe_kerja') === 'kontrak'  ? 'selected' : '' }}>Kontrak</option>
                            <option value="magang"   {{ old('tipe_kerja') === 'magang'   ? 'selected' : '' }}>Magang</option>
                        </select>
                        @if ($errorModal === 'create-lowongan')
                            @error('tipe_kerja')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Lokasi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="lokasi"
                            value="{{ old('lokasi') }}"
                            placeholder="Contoh: Jakarta Selatan"
                            class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                                focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                {{ $errors->has('lokasi') && $errorModal === 'create-lowongan' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @if ($errorModal === 'create-lowongan')
                            @error('lokasi')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        @endif
                    </div>

                </div>

                {{-- Tanggal Dibuka + Ditutup --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Tanggal Dibuka <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_dibuka"
                            value="{{ old('tanggal_dibuka') }}"
                            class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                                focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                {{ $errors->has('tanggal_dibuka') && $errorModal === 'create-lowongan' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @if ($errorModal === 'create-lowongan')
                            @error('tanggal_dibuka')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Tanggal Ditutup <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_ditutup"
                            value="{{ old('tanggal_ditutup') }}"
                            class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                                focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                {{ $errors->has('tanggal_ditutup') && $errorModal === 'create-lowongan' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @if ($errorModal === 'create-lowongan')
                            @error('tanggal_ditutup')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        @endif
                    </div>

                </div>

            </div>

            <div class="px-5 sm:px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end gap-2 shrink-0">
                <button type="button" onclick="closeModal('modalTambah')"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold
                        hover:bg-gray-100 transition cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-[#145D9E] text-white text-sm font-semibold
                        hover:bg-[#0f4d85] transition inline-flex items-center gap-2 shadow-sm cursor-pointer">
                    <i class="bi bi-plus-lg"></i> Buka Lowongan
                </button>
            </div>

        </form>
        </div>
    </div>

    {{-- MODAL EDIT LOWONGAN --}}
    <div id="modalEdit" class="fixed inset-0 z-[60] flex items-end sm:items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalEdit')"></div>
        <div class="relative bg-white rounded-t-2xl sm:rounded-2xl w-full sm:max-w-lg mx-0 sm:mx-4
                    shadow-2xl max-h-[95vh] sm:max-h-[90vh] flex flex-col">

            <div class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-gray-100 shrink-0">
                <div>
                    <div class="text-base font-bold text-gray-800">Edit Lowongan</div>
                    <div class="text-xs text-gray-400 mt-0.5">Departemen & posisi tidak bisa diubah (mengikuti FPTK)</div>
                </div>
                <button type="button" onclick="closeModal('modalEdit')"
                    class="w-8 h-8 rounded-xl border border-gray-200 flex items-center justify-center
                           text-white bg-red-600 hover:bg-red-700 cursor-pointer transition shrink-0">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <form id="formEdit" method="POST" class="overflow-y-auto">
                @csrf
                @method('PUT')

                <div class="px-5 sm:px-6 py-5 space-y-4">

                    {{-- Judul --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Judul Lowongan <span class="text-red-500">*</span>
                        </label>
                        <input id="editJudul" type="text" name="judul"
                            value="{{ $errorModal === 'edit-lowongan' ? old('judul') : '' }}"
                            class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                                   focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                   {{ $errors->has('judul') && $errorModal === 'edit-lowongan' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @if ($errorModal === 'edit-lowongan')
                            @error('judul')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        @endif
                    </div>

                    {{-- Tipe Kerja + Lokasi --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Tipe Kerja <span class="text-red-500">*</span>
                            </label>
                            <select id="editTipeKerja" name="tipe_kerja"
                                class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none bg-white
                                       focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                       {{ $errors->has('tipe_kerja') && $errorModal === 'edit-lowongan' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                                <option value="">Pilih tipe kerja</option>
                                <option value="fulltime" {{ $errorModal === 'edit-lowongan' && old('tipe_kerja') === 'fulltime' ? 'selected' : '' }}>Full-time</option>
                                <option value="kontrak"  {{ $errorModal === 'edit-lowongan' && old('tipe_kerja') === 'kontrak'  ? 'selected' : '' }}>Kontrak</option>
                                <option value="magang"   {{ $errorModal === 'edit-lowongan' && old('tipe_kerja') === 'magang'   ? 'selected' : '' }}>Magang</option>
                            </select>
                            @if ($errorModal === 'edit-lowongan')
                                @error('tipe_kerja')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Lokasi <span class="text-red-500">*</span>
                            </label>
                            <input id="editLokasi" type="text" name="lokasi"
                                value="{{ $errorModal === 'edit-lowongan' ? old('lokasi') : '' }}"
                                class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                                       focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                       {{ $errors->has('lokasi') && $errorModal === 'edit-lowongan' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            @if ($errorModal === 'edit-lowongan')
                                @error('lokasi')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            @endif
                        </div>

                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="editStatus" name="status"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm outline-none bg-white
                                   focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition">
                            <option value="dibuka"  {{ $errorModal === 'edit-lowongan' && old('status') === 'dibuka'  ? 'selected' : '' }}>Aktif (Dibuka)</option>
                            <option value="ditutup" {{ $errorModal === 'edit-lowongan' && old('status') === 'ditutup' ? 'selected' : '' }}>Tutup</option>
                        </select>
                    </div>

                    {{-- Tanggal Dibuka + Ditutup --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Tanggal Dibuka <span class="text-red-500">*</span>
                            </label>
                            <input id="editTanggalDibuka" type="date" name="tanggal_dibuka"
                                value="{{ $errorModal === 'edit-lowongan' ? old('tanggal_dibuka') : '' }}"
                                class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                                       focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                       {{ $errors->has('tanggal_dibuka') && $errorModal === 'edit-lowongan' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            @if ($errorModal === 'edit-lowongan')
                                @error('tanggal_dibuka')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Tanggal Ditutup <span class="text-red-500">*</span>
                            </label>
                            <input id="editTanggalDitutup" type="date" name="tanggal_ditutup"
                                value="{{ $errorModal === 'edit-lowongan' ? old('tanggal_ditutup') : '' }}"
                                class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                                       focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                       {{ $errors->has('tanggal_ditutup') && $errorModal === 'edit-lowongan' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            @if ($errorModal === 'edit-lowongan')
                                @error('tanggal_ditutup')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            @endif
                        </div>

                    </div>

                </div>

                <div class="px-5 sm:px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end gap-2 shrink-0">
                    <button type="button" onclick="closeModal('modalEdit')"
                        class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold
                               hover:bg-gray-100 transition cursor-pointer">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#145D9E] text-white text-sm font-semibold
                               hover:bg-[#0f4d85] transition inline-flex items-center gap-2 shadow-sm cursor-pointer">
                        <i class="bi bi-floppy"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- MODAL DETAIL LOWONGAN --}}
    <div id="modalDetail" class="fixed inset-0 z-[60] flex items-end sm:items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalDetail')"></div>
        <div class="relative bg-white rounded-t-2xl sm:rounded-2xl w-full sm:max-w-md mx-0 sm:mx-4
                    shadow-2xl max-h-[95vh] sm:max-h-[90vh] flex flex-col">

            <div class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-gray-100 shrink-0">
                <div>
                    <div class="text-base font-bold text-gray-800">Detail Lowongan</div>
                    <div class="text-xs text-gray-400 mt-0.5">Informasi lengkap lowongan kerja</div>
                </div>
                <button type="button" onclick="closeModal('modalDetail')"
                    class="w-8 h-8 rounded-xl border border-gray-200 flex items-center justify-center
                           text-white bg-red-600 hover:bg-red-700 cursor-pointer transition shrink-0">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <div class="px-5 sm:px-6 py-5 space-y-5 overflow-y-auto">

                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-[#145D9E]/10 text-[#145D9E] text-xl font-bold
                                flex items-center justify-center shrink-0">
                        <i class="bi bi-briefcase-fill"></i>
                    </div>
                    <div>
                        <div id="detailJudul" class="text-base font-bold text-gray-800"></div>
                        <div id="detailDepartemenNama" class="text-xs text-gray-400 mt-0.5"></div>
                    </div>
                </div>

                <div class="border-t border-gray-100"></div>

                <div class="grid grid-cols-2 gap-3 sm:gap-4">

                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Departemen</div>
                        <div id="detailDepartemenKode"
                            class="inline-flex items-center rounded-full border border-blue-200
                                   bg-blue-50 text-blue-700 px-2.5 py-1 text-xs font-semibold"></div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Ref. FPTK</div>
                        <div id="detailFptk"
                            class="inline-flex items-center rounded-full border border-indigo-200
                                   bg-indigo-50 text-indigo-700 px-2.5 py-1 text-xs font-semibold"></div>
                    </div>

                    {{-- Posisi & jumlah dari FPTK --}}
                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Posisi Dibutuhkan</div>
                        <div id="detailPosisi" class="text-sm font-semibold text-gray-700"></div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Jml. Kebutuhan</div>
                        <div id="detailKebutuhan" class="text-sm font-semibold text-gray-700"></div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Status</div>
                        <div id="detailStatus"></div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Tipe Kerja</div>
                        <div id="detailTipe" class="text-sm font-semibold text-gray-700"></div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Lokasi</div>
                        <div id="detailLokasi" class="text-sm font-semibold text-gray-700"></div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Tanggal Dibuka</div>
                        <div id="detailTanggalDibuka" class="text-sm text-gray-700"></div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Tanggal Ditutup</div>
                        <div id="detailTanggalDitutup" class="text-sm text-gray-700"></div>
                    </div>

                </div>

            </div>

            <div class="px-5 sm:px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end shrink-0">
                <button type="button" onclick="closeModal('modalDetail')"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold
                           hover:bg-gray-100 transition cursor-pointer">
                    Tutup
                </button>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>

            // ========== BUKA / TUTUP MODAL ==========
            function openModal(id) {
                document.getElementById(id).classList.remove('hidden');
            }

            function closeModal(id) {
                document.getElementById(id).classList.add('hidden');
                resetModal(id);
            }

            // ========== PREVIEW INFO FPTK DI MODAL TAMBAH ==========
                function previewFptk(select) {
                    const option = select.options[select.selectedIndex];
                    const box = document.getElementById('previewFptkBox');
                    const kualBox = document.getElementById('kualifikasiBox');
                    const kualList = document.getElementById('previewKualifikasi');

                    if (!option.value) {
                        box.classList.add('hidden');
                        kualBox.classList.add('hidden');
                        kualList.innerHTML = '';
                        return;
                    }

                    document.getElementById('previewDept').textContent = option.dataset.dept ?? '-';
                    document.getElementById('previewPosisi').textContent = option.dataset.posisi ?? '-';
                    document.getElementById('previewJumlah').textContent = option.dataset.jumlah ?? '0';
                    box.classList.remove('hidden');

                    const kualifikasi = option.dataset.kualifikasi ?? '';
                    kualList.innerHTML = '';
                    if (kualifikasi.trim()) {
                        kualifikasi
                            .split(/\r?\n/)
                            .map(item => item.trim())
                            .filter(item => item.length)
                            .forEach(item => {
                                kualList.insertAdjacentHTML(
                                    'beforeend',
                                    `
                                    <li class="flex items-start gap-2 text-sm text-amber-900">
                                        <i class="bi bi-check2 text-amber-500 mt-0.5 shrink-0"></i>
                                        <span>${item}</span>
                                    </li>
                                    `
                                );
                            });
                    } else {
                        kualList.innerHTML = `
                            <li class="text-sm text-gray-400 italic">
                                Belum ada kualifikasi untuk departemen ini.
                            </li>
                        `;
                    }
                    kualBox.classList.remove('hidden');
                }

            // ========== MODAL DETAIL ==========
            function openModalDetail(judul, depNama, depKode, fptk, posisi, jumlah, lokasi, tipe, status, tglDibuka, tglDitutup) {
                document.getElementById('detailJudul').textContent          = judul;
                document.getElementById('detailDepartemenNama').textContent = depNama;
                document.getElementById('detailDepartemenKode').textContent = depKode;
                document.getElementById('detailFptk').textContent           = fptk;
                document.getElementById('detailPosisi').textContent         = posisi;
                document.getElementById('detailKebutuhan').textContent      = jumlah + ' orang';
                document.getElementById('detailLokasi').textContent         = lokasi;
                document.getElementById('detailTanggalDibuka').textContent  = tglDibuka;
                document.getElementById('detailTanggalDitutup').textContent = tglDitutup;

                const tipeLabel = { fulltime: 'Full-time', kontrak: 'Kontrak', magang: 'Magang' };
                document.getElementById('detailTipe').textContent = tipeLabel[tipe] ?? tipe;

                const statusCfg = {
                    dibuka:  { cls: 'bg-green-50 border-green-200 text-green-700', icon: 'bi-circle-fill',   label: 'Aktif' },
                    ditutup: { cls: 'bg-red-50 border-red-200 text-red-600',       icon: 'bi-x-circle-fill', label: 'Tutup' },
                };
                const s = statusCfg[status] ?? { cls: 'bg-gray-100 border-gray-200 text-gray-500', icon: 'bi-dash-circle', label: status };
                document.getElementById('detailStatus').innerHTML =
                    `<span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-semibold ${s.cls}">
                        <i class="bi ${s.icon} text-[9px]"></i>${s.label}
                    </span>`;

                openModal('modalDetail');
            }

            // ========== MODAL EDIT ==========
            function openModalEdit(id, judul, lokasi, tipe, status, tglDibuka, tglDitutup) {
                document.getElementById('formEdit').action = `/hrd/lowongan/${id}`;

                document.getElementById('editJudul').value          = judul;
                document.getElementById('editLokasi').value         = lokasi;
                document.getElementById('editTanggalDibuka').value  = tglDibuka;
                document.getElementById('editTanggalDitutup').value = tglDitutup;

                const selectTipe = document.getElementById('editTipeKerja');
                for (let opt of selectTipe.options) {
                    opt.selected = (opt.value === tipe);
                }

                const selectStatus = document.getElementById('editStatus');
                for (let opt of selectStatus.options) {
                    opt.selected = (opt.value === status);
                }

                openModal('modalEdit');
            }

            // ========== HAPUS LOWONGAN ==========
            function hapusLowongan(id) {
                Swal.fire({
                    title: 'Hapus Lowongan?',
                    text: 'Data lowongan akan dihapus secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DC2626',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/hrd/lowongan/${id}`;
                        form.innerHTML = `@csrf <input type="hidden" name="_method" value="DELETE">`;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            // ========== RESET MODAL ==========
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

                // Reset preview box jika ada
                const previewBox = modal.querySelector('#previewFptkBox');
                if (previewBox) previewBox.classList.add('hidden');
            }

            // ========== GANTI JUMLAH PER HALAMAN ==========
            function changePerPage(value) {
                const url = new URL(window.location.href);
                url.searchParams.set('perPage', value);
                url.searchParams.set('page', 1);
                window.location.href = url.toString();
            }

            // ========== AUTO-DISMISS FLASH ==========
            ['flashSuccess', 'flashError'].forEach(function(id) {
                const el = document.getElementById(id);
                if (el) setTimeout(() => el.remove(), 5000);
            });

            // ========== BUKA MODAL OTOMATIS JIKA ADA ERROR VALIDASI ==========
            @if ($errors->any())
                @if ($errorModal === 'create-lowongan')
                    openModal('modalTambah');
                    // Tampilkan kembali preview jika fptk_id ada di old input
                    const oldFptk = document.getElementById('selectFptk');
                    if (oldFptk && oldFptk.value) previewFptk(oldFptk);
                @elseif ($errorModal === 'edit-lowongan')
                    document.getElementById('formEdit').action = `/hrd/lowongan/{{ $errorLowonganId }}`;
                    openModal('modalEdit');
                @endif
            @endif

        </script>
    @endpush

@endsection