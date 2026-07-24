<div id="tabContentKandidat" class="mt-0">
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm overflow-hidden">

        {{-- ── HEADER ── --}}
        <div class="p-5 border-b border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="bi bi-people-fill text-indigo-600"></i>
                        Daftar Kandidat
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Kandidat yang dikirim oleh HRD untuk dilakukan review dan screening lanjutan.
                    </p>
                </div>

                <div class="shrink-0">
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-50 text-indigo-700 border border-indigo-200 text-sm font-semibold">
                        <i class="bi bi-person-lines-fill"></i>
                        {{ count($kandidat) }} Total Kandidat
                    </span>
                </div>
            </div>
        </div>

        {{-- ── FILTER & PENCARIAN ── --}}
        <div class="p-5 border-b border-gray-100 bg-gray-50/60">
            <div class="flex flex-col xl:flex-row xl:items-center gap-3">

                {{-- Input pencarian --}}
                <div class="flex-1 relative">
                    <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    <input
                        id="searchInput"
                        type="text"
                        placeholder="Cari nama kandidat atau posisi..."
                        class="w-full rounded-xl border border-gray-300 pl-11 pr-4 py-2.5 text-sm outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition bg-white"
                        autocomplete="off"
                    />
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    {{-- Dropdown filter status --}}
                    <div class="relative">
                        <i class="bi bi-funnel absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-sm"></i>
                        <select id="statusFilter" class="cursor-pointer appearance-none rounded-xl border border-gray-300 pl-9 pr-8 py-2.5 text-sm outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition bg-white">
                            <option value="Semua">Semua Status</option>
                            <option value="Dikirim ke HOD">Dikirim ke HOD</option>
                            <option value="Screening HOD">Screening HOD</option>
                            <option value="Disetujui HOD">Disetujui HOD</option>
                            <option value="Ditolak HOD">Ditolak HOD</option>
                        </select>
                        <i class="bi bi-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                    </div>

                    {{-- Dropdown jumlah per halaman --}}
                    <div class="relative">
                        <i class="bi bi-list-ol absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-sm"></i>
                        <select id="perPageSelect" class="cursor-pointer appearance-none rounded-xl border border-gray-300 pl-9 pr-8 py-2.5 text-sm outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition bg-white">
                            <option value="5">5 per halaman</option>
                            <option value="10">10 per halaman</option>
                            <option value="25">25 per halaman</option>
                        </select>
                        <i class="bi bi-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                    </div>
                </div>
            </div>

            {{-- Info filter aktif --}}
            <div class="mt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-xs text-gray-500">
                <div class="flex items-center gap-1.5">
                    <i class="bi bi-filter-circle text-gray-400"></i>
                    Filter aktif:
                    <span id="activeFilterText" class="font-semibold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100">
                        Semua
                    </span>
                </div>
                <div class="flex items-center gap-1.5">
                    <i class="bi bi-eye text-gray-400"></i>
                    Menampilkan
                    <span id="showCount" class="font-semibold text-gray-700">{{ count($kandidat) }}</span>
                    dari
                    <span id="totalCount" class="font-semibold text-gray-700">{{ count($kandidat) }}</span>
                    kandidat
                </div>
            </div>
        </div>

        {{-- ── TABEL KANDIDAT ── --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 text-gray-500 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3.5 text-left font-semibold whitespace-nowrap w-12">No</th>
                        <th class="px-5 py-3.5 text-left font-semibold whitespace-nowrap">Kandidat</th>
                        <th class="px-5 py-3.5 text-left font-semibold whitespace-nowrap">Posisi</th>
                        <th class="px-5 py-3.5 text-left font-semibold whitespace-nowrap">Kontak</th>
                        <th class="px-5 py-3.5 text-left font-semibold whitespace-nowrap">Tanggal Masuk</th>
                        <th class="px-5 py-3.5 text-left font-semibold whitespace-nowrap">Status</th>
                        <th class="px-5 py-3.5 text-center font-semibold whitespace-nowrap w-36">Aksi</th>
                    </tr>
                </thead>

                <tbody id="kandidatTbody">

                    {{-- Loop setiap kandidat --}}
                    @forelse($kandidat as $i => $k)
                        <tr
                            id="kandidatRow_{{ $i }}"
                            class="hover:bg-indigo-50/30 transition-colors duration-150 kandidat-row"
                            data-nama="{{ strtolower($k['nama']) }}"
                            data-posisi="{{ strtolower($k['posisi']) }}"
                            data-tahap="{{ $k['tahap'] }}"
                            data-index="{{ $i }}"
                        >
                            {{-- Nomor urut --}}
                            <td class="px-5 py-4 text-gray-400 font-medium kandidat-no">
                                {{ $i + 1 }}
                            </td>

                            {{-- Nama & avatar --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    {{-- Avatar inisial nama --}}
                                    <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center text-sm font-bold shrink-0 uppercase">
                                        {{ mb_substr($k['nama'], 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900 leading-tight">{{ $k['nama'] }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $k['posisi'] }}</div>
                                    </div>
                                </div>
                            </td>

                            {{-- Posisi --}}
                            <td class="px-5 py-4 text-gray-700 font-medium">
                                {{ $k['posisi'] }}
                            </td>

                            {{-- Kontak / Email --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2 text-gray-600">
                                    <i class="bi bi-envelope text-gray-400"></i>
                                    <a href="mailto:{{ $k['email'] }}" class="text-sm hover:text-indigo-600 transition">
                                        {{ $k['email'] }}
                                    </a>
                                </div>
                            </td>

                            {{-- Tanggal masuk --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2 text-gray-600 text-sm">
                                    <i class="bi bi-calendar-event text-gray-400"></i>
                                    {{ \Carbon\Carbon::parse($k['tgl'])->translatedFormat('d M Y') }}
                                </div>
                            </td>

                            {{-- Badge status / tahap --}}
                            <td class="px-5 py-4" id="kandidatBadge_{{ $i }}">
                                <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $tahapStyle[$k['tahap']] ?? 'bg-gray-100 text-gray-600 border-gray-200' }}">
                                    {{-- Icon sesuai tahap --}}
                                    @switch($k['tahap'])
                                        @case('Dikirim ke HOD')   <i class="bi bi-send-fill mr-1.5 text-[10px]"></i>         @break
                                        @case('Screening HOD')    <i class="bi bi-hourglass-split mr-1.5 text-[10px]"></i>    @break
                                        @case('Disetujui HOD')    <i class="bi bi-check-circle-fill mr-1.5 text-[10px]"></i>  @break
                                        @case('Ditolak HOD')      <i class="bi bi-x-circle-fill mr-1.5 text-[10px]"></i>      @break
                                        @default                  <i class="bi bi-circle mr-1.5 text-[10px]"></i>
                                    @endswitch
                                    {{ $k['tahap'] }}
                                </span>
                            </td>

                            {{-- Tombol aksi --}}
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-1.5">

                                    {{-- Tombol Setujui — hanya tampil jika belum disetujui dan bukan tahap akhir --}}
                                    @if(!$k['isApproved'] && !in_array($k['tahap'], ['Ditolak HOD', 'HOD kirim ke HRD', 'Selesai']))
                                        <button
                                            type="button"
                                            onclick="approveRow({{ $i }})"
                                            title="Setujui Kandidat"
                                            class="w-9 h-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center hover:bg-emerald-700 active:scale-95 transition-all shadow-sm"
                                        >
                                            <i class="bi bi-check-lg"></i>
                                        </button>

                                        <button
                                            type="button"
                                            onclick="rejectRow({{ $i }})"
                                            title="Tolak Kandidat"
                                            class="w-9 h-9 rounded-xl bg-red-500 text-white flex items-center justify-center hover:bg-red-600 active:scale-95 transition-all shadow-sm"
                                        >
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    @endif

                                    {{-- Tombol Detail — selalu tampil --}}
                                    <button
                                        type="button"
                                        onclick="openDetailKandidat({{ $i }})"
                                        title="Lihat Detail Kandidat"
                                        class="w-9 h-9 rounded-xl border border-gray-200 bg-white hover:bg-indigo-50 hover:border-indigo-300 text-gray-600 hover:text-indigo-700 flex items-center justify-center transition-all shadow-sm"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>

                    @empty
                        {{-- Tampil jika tidak ada kandidat sama sekali --}}
                        <tr id="kandidatEmptyRow">
                            <td colspan="7" class="px-5 py-24 text-center">
                                <div class="w-16 h-16 mx-auto rounded-2xl bg-gray-100 flex items-center justify-center text-gray-400 text-3xl">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="mt-4 text-base font-semibold text-gray-800">Belum ada kandidat</div>
                                <div class="text-sm text-gray-500 mt-1">Kandidat recruitment akan muncul di sini.</div>
                            </td>
                        </tr>
                    @endforelse

                    {{-- Baris kosong pengisi agar tinggi tabel konsisten --}}
                    @php
                        $perPage     = 5;
                        $spacerCount = max(0, $perPage - count($kandidat));
                    @endphp
                    @for($s = 0; $s < $spacerCount; $s++)
                        <tr class="pagination-spacer" aria-hidden="true">
                            <td colspan="7" class="px-5 py-[22px] border-0"></td>
                        </tr>
                    @endfor

                </tbody>
            </table>

            {{-- Tampil jika pencarian / filter tidak menemukan hasil --}}
            <div id="filterEmptyState" class="hidden px-5 py-16 text-center border-t border-gray-100">
                <div class="w-16 h-16 mx-auto rounded-2xl bg-amber-50 flex items-center justify-center text-amber-400 text-3xl">
                    <i class="bi bi-search"></i>
                </div>
                <div class="mt-4 text-base font-semibold text-gray-800">Tidak ada hasil</div>
                <div class="text-sm text-gray-500 mt-1">Coba ubah kata kunci atau filter yang digunakan.</div>
                <button onclick="resetFilter()" class="mt-4 px-4 py-2 rounded-xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                    <i class="bi bi-arrow-counterclockwise mr-1"></i> Reset Filter
                </button>
            </div>
        </div>

        {{-- ── PAGINATION ── --}}
        <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                {{-- Info halaman --}}
                <div class="text-xs text-gray-500 flex items-center gap-1.5">
                    <i class="bi bi-layers text-gray-400"></i>
                    Halaman <span id="pgCurrentPage" class="font-semibold text-gray-700">1</span>
                    dari <span id="pgTotalPage" class="font-semibold text-gray-700">1</span>
                </div>

                {{-- Tombol navigasi halaman --}}
                <div class="flex items-center gap-1.5" id="pgControls">
                    <button id="pgPrev" onclick="changePage('prev')"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-600 bg-white hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 disabled:opacity-40 disabled:cursor-not-allowed transition-all"
                        disabled>
                        <i class="bi bi-chevron-left text-[11px]"></i>
                    </button>

                    <div id="pgNumbers" class="flex items-center gap-1"></div>

                    <button id="pgNext" onclick="changePage('next')"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-600 bg-white hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 disabled:opacity-40 disabled:cursor-not-allowed transition-all">
                        <i class="bi bi-chevron-right text-[11px]"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

@once
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput      = document.getElementById('searchInput');
        const statusFilter     = document.getElementById('statusFilter');
        const perPageSelect    = document.getElementById('perPageSelect');
        const activeFilterText = document.getElementById('activeFilterText');
        const showCount        = document.getElementById('showCount');
        const totalCount       = document.getElementById('totalCount');
        const filterEmptyState = document.getElementById('filterEmptyState');
        const tbody            = document.getElementById('kandidatTbody');

        let halamanSaatIni = 1;
        let perPage        = parseInt(perPageSelect?.value ?? 5);

        // Ambil semua baris kandidat dari tbody
        function semuaBaris() {
            return Array.from(document.querySelectorAll('.kandidat-row'));
        }

        // Saring baris berdasarkan kata kunci dan filter status
        function barisYangDisaring() {
            const keyword = searchInput?.value.toLowerCase().trim() ?? '';
            const status  = statusFilter?.value ?? 'Semua';

            return semuaBaris().filter(function(baris) {
                const cocokCari   = !keyword || baris.dataset.nama.includes(keyword) || baris.dataset.posisi.includes(keyword);
                const cocokStatus = status === 'Semua' || baris.dataset.tahap === status;
                return cocokCari && cocokStatus;
            });
        }

        // Tambahkan baris kosong agar tinggi tabel konsisten
        function isiBarisKosong(jumlahTampil) {
            tbody.querySelectorAll('.pagination-spacer').forEach(function(el) { el.remove(); });

            const jumlahKosong = Math.max(0, perPage - jumlahTampil);
            for (let i = 0; i < jumlahKosong; i++) {
                const tr = document.createElement('tr');
                tr.className = 'pagination-spacer';
                tr.setAttribute('aria-hidden', 'true');
                tr.innerHTML = '<td colspan="7" class="px-5 py-[22px] border-0"></td>';
                tbody.appendChild(tr);
            }
        }

        // Render tombol nomor halaman
        function renderNomorHalaman(totalHalaman) {
            const container = document.getElementById('pgNumbers');
            if (!container) return;
            container.innerHTML = '';

            // Buat range nomor halaman dengan elipsis jika banyak
            const range = [];
            for (let p = 1; p <= totalHalaman; p++) {
                if (p === 1 || p === totalHalaman || (p >= halamanSaatIni - 1 && p <= halamanSaatIni + 1)) {
                    range.push(p);
                } else if (range[range.length - 1] !== '…') {
                    range.push('…');
                }
            }

            range.forEach(function(p) {
                if (p === '…') {
                    const span = document.createElement('span');
                    span.className = 'px-1 text-xs text-gray-400';
                    span.textContent = '…';
                    container.appendChild(span);
                    return;
                }

                const btn = document.createElement('button');
                btn.textContent = p;
                btn.className = (p === halamanSaatIni)
                    ? 'w-7 h-7 rounded-lg text-xs font-bold bg-indigo-600 text-white border border-indigo-600 transition-all'
                    : 'w-7 h-7 rounded-lg text-xs font-semibold border border-gray-200 bg-white text-gray-600 hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 transition-all';
                btn.onclick = function() { pindahHalaman(p); };
                container.appendChild(btn);
            });
        }

        // Terapkan filter + pagination ke tabel
        function terapkanFilter() {
            const tersaring   = barisYangDisaring();
            const semua       = semuaBaris();
            const totalHalaman = Math.max(1, Math.ceil(tersaring.length / perPage));

            if (halamanSaatIni > totalHalaman) halamanSaatIni = totalHalaman;

            const mulai = (halamanSaatIni - 1) * perPage;
            const akhir = mulai + perPage;

            // Sembunyikan semua baris dulu
            semua.forEach(function(baris) { baris.style.display = 'none'; });

            // Tampilkan baris yang sesuai halaman saat ini
            const barisHalaman = tersaring.slice(mulai, akhir);
            barisHalaman.forEach(function(baris, i) {
                baris.style.display = '';
                const noCell = baris.querySelector('.kandidat-no');
                if (noCell) noCell.textContent = mulai + i + 1;
            });

            isiBarisKosong(barisHalaman.length);

            // Update info teks
            if (showCount)   showCount.textContent   = tersaring.length;
            if (totalCount)  totalCount.textContent  = semua.length;

            const statusAktif = statusFilter?.value ?? 'Semua';
            if (activeFilterText) {
                activeFilterText.textContent = statusAktif === 'Semua' ? 'Semua' : statusAktif;
            }

            // Tampilkan/sembunyikan empty state filter
            if (filterEmptyState) {
                filterEmptyState.classList.toggle('hidden', tersaring.length > 0 || semua.length === 0);
            }

            // Update kontrol pagination
            const pgCurrentPage = document.getElementById('pgCurrentPage');
            const pgTotalPage   = document.getElementById('pgTotalPage');
            const pgPrev        = document.getElementById('pgPrev');
            const pgNext        = document.getElementById('pgNext');

            if (pgCurrentPage) pgCurrentPage.textContent = halamanSaatIni;
            if (pgTotalPage)   pgTotalPage.textContent   = totalHalaman;
            if (pgPrev)        pgPrev.disabled           = halamanSaatIni <= 1;
            if (pgNext)        pgNext.disabled           = halamanSaatIni >= totalHalaman;

            renderNomorHalaman(totalHalaman);
        }

        function pindahHalaman(p) {
            halamanSaatIni = p;
            terapkanFilter();
        }

        // Ekspor fungsi ke global agar bisa dipanggil dari onclick HTML
        window.resetFilter = function() {
            if (searchInput)  searchInput.value  = '';
            if (statusFilter) statusFilter.value = 'Semua';
            halamanSaatIni = 1;
            terapkanFilter();
        };

        window.changePage = function(arah) {
            const totalHalaman = Math.max(1, Math.ceil(barisYangDisaring().length / perPage));
            if (arah === 'prev' && halamanSaatIni > 1)              { halamanSaatIni--; terapkanFilter(); }
            if (arah === 'next' && halamanSaatIni < totalHalaman)   { halamanSaatIni++; terapkanFilter(); }
        };

        // Pasang event listener ke input & select
        searchInput?.addEventListener('input',  function() { halamanSaatIni = 1; terapkanFilter(); });
        statusFilter?.addEventListener('change', function() { halamanSaatIni = 1; terapkanFilter(); });
        perPageSelect?.addEventListener('change', function() {
            perPage        = parseInt(perPageSelect.value);
            halamanSaatIni = 1;
            terapkanFilter();
        });

        // Jalankan filter pertama kali saat halaman dimuat
        terapkanFilter();
    });
</script>
@endonce