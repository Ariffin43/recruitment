@extends('hod.layouts.app')

@section('title', 'Dashboard HOD')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Ringkasan screening dan data pelamar departemen Anda.')

@section('content')
    <div class="space-y-6">

        {{-- Flash message --}}
        @if (session('success'))
            <div id="flashSuccess"
                class="flex items-center gap-3 px-4 py-3 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-semibold shadow-sm">
                <i class="bi bi-check-circle-fill text-green-500 text-base"></i>
                <span>{{ session('success') }}</span>

                <button type="button"
                    onclick="document.getElementById('flashSuccess')?.remove()"
                    class="ml-auto text-green-400 hover:text-green-600 transition">
                    <i class="bi bi-x-lg text-xs"></i>
                </button>
            </div>
        @endif

        {{-- Informasi HOD --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-slate-800">
                    Selamat datang, {{ auth()->user()->nama }} 👋
                </h2>

                <p class="text-sm text-slate-500 mt-0.5">
                    <i class="bi bi-building mr-1"></i>
                    Departemen:
                    <span class="font-semibold text-slate-700">
                        {{ auth()->user()->karyawan->departemen?->nama ?? 'Belum ditetapkan' }}
                    </span>
                    &nbsp;·&nbsp;
                    {{ now()->translatedFormat('l, d F Y') }}
                </p>
            </div>
        </div>

        {{-- Ringkasan kandidat --}}
        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

            {{-- Total kandidat --}}
            <div class="bg-white border border-slate-100 rounded-2xl p-5 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest leading-snug">
                        Total<br>Kandidat
                    </p>

                    <div class="w-9 h-9 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>

                <p class="text-4xl font-bold text-slate-800">{{ $totalKandidat }}</p>
                <p class="text-xs text-slate-400 mt-2">Seluruh pelamar departemen</p>
            </div>

            {{-- Menunggu review --}}
            <div class="bg-[#4338CA] rounded-2xl p-5 hover:shadow-lg transition-shadow">
                <div class="flex justify-between items-start mb-3">
                    <p class="text-xs font-semibold text-white uppercase tracking-widest leading-snug">
                        Menunggu<br>Review
                    </p>

                    <div class="w-9 h-9 rounded-xl bg-white/15 flex items-center justify-center text-white">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>

                <p class="text-4xl font-bold text-white">{{ $menungguReview }}</p>

                @if ($menungguReview > 0)
                    <p class="text-xs text-amber-300 mt-2 flex items-center gap-1">
                        <i class="bi bi-bell-fill"></i>
                        Perlu perhatian Anda
                    </p>
                @else
                    <p class="text-xs text-white mt-2">Semua sudah ditinjau</p>
                @endif
            </div>

            {{-- Disetujui --}}
            <div class="bg-white border border-emerald-100 rounded-2xl p-5 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">
                        Disetujui
                    </p>

                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>

                <p class="text-4xl font-bold text-emerald-700">{{ $disetujui }}</p>
                <p class="text-xs text-slate-400 mt-2">Lolos screening HOD</p>
            </div>

            {{-- Ditolak --}}
            <div class="bg-white border border-red-100 rounded-2xl p-5 hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start mb-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest">
                        Ditolak
                    </p>

                    <div class="w-9 h-9 rounded-xl bg-red-50 text-red-500 flex items-center justify-center">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                </div>

                <p class="text-4xl font-bold text-red-600">{{ $ditolak }}</p>
                <p class="text-xs text-slate-400 mt-2">Tidak memenuhi kriteria</p>
            </div>
        </div>

        {{-- Data kandidat --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Header dan filter --}}
            <div class="flex items-center gap-2 p-4 border-b border-slate-100 bg-slate-50 flex-wrap">
                <div class="flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold bg-[#4338CA] text-white">
                    <i class="bi bi-people-fill"></i>
                    Data Pelamar

                    @if ($menungguReview > 0)
                        <span
                            class="bg-orange-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">
                            {{ $menungguReview }}
                        </span>
                    @endif
                </div>

                <div class="ml-auto flex items-center gap-2 flex-wrap">
                    <div class="relative">
                        <i
                            class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>

                        <input type="text"
                            id="inputCariKandidat"
                            oninput="cariKandidat()"
                            placeholder="Cari nama / posisi…"
                            class="pl-8 pr-3 py-2 border border-slate-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-slate-300 w-44 bg-white">
                    </div>

                    <select id="selectFilterTahap"
                        onchange="cariKandidat()"
                        class="text-xs border border-slate-200 rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-slate-300 cursor-pointer">
                        <option value="ALL">Semua Tahap</option>

                        @foreach (array_keys($tahapStyle) as $namaTahap)
                            <option value="{{ $namaTahap }}">{{ $namaTahap }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="konten-kandidat">

                {{-- Tampilan desktop --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th
                                    class="text-left px-5 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                    Kandidat
                                </th>

                                <th
                                    class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                    Posisi
                                </th>

                                <th
                                    class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                    Tgl Lamar
                                </th>

                                <th
                                    class="text-left px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                    Tahap
                                </th>

                                <th
                                    class="text-center px-4 py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody id="tabelKandidat" class="divide-y divide-slate-50">
                            @forelse ($kandidat as $idx => $k)
                                @php
                                    $badgeTahap = $tahapStyle[$k['tahap']]
                                        ?? 'bg-slate-100 text-slate-600 border border-slate-200';
                                @endphp

                                <tr class="hover:bg-slate-50 transition-colors kandidat-baris"
                                    data-nama="{{ strtolower($k['nama']) }}"
                                    data-posisi="{{ strtolower($k['posisi']) }}"
                                    data-tahap="{{ $k['tahap'] }}">

                                    {{-- Identitas kandidat --}}
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-9 h-9 rounded-full bg-[#003366] flex items-center justify-center text-white text-xs font-bold shrink-0">
                                                {{ strtoupper(substr($k['nama'], 0, 2)) }}
                                            </div>

                                            <div>
                                                <p class="font-semibold text-slate-800">{{ $k['nama'] }}</p>
                                                <p class="text-xs text-slate-400">{{ $k['email'] }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Posisi --}}
                                    <td class="px-4 py-4">
                                        <p class="font-medium text-slate-700">{{ $k['posisi'] }}</p>
                                        <p class="text-xs text-slate-400">{{ $k['nomor_fptk'] }}</p>
                                    </td>

                                    {{-- Tanggal melamar --}}
                                    <td class="px-4 py-4 text-slate-500 text-sm">
                                        {{ $k['tgl'] ? \Carbon\Carbon::parse($k['tgl'])->format('d M Y') : '-' }}
                                    </td>

                                    {{-- Status --}}
                                    <td class="px-4 py-4">
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeTahap }}">

                                            @if ($k['isApproved'])
                                                <i
                                                    class="bi bi-check-circle-fill text-emerald-500 text-[10px]"></i>
                                            @elseif ($k['isRejected'])
                                                <i
                                                    class="bi bi-x-circle-fill text-red-400 text-[10px]"></i>
                                            @else
                                                <span
                                                    class="w-1.5 h-1.5 rounded-full bg-current inline-block"></span>
                                            @endif

                                            {{ $k['tahap'] }}
                                        </span>
                                    </td>

                                    {{-- Tombol aksi --}}
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-center gap-2 flex-wrap">
                                            <button type="button"
                                                onclick="openDetailKandidat({{ $idx }})"
                                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition-colors">
                                                <i class="bi bi-eye"></i>
                                                Detail
                                            </button>

                                            @if ($k['canApprove'])
                                                <button type="button"
                                                    data-nama="{{ $k['nama'] }}"
                                                    onclick="konfirmasiAksi('setujui', {{ $k['id'] }}, this.dataset.nama)"
                                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white transition-colors">
                                                    <i class="bi bi-check-lg"></i>
                                                    Setujui
                                                </button>

                                                <button type="button"
                                                    data-nama="{{ $k['nama'] }}"
                                                    onclick="konfirmasiAksi('tolak', {{ $k['id'] }}, this.dataset.nama)"
                                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-500 hover:bg-red-600 text-white transition-colors">
                                                    <i class="bi bi-x-lg"></i>
                                                    Tolak
                                                </button>
                                            @endif

                                            @if ($k['canSendHrd'])
                                                <button type="button"
                                                    data-nama="{{ $k['nama'] }}"
                                                    onclick="konfirmasiAksi('kirim-hrd', {{ $k['id'] }}, this.dataset.nama)"
                                                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-[#003366] hover:bg-slate-800 text-white transition-colors">
                                                    <i class="bi bi-send"></i>
                                                    Kirim ke HRD
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-16 text-center">
                                        <i class="bi bi-people text-4xl text-slate-200 block mb-3"></i>

                                        <p class="font-semibold text-slate-500">
                                            Belum ada kandidat
                                        </p>

                                        <p class="text-sm text-slate-400 mt-1">
                                            Kandidat yang melamar di departemen Anda akan muncul di sini.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Tampilan mobile --}}
                <div class="md:hidden divide-y divide-slate-100">
                    @forelse ($kandidat as $idx => $k)
                        @php
                            $badgeTahap = $tahapStyle[$k['tahap']]
                                ?? 'bg-slate-100 text-slate-600 border border-slate-200';
                        @endphp

                        <div class="p-4 kandidat-kartu-mobile"
                            data-nama="{{ strtolower($k['nama']) }}"
                            data-posisi="{{ strtolower($k['posisi']) }}"
                            data-tahap="{{ $k['tahap'] }}">

                            <div class="flex items-start gap-3">
                                <div
                                    class="w-10 h-10 rounded-full bg-[#003366] flex items-center justify-center text-white text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($k['nama'], 0, 2)) }}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start gap-2">
                                        <div>
                                            <p class="font-semibold text-slate-800 text-sm">
                                                {{ $k['nama'] }}
                                            </p>

                                            <p class="text-xs text-slate-400">
                                                {{ $k['posisi'] }}
                                            </p>
                                        </div>

                                        <span
                                            class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-semibold shrink-0 {{ $badgeTahap }}">
                                            {{ $k['tahap'] }}
                                        </span>
                                    </div>

                                    {{-- Indikator tahap --}}
                                    <div class="flex gap-0.5 mt-2">
                                        @for ($step = 1; $step <= 7; $step++)
                                            <div
                                                class="h-1 flex-1 rounded-full
                                                {{ $step <= $k['step']
                                                    ? ($k['isApproved']
                                                        ? 'bg-emerald-500'
                                                        : ($k['isRejected']
                                                            ? 'bg-red-400'
                                                            : 'bg-orange-500'))
                                                    : 'bg-slate-100' }}">
                                            </div>
                                        @endfor
                                    </div>

                                    {{-- Tombol aksi mobile --}}
                                    <div class="flex gap-2 mt-3 flex-wrap">
                                        <button type="button"
                                            onclick="openDetailKandidat({{ $idx }})"
                                            class="flex-1 text-center px-3 py-2 rounded-lg text-xs font-semibold border border-slate-200 bg-white text-slate-600">
                                            <i class="bi bi-eye mr-1"></i>
                                            Detail
                                        </button>

                                        @if ($k['canApprove'])
                                            <button type="button"
                                                data-nama="{{ $k['nama'] }}"
                                                onclick="konfirmasiAksi('setujui', {{ $k['id'] }}, this.dataset.nama)"
                                                class="flex-1 text-center px-3 py-2 rounded-lg text-xs font-semibold bg-emerald-600 text-white">
                                                <i class="bi bi-check-lg mr-1"></i>
                                                Setujui
                                            </button>

                                            <button type="button"
                                                data-nama="{{ $k['nama'] }}"
                                                onclick="konfirmasiAksi('tolak', {{ $k['id'] }}, this.dataset.nama)"
                                                class="flex-1 text-center px-3 py-2 rounded-lg text-xs font-semibold bg-red-500 text-white">
                                                <i class="bi bi-x-lg mr-1"></i>
                                                Tolak
                                            </button>
                                        @endif

                                        @if ($k['canSendHrd'])
                                            <button type="button"
                                                data-nama="{{ $k['nama'] }}"
                                                onclick="konfirmasiAksi('kirim-hrd', {{ $k['id'] }}, this.dataset.nama)"
                                                class="flex-1 text-center px-3 py-2 rounded-lg text-xs font-semibold bg-[#003366] text-white">
                                                <i class="bi bi-send mr-1"></i>
                                                Kirim HRD
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center text-slate-400 text-sm">
                            Belum ada kandidat.
                        </div>
                    @endforelse
                </div>

                {{-- Pesan hasil pencarian kosong --}}
                <div id="pesanTidakAda"
                    class="hidden py-12 text-center text-slate-400 text-sm">
                    <i class="bi bi-search text-2xl text-slate-300 block mb-2"></i>
                    Tidak ada kandidat yang cocok dengan filter.
                </div>
            </div>
        </div>
    </div>

    {{-- Modal detail sudah menangani detail, dokumen, dan timeline --}}
    @include('components.modal-detail')
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        /*
         * Data ini sudah diformat oleh LamaranService.
         * modal-detail.blade.php akan membaca window.kandidatData.
         */
        window.kandidatData = @json($kandidat->values()->all());

        const kandidatData = window.kandidatData;
        const csrfToken =
            document.querySelector('meta[name="csrf-token"]')?.content
            ?? '{{ csrf_token() }}';

        /**
         * Mencari kandidat berdasarkan nama, posisi, dan tahap.
         */
        function cariKandidat() {
            const keyword = document
                .getElementById('inputCariKandidat')
                .value
                .trim()
                .toLowerCase();

            const tahap = document.getElementById('selectFilterTahap').value;
            let jumlahTampil = 0;

            document.querySelectorAll('.kandidat-baris').forEach(function (baris) {
                const cocokKeyword =
                    baris.dataset.nama.includes(keyword)
                    || baris.dataset.posisi.includes(keyword);

                const cocokTahap =
                    tahap === 'ALL'
                    || baris.dataset.tahap === tahap;

                const cocok = cocokKeyword && cocokTahap;

                baris.classList.toggle('hidden', !cocok);

                if (cocok) {
                    jumlahTampil++;
                }
            });

            document.querySelectorAll('.kandidat-kartu-mobile').forEach(function (kartu) {
                const cocokKeyword =
                    kartu.dataset.nama.includes(keyword)
                    || kartu.dataset.posisi.includes(keyword);

                const cocokTahap =
                    tahap === 'ALL'
                    || kartu.dataset.tahap === tahap;

                kartu.classList.toggle('hidden', !(cocokKeyword && cocokTahap));
            });

            document
                .getElementById('pesanTidakAda')
                .classList
                .toggle('hidden', jumlahTampil > 0);
        }

        /**
         * Mencegah nama kandidat dimasukkan sebagai HTML mentah ke SweetAlert.
         */
        function escapeSwalHtml(value) {
            return String(value ?? '-')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        /**
         * Menampilkan dialog konfirmasi sebelum request dikirim.
         */
        function konfirmasiAksi(aksi, id, nama) {
            const namaAman = escapeSwalHtml(nama);

            const konfigurasi = {
                setujui: {
                    title: 'Setujui Kandidat?',
                    html: `Anda akan menyetujui lamaran dari <strong>${namaAman}</strong>.`,
                    icon: 'question',
                    confirmButtonText: 'Ya, Setujui',
                    confirmButtonColor: '#059669',
                    wajibCatatan: false,
                },

                tolak: {
                    title: 'Tolak Kandidat?',
                    html: `Anda akan menolak lamaran dari <strong>${namaAman}</strong>.<br>Tindakan ini tidak dapat dibatalkan.`,
                    icon: 'warning',
                    confirmButtonText: 'Ya, Tolak',
                    confirmButtonColor: '#ef4444',
                    wajibCatatan: true,
                },

                'kirim-hrd': {
                    title: 'Kirim ke HRD?',
                    html: `Kandidat <strong>${namaAman}</strong> akan dikirim ke HRD untuk proses interview.`,
                    icon: 'question',
                    confirmButtonText: 'Ya, Kirim',
                    confirmButtonColor: '#003366',
                    wajibCatatan: false,
                },
            };

            const config = konfigurasi[aksi];

            if (!config) {
                return;
            }

            const opsiSwal = {
                title: config.title,
                html: config.html,
                icon: config.icon,
                showCancelButton: true,
                confirmButtonText: config.confirmButtonText,
                confirmButtonColor: config.confirmButtonColor,
                cancelButtonText: 'Batal',
                cancelButtonColor: '#94a3b8',

                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-6',
                    cancelButton: 'rounded-xl px-6',
                },
            };

            if (config.wajibCatatan) {
                opsiSwal.input = 'textarea';
                opsiSwal.inputLabel = 'Catatan penolakan wajib diisi';
                opsiSwal.inputPlaceholder = 'Tuliskan alasan penolakan...';

                opsiSwal.inputAttributes = {
                    rows: 3,
                    maxlength: 500,
                };

                opsiSwal.inputValidator = function (value) {
                    if (!value || !value.trim()) {
                        return 'Catatan penolakan wajib diisi!';
                    }
                };
            }

            Swal.fire(opsiSwal).then(function (result) {
                if (result.isConfirmed) {
                    eksekusiAksiKandidat(
                        aksi,
                        id,
                        result.value ?? null
                    );
                }
            });
        }

        /**
         * Mengirim aksi ke controller HOD.
         * Route tetap sama seperti kode sebelumnya.
         */
        async function eksekusiAksiKandidat(aksi, id, catatan) {
            const urlEndpoint = {
                setujui: `{{ url('hod/kandidat') }}/${id}/setujui`,
                tolak: `{{ url('hod/kandidat') }}/${id}/tolak`,
                'kirim-hrd': `{{ url('hod/kandidat') }}/${id}/kirim-hrd`,
            };

            const url = urlEndpoint[aksi];

            if (!url) {
                return;
            }

            try {
                Swal.fire({
                    title: 'Memproses...',
                    allowOutsideClick: false,

                    didOpen: function () {
                        Swal.showLoading();
                    },

                    customClass: {
                        popup: 'rounded-2xl',
                    },
                });

                const response = await fetch(url, {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },

                    credentials: 'same-origin',

                    body: JSON.stringify({
                        catatan: catatan,
                    }),
                });

                const contentType = response.headers.get('content-type') ?? '';

                const data = contentType.includes('application/json')
                    ? await response.json()
                    : {};

                if (!response.ok) {
                    throw new Error(
                        data.message
                        ?? 'Terjadi kesalahan saat memproses kandidat.'
                    );
                }

                await Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message ?? 'Data kandidat berhasil diperbarui.',
                    timer: 1800,
                    showConfirmButton: false,

                    customClass: {
                        popup: 'rounded-2xl',
                    },
                });

                window.location.reload();
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: error.message ?? 'Terjadi kesalahan.',
                    confirmButtonColor: '#003366',

                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-xl px-6',
                    },
                });
            }
        }

        /**
         * Menghapus flash message secara otomatis.
         * Listener Escape tidak ditulis ulang karena sudah ada di modal-detail.
         */
        const flash = document.getElementById('flashSuccess');

        if (flash) {
            setTimeout(function () {
                flash.remove();
            }, 5000);
        }
    </script>
@endpush
