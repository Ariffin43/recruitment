@extends('gm.layouts.app')

@section('title', 'Persetujuan FPTK')
@section('page_title', 'Persetujuan FPTK')
@section('page_subtitle', 'Tinjau dan proses persetujuan.')

@section('content')

    @php
        $statusFptkMap = [
            'pending_gm' => ['bg-yellow-50 border-yellow-200 text-yellow-700', 'bi-clock-fill', 'Pending GM'],
            'revisi_gm' => ['bg-orange-50 border-orange-200 text-orange-700', 'bi-arrow-repeat', 'Revisi GM'],
            'approved_gm' => ['bg-blue-50 border-blue-200 text-blue-700', 'bi-check-circle-fill', 'Disetujui GM'],
            'revisi_hrd' => ['bg-purple-50 border-purple-200 text-purple-700', 'bi-arrow-repeat', 'Revisi HRD'],
            'approved_hrd' => ['bg-green-50 border-green-200 text-green-700', 'bi-check-circle-fill', 'Disetujui HRD'],
            'ditolak' => ['bg-red-50 border-red-200 text-red-600', 'bi-x-circle-fill', 'Ditolak'],
        ];
    @endphp

    <div class="space-y-4">
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">

            <div class="px-5 py-4 border-b border-gray-100">
                <div class="text-sm font-semibold text-gray-700">Daftar Pengajuan FPTK</div>
                <div class="text-xs text-gray-400 mt-0.5">FPTK yang diajukan HOD dan menunggu persetujuan GM.</div>
            </div>

            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50/40">
                <form method="GET" action="{{ route('gm.approval.index') }}" id="filterForm"
                    class="flex flex-col sm:flex-row gap-3 sm:items-center">

                    <input type="hidden" name="perPage" value="{{ request('perPage', 10) }}">

                    <div class="relative flex-1 max-w-sm">
                        <i
                            class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm pointer-events-none"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nomor FPTK atau posisi..."
                            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-gray-300 text-sm outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition">
                    </div>

                    <select name="status" onchange="document.getElementById('filterForm').submit()"
                        class="px-4 py-2.5 rounded-xl border border-gray-300 text-sm outline-none bg-white focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition cursor-pointer">
                        <option value="">Semua Status</option>
                        @foreach ($statusFptkMap as $val => [$cls, $icon, $label])
                            <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>

                    <button type="submit"
                        class="px-4 py-2.5 rounded-xl bg-[#0F766E] text-white text-sm font-semibold hover:bg-[#0b5e57] transition inline-flex items-center gap-2 shrink-0 cursor-pointer">
                        <i class="bi bi-search text-xs"></i> Cari
                    </button>

                    @if (request('search') || request('status'))
                        <a href="{{ route('gm.approval.index') }}"
                            class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-500 text-sm font-semibold hover:bg-gray-50 transition inline-flex items-center gap-1.5 shrink-0">
                            <i class="bi bi-x-lg text-xs"></i> Reset
                        </a>
                    @endif

                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-center font-semibold w-12">No</th>
                            <th class="px-4 py-3 text-left font-semibold w-40">Nomor FPTK</th>
                            <th class="px-4 py-3 text-left font-semibold">Posisi Yang Dibutuhkan</th>
                            <th class="px-4 py-3 text-left font-semibold w-44">Diajukan Oleh</th>
                            <th class="px-4 py-3 text-left font-semibold w-28">Kebutuhan</th>
                            <th class="px-4 py-3 text-left font-semibold w-36">Tanggal Pengajuan</th>
                            <th class="px-4 py-3 text-left font-semibold w-36">Status</th>
                            <th class="px-4 py-3 text-center font-semibold w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($fptks as $i => $item)
                            @php [$cls, $icon, $label] = $statusFptkMap[$item->status] ?? ['bg-gray-100 border-gray-200 text-gray-500', 'bi-dash-circle', $item->status]; @endphp

                            <tr class="hover:bg-gray-50/70 transition">

                                <td class="px-4 py-3 text-gray-400 text-xs text-center font-medium">
                                    {{ $fptks->firstItem() + $i }}
                                </td>

                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center rounded-full border border-indigo-200 bg-indigo-50 text-indigo-700 px-3 py-1 text-xs font-semibold font-mono">
                                        {{ $item->nomor_fptk }}
                                    </span>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="font-semibold text-gray-800 text-sm">{{ $item->posisi_dibutuhkan }}</div>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-7 h-7 rounded-full bg-[#0F766E]/10 text-[#0F766E] text-xs font-bold flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($item->hod->nama ?? '-', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-800">{{ $item->hod->nama ?? '-' }}</div>
                                            <div class="text-xs text-gray-400">{{ $item->hod->role ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center rounded-full border border-sky-200 bg-sky-50 text-sky-700 px-3 py-1 text-xs font-semibold">
                                        {{ $item->jumlah_kebutuhan }} Orang
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-xs text-gray-500">
                                    {{ $item->created_at?->translatedFormat('d M Y') ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-semibold {{ $cls }}">
                                        <i class="bi {{ $icon }} text-[9px]"></i> {{ $label }}
                                    </span>
                                </td>

                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-1.5">

                                        <button type="button" onclick='bukaDetailFptk(@json($item->load("departemen", "hod")))'
                                            title="Detail"
                                            class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 flex items-center justify-center hover:bg-gray-50 hover:border-gray-300 cursor-pointer transition">
                                            <i class="bi bi-eye text-sm"></i>
                                        </button>

                                        @if (in_array($item->status, ['pending_gm']))
                                            <button type="button"
                                                onclick="konfirmasiApproveGm({{ $item->id }}, '{{ $item->nomor_fptk }}')"
                                                title="Setujui"
                                                class="w-8 h-8 rounded-lg border border-green-200 text-green-600 flex items-center justify-center hover:bg-green-50 hover:border-green-300 cursor-pointer transition">
                                                <i class="bi bi-check-lg text-sm"></i>
                                            </button>

                                            <button type="button"
                                                onclick='bukaModalRevisi(@json($item->only(["id", "nomor_fptk", "posisi_dibutuhkan"])))'
                                                title="Minta Revisi"
                                                class="w-8 h-8 rounded-lg border border-orange-200 text-orange-500 flex items-center justify-center hover:bg-orange-50 hover:border-orange-300 cursor-pointer transition">
                                                <i class="bi bi-arrow-repeat text-sm"></i>
                                            </button>

                                            <button type="button"
                                                onclick='bukaModalTolak(@json($item->only(["id", "nomor_fptk", "posisi_dibutuhkan"])))'
                                                title="Tolak"
                                                class="w-8 h-8 rounded-lg border border-gray-200 text-red-500 flex items-center justify-center hover:bg-red-50 hover:border-red-200 cursor-pointer transition">
                                                <i class="bi bi-x-lg text-sm"></i>
                                            </button>
                                        @endif

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div
                                            class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center text-gray-300 text-2xl">
                                            <i class="bi bi-folder2-open"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-semibold text-gray-600">Tidak ada FPTK yang perlu ditinjau
                                            </div>
                                            <div class="text-xs text-gray-400 mt-0.5">Semua FPTK sudah diproses atau belum ada
                                                yang masuk.</div>
                                        </div>
                                        @if (request('search') || request('status'))
                                            <a href="{{ route('gm.approval.index') }}"
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

            <div
                class="px-5 py-3.5 border-t border-gray-100 bg-gray-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div class="flex items-center gap-2 text-xs text-gray-500">
                    <span>Tampilkan</span>
                    <select onchange="gantiPerPage(this.value)"
                        class="px-2.5 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 bg-white outline-none focus:ring-2 focus:ring-blue-200 cursor-pointer">
                        @foreach ([5, 10, 20, 50] as $opt)
                            <option value="{{ $opt }}" {{ request('perPage', 10) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                    @if ($fptks->count() > 0)
                        <span>
                            menampilkan
                            <span class="font-semibold text-gray-700">{{ $fptks->firstItem() }}</span>
                            &ndash;
                            <span class="font-semibold text-gray-700">{{ $fptks->lastItem() }}</span>
                            dari
                            <span class="font-semibold text-gray-700">{{ $fptks->total() }}</span>
                        </span>
                    @endif
                </div>

                @if ($fptks->total() > 0)
                    @php
                        $currentPage = $fptks->currentPage();
                        $lastPage = $fptks->lastPage();
                        $paginated = $fptks->appends(request()->query());
                        $range = [];
                        for ($p = 1; $p <= $lastPage; $p++) {
                            if ($p === 1 || $p === $lastPage || abs($p - $currentPage) <= 2) {
                                $range[] = $p;
                            }
                        }
                    @endphp
                    <div class="flex items-center gap-1">

                        @if ($paginated->onFirstPage())
                            <span
                                class="w-8 h-8 rounded-lg border border-gray-200 text-gray-300 flex items-center justify-center cursor-not-allowed select-none">
                                <i class="bi bi-chevron-left text-xs"></i>
                            </span>
                        @else
                            <a href="{{ $paginated->previousPageUrl() }}"
                                class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 bg-white flex items-center justify-center hover:bg-blue-50 hover:border-blue-200 hover:text-[#0F766E] transition">
                                <i class="bi bi-chevron-left text-xs"></i>
                            </a>
                        @endif

                        @foreach ($range as $idx => $page)
                            @if ($idx > 0 && $page - $range[$idx - 1] > 1)
                                <span class="w-8 h-8 flex items-center justify-center text-xs text-gray-400">…</span>
                            @endif
                            @if ($page === $currentPage)
                                <span
                                    class="w-8 h-8 rounded-lg text-xs font-semibold bg-[#0F766E] text-white flex items-center justify-center shadow-sm">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $paginated->url($page) }}"
                                    class="w-8 h-8 rounded-lg text-xs font-semibold border border-gray-200 bg-white text-gray-600 flex items-center justify-center hover:bg-blue-50 hover:border-blue-200 hover:text-[#0F766E] transition">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        @if ($paginated->hasMorePages())
                            <a href="{{ $paginated->nextPageUrl() }}"
                                class="w-8 h-8 rounded-lg border border-gray-200 text-gray-500 bg-white flex items-center justify-center hover:bg-blue-50 hover:border-blue-200 hover:text-[#0F766E] transition">
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

    {{-- Modal Detail FPTK --}}
    <div id="modalDetailFptk" class="fixed inset-0 z-[60] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalDetailFptk')"></div>
        <div class="relative bg-white rounded-2xl w-full max-w-lg mx-4 shadow-2xl max-h-[90vh] flex flex-col">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
                <div>
                    <div class="text-base font-bold text-gray-800">Detail FPTK</div>
                    <div class="text-xs text-gray-400 mt-0.5">Informasi lengkap &amp; alur persetujuan</div>
                </div>
                <button type="button" onclick="closeModal('modalDetailFptk')"
                    class="w-8 h-8 rounded-xl border border-gray-200 bg-red-600 text-white flex items-center justify-center hover:bg-red-700 cursor-pointer transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <div class="overflow-y-auto px-6 py-5 space-y-5">
                <div>
                    <div class="text-xs text-gray-400 font-medium mb-1">Nomor FPTK</div>
                    <div id="fd_nomor"
                        class="inline-flex items-center rounded-full border border-indigo-200 bg-indigo-50 text-indigo-700 px-3 py-1 text-xs font-semibold font-mono">
                    </div>
                </div>

                <div class="border-t border-gray-100"></div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Posisi Dibutuhkan</div>
                        <div id="fd_posisi" class="text-sm font-semibold text-gray-800"></div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Jumlah Kebutuhan</div>
                        <div id="fd_jumlah" class="text-sm font-semibold text-gray-800"></div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Departemen</div>
                        <div id="fd_departemen"
                            class="inline-flex items-center rounded-full border border-blue-200 bg-blue-50 text-blue-700 px-3 py-1 text-xs font-semibold">
                        </div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Tanggal Dibutuhkan</div>
                        <div id="fd_tgl_butuh" class="text-sm font-semibold text-gray-800"></div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Diajukan Oleh</div>
                        <div id="fd_hod" class="text-sm font-semibold text-gray-800"></div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Tanggal Pengajuan</div>
                        <div id="fd_tgl_dibuat" class="text-sm text-gray-700"></div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 font-medium mb-1">Status</div>
                        <div id="fd_status"></div>
                    </div>
                    <div class="col-span-2">
                        <div class="text-xs text-gray-400 font-medium mb-1">Alasan Pengajuan</div>
                        <div id="fd_alasan" class="text-sm text-gray-700 leading-relaxed"></div>
                    </div>
                    <div class="col-span-2 hidden" id="fd_catatan_wrap">
                        <div class="text-xs text-gray-400 font-medium mb-1">Catatan Tambahan</div>
                        <div id="fd_catatan" class="text-sm text-gray-700 leading-relaxed"></div>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="bi bi-diagram-3 text-gray-400 text-sm"></i>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Alur Persetujuan</span>
                    </div>
                    <ol id="fd_timeline" class="relative space-y-0 ml-3"></ol>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2 shrink-0">
                <div id="fd_aksi" class="flex items-center gap-2"></div>
                <button type="button" onclick="closeModal('modalDetailFptk')"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-100 transition cursor-pointer">
                    Tutup
                </button>
            </div>

        </div>
    </div>

    {{-- Modal Revisi FPTK --}}
    <div id="modalRevisiFptk" class="fixed inset-0 z-[60] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalRevisiFptk')"></div>
        <div class="relative bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl flex flex-col">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-orange-50 flex items-center justify-center shrink-0">
                        <i class="bi bi-arrow-repeat text-orange-500 text-lg"></i>
                    </div>
                    <div>
                        <div class="text-base font-bold text-gray-800">Minta Revisi</div>
                        <div class="text-xs text-gray-400 mt-0.5">Sampaikan poin yang perlu diperbaiki HOD</div>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalRevisiFptk')"
                    class="w-8 h-8 rounded-xl border border-gray-200 bg-red-600 text-white flex items-center justify-center hover:bg-red-700 cursor-pointer transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <div class="px-6 py-5 space-y-4">
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-gray-50 border border-gray-100">
                    <i class="bi bi-file-earmark-text text-gray-400 text-base shrink-0"></i>
                    <div>
                        <div id="fr_nomor" class="text-xs font-semibold text-indigo-700 font-mono"></div>
                        <div id="fr_posisi" class="text-sm font-semibold text-gray-800 mt-0.5"></div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Catatan Revisi <span class="text-red-500">*</span>
                    </label>
                    <textarea id="fr_catatan" rows="4"
                        placeholder="Jelaskan secara spesifik apa yang perlu diperbaiki oleh HOD..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition resize-none"></textarea>
                    <p class="mt-1.5 text-xs text-gray-400">
                        <i class="bi bi-info-circle"></i> Catatan ini akan dilihat oleh HOD saat melakukan revisi.
                    </p>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end gap-2 shrink-0">
                <button type="button" onclick="closeModal('modalRevisiFptk')"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-100 transition cursor-pointer">
                    Batal
                </button>
                <button type="button" onclick="kirimRevisi()"
                    class="px-5 py-2.5 rounded-xl bg-orange-500 text-white text-sm font-semibold hover:bg-orange-600 transition inline-flex items-center gap-2 shadow-sm cursor-pointer">
                    <i class="bi bi-send"></i> Kirim Catatan
                </button>
            </div>

        </div>
    </div>

    {{-- Modal Tolak FPTK --}}
    <div id="modalTolakFptk" class="fixed inset-0 z-[60] flex items-center justify-center hidden">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeModal('modalTolakFptk')"></div>
        <div class="relative bg-white rounded-2xl w-full max-w-md mx-4 shadow-2xl flex flex-col">

            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
                        <i class="bi bi-x-circle text-red-500 text-lg"></i>
                    </div>
                    <div>
                        <div class="text-base font-bold text-gray-800">Tolak FPTK</div>
                        <div class="text-xs text-gray-400 mt-0.5">Tindakan ini tidak dapat diurungkan</div>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalTolakFptk')"
                    class="w-8 h-8 rounded-xl border border-gray-200 bg-red-600 text-white flex items-center justify-center hover:bg-red-700 cursor-pointer transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>

            <div class="px-6 py-5 space-y-4">
                <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-red-50 border border-red-100">
                    <i class="bi bi-exclamation-triangle-fill text-red-400 text-base shrink-0"></i>
                    <div>
                        <div id="ft_nomor" class="text-xs font-semibold text-red-700 font-mono"></div>
                        <div id="ft_posisi" class="text-sm font-semibold text-gray-800 mt-0.5"></div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                        Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea id="ft_catatan" rows="4" placeholder="Jelaskan alasan penolakan FPTK ini..."
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition resize-none"></textarea>
                    <p class="mt-1.5 text-xs text-gray-400">
                        <i class="bi bi-info-circle"></i> Alasan ini akan tercatat di riwayat.
                    </p>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end gap-2 shrink-0">
                <button type="button" onclick="closeModal('modalTolakFptk')"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-600 text-sm font-semibold hover:bg-gray-100 transition cursor-pointer">
                    Batal
                </button>
                <button type="button" onclick="kirimTolak()"
                    class="px-5 py-2.5 rounded-xl bg-red-600 text-white text-sm font-semibold hover:bg-red-700 transition inline-flex items-center gap-2 shadow-sm cursor-pointer">
                    <i class="bi bi-x-circle"></i> Ya, Tolak
                </button>
            </div>

        </div>
    </div>

    <form id="formApproveGm" method="POST" action="" class="hidden">
        @csrf
        @method('PATCH')
    </form>
    <form id="formRevisiGm" method="POST" action="" class="hidden">
        @csrf
        @method('PATCH')
        <input type="hidden" name="catatan_gm" id="formRevisi_catatan">
    </form>
    <form id="formTolakGm" method="POST" action="" class="hidden">
        @csrf
        @method('PATCH')
        <input type="hidden" name="catatan_gm" id="formTolak_catatan">
    </form>

    @push('scripts')
        <style>
            #fd_timeline li:last-child .timeline-line {
                display: none;
            }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            var statusFptkMap = {
                pending_gm: { cls: 'bg-yellow-50 border-yellow-200 text-yellow-700', icon: 'bi-clock-fill', label: 'Pending GM' },
                revisi_gm: { cls: 'bg-orange-50 border-orange-200 text-orange-700', icon: 'bi-arrow-repeat', label: 'Revisi GM' },
                approved_gm: { cls: 'bg-blue-50 border-blue-200 text-blue-700', icon: 'bi-check-circle-fill', label: 'Disetujui GM' },
                revisi_hrd: { cls: 'bg-purple-50 border-purple-200 text-purple-700', icon: 'bi-arrow-repeat', label: 'Revisi HRD' },
                approved_hrd: { cls: 'bg-green-50 border-green-200 text-green-700', icon: 'bi-check-circle-fill', label: 'Disetujui HRD' },
                ditolak: { cls: 'bg-red-50 border-red-200 text-red-600', icon: 'bi-x-circle-fill', label: 'Ditolak' },
            };

            function openModal(id) { document.getElementById(id).classList.remove('hidden'); }

            function closeModal(id) {
                document.getElementById(id).classList.add('hidden');
                var clearMap = { modalRevisiFptk: 'fr_catatan', modalTolakFptk: 'ft_catatan' };
                if (clearMap[id]) document.getElementById(clearMap[id]).value = '';
            }

            function formatTanggal(val, withTime) {
                if (!val) return null;
                var opts = { day: '2-digit', month: 'short', year: 'numeric' };
                if (withTime) { opts.hour = '2-digit'; opts.minute = '2-digit'; }
                return new Date(val).toLocaleDateString('id-ID', opts);
            }

            function buatLangkahTimeline(state, judul, tanggal, catatan, alertStyle) {
                var cfg = {
                    done: { dot: 'border-green-500', icon: '<i class="bi bi-check-lg text-green-600 text-[10px] font-bold"></i>', line: 'bg-green-200', titleCls: 'text-gray-800 font-semibold', dateCls: 'text-gray-400' },
                    revisi: { dot: 'border-orange-400', icon: '<i class="bi bi-arrow-repeat text-orange-500 text-[10px]"></i>', line: 'bg-orange-200', titleCls: 'text-orange-700 font-semibold', dateCls: 'text-orange-400' },
                    waiting: { dot: 'border-gray-300', icon: '<i class="bi bi-three-dots text-gray-300 text-[10px]"></i>', line: 'bg-gray-100', titleCls: 'text-gray-400 font-medium', dateCls: 'text-gray-300' },
                    cancelled: { dot: 'border-red-400', icon: '<i class="bi bi-x text-red-500 text-[11px] font-bold"></i>', line: 'bg-red-200', titleCls: 'text-red-600 font-semibold', dateCls: 'text-red-400' },
                }[state] || {};

                var banner = catatan
                    ? '<div class="mt-2 flex items-start gap-2 px-3 py-2.5 rounded-lg ' + (alertStyle ? 'bg-orange-50 border border-orange-200' : 'bg-gray-50 border border-gray-100') + '">'
                    + '<i class="bi bi-chat-left-text-fill ' + (alertStyle ? 'text-orange-400' : 'text-gray-300') + ' text-[10px] mt-0.5 shrink-0"></i>'
                    + '<span class="text-xs leading-relaxed ' + (alertStyle ? 'text-orange-800' : 'text-gray-500') + '">' + catatan + '</span></div>'
                    : '';

                return '<li class="relative flex gap-4 pb-5 last:pb-0">'
                    + '<div class="flex flex-col items-center">'
                    + '<div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 z-10 bg-white border-2 ' + cfg.dot + '">' + cfg.icon + '</div>'
                    + '<div class="w-px flex-1 mt-1 timeline-line ' + cfg.line + '"></div>'
                    + '</div>'
                    + '<div class="flex-1 pt-0.5 pb-1">'
                    + '<div class="text-xs ' + cfg.titleCls + '">' + judul + '</div>'
                    + '<div class="text-[11px] mt-0.5 ' + cfg.dateCls + '">' + (tanggal || '<span class="italic">Menunggu tindakan…</span>') + '</div>'
                    + banner
                    + '</div></li>';
            }

            function bukaDetailFptk(item) {
                var s = statusFptkMap[item.status] || { cls: 'bg-gray-100 border-gray-200 text-gray-500', icon: 'bi-dash-circle', label: item.status };

                document.getElementById('fd_nomor').textContent = item.nomor_fptk;
                document.getElementById('fd_posisi').textContent = item.posisi_dibutuhkan;
                document.getElementById('fd_jumlah').textContent = item.jumlah_kebutuhan + ' Orang';
                document.getElementById('fd_hod').textContent = item.hod ? item.hod.nama : '-';
                document.getElementById('fd_departemen').textContent = item.departemen ? item.departemen.kode + ' — ' + item.departemen.nama : '-';
                document.getElementById('fd_tgl_butuh').textContent = formatTanggal(item.tanggal_dibutuhkan) || '-';
                document.getElementById('fd_alasan').textContent = item.alasan || '-';
                document.getElementById('fd_tgl_dibuat').textContent = formatTanggal(item.created_at) || '-';

                var catatanWrap = document.getElementById('fd_catatan_wrap');
                if (item.catatan_tambahan) {
                    document.getElementById('fd_catatan').textContent = item.catatan_tambahan;
                    catatanWrap.classList.remove('hidden');
                } else {
                    catatanWrap.classList.add('hidden');
                }

                document.getElementById('fd_status').innerHTML =
                    '<span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-semibold ' + s.cls + '">'
                    + '<i class="bi ' + s.icon + ' text-[9px]"></i>' + s.label + '</span>';

                var bisaAksi = ['pending_gm', 'revisi_gm'].includes(item.status);
                document.getElementById('fd_aksi').innerHTML = bisaAksi
                    ? '<button type="button" onclick=\'closeModal("modalDetailFptk");konfirmasiApproveGm(' + item.id + ',"' + item.nomor_fptk + '")\''
                    + ' class="px-4 py-2.5 rounded-xl bg-green-600 text-white text-sm font-semibold hover:bg-green-700 transition inline-flex items-center gap-2 cursor-pointer">'
                    + '<i class="bi bi-check-lg"></i> Setujui</button>'
                    + '<button type="button" onclick=\'closeModal("modalDetailFptk");bukaModalRevisi(' + JSON.stringify({ id: item.id, nomor_fptk: item.nomor_fptk, posisi_dibutuhkan: item.posisi_dibutuhkan }) + ')\''
                    + ' class="px-4 py-2.5 rounded-xl bg-orange-500 text-white text-sm font-semibold hover:bg-orange-600 transition inline-flex items-center gap-2 cursor-pointer">'
                    + '<i class="bi bi-arrow-repeat"></i> Revisi</button>'
                    : '';

                var st = item.status;
                var batal = st === 'ditolak';
                var gmOk = ['approved_gm', 'revisi_hrd', 'approved_hrd'].includes(st);
                var gmRevisi = st === 'revisi_gm';
                var hrdOk = st === 'approved_hrd';
                var hrdRevisi = st === 'revisi_hrd';

                document.getElementById('fd_timeline').innerHTML =
                    buatLangkahTimeline('done', 'Pengajuan dibuat oleh HOD', formatTanggal(item.created_at, true), null, false)
                    + buatLangkahTimeline(
                        batal ? 'cancelled' : (gmOk ? 'done' : (gmRevisi ? 'revisi' : 'waiting')),
                        gmRevisi ? 'Diminta revisi oleh GM' : (gmOk ? 'Disetujui GM' : 'Menunggu persetujuan GM'),
                        formatTanggal(item.gm_approved_at, true), item.catatan_gm, gmRevisi
                    )
                    + buatLangkahTimeline(
                        batal ? 'cancelled' : (hrdOk ? 'done' : (hrdRevisi ? 'revisi' : 'waiting')),
                        hrdRevisi ? 'Diminta revisi oleh HRD' : (hrdOk ? 'Disetujui HRD' : 'Menunggu persetujuan HRD'),
                        formatTanggal(item.hrd_approved_at, true), item.catatan_hrd, hrdRevisi
                    )
                    + buatLangkahTimeline(
                        batal ? 'cancelled' : (hrdOk ? 'done' : 'waiting'),
                        batal ? 'Pengajuan ditolak' : 'Selesai disetujui',
                        batal ? formatTanggal(item.updated_at, true) : formatTanggal(item.hrd_approved_at, true), null, false
                    );

                openModal('modalDetailFptk');
            }

            function konfirmasiApproveGm(id, nomorFptk) {
                Swal.fire({
                    title: 'Setujui FPTK?',
                    html: '<div class="text-left text-sm"><div><strong>Nomor:</strong> ' + nomorFptk + '</div>'
                        + '<div class="text-gray-500 text-xs mt-1">FPTK akan diteruskan ke HRD untuk diproses lebih lanjut.</div></div>',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: '<i class="bi bi-check-lg"></i> Ya, Setujui!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                }).then(function (result) {
                    if (result.isConfirmed) {
                        var f = document.getElementById('formApproveGm');
                        f.action = '{{ url('/gm/approval') }}/' + id + '/approve';
                        f.submit();
                    }
                });
            }

            function bukaModalRevisi(item) {
                document.getElementById('fr_nomor').textContent = item.nomor_fptk;
                document.getElementById('fr_posisi').textContent = item.posisi_dibutuhkan;
                document.getElementById('fr_catatan').value = '';
                document.getElementById('formRevisiGm').action = '{{ url('/gm/approval') }}/' + item.id + '/revisi';
                openModal('modalRevisiFptk');
            }

            function kirimRevisi() {
                var catatan = document.getElementById('fr_catatan').value.trim();
                if (!catatan) {
                    Swal.fire({ icon: 'warning', title: 'Catatan wajib diisi', text: 'Jelaskan kepada HOD apa yang perlu diperbaiki.', confirmButtonColor: '#0F766E' });
                    return;
                }
                Swal.fire({
                    title: 'Kirim Catatan Revisi?',
                    text: 'HOD akan diminta memperbaiki dan mengajukan ulang.',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#f97316',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Kirim!',
                    cancelButtonText: 'Cek Lagi',
                    reverseButtons: true,
                }).then(function (r) {
                    if (r.isConfirmed) {
                        document.getElementById('formRevisi_catatan').value = catatan;
                        document.getElementById('formRevisiGm').submit();
                    }
                });
            }

            function bukaModalTolak(item) {
                document.getElementById('ft_nomor').textContent = item.nomor_fptk;
                document.getElementById('ft_posisi').textContent = item.posisi_dibutuhkan;
                document.getElementById('ft_catatan').value = '';
                document.getElementById('formTolakGm').action = '{{ url('/gm/approval') }}/' + item.id + '/tolak';
                openModal('modalTolakFptk');
            }

            function kirimTolak() {
                var catatan = document.getElementById('ft_catatan').value.trim();
                if (!catatan) {
                    Swal.fire({ icon: 'warning', title: 'Alasan wajib diisi', text: 'Berikan alasan penolakan FPTK ini.', confirmButtonColor: '#0F766E' });
                    return;
                }
                Swal.fire({
                    title: 'Tolak FPTK?',
                    text: 'Tindakan ini tidak dapat diurungkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#DC2626',
                    cancelButtonColor: '#6B7280',
                    confirmButtonText: 'Ya, Tolak!',
                    cancelButtonText: 'Kembali',
                    reverseButtons: true,
                }).then(function (r) {
                    if (r.isConfirmed) {
                        document.getElementById('formTolak_catatan').value = catatan;
                        document.getElementById('formTolakGm').submit();
                    }
                });
            }

            function gantiPerPage(value) {
                var url = new URL(window.location.href);
                url.searchParams.set('perPage', value);
                url.searchParams.set('page', 1);
                window.location.href = url.toString();
            }
        </script>
    @endpush

@endsection