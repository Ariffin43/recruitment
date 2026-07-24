@extends('hrd.layouts.app')

@section('title', 'Master Departemen')
@section('page_title', 'Departemen')
@section('page_subtitle', 'Kelola data departemen perusahaan.')

@section('content')

    <div class="space-y-4">

        {{-- MAIN CARD --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">

            {{-- FILTER + BUTTON TAMBAH --}}
            <div class="px-5 py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center gap-3">

                {{-- Search Form --}}
                <form method="GET" action="{{ route('hrd.department.index') }}" id="filterForm"
                    class="flex-1 flex flex-col sm:flex-row gap-3">

                    {{-- Hidden perPage ikut serta saat search --}}
                    <input type="hidden" name="perPage" id="hiddenPerPage" value="{{ request('perPage', 5) }}">

                    {{-- Search Input --}}
                    <div class="relative flex-1 max-w-sm">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                        <input id="searchInput" type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari kode atau nama departemen..."
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-300 text-sm outline-none
                               focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition">
                    </div>
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
                            <th class="px-4 py-3 text-left font-semibold w-36">Kode</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama Departemen</th>
                            <th class="px-4 py-3 text-center font-semibold w-28">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($departemen as $i => $d)
                            <tr class="hover:bg-gray-50/70 transition">

                                {{-- No --}}
                                <td class="px-4 py-3 text-gray-400 text-xs font-medium">
                                    {{ $departemen->firstItem() + $i }}
                                </td>

                                {{-- Kode --}}
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center rounded-full border border-indigo-200
                                             bg-indigo-50 text-indigo-700 px-3 py-1 text-xs font-semibold">
                                        {{ $d->kode }}
                                    </span>
                                </td>

                                {{-- Nama --}}
                                <td class="px-4 py-3">
                                    <span class="font-medium text-gray-800">{{ $d->nama }}</span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">

                                        {{-- Edit --}}
                                        <button type="button"
                                            onclick='openModalEdit({{ $d->id }}, @json($d->kode), @json($d->nama))'
                                            title="Edit"
                                            class="w-8 h-8 rounded-lg border border-gray-200 text-blue-600 flex items-center
                                               justify-center hover:bg-blue-50 hover:border-blue-200 cursor-pointer transition">
                                            <i class="bi bi-pencil-square text-sm"></i>
                                        </button>

                                        {{-- Hapus --}}
                                        <button type="button" onclick="hapusDepartemen({{ $d->id }})" title="Hapus"
                                            class="w-8 h-8 rounded-lg border border-gray-200 text-red-500 flex items-center
                                               justify-center hover:bg-red-50 hover:border-red-200 cursor-pointer transition">
                                            <i class="bi bi-trash3 text-sm"></i>
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
                                        @if (request('search'))
                                            <a href="{{ route('hrd.department.index') }}"
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
            <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

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
                        @if ($departemen->total() > 0)
                            menampilkan
                            <span class="font-semibold text-gray-700">{{ $departemen->firstItem() }}</span>
                            &ndash;
                            <span class="font-semibold text-gray-700">{{ $departemen->lastItem() }}</span>
                            dari
                            <span class="font-semibold text-gray-700">{{ $departemen->total() }}</span>
                        @endif
                    </span>
                </div>

                {{-- Custom Pagination --}}
                @if ($departemen->total() > 0)
                    @php
                        $currentPage = $departemen->currentPage();
                        $lastPage = $departemen->lastPage();
                        $paginated = $departemen->appends(request()->query());

                        // Smart page range: always show first, last, current ± 2
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

    </div>

    {{-- MODAL TAMBAH --}}
    <div id="modalTambah" class="fixed inset-0 z-[60] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalTambah')"></div>
        <div class="relative bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl">

            {{-- Header Modal --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <div class="text-base font-bold text-gray-800">Tambah Departemen</div>
                    <div class="text-xs text-gray-400 mt-0.5">Isi kode dan nama departemen baru</div>
                </div>
                <button onclick="closeModal('modalTambah')"
                    class="w-8 h-8 rounded-xl border border-gray-200 flex items-center justify-center
                       text-white bg-red-600 hover:bg-red-700 cursor-pointer transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            {{-- Body Modal --}}
            <form action="{{ route('hrd.department.store') }}" method="POST" class="px-6 py-5 space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Kode Departemen <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="kode" value="{{ old('kode') }}" placeholder="Contoh: IT, HR, FIN"
                        class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                           focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                           {{ $errors->has('kode') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('kode')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <i class="bi bi-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Nama Departemen <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ old('nama') }}"
                        placeholder="Contoh: Information Technology"
                        class="w-full px-4 py-2.5 border rounded-xl text-sm outline-none
                           focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition
                           {{ $errors->has('nama') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    @error('nama')
                        <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                            <i class="bi bi-exclamation-circle"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modalTambah')"
                        class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold
                           hover:bg-gray-50 transition">
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

            {{-- Header Modal --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div>
                    <div class="text-base font-bold text-gray-800">Edit Departemen</div>
                    <div class="text-xs text-gray-400 mt-0.5">Perbarui data departemen</div>
                </div>
                <button onclick="closeModal('modalEdit')"
                    class="w-8 h-8 rounded-xl border border-gray-200 flex items-center justify-center
                       text-white bg-red-600 hover:bg-red-700 cursor-pointer transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            {{-- Body Modal --}}
            <form id="formEdit" method="POST" class="px-6 py-5 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Kode Departemen <span class="text-red-500">*</span>
                    </label>
                    <input id="editKode" type="text" name="kode"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm outline-none
                           focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                        required>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Nama Departemen <span class="text-red-500">*</span>
                    </label>
                    <input id="editNama" type="text" name="nama"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm outline-none
                           focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition"
                        required>
                </div>

                <div class="pt-2 flex justify-end gap-2">
                    <button type="button" onclick="closeModal('modalEdit')"
                        class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold
                           hover:bg-gray-50 transition">
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

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <script>
            // MODAL
            function openModal(id) {
                document.getElementById(id).classList.remove('hidden');
            }

            function closeModal(id) {
                document.getElementById(id).classList.add('hidden');
                resetModal(id);
            }

            // EDIT
            function openModalEdit(id, kode, nama) {
                const form = document.getElementById('formEdit');
                form.action = `/hrd/department/${id}`;
                document.getElementById('editKode').value = kode;
                document.getElementById('editNama').value = nama;
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

            // HAPUS
            function hapusDepartemen(id) {
                Swal.fire({
                    title: 'Hapus Departemen?',
                    text: 'Data departemen akan dihapus secara permanen!',
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
                        form.action = `/hrd/department/${id}`;
                        form.innerHTML = `@csrf <input type="hidden" name="_method" value="DELETE">`;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            // PER PAGE
            function changePerPage(value) {
                const url = new URL(window.location.href);
                url.searchParams.set('perPage', value);
                url.searchParams.set('page', 1);
                window.location.href = url.toString();
            }

            // AUTO-DISMISS FLASH
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
                    const url = `/hrd/department?search=${encodeURIComponent(keyword)}`;
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
                openModal('modalTambah');
            @endif
        </script>
    @endpush

@endsection