@extends('hrd.layouts.app')

@section('title', 'Master Kualifikasi')
@section('page_title', 'Kualifikasi')
@section('page_subtitle', 'Kelola data kualifikasi yang dibutuhkan untuk lowongan kerja.')

@section('content')

    <div class="space-y-4">

        {{-- MAIN CARD --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            {{-- FILTER + BUTTON TAMBAH --}}
            <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center gap-3">

                {{-- Filter Form --}}
                <form method="GET" action="{{ route('hrd.kualifikasi.index') }}" id="filterForm"
                    class="flex-1 flex flex-col sm:flex-row gap-3">

                    {{-- Search Input --}}
                    <div class="relative flex-1 max-w-sm">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                            placeholder="Cari kualifikasi..."
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-300 text-sm outline-none
                               focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition">
                    </div>

                    {{-- Filter Departemen --}}
                    <select name="departemen" id="departemenFilter"
                        onchange="document.getElementById('filterForm').submit()"
                        class="px-4 py-2.5 rounded-xl border border-gray-300 text-sm outline-none
                           focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition bg-white cursor-pointer">
                        <option value="">Semua Departemen</option>
                        @foreach ($departemen as $dep)
                            <option value="{{ $dep->id }}" {{ request('departemen') == $dep->id ? 'selected' : '' }}>
                                {{ $dep->kode }}
                            </option>
                        @endforeach
                    </select>

                    {{-- Hidden perPage ikut serta saat filter --}}
                    <input type="hidden" name="perPage" value="{{ request('perPage', 5) }}">
                </form>

                {{-- Tombol Tambah --}}
                <button onclick="openModal('modalTambah')"
                    class="shrink-0 px-4 py-2.5 rounded-xl bg-[#145D9E] text-white text-sm font-semibold
                       hover:bg-[#0f4d85] transition inline-flex items-center gap-2 cursor-pointer shadow-sm">
                    <i class="bi bi-plus-lg"></i>
                    Tambah
                </button>

            </div>

            {{-- TABEL --}}
            <div id="tableContainer" class="overflow-x-auto">
                <table class="min-w-full text-sm">

                    <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold w-12">No</th>
                            <th class="px-4 py-3 text-left font-semibold w-36">Departemen</th>
                            <th class="px-4 py-3 text-left font-semibold">Kualifikasi</th>
                            <th class="px-4 py-3 text-center font-semibold w-20">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($kualifikasi as $i => $k)
                            <tr class="hover:bg-gray-50/70 transition">

                                {{-- No --}}
                                <td class="px-4 py-3 text-gray-400 text-xs font-medium align-top pt-4">
                                    {{ $kualifikasi->firstItem() + $i }}
                                </td>

                                {{-- Departemen --}}
                                <td class="px-4 py-3 align-top pt-4">
                                    <span
                                        class="inline-flex items-center rounded-full border border-indigo-200
                                             bg-indigo-50 text-indigo-700 px-3 py-1 text-xs font-semibold">
                                        {{ $k->departemen->kode }}
                                    </span>
                                </td>

                                <td class="px-4 py-3">
                                    @php
                                        $items = preg_split("/\r\n|\r|\n/", $k->nama_kualifikasi ?? '');
                                        $items = array_values(array_filter(array_map('trim', $items)));
                                    @endphp

                                    <div class="space-y-1.5 max-w-xl">

                                        {{-- Tampilkan maksimal 3 --}}
                                        @foreach (array_slice($items, 0, 3) as $point)
                                            <div class="flex items-start gap-2 text-sm text-gray-700">
                                                <div class="w-1.5 h-1.5 rounded-full bg-indigo-400 mt-2 shrink-0"></div>
                                                <span>{{ $point }}</span>
                                            </div>
                                        @endforeach

                                        {{-- Jika lebih dari 3 --}}
                                        @if (count($items) > 3)
                                            <button type="button"
                                                onclick='showKualifikasiDetail(@json($items))'
                                                class="inline-flex items-center gap-1.5 text-xs font-semibold
                                                    text-indigo-600 hover:text-indigo-700 mt-1 cursor-pointer transition">

                                                <i class="bi bi-eye text-xs"></i>
                                                +{{ count($items) - 3 }} lainnya
                                            </button>
                                        @endif

                                    </div>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-4 py-3 align-top pt-3.5">
                                    <div class="flex items-center justify-center gap-2">

                                        {{-- Edit --}}
                                        <button type="button" onclick='openModalEdit(
                                            {{ $k->id }},
                                            {{ $k->departemen_id }},
                                            @json($k->nama_kualifikasi)
                                        )'
                                            class="w-8 h-8 rounded-lg border border-gray-200 text-blue-600 flex items-center
                                               justify-center hover:bg-blue-50 hover:border-blue-200 cursor-pointer transition">
                                            <i class="bi bi-pencil-square text-blue-500"></i>
                                        </button>

                                        {{-- Hapus --}}
                                        <button type="button" onclick="hapusKualifikasi({{ $k->id }})" 
                                            class="w-8 h-8 rounded-lg border border-gray-200 text-red-500 flex items-center
                                            justify-center hover:bg-red-50 hover:border-red-200 cursor-pointer transition">
                                            <i class="bi bi-trash3"></i>
                                        </button>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div
                                            class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-300 text-2xl">
                                            <i class="bi bi-inbox"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-600">Data tidak ditemukan</div>
                                        </div>
                                        @if (request('search') || request('departemen'))
                                            <a href="{{ route('hrd.kualifikasi.index') }}"
                                                class="mt-1 px-4 py-2 rounded-xl bg-red-600 text-white text-xs font-semibold
                                                   hover:bg-red-700 transition">
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
            <div
                class="px-5 py-3.5 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                {{-- Show entries --}}
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
                    <span>
                        @if ($kualifikasi->total() > 0)
                            menampilkan
                            <span class="font-semibold text-gray-700">{{ $kualifikasi->firstItem() }}</span>
                            &ndash;
                            <span class="font-semibold text-gray-700">{{ $kualifikasi->lastItem() }}</span>
                            dari
                            <span class="font-semibold text-gray-700">{{ $kualifikasi->total() }}</span>
                        @endif
                    </span>
                </div>

                {{-- Custom Pagination --}}
                @if ($kualifikasi->total() > 0)
                    @php
                        $currentPage = $kualifikasi->currentPage();
                        $lastPage = $kualifikasi->lastPage();
                        $paginated = $kualifikasi->appends(request()->query());

                        // Smart page range: selalu tampilkan halaman pertama, terakhir, dan current ± 2
                        $range = [];
                        for ($p = 1; $p <= $lastPage; $p++) {
                            if ($p === 1 || $p === $lastPage || abs($p - $currentPage) <= 2) {
                                $range[] = $p;
                            }
                        }
                    @endphp

                    <div class="flex items-center gap-1">

                        {{-- Prev --}}
                        @if ($paginated->onFirstPage())
                            <span
                                class="w-8 h-8 rounded-lg border border-gray-200 text-gray-300 flex items-center justify-center cursor-not-allowed select-none">
                                <i class="bi bi-chevron-left text-xs"></i>
                            </span>
                        @else
                            <a href="{{ $paginated->previousPageUrl() }}"
                                class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 bg-white flex items-center
                                   justify-center hover:bg-blue-50 hover:border-blue-200 hover:text-[#145D9E] transition">
                                <i class="bi bi-chevron-left text-xs"></i>
                            </a>
                        @endif

                        {{-- Page Numbers --}}
                        @foreach ($range as $idx => $page)
                            {{-- Ellipsis --}}
                            @if ($idx > 0 && $page - $range[$idx - 1] > 1)
                                <span class="w-8 h-8 flex items-center justify-center text-xs text-gray-400">…</span>
                            @endif

                            @if ($page === $currentPage)
                                <span
                                    class="w-8 h-8 rounded-lg text-xs font-semibold bg-[#145D9E] text-white flex items-center justify-center shadow-sm">
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

                        {{-- Next --}}
                        @if ($paginated->hasMorePages())
                            <a href="{{ $paginated->nextPageUrl() }}"
                                class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 bg-white flex items-center
                                   justify-center hover:bg-blue-50 hover:border-blue-200 hover:text-[#145D9E] transition">
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
        {{-- end main card --}}

    </div>

    {{-- MODAL TAMBAH --}}
    <div id="modalTambah" class="fixed inset-0 z-[60] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalTambah')"></div>
        <div class="relative bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <div class="text-base font-bold text-gray-800">Tambah Kualifikasi</div>
                    <div class="text-xs text-gray-400 mt-0.5">Pilih departemen dan isi daftar kualifikasi</div>
                </div>
                <button onclick="closeModal('modalTambah')"
                    class="w-8 h-8 rounded-xl border border-gray-200 flex items-center justify-center
                       text-white bg-red-600 hover:bg-red-700 cursor-pointer transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            {{-- Body --}}
            <form method="POST" action="{{ route('hrd.kualifikasi.store') }}" class="px-6 py-5 space-y-4">
                @csrf

                {{-- Departemen --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Departemen <span class="text-red-500">*</span>
                    </label>
                    <select name="departemen_id"
                        class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none bg-white
                           focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                           {{ $errors->has('departemen_id') && session('openModal') === 'modalTambah' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                        required>
                        <option value="">Pilih Departemen</option>
                        @foreach ($departemen as $dep)
                            <option value="{{ $dep->id }}" {{ old('departemen_id') == $dep->id ? 'selected' : '' }}
                                {{ in_array($dep->id, $depTerpakai) ? 'disabled' : '' }}>
                                {{ $dep->kode }}
                                {{ in_array($dep->id, $depTerpakai) ? '(sudah ada)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @if (session('openModal') === 'modalTambah')
                        @error('departemen_id')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    @endif
                </div>

                {{-- Kualifikasi --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Kualifikasi <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-400 mb-1.5">Pisahkan setiap kualifikasi dengan baris baru.</p>
                    <textarea name="nama_kualifikasi" rows="5"
                        placeholder="Contoh:&#10;Menguasai MySQL&#10;Memahami jaringan&#10;Minimal D4 Teknik Informatika&#10;Pengalaman kerja minimal 1 tahun"
                        class="w-full px-4 py-3 border rounded-xl text-sm outline-none resize-none
                           focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                           {{ $errors->has('nama_kualifikasi') && session('openModal') === 'modalTambah' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                        required>{{ session('openModal') === 'modalTambah' ? old('nama_kualifikasi') : '' }}</textarea>
                    @if (session('openModal') === 'modalTambah')
                        @error('nama_kualifikasi')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    @endif
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modalTambah')"
                        class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-50 transition">
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

    {{-- MODAL EDIT --}}
    <div id="modalEdit" class="fixed inset-0 z-[60] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalEdit')"></div>
        <div class="relative bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl">

            {{-- Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <div class="text-base font-bold text-gray-800">Edit Kualifikasi</div>
                    <div class="text-xs text-gray-400 mt-0.5">Perbarui data kualifikasi</div>
                </div>
                <button onclick="closeModal('modalEdit')"
                    class="w-8 h-8 rounded-xl border border-gray-200 flex items-center justify-center
                       text-white bg-red-600 hover:bg-red-700 cursor-pointer transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            {{-- Body --}}
            <form id="formEdit" method="POST"
                action="{{ session('openModal') === 'modalEdit' ? route('hrd.kualifikasi.update', session('editId')) : '' }}"
                class="px-6 py-5 space-y-4">
                @csrf
                @method('PUT')

                {{-- Departemen --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Departemen <span class="text-red-500">*</span>
                    </label>
                    <select id="editDepartemenId" name="departemen_id"
                        class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none bg-white
                           focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                           {{ $errors->has('departemen_id') && session('openModal') === 'modalEdit' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                        required>
                        <option value="">Pilih Departemen</option>
                        @foreach ($departemen as $dep)
                            <option value="{{ $dep->id }}"
                                {{ (session('openModal') === 'modalEdit' ? old('departemen_id') : '') == $dep->id ? 'selected' : '' }}>
                                {{ $dep->kode }}
                            </option>
                        @endforeach
                    </select>
                    @if (session('openModal') === 'modalEdit')
                        @error('departemen_id')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    @endif
                </div>

                {{-- Kualifikasi --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                        Kualifikasi <span class="text-red-500">*</span>
                    </label>
                    <p class="text-xs text-gray-400 mb-1.5">Pisahkan setiap kualifikasi dengan baris baru.</p>
                    <textarea id="editNamaKualifikasi" name="nama_kualifikasi" rows="5"
                        placeholder="Contoh:&#10;Menguasai MySQL&#10;Memahami jaringan&#10;Minimal D4 Teknik Informatika&#10;Pengalaman kerja minimal 1 tahun"
                        class="w-full px-4 py-3 border rounded-xl text-sm outline-none resize-none
                           focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                           {{ $errors->has('nama_kualifikasi') && session('openModal') === 'modalEdit' ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                        required>{{ session('openModal') === 'modalEdit' ? old('nama_kualifikasi') : '' }}</textarea>
                    @if (session('openModal') === 'modalEdit')
                        @error('nama_kualifikasi')
                            <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </p>
                        @enderror
                    @endif
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modalEdit')"
                        class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-50 transition">
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

    {{-- MODAL DETAIL --}}
    <div id="modalDetailKualifikasi" class="fixed inset-0 z-[60] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalDetailKualifikasi')"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <div class="text-base font-bold text-gray-900">Detail Kualifikasi</div>
                    <div class="text-xs text-gray-400 mt-0.5">Seluruh daftar kualifikasi recruitment</div>
                </div>
                <button onclick="closeModal('modalDetailKualifikasi')"
                    class="w-8 h-8 rounded-xl border border-gray-200 flex items-center justify-center
                       text-white bg-red-600 hover:bg-red-700 cursor-pointer transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <div id="detailKualifikasiContent" class="px-6 py-5 space-y-3 max-h-[65vh] overflow-y-auto">
                {{-- Diisi oleh JavaScript --}}
            </div>

        </div>
    </div>

    {{-- FORM HAPUS --}}
    <form id="formHapus" method="POST" action="" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>

            function openModal(id) {
                document.getElementById(id).classList.remove('hidden');
            }

            function closeModal(id) {
                document.getElementById(id).classList.add('hidden');
                resetModal(id);
            }

            //─ EDIT
            function openModalEdit(id, departemenId, items) {
                const form = document.getElementById('formEdit');
                form.action = `/hrd/kualifikasi/${id}`;
                document.getElementById('editDepartemenId').value = departemenId;
                document.getElementById('editNamaKualifikasi').value =
                    Array.isArray(items) ? items.join('\n') : items;
                openModal('modalEdit');
            }

            // Modal Reset
            function resetModal(modalId) {
                const modal = document.getElementById(modalId);

                // RESET SEMUA INPUT
                modal.querySelectorAll('input').forEach(input => {
                    if (
                        input.type !== 'hidden' &&
                        input.type !== 'submit' &&
                        input.type !== 'button'
                    ) {
                        input.value = '';
                    }
                    if (input.type === 'text' && input.id.includes('Password')) {
                        input.type = 'password';
                    }
                });

                // RESET SELECT
                modal.querySelectorAll('select').forEach(select => {
                    select.selectedIndex = 0;
                });

                // RESET TEXTAREA
                modal.querySelectorAll('textarea').forEach(textarea => {
                    textarea.value = '';
                });

                // RESET BORDER ERROR
                modal.querySelectorAll('.border-red-400').forEach(el => {
                    el.classList.remove('border-red-400', 'bg-red-50');
                    el.classList.add('border-gray-300');
                });

                // HAPUS PESAN ERROR
                modal.querySelectorAll('p.text-red-500').forEach(el => {
                    el.remove();
                });

                // RESET EYE ICON
                modal.querySelectorAll('.bi-eye-slash').forEach(icon => {
                    icon.classList.replace('bi-eye-slash', 'bi-eye');
                });
            }
            
            //─ HAPUS─
            function hapusKualifikasi(id) {
                Swal.fire({
                    title: 'Hapus Kualifikasi?',
                    text: 'Data kualifikasi akan dihapus secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DC2626',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('formHapus');
                        form.action = `/hrd/kualifikasi/${id}`;
                        form.submit();
                    }
                });
            }

            //─ DETAIL
            function showKualifikasiDetail(items) {
                const container = document.getElementById('detailKualifikasiContent');
                if (!Array.isArray(items)) items = [];

                container.innerHTML = items.map((item, index) => `
            <div class="flex items-start gap-3 p-3.5 rounded-xl border border-gray-100 bg-gray-50/80">
                <div class="w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold
                            flex items-center justify-center shrink-0">
                    ${index + 1}
                </div>
                <div class="text-sm text-gray-700 leading-relaxed">${item}</div>
            </div>
        `).join('');

                openModal('modalDetailKualifikasi');
            }

            //─ PER PAGE
            function changePerPage(value) {
                const url = new URL(window.location.href);
                url.searchParams.set('perPage', value);
                url.searchParams.set('page', 1);
                window.location.href = url.toString();
            }

            //─ AUTO-DISMISS FLASH─
            const flash = document.getElementById('flashSuccess');
            if (flash) {
                setTimeout(() => flash.remove(), 5000);
            }

            // Search
            const searchInput = document.getElementById('searchInput');
            let debounce;

            searchInput.addEventListener('input', function () {
                clearTimeout(debounce);
                debounce = setTimeout(async () => {
                    const keyword = this.value;
                    const url = `/hrd/kualifikasi?search=${encodeURIComponent(keyword)}`;
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

            @if (session('openModal'))
                openModal('{{ session('openModal') }}');
            @endif
        </script>
    @endpush

@endsection