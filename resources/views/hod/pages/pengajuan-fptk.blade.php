@extends('hod.layouts.app')

@section('title', 'Pengajuan FPTK')
@section('page_title', 'Pengajuan FPTK')
@section('page_subtitle', 'Ajukan Formulir Permintaan Tenaga Kerja baru.')

@section('content')

    @php
        $errorModal  = session('error_modal', '');
        $errorFptkId = session('error_fptk_id', '');

        $statusMap = [
            'pending_gm'   => ['bg-yellow-50 border-yellow-200 text-yellow-700',  'bi-clock-fill',        'Pending GM'],
            'revisi_gm'    => ['bg-orange-50 border-orange-200 text-orange-700',  'bi-arrow-repeat',      'Revisi GM'],
            'approved_gm'  => ['bg-blue-50 border-blue-200 text-blue-700',        'bi-check-circle-fill', 'Disetujui GM'],
            'revisi_hrd'   => ['bg-purple-50 border-purple-200 text-purple-700',  'bi-arrow-repeat',      'Revisi HRD'],
            'approved_hrd' => ['bg-green-50 border-green-200 text-green-700',     'bi-check-circle-fill', 'Disetujui HRD'],
            'ditolak'   => ['bg-red-50 border-red-200 text-red-600',           'bi-x-circle-fill',     'Ditolak'],
        ];
    @endphp

    <div class="space-y-4">

        {{-- FLASH MESSAGE --}}
        @if (session('success'))
            <div id="flashSuccess"
                class="flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 border border-green-200
                       text-green-700 text-sm font-semibold shadow-sm">
                <i class="bi bi-check-circle-fill text-green-500 text-base"></i>
                <span>{{ session('success') }}</span>
                <button onclick="document.getElementById('flashSuccess').remove()"
                    class="ml-auto text-green-400 hover:text-green-600 transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>
        @endif

        {{-- MAIN CARD --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">

            {{-- FILTER BAR --}}
            <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center gap-3">

                <form method="GET" action="{{ route('hod.fptk.index') }}" id="filterForm"
                    class="flex-1 flex flex-col sm:flex-row gap-3">

                    <input type="hidden" name="perPage" value="{{ request('perPage', 5) }}">

                    {{-- Search --}}
                    <div class="relative flex-1 max-w-sm">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2
                                  text-gray-400 text-sm pointer-events-none"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nomor FPTK"
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-300 text-sm
                                   outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition">
                    </div>

                    {{-- Filter Status --}}
                    <select name="status" onchange="document.getElementById('filterForm').submit()"
                        class="px-4 py-2.5 rounded-xl border border-gray-300 text-sm outline-none bg-white
                               focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition cursor-pointer">
                        <option value="">Semua Status</option>
                        @foreach ($statusMap as $val => [$cls, $icon, $label])
                            <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Reset (hanya tampil jika ada filter aktif) --}}
                    @if (request('search') || request('status'))
                        <a href="{{ route('hod.fptk.index') }}"
                            class="px-4 py-2.5 rounded-xl border border-gray-200 bg-red-600 text-white
                                   text-sm font-semibold hover:bg-red-700 transition inline-flex items-center gap-1.5">
                            <i class="bi bi-x-lg text-xs"></i> Reset
                        </a>
                    @endif

                </form>

                {{-- Tombol Ajukan FPTK --}}
                <button type="button" onclick="openModalTambah()"
                    class="shrink-0 px-4 py-2.5 rounded-xl bg-[#4338CA] text-white text-sm font-semibold
                           hover:bg-[#372da5] transition inline-flex items-center gap-2 cursor-pointer shadow-sm">
                    <i class="bi bi-plus-lg"></i>
                    Ajukan FPTK
                </button>

            </div>

            {{-- TABEL --}}
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">

                    <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold w-12">No</th>
                            <th class="px-4 py-3 text-left font-semibold">Nomor FPTK</th>
                            <th class="px-4 py-3 text-left font-semibold">Posisi</th>
                            <th class="px-4 py-3 text-left font-semibold w-32">Kebutuhan</th>
                            <th class="px-4 py-3 text-left font-semibold w-40">Tanggal Dibutuhkan</th>
                            <th class="px-4 py-3 text-left font-semibold w-40">Status</th>
                            <th class="px-4 py-3 text-center font-semibold w-28">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($fptk as $i => $item)
                            @php
                                [$cls, $icon, $label] = $statusMap[$item->status] ?? [
                                    'bg-gray-100 border-gray-200 text-gray-500', 'bi-dash-circle', $item->status,
                                ];
                                $butuhRevisi = in_array($item->status, ['revisi_gm', 'revisi_hrd']);
                            @endphp

                            <tr class="hover:bg-gray-50/70 transition">

                                {{-- Nomor urut --}}
                                <td class="px-4 py-3 text-gray-400 text-xs font-medium">
                                    {{ $fptk->firstItem() + $i }}
                                </td>

                                {{-- Nomor FPTK --}}
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-full border border-indigo-200
                                                 bg-indigo-50 text-indigo-700 px-3 py-1 text-xs font-semibold">
                                        {{ $item->nomor_fptk }}
                                    </span>
                                </td>

                                {{-- Posisi Dibutuhkan --}}
                                <td class="px-4 py-3 font-semibold text-gray-800">
                                    {{ $item->posisi_dibutuhkan }}
                                </td>

                                {{-- Jumlah kebutuhan --}}
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center rounded-full border border-sky-200
                                                 bg-sky-50 text-sky-700 px-3 py-1 text-xs font-semibold">
                                        {{ $item->jumlah_kebutuhan }} Orang
                                    </span>
                                </td>

                                {{-- Tanggal dibutuhkan --}}
                                <td class="px-4 py-3 text-gray-700">
                                    {{ \Carbon\Carbon::parse($item->tanggal_dibutuhkan)->translatedFormat('d M Y') }}
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-3">
                                    @if ($butuhRevisi)
                                        <button type="button" onclick='openModalDetail(@json($item))'
                                            class="inline-flex items-center gap-1.5 rounded-full border
                                                   border-orange-300 bg-orange-50 text-orange-700
                                                   px-2.5 py-1 text-xs font-semibold cursor-pointer
                                                   hover:bg-orange-100 hover:border-orange-400 transition">
                                            <i class="bi bi-arrow-repeat text-[9px]"></i>
                                            {{ $label }}
                                            <i class="bi bi-chevron-right text-[9px] opacity-60"></i>
                                        </button>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full border
                                                     px-2.5 py-1 text-xs font-semibold {{ $cls }}">
                                            <i class="bi {{ $icon }} text-[9px]"></i>
                                            {{ $label }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-1.5">

                                        {{-- Detail (selalu ada) --}}
                                        <button type="button" onclick='openModalDetail(@json($item))'
                                            title="Lihat Detail"
                                            class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500
                                                   flex items-center justify-center
                                                   hover:bg-gray-50 hover:border-gray-300 cursor-pointer transition">
                                            <i class="bi bi-eye text-sm"></i>
                                        </button>

                                        {{-- Hapus (hanya untuk status yang belum final) --}}
                                        @if (in_array($item->status, ['pending_gm', 'revisi_gm', 'revisi_hrd']))
                                            <button type="button" onclick="hapusFptk({{ $item->id }})"
                                                title="Hapus"
                                                class="w-8 h-8 rounded-lg border border-gray-200 text-red-500
                                                       flex items-center justify-center
                                                       hover:bg-red-50 hover:border-red-200 cursor-pointer transition">
                                                <i class="bi bi-trash3 text-sm"></i>
                                            </button>
                                        @endif

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center
                                                    justify-center text-gray-300 text-2xl">
                                            <i class="bi bi-folder2-open"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-600">
                                                Belum ada pengajuan FPTK
                                            </div>
                                            <div class="text-xs text-gray-400 mt-0.5">
                                                @if (request('search') || request('status'))
                                                    Coba gunakan keyword atau filter lain.
                                                @else
                                                    Klik tombol "Ajukan FPTK" untuk membuat pengajuan baru.
                                                @endif
                                            </div>
                                        </div>
                                        @if (request('search') || request('status'))
                                            <a href="{{ route('hod.fptk.index') }}"
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

            {{-- PAGINATION --}}
            <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50/50
                        flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                {{-- Per page --}}
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <span>Tampilkan</span>
                    <select onchange="changePerPage(this.value)"
                        class="px-2.5 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold
                               text-gray-700 bg-white outline-none focus:ring-2 focus:ring-blue-200 cursor-pointer">
                        @foreach ([5, 10, 20, 50] as $opt)
                            <option value="{{ $opt }}" {{ request('perPage', 5) == $opt ? 'selected' : '' }}>
                                {{ $opt }}
                            </option>
                        @endforeach
                    </select>
                    <span>
                        @if ($fptk->count() > 0)
                            menampilkan
                            <span class="font-semibold text-gray-700">{{ $fptk->firstItem() }}</span>
                            &ndash;
                            <span class="font-semibold text-gray-700">{{ $fptk->lastItem() }}</span>
                            dari
                            <span class="font-semibold text-gray-700">{{ $fptk->total() }}</span>
                        @endif
                    </span>
                </div>

                {{-- Tombol navigasi halaman --}}
                @if ($fptk->total() > 0)
                    @php
                        $currentPage = $fptk->currentPage();
                        $lastPage    = $fptk->lastPage();
                        $paginated   = $fptk->appends(request()->query());
                        $range       = [];
                        for ($p = 1; $p <= $lastPage; $p++) {
                            if ($p === 1 || $p === $lastPage || abs($p - $currentPage) <= 2) {
                                $range[] = $p;
                            }
                        }
                    @endphp

                    <div class="flex items-center gap-1">

                        {{-- Prev --}}
                        @if ($paginated->onFirstPage())
                            <span class="w-8 h-8 rounded-lg border border-gray-200 text-gray-300
                                         flex items-center justify-center cursor-not-allowed select-none">
                                <i class="bi bi-chevron-left text-xs"></i>
                            </span>
                        @else
                            <a href="{{ $paginated->previousPageUrl() }}"
                                class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 bg-white
                                       flex items-center justify-center
                                       hover:bg-blue-50 hover:border-blue-200 hover:text-[#145D9E] transition">
                                <i class="bi bi-chevron-left text-xs"></i>
                            </a>
                        @endif

                        {{-- Nomor halaman --}}
                        @foreach ($range as $idx => $page)
                            @if ($idx > 0 && $page - $range[$idx - 1] > 1)
                                <span class="w-8 h-8 flex items-center justify-center text-xs text-gray-400">…</span>
                            @endif

                            @if ($page === $currentPage)
                                <span class="w-8 h-8 rounded-lg text-xs font-semibold bg-[#145D9E] text-white
                                             flex items-center justify-center shadow-sm">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $paginated->url($page) }}"
                                    class="w-8 h-8 rounded-lg text-xs font-semibold border border-gray-200
                                           bg-white text-gray-600 flex items-center justify-center
                                           hover:bg-blue-50 hover:border-blue-200 hover:text-[#145D9E] transition">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        {{-- Next --}}
                        @if ($paginated->hasMorePages())
                            <a href="{{ $paginated->nextPageUrl() }}"
                                class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 bg-white
                                       flex items-center justify-center
                                       hover:bg-blue-50 hover:border-blue-200 hover:text-[#145D9E] transition">
                                <i class="bi bi-chevron-right text-xs"></i>
                            </a>
                        @else
                            <span class="w-8 h-8 rounded-lg border border-gray-200 text-gray-300
                                         flex items-center justify-center cursor-not-allowed select-none">
                                <i class="bi bi-chevron-right text-xs"></i>
                            </span>
                        @endif

                    </div>
                @endif

            </div>

        </div>

    </div>

    {{-- MODAL — TAMBAH / REVISI FPTK --}}
    <div id="modalFptk" class="fixed inset-0 z-[60] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalFptk')"></div>
        <div class="relative bg-white rounded-2xl w-full max-w-2xl mx-4 shadow-2xl max-h-[90vh] flex flex-col">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0 rounded-t-2xl">
                <div class="flex items-center gap-3">
                    <div id="fptkModalIconWrap"
                        class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0">
                        <i id="fptkModalIcon" class="bi text-lg"></i>
                    </div>
                    <div>
                        <div id="fptkModalTitle" class="text-base font-bold text-gray-800"></div>
                        <div id="fptkModalSubtitle" class="text-xs text-gray-400 mt-0.5"></div>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalFptk')"
                    class="w-8 h-8 rounded-xl border border-gray-200 bg-red-600 text-white
                           flex items-center justify-center hover:bg-red-700 cursor-pointer transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            {{-- Banner catatan revisi (disembunyikan saat mode ajukan) --}}
            <div id="fptkRevisiBanner" class="hidden px-6 pt-4 shrink-0">
                <div class="flex items-start gap-3 px-4 py-3 rounded-xl bg-orange-50 border border-orange-200">
                    <i class="bi bi-exclamation-triangle-fill text-orange-500 text-sm mt-0.5 shrink-0"></i>
                    <div>
                        <div class="text-xs font-bold text-orange-800 mb-0.5">
                            Catatan dari <span id="fptkRevisiDari"></span>
                        </div>
                        <div id="fptkRevisiCatatan" class="text-xs text-orange-700 leading-relaxed"></div>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form id="formFptk" method="POST" action="{{ route('hod.fptk.store') }}" class="overflow-y-auto">
                @csrf
                <input type="hidden" id="formMethod" name="_method" value="POST">

                <div class="px-6 py-5 space-y-4">

                    {{-- Departemen (readonly, otomatis dari akun yang login) --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Departemen</label>
                        <input type="hidden" name="departemen_id"
                            value="{{ auth()->user()->karyawan->departemen_id ?? '' }}">
                        <div class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50
                                    text-sm text-gray-600 flex items-center gap-2 cursor-not-allowed">
                            <i class="bi bi-building text-gray-400"></i>
                            <span>
                                {{ auth()->user()->karyawan->departemen->kode ?? '' }}
                                @if (auth()->user()->karyawan?->departemen) - @endif
                                {{ auth()->user()->karyawan->departemen->nama ?? 'Departemen belum diatur' }}
                            </span>
                        </div>
                        <p class="mt-1.5 text-xs text-gray-400 flex items-center gap-1">
                            <i class="bi bi-info-circle"></i>
                            Departemen diisi otomatis dari akun Anda dan tidak dapat diubah.
                        </p>
                    </div>

                    {{-- Kualifikasi departemen (info dari HRD, readonly) --}}
                    @if ($kualifikasi)
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                <i class="bi bi-award text-amber-500"></i>
                                Kualifikasi Departemen
                                <span class="text-gray-400 font-normal">(ditetapkan oleh HRD)</span>
                            </label>
                            <div class="w-full px-4 py-3 rounded-xl border border-amber-200 bg-amber-50/60">
                                <ul class="space-y-1.5">
                                    @foreach (preg_split('/\r\n|\r|\n/', $kualifikasi->nama_kualifikasi ?? '-') as $k)
                                        <li class="flex items-start gap-2 text-sm text-amber-900">
                                            <i class="bi bi-check2 text-amber-500 mt-0.5 shrink-0"></i>
                                            <span>{{ trim($k) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <p class="mt-1.5 text-xs text-gray-400 flex items-center gap-1">
                                <i class="bi bi-info-circle"></i>
                                Pastikan kandidat memenuhi standar kualifikasi ini sebelum mengajukan.
                            </p>
                        </div>
                    @else
                        <div class="flex items-start gap-3 px-4 py-3 rounded-xl border border-gray-200 bg-gray-50">
                            <i class="bi bi-exclamation-circle text-gray-400 mt-0.5 shrink-0"></i>
                            <div>
                                <div class="text-xs font-semibold text-gray-600">Kualifikasi belum tersedia</div>
                                <div class="text-xs text-gray-400 mt-0.5">
                                    HRD belum mengisi kualifikasi untuk departemen Anda. Silakan hubungi HRD.
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Posisi Dibutuhkan --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Posisi yang Dibutuhkan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="fptkPosisi" name="posisi_dibutuhkan"
                            value="{{ $errorModal === 'fptk' ? old('posisi_dibutuhkan') : '' }}"
                            placeholder="Contoh: Staff Administrasi"
                            class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                                   focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                   {{ $errors->has('posisi_dibutuhkan') && $errorModal === 'fptk' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                        @if ($errorModal === 'fptk')
                            @error('posisi_dibutuhkan')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        @endif
                    </div>

                    {{-- Jumlah + Tanggal --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Jumlah Kebutuhan <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" id="fptkJumlah" name="jumlah_kebutuhan"
                                    value="{{ $errorModal === 'fptk' ? old('jumlah_kebutuhan') : '' }}"
                                    min="1" placeholder="0"
                                    class="w-full px-4 py-2.5 pr-16 border rounded-xl text-sm outline-none
                                           focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                           {{ $errors->has('jumlah_kebutuhan') && $errorModal === 'fptk' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2
                                             text-xs text-gray-400 pointer-events-none">orang</span>
                            </div>
                            @if ($errorModal === 'fptk')
                                @error('jumlah_kebutuhan')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                                Tanggal Dibutuhkan <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="fptkTanggal" name="tanggal_dibutuhkan"
                                value="{{ $errorModal === 'fptk' ? old('tanggal_dibutuhkan') : '' }}"
                                class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                                       focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                                       {{ $errors->has('tanggal_dibutuhkan') && $errorModal === 'fptk' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            @if ($errorModal === 'fptk')
                                @error('tanggal_dibutuhkan')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                    </p>
                                @enderror
                            @endif
                        </div>

                    </div>

                    {{-- Alasan --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Alasan Pengajuan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="fptkAlasan" name="alasan" rows="3"
                            placeholder="Jelaskan alasan dibutuhkannya tenaga kerja baru..."
                            class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                                   focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition resize-none
                                   {{ $errors->has('alasan') && $errorModal === 'fptk' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">{{ $errorModal === 'fptk' ? old('alasan') : '' }}</textarea>
                        @if ($errorModal === 'fptk')
                            @error('alasan')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </p>
                            @enderror
                        @endif
                    </div>

                    {{-- Catatan tambahan (opsional) --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Catatan Tambahan
                            <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <textarea id="fptkCatatan" name="catatan_tambahan" rows="2"
                            placeholder="Informasi tambahan yang perlu diketahui HRD..."
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm outline-none
                                   focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition resize-none">{{ $errorModal === 'fptk' ? old('catatan_tambahan') : '' }}</textarea>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end gap-2 shrink-0">
                    <button type="button" onclick="closeModal('modalFptk')"
                        class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600
                               text-sm font-semibold hover:bg-gray-100 transition">
                        Batal
                    </button>
                    <button type="button" id="fptkSubmitBtn" onclick="konfirmasiSimpan()"
                        class="px-5 py-2.5 rounded-xl text-white text-sm font-semibold
                               transition inline-flex items-center gap-2 shadow-sm cursor-pointer">
                        <i id="fptkSubmitIcon" class="bi"></i>
                        <span id="fptkSubmitLabel"></span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    {{-- MODAL — DETAIL FPTK --}}
    <div id="modalDetail" class="fixed inset-0 z-[60] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalDetail')"></div>
        <div class="relative bg-white rounded-2xl w-full max-w-lg mx-4 shadow-2xl max-h-[90vh] flex flex-col">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
                <div>
                    <div class="text-base font-bold text-gray-800">Detail FPTK</div>
                    <div class="text-xs text-gray-400 mt-0.5">Informasi lengkap &amp; alur persetujuan</div>
                </div>
                <button type="button" onclick="closeModal('modalDetail')"
                    class="w-8 h-8 rounded-xl border border-gray-200 bg-red-600 text-white
                           flex items-center justify-center hover:bg-red-700 cursor-pointer transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="overflow-y-auto px-6 py-5 space-y-5">

                {{-- Info Utama --}}
                <div class="space-y-4">

                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Nomor FPTK</div>
                        <div id="detailNomorFptk"
                            class="inline-flex items-center rounded-full border border-indigo-200
                                   bg-indigo-50 text-indigo-700 px-3 py-1 text-xs font-semibold font-mono">
                        </div>
                    </div>

                    <div class="border-t border-gray-100"></div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <div class="text-xs text-gray-400 font-medium mb-1">Posisi Dibutuhkan</div>
                            <div id="detailPosisi" class="text-sm font-semibold text-gray-800"></div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 font-medium mb-1">Jumlah Kebutuhan</div>
                            <div id="detailJumlah" class="text-sm font-semibold text-gray-800"></div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 font-medium mb-1">Departemen</div>
                            <div id="detailDepartemen"
                                class="inline-flex items-center rounded-full border border-blue-200
                                       bg-blue-50 text-blue-700 px-3 py-1 text-xs font-semibold">
                            </div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 font-medium mb-1">Tanggal Dibutuhkan</div>
                            <div id="detailTanggal" class="text-sm font-semibold text-gray-800"></div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 font-medium mb-1">Status</div>
                            <div id="detailStatus"></div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-400 font-medium mb-1">Tanggal Pengajuan</div>
                            <div id="detailTglDibuat" class="text-sm font-semibold text-gray-800"></div>
                        </div>
                        <div class="col-span-2">
                            <div class="text-xs text-gray-400 font-medium mb-1">Alasan Pengajuan</div>
                            <div id="detailAlasan" class="text-sm text-gray-700 leading-relaxed"></div>
                        </div>
                        <div class="col-span-2 hidden" id="detailCatatanWrap">
                            <div class="text-xs text-gray-400 font-medium mb-1">Catatan Tambahan</div>
                            <div id="detailCatatan" class="text-sm text-gray-700 leading-relaxed"></div>
                        </div>
                    </div>

                </div>

                {{-- Alur Persetujuan --}}
                <div class="border-t border-gray-100 pt-4">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="bi bi-diagram-3 text-gray-400 text-sm"></i>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            Alur Persetujuan
                        </span>
                    </div>
                    <ol id="auditTimeline" class="relative space-y-0 ml-3">
                        {{-- Diisi oleh JS --}}
                    </ol>
                </div>

            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2 shrink-0">
                <button type="button" id="btnRevisiDariDetail"
                    class="hidden px-4 py-2.5 rounded-xl bg-orange-500 text-white text-sm font-semibold
                        hover:bg-orange-600 transition inline-flex items-center gap-2 shadow-sm cursor-pointer">
                    <i class="bi bi-pencil-square"></i>
                    Revisi Sekarang
                </button>

                <button type="button" onclick="closeModal('modalDetail')"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600
                           text-sm font-semibold hover:bg-gray-100 transition">
                    Tutup
                </button>

            </div>

        </div>
    </div>

    @push('scripts')
        <style>
            #auditTimeline li:last-child .timeline-line { display: none; }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            var STATUS_MAP = {
                pending_gm:   { cls: 'bg-yellow-50 border-yellow-200 text-yellow-700',  icon: 'bi-clock-fill',        label: 'Pending GM'   },
                revisi_gm:    { cls: 'bg-orange-50 border-orange-200 text-orange-700',  icon: 'bi-arrow-repeat',      label: 'Revisi GM'    },
                approved_gm:  { cls: 'bg-blue-50 border-blue-200 text-blue-700',        icon: 'bi-check-circle-fill', label: 'Disetujui GM'  },
                revisi_hrd:   { cls: 'bg-purple-50 border-purple-200 text-purple-700',  icon: 'bi-arrow-repeat',      label: 'Revisi HRD'   },
                approved_hrd: { cls: 'bg-green-50 border-green-200 text-green-700',     icon: 'bi-check-circle-fill', label: 'Disetujui HRD' },
                ditolak:   { cls: 'bg-red-50 border-red-200 text-red-600',           icon: 'bi-x-circle-fill',     label: 'Ditolak'   },
            };

            //─ HELPER: BUKA / TUTUP MODAL
            function openModal(id) {
                document.getElementById(id).classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeModal(id) {
                document.getElementById(id).classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                if (id === 'modalFptk') resetModalFptk();
            }

            //─ RESET FORM FPTK─
            function resetModalFptk() {
                var modal = document.getElementById('modalFptk');
                modal.querySelectorAll('input:not([type="hidden"]), textarea').forEach(function (el) {
                    el.value = '';
                    el.classList.remove('border-red-400', 'bg-red-50');
                    el.classList.add('border-gray-300');
                });
                modal.querySelectorAll('p.text-red-500').forEach(function (el) { el.remove(); });
                document.getElementById('fptkRevisiBanner').classList.add('hidden');
            }

            //─ SET MODE MODAL: AJUKAN─
            function setModeAjukan() {
                document.getElementById('fptkModalTitle').innerText    = 'Ajukan FPTK';
                document.getElementById('fptkModalSubtitle').innerText = 'Lengkapi data kebutuhan tenaga kerja.';
                document.getElementById('fptkModalIcon').className     = 'bi bi-file-earmark-plus text-[#145D9E] text-lg';
                document.getElementById('fptkModalIconWrap').className = 'w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center shrink-0';

                var btn = document.getElementById('fptkSubmitBtn');
                btn.classList.remove('bg-orange-500', 'hover:bg-orange-600');
                btn.classList.add('bg-[#145D9E]', 'hover:bg-[#0f4d85]');
                document.getElementById('fptkSubmitIcon').className  = 'bi bi-send';
                document.getElementById('fptkSubmitLabel').innerText = 'Kirim Pengajuan';

                document.getElementById('formFptk').action  = '{{ route('hod.fptk.store') }}';
                document.getElementById('formMethod').value = 'POST';
            }

            //─ SET MODE MODAL: REVISI─
            function setModeRevisi(item) {
                var dariGM   = item.status === 'revisi_gm';
                var peninjau = dariGM ? 'GM' : 'HRD';
                var catatan  = dariGM ? (item.catatan_gm || '') : (item.catatan_hrd || '');

                document.getElementById('fptkModalTitle').innerText    = 'Revisi Pengajuan FPTK';
                document.getElementById('fptkModalSubtitle').innerText = 'Perbaiki data sesuai catatan ' + peninjau + '.';
                document.getElementById('fptkModalIcon').className     = 'bi bi-pencil-square text-orange-600 text-lg';
                document.getElementById('fptkModalIconWrap').className = 'w-9 h-9 rounded-xl bg-orange-50 flex items-center justify-center shrink-0';

                document.getElementById('fptkRevisiDari').innerText    = peninjau;
                document.getElementById('fptkRevisiCatatan').innerText = catatan || '(tidak ada catatan spesifik)';
                document.getElementById('fptkRevisiBanner').classList.remove('hidden');

                var btn = document.getElementById('fptkSubmitBtn');
                btn.classList.remove('bg-[#145D9E]', 'hover:bg-[#0f4d85]');
                btn.classList.add('bg-orange-500', 'hover:bg-orange-600');
                document.getElementById('fptkSubmitIcon').className  = 'bi bi-check2-circle';
                document.getElementById('fptkSubmitLabel').innerText = 'Simpan Revisi';

                document.getElementById('formFptk').action  = '{{ url('/hod/fptk') }}/' + item.id;
                document.getElementById('formMethod').value = 'PUT';
            }

            //─ MODAL TAMBAH (ajukan baru)─
            function openModalTambah() {
                resetModalFptk();
                setModeAjukan();
                openModal('modalFptk');
            }

            //─ MODAL REVISI (edit FPTK yang diminta direvisi)─
            function openModalEdit(item) {
                resetModalFptk();
                setModeRevisi(item);

                document.getElementById('fptkPosisi').value = item.posisi_dibutuhkan;
                document.getElementById('fptkJumlah').value  = item.jumlah_kebutuhan;
                document.getElementById('fptkTanggal').value = item.tanggal_dibutuhkan;
                document.getElementById('fptkAlasan').value  = item.alasan;
                document.getElementById('fptkCatatan').value = item.catatan_tambahan || '';

                openModal('modalFptk');
            }

            //─ MODAL DETAIL + AUDIT TRAIL─
            function fmtDate(val, withTime) {
                if (!val) return null;
                var d    = new Date(val);
                var opts = { day: '2-digit', month: 'short', year: 'numeric' };
                if (withTime) { opts.hour = '2-digit'; opts.minute = '2-digit'; }
                return d.toLocaleDateString('id-ID', opts);
            }

            function auditStep(state, title, date, note, noteAlert) {
                var cfg = {
                    done:      { dot: 'border-green-500',  icon: '<i class="bi bi-check-lg text-green-600 text-[10px] font-bold"></i>',         line: 'bg-green-200',  titleCls: 'text-gray-800 font-semibold',  dateCls: 'text-gray-400'  },
                    revisi:    { dot: 'border-orange-400', icon: '<i class="bi bi-arrow-repeat text-orange-500 text-[10px]"></i>',               line: 'bg-orange-200', titleCls: 'text-orange-700 font-semibold', dateCls: 'text-orange-400' },
                    waiting:   { dot: 'border-gray-300',   icon: '<i class="bi bi-three-dots text-gray-300 text-[10px]"></i>',                   line: 'bg-gray-100',   titleCls: 'text-gray-400 font-medium',    dateCls: 'text-gray-300'  },
                    cancelled: { dot: 'border-red-400',    icon: '<i class="bi bi-x text-red-500 text-[11px] font-bold"></i>',                   line: 'bg-red-200',    titleCls: 'text-red-600 font-semibold',   dateCls: 'text-red-400'   },
                }[state] || {};

                var noteBanner = note
                    ? '<div class="mt-2 flex items-start gap-2 px-3 py-2.5 rounded-lg ' +
                        (noteAlert ? 'bg-orange-50 border border-orange-200' : 'bg-gray-50 border border-gray-100') + '">' +
                        '<i class="bi bi-chat-left-text-fill ' + (noteAlert ? 'text-orange-400' : 'text-gray-300') + ' text-[10px] mt-0.5 shrink-0"></i>' +
                        '<span class="text-xs leading-relaxed ' + (noteAlert ? 'text-orange-800' : 'text-gray-500') + '">' + note + '</span>' +
                        '</div>'
                    : '';

                return '<li class="relative flex gap-4 pb-5 last:pb-0">' +
                    '<div class="flex flex-col items-center">' +
                        '<div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 z-10 bg-white border-2 ' + cfg.dot + '">' + cfg.icon + '</div>' +
                        '<div class="w-px flex-1 mt-1 timeline-line ' + cfg.line + '"></div>' +
                    '</div>' +
                    '<div class="flex-1 pt-0.5 pb-1">' +
                        '<div class="text-xs ' + cfg.titleCls + '">' + title + '</div>' +
                        '<div class="text-[11px] mt-0.5 ' + cfg.dateCls + '">' + (date || '<span class="italic">Menunggu tindakan…</span>') + '</div>' +
                        noteBanner +
                    '</div>' +
                    '</li>';
            }

            function openModalDetail(item) {
                var s = STATUS_MAP[item.status] || { cls: 'bg-gray-100 border-gray-200 text-gray-500', icon: 'bi-dash-circle', label: item.status };

                // Info utama
                document.getElementById('detailNomorFptk').textContent  = item.nomor_fptk;
                document.getElementById('detailPosisi').textContent    = item.posisi_dibutuhkan;
                document.getElementById('detailJumlah').textContent     = item.jumlah_kebutuhan + ' Orang';
                document.getElementById('detailDepartemen').textContent = item.departemen
                    ? item.departemen.kode + ' - ' + item.departemen.nama : '-';
                document.getElementById('detailTanggal').textContent    = fmtDate(item.tanggal_dibutuhkan) || '-';
                document.getElementById('detailAlasan').textContent     = item.alasan || '-';
                document.getElementById('detailTglDibuat').textContent  = fmtDate(item.created_at) || '-';

                var catatanWrap = document.getElementById('detailCatatanWrap');
                if (item.catatan_tambahan) {
                    document.getElementById('detailCatatan').textContent = item.catatan_tambahan;
                    catatanWrap.classList.remove('hidden');
                } else {
                    catatanWrap.classList.add('hidden');
                }

                document.getElementById('detailStatus').innerHTML =
                    '<span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-semibold ' + s.cls + '">' +
                        '<i class="bi ' + s.icon + ' text-[9px]"></i>' + s.label +
                    '</span>';

                const btnRevisi = document.getElementById('btnRevisiDariDetail');
                
                if (['revisi_gm', 'revisi_hrd'].includes(item.status)) {
                    btnRevisi.classList.remove('hidden');
                    btnRevisi.classList.add('inline-flex');

                    btnRevisi.onclick = function () {
                        closeModal('modalDetail');
                        openModalEdit(item);
                    };

                } else {

                    btnRevisi.classList.add('hidden');
                    btnRevisi.classList.remove('inline-flex');

                    btnRevisi.onclick = null;
                }

                var st      = item.status;
                var isBatal = st === 'ditolak';
                var gmDone  = ['approved_gm', 'revisi_hrd', 'approved_hrd'].includes(st);
                var gmRevisi= st === 'revisi_gm';
                var hrdDone = st === 'approved_hrd';
                var hrdRevisi= st === 'revisi_hrd';

                var stateGM  = isBatal ? 'cancelled' : (gmDone ? 'done' : (gmRevisi ? 'revisi' : 'waiting'));
                var stateHRD = isBatal ? 'cancelled' : (hrdDone ? 'done' : (hrdRevisi ? 'revisi' : 'waiting'));
                var stateFinal = isBatal ? 'cancelled' : (hrdDone ? 'done' : 'waiting');

                var titleGM  = gmRevisi ? 'Diminta revisi oleh GM'  : (gmDone  ? 'Disetujui GM'  : 'Menunggu persetujuan GM');
                var titleHRD = hrdRevisi? 'Diminta revisi oleh HRD' : (hrdDone ? 'Disetujui HRD' : 'Menunggu persetujuan HRD');

                var timeline = document.getElementById('auditTimeline');
                timeline.innerHTML =
                    auditStep('done',     'Pengajuan dibuat oleh HOD',                        fmtDate(item.created_at, true),      null,              false) +
                    auditStep(stateGM,   titleGM,                                              fmtDate(item.gm_approved_at, true),  item.catatan_gm,   gmRevisi) +
                    auditStep(stateHRD,  titleHRD,                                             fmtDate(item.hrd_approved_at, true), item.catatan_hrd,  hrdRevisi) +
                    auditStep(stateFinal, isBatal ? 'Pengajuan ditolak' : 'Selesai disetujui',
                              isBatal ? fmtDate(item.updated_at, true) : fmtDate(item.hrd_approved_at, true), null, false);

                openModal('modalDetail');
            }

            function hapusFptk(id) {
                Swal.fire({
                    title: 'Hapus Pengajuan?',
                    text: 'Data pengajuan FPTK ini akan dihapus secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DC2626',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then(function (result) {
                    if (result.isConfirmed) {
                        var form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ url('/hod/fptk') }}/' + id;
                        form.innerHTML = '@csrf <input type="hidden" name="_method" value="DELETE">';
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            function konfirmasiSimpan() {
                var isRevisi = document.getElementById('formMethod').value === 'PUT';
                Swal.fire({
                    title: isRevisi ? 'Simpan Revisi?' : 'Kirim Pengajuan?',
                    text: 'Pastikan semua data sudah benar sebelum melanjutkan.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: isRevisi ? '#f97316' : '#145D9E',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: isRevisi ? 'Ya, Simpan Revisi!' : 'Ya, Kirim!',
                    cancelButtonText: 'Cek Lagi',
                    reverseButtons: true,
                }).then(function (result) {
                    if (result.isConfirmed) document.getElementById('formFptk').submit();
                });
            }

            function changePerPage(value) {
                var url = new URL(window.location.href);
                url.searchParams.set('perPage', value);
                url.searchParams.set('page', 1);
                window.location.href = url.toString();
            }

            //─ AUTO DISMISS FLASH
            var flash = document.getElementById('flashSuccess');
            if (flash) setTimeout(function () { flash.remove(); }, 5000);

            //─ BUKA MODAL KEMBALI JIKA ADA ERROR VALIDASI─
            @if ($errors->any() && $errorModal === 'fptk')
                @if ($errorFptkId)
                    // Mode revisi: set action form ke URL update
                    document.getElementById('formFptk').action  = '{{ url('/hod/fptk') }}/{{ $errorFptkId }}';
                    document.getElementById('formMethod').value = 'PUT';
                    document.getElementById('fptkModalTitle').innerText    = 'Revisi Pengajuan FPTK';
                    document.getElementById('fptkModalSubtitle').innerText = 'Perbaiki data yang ditandai di bawah ini.';
                    document.getElementById('fptkModalIcon').className     = 'bi bi-pencil-square text-orange-600 text-lg';
                    document.getElementById('fptkModalIconWrap').className = 'w-9 h-9 rounded-xl bg-orange-50 flex items-center justify-center shrink-0';
                    var _btn = document.getElementById('fptkSubmitBtn');
                    _btn.classList.remove('bg-[#145D9E]', 'hover:bg-[#0f4d85]');
                    _btn.classList.add('bg-orange-500', 'hover:bg-orange-600');
                    document.getElementById('fptkSubmitIcon').className  = 'bi bi-check2-circle';
                    document.getElementById('fptkSubmitLabel').innerText = 'Simpan Revisi';
                @else
                    // Mode ajukan baru
                    setModeAjukan();
                @endif
                openModal('modalFptk');
            @endif

        </script>
    @endpush

@endsection