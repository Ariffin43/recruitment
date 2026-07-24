@extends('hrd.layouts.app')

@section('title', 'Laporan Rekrutmen')
@section('page_title', 'Laporan')
@section('page_subtitle', 'Ringkasan data rekrutmen, statistik pelamar, dan rekap per periode.')

@php
    $statBulan = [
        ['bulan' => 'Okt 2025', 'jumlah' => 15],
        ['bulan' => 'Nov 2025', 'jumlah' => 19],
        ['bulan' => 'Des 2025', 'jumlah' => 12],
        ['bulan' => 'Jan 2026', 'jumlah' => 22],
        ['bulan' => 'Feb 2026', 'jumlah' => 35],
        ['bulan' => 'Mar 2026', 'jumlah' => 25],
    ];

    $statDept = [
        ['dept' => 'HRD', 'jumlah' => 32, 'diterima' => 12, 'ditolak' => 8, 'proses' => 12],
        ['dept' => 'Operation', 'jumlah' => 28, 'diterima' => 10, 'ditolak' => 7, 'proses' => 11],
        ['dept' => 'QA', 'jumlah' => 22, 'diterima' => 8, 'ditolak' => 6, 'proses' => 8],
        ['dept' => 'Engineering', 'jumlah' => 18, 'diterima' => 5, 'ditolak' => 5, 'proses' => 8],
        ['dept' => 'Accounting', 'jumlah' => 14, 'diterima' => 4, 'ditolak' => 3, 'proses' => 7],
        ['dept' => 'Production', 'jumlah' => 14, 'diterima' => 3, 'ditolak' => 2, 'proses' => 9],
    ];

    $statSumber = [
        ['sumber' => 'Portal Kandidat', 'jumlah' => 58, 'persen' => 45],
        ['sumber' => 'LinkedIn', 'jumlah' => 30, 'persen' => 23],
        ['sumber' => 'Referral', 'jumlah' => 22, 'persen' => 17],
        ['sumber' => 'Job Fair', 'jumlah' => 18, 'persen' => 15],
    ];

    $statPosisi = [
        ['posisi' => 'IT Support', 'jumlah' => 18],
        ['posisi' => 'HSE', 'jumlah' => 15],
        ['posisi' => 'HR Admin', 'jumlah' => 12],
        ['posisi' => 'QMS', 'jumlah' => 10],
        ['posisi' => 'Project Admin', 'jumlah' => 8],
    ];

    $maxBulan = max(array_column($statBulan, 'jumlah'));
    $maxDept = max(array_column($statDept, 'jumlah'));
    $maxSumber = max(array_column($statSumber, 'jumlah'));
    $maxPosisi = max(array_column($statPosisi, 'jumlah'));
@endphp

@section('content')
<div class="space-y-6">

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Card 1: Total Pelamar --}}
        <div class="kpiCard rounded-2xl shadow-sm overflow-hidden relative cursor-pointer hover:shadow-md transition"
             data-filter-status=""
             style="background: linear-gradient(135deg, #145D9E 0%, #001E56 100%);">
            <div class="p-5 relative z-10">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-sm font-semibold text-white/80 tracking-wide">TOTAL PELAMAR</div>
                        <div class="mt-1 text-5xl font-extrabold text-white" id="kpiTotal">0</div>
                        <div class="mt-2 text-sm text-white/70">Seluruh periode</div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-white/15 flex items-center justify-center text-white text-xl">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-400/20 text-emerald-300">
                        <i class="bi bi-arrow-up-short text-sm"></i> +12%
                    </span>
                    <span class="text-xs text-white/60">dari bulan lalu</span>
                </div>
            </div>
            <div class="absolute -top-6 -right-6 w-28 h-28 rounded-full bg-white/5"></div>
            <div class="absolute -bottom-4 -right-2 w-20 h-20 rounded-full bg-white/5"></div>
        </div>

        {{-- Card 2: Diterima --}}
        <div class="kpiCard rounded-2xl border border-emerald-200 bg-white shadow-sm overflow-hidden cursor-pointer hover:shadow-md transition"
             data-filter-status="Diterima">
            <div class="p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-xs font-semibold text-gray-500 tracking-wide">DITERIMA</div>
                        <div class="mt-1 text-4xl font-extrabold text-emerald-700" id="kpiDiterima">0</div>
                        <div class="mt-1 text-sm text-gray-500">Lolos seluruh tahap</div>
                    </div>
                    <div class="w-11 h-11 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="text-gray-400" id="kpiDiterimaPct">0% dari total</span>
                        <span class="font-semibold text-gray-600" id="kpiDiterimaRatio">0/0</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-emerald-600" id="kpiDiterimaBar" style="width: 0%;"></div>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-2">
                    <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600">
                        <i class="bi bi-arrow-up-short text-sm"></i> +8%
                    </span>
                    <span class="text-xs text-gray-400">dari bulan lalu</span>
                </div>
            </div>
        </div>

        {{-- Card 3: Ditolak --}}
        <div class="kpiCard rounded-2xl border border-red-200 bg-white shadow-sm overflow-hidden cursor-pointer hover:shadow-md transition"
             data-filter-status="Ditolak">
            <div class="p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-xs font-semibold text-gray-500 tracking-wide">DITOLAK</div>
                        <div class="mt-1 text-4xl font-extrabold text-red-600" id="kpiDitolak">0</div>
                        <div class="mt-1 text-sm text-gray-500">Tidak memenuhi kriteria</div>
                    </div>
                    <div class="w-11 h-11 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center text-lg">
                        <i class="bi bi-x-circle-fill"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="text-gray-400" id="kpiDitolakPct">0% dari total</span>
                        <span class="font-semibold text-gray-600" id="kpiDitolakRatio">0/0</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="h-full rounded-full bg-gradient-to-r from-red-400 to-red-600" id="kpiDitolakBar" style="width: 0%;"></div>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-2">
                    <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-600">
                        <i class="bi bi-arrow-down-short text-sm"></i> -5%
                    </span>
                    <span class="text-xs text-gray-400">dari bulan lalu</span>
                </div>
            </div>
        </div>

        {{-- Card 4: Dalam Proses --}}
        <div class="kpiCard rounded-2xl border border-amber-200 bg-white shadow-sm overflow-hidden cursor-pointer hover:shadow-md transition"
             data-filter-status="Proses">
            <div class="p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-xs font-semibold text-gray-500 tracking-wide">DALAM PROSES</div>
                        <div class="mt-1 text-4xl font-extrabold text-amber-600" id="kpiProses">0</div>
                        <div class="mt-1 text-sm text-gray-500">Masih berjalan</div>
                    </div>
                    <div class="w-11 h-11 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-lg">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="flex items-center justify-between text-xs mb-1">
                        <span class="text-gray-400" id="kpiProsesPct">0% dari total</span>
                        <span class="font-semibold text-gray-600" id="kpiProsesRatio">0/0</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2">
                        <div class="h-full rounded-full bg-gradient-to-r from-amber-400 to-amber-500" id="kpiProsesBar" style="width: 0%;"></div>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-2">
                    <span class="inline-flex items-center gap-0.5 px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600">
                        <i class="bi bi-arrow-up-short text-sm"></i> +3%
                    </span>
                    <span class="text-xs text-gray-400">dari bulan lalu</span>
                </div>
            </div>
        </div>
    </div>

    {{-- FILTER BAR --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
        <div class="flex flex-col lg:flex-row lg:items-end gap-4">
            <div class="flex-1">
                <label class="text-sm font-semibold text-gray-700">Dari Tanggal</label>
                <input type="date" id="filterDari" value="2025-10-01"
                    class="w-full mt-1 px-4 py-3 border rounded-xl text-sm focus:ring-2 focus:ring-blue-200 outline-none">
            </div>
            <div class="flex-1">
                <label class="text-sm font-semibold text-gray-700">Sampai Tanggal</label>
                <input type="date" id="filterSampai" value="2026-03-27"
                    class="w-full mt-1 px-4 py-3 border rounded-xl text-sm focus:ring-2 focus:ring-blue-200 outline-none">
            </div>
            <div class="flex-1">
                <label class="text-sm font-semibold text-gray-700">Departemen</label>
                <select id="filterDept"
                    class="w-full mt-1 px-4 py-3 border rounded-xl text-sm text-gray-500 focus:ring-2 focus:ring-blue-200 outline-none cursor-pointer">
                    <option value="">Semua Departemen</option>
                    <option>HRD</option>
                    <option>Operation</option>
                    <option>QA</option>
                    <option>Engineering</option>
                    <option>Accounting</option>
                    <option>Production</option>
                </select>
            </div>
            <div class="flex-1">
                <label class="text-sm font-semibold text-gray-700">Status</label>
                <select id="filterStatus"
                    class="w-full mt-1 px-4 py-3 border rounded-xl text-sm text-gray-500 focus:ring-2 focus:ring-blue-200 outline-none cursor-pointer">
                    <option value="">Semua Status</option>
                    <option>Diterima</option>
                    <option>Ditolak</option>
                    <option>Proses</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button id="btnTerapkan"
                    class="px-5 py-3 rounded-xl bg-[#145D9E] text-white text-sm font-semibold hover:bg-[#084b85] cursor-pointer whitespace-nowrap">
                    <i class="bi bi-funnel"></i> Terapkan
                </button>
                <button id="btnReset"
                    class="px-5 py-3 rounded-xl border border-gray-300 text-gray-700 text-sm font-semibold hover:bg-gray-50 cursor-pointer">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </button>
            </div>
        </div>
        {{-- Active filter indicator --}}
        <div class="mt-3 text-xs text-gray-500 flex items-center justify-between">
            <div>Filter aktif: <span id="activeFilterText" class="font-semibold text-gray-700">Semua</span></div>
            <div id="resultCount" class="text-gray-500"></div>
        </div>
    </div>

    {{-- MAIN CARD --}}
    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

        {{-- HEADER + EXPORT --}}
        <div class="flex items-center justify-between">
            <div>
                <div class="text-lg font-bold">Laporan Rekrutmen</div>
                <div class="text-sm text-gray-500">Periode: <span class="font-semibold text-gray-700" id="periodeText">Oktober 2025 — Maret 2026</span></div>
            </div>
            <div class="flex gap-2">
                <button id="btnExportPdf"
                    class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 text-sm font-semibold hover:bg-gray-50 cursor-pointer">
                    <i class="bi bi-file-earmark-pdf"></i> PDF
                </button>
                <button id="btnExport"
                    class="px-4 py-2 rounded-xl bg-[#108460] text-white text-sm font-semibold hover:bg-[#0a6e50] cursor-pointer">
                    <i class="bi bi-file-earmark-excel"></i> Excel
                </button>
            </div>
        </div>

        {{-- TABS --}}
        <div class="mt-4 flex gap-3">
            <button id="tabRekap"
                class="cursor-pointer flex-1 rounded-xl bg-[#145D9E] text-white py-2.5 text-sm font-semibold transition">
                <i class="bi bi-table"></i> Rekap Rekrutmen
            </button>
            <button id="tabStatistik"
                class="cursor-pointer flex-1 rounded-xl border border-gray-300 bg-white py-2.5 text-sm font-semibold text-gray-700 transition">
                <i class="bi bi-bar-chart-line"></i> Statistik Pelamar
            </button>
        </div>

        {{-- TAB 1: REKAP REKRUTMEN --}}
        <div id="contentRekap" class="mt-5">

            {{-- Mini summary (dynamic) --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
                <div class="kpiMini rounded-xl bg-gray-50 border border-gray-100 p-3 text-center cursor-pointer hover:bg-gray-100 transition" data-filter-status="">
                    <div class="text-2xl font-extrabold text-gray-900" id="miniTotal">0</div>
                    <div class="text-xs text-gray-500 mt-0.5">Ditampilkan</div>
                </div>
                <div class="kpiMini rounded-xl bg-emerald-50 border border-emerald-100 p-3 text-center cursor-pointer hover:bg-emerald-100 transition" data-filter-status="Diterima">
                    <div class="text-2xl font-extrabold text-emerald-700" id="miniDiterima">0</div>
                    <div class="text-xs text-emerald-600 mt-0.5">Diterima</div>
                </div>
                <div class="kpiMini rounded-xl bg-red-50 border border-red-100 p-3 text-center cursor-pointer hover:bg-red-100 transition" data-filter-status="Ditolak">
                    <div class="text-2xl font-extrabold text-red-600" id="miniDitolak">0</div>
                    <div class="text-xs text-red-500 mt-0.5">Ditolak</div>
                </div>
                <div class="kpiMini rounded-xl bg-amber-50 border border-amber-100 p-3 text-center cursor-pointer hover:bg-amber-100 transition" data-filter-status="Proses">
                    <div class="text-2xl font-extrabold text-amber-600" id="miniProses">0</div>
                    <div class="text-xs text-amber-500 mt-0.5">Dalam Proses</div>
                </div>
            </div>

            <div class="overflow-x-auto rounded-2xl border border-gray-100">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold w-12">No</th>
                            <th class="px-4 py-3 text-left font-semibold">Nama</th>
                            <th class="px-4 py-3 text-left font-semibold">Posisi</th>
                            <th class="px-4 py-3 text-left font-semibold">Departemen</th>
                            <th class="px-4 py-3 text-left font-semibold">Tahap Terakhir</th>
                            <th class="px-4 py-3 text-left font-semibold">Tgl Masuk</th>
                            <th class="px-4 py-3 text-left font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody id="rekapTbody" class="divide-y divide-gray-100"></tbody>
                </table>
            </div>

            {{-- Empty state --}}
            <div id="emptyState" class="hidden mt-4 rounded-2xl border border-gray-200 bg-gray-50 p-6">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center text-gray-600">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <div>
                        <div class="font-semibold text-gray-900">Tidak ada data</div>
                        <div class="text-sm text-gray-500 mt-1">Coba ubah filter atau kata kunci pencarian.</div>
                    </div>
                </div>
            </div>

            {{-- PAGINATION (dynamic) --}}
            <div class="mt-4 flex items-center justify-between">
                <div class="text-xs text-gray-500" id="paginationInfo">Menampilkan 0 dari 0 data</div>
                <div class="flex items-center gap-2" id="paginationBtns"></div>
            </div>
        </div>

        {{-- TAB 2: STATISTIK PELAMAR (stays Blade-rendered) --}}
        <div id="contentStatistik" class="hidden mt-5">

            {{-- ROW 1: Pelamar per Bulan --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 mb-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-base font-bold text-gray-900">
                            <i class="bi bi-calendar3 text-[#145D9E]"></i> Tren Pelamar per Bulan
                        </div>
                        <div class="text-sm text-gray-500 mt-1">Jumlah pelamar masuk per bulan (6 bulan terakhir)</div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-extrabold text-gray-900">128</div>
                        <div class="text-xs text-gray-400">Total pelamar</div>
                    </div>
                </div>
                <div class="mt-6 flex items-end gap-3 h-44">
                    @foreach ($statBulan as $s)
                        @php $heightPct = ($s['jumlah'] / $maxBulan) * 100; @endphp
                        <div class="flex-1 flex flex-col items-center gap-1">
                            <div class="text-xs font-bold text-gray-700">{{ $s['jumlah'] }}</div>
                            <div class="w-full rounded-t-lg relative overflow-hidden" style="height: {{ $heightPct }}%; min-height: 20px;">
                                <div class="absolute inset-0 rounded-t-lg" style="background: linear-gradient(180deg, #2B7FD4 0%, #145D9E 100%);"></div>
                            </div>
                            <div class="text-[11px] font-semibold text-gray-500 mt-1 text-center leading-tight">{{ $s['bulan'] }}</div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap gap-4 text-xs">
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-50 border border-emerald-100">
                        <i class="bi bi-arrow-up text-emerald-600"></i>
                        <span class="text-gray-700">Tertinggi: <strong class="text-emerald-700">Feb 2026</strong> (35 pelamar)</span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-red-50 border border-red-100">
                        <i class="bi bi-arrow-down text-red-600"></i>
                        <span class="text-gray-700">Terendah: <strong class="text-red-600">Des 2025</strong> (12 pelamar)</span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-50 border border-blue-100">
                        <i class="bi bi-calculator text-blue-600"></i>
                        <span class="text-gray-700">Rata-rata: <strong class="text-blue-700">21.3</strong> / bulan</span>
                    </div>
                </div>
            </div>

            {{-- ROW 2 --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-5">
                {{-- DEPARTEMEN --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5">
                    <div class="text-base font-bold text-gray-900"><i class="bi bi-building text-[#108460]"></i> Pelamar per Departemen</div>
                    <div class="text-sm text-gray-500 mt-1">Distribusi dan breakdown status per departemen</div>
                    <div class="mt-5 space-y-4">
                        @foreach ($statDept as $s)
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-semibold text-gray-700">{{ $s['dept'] }}</span>
                                    <span class="text-sm font-bold text-gray-900">{{ $s['jumlah'] }} <span class="text-xs text-gray-400 font-normal">orang</span></span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-5 flex overflow-hidden">
                                    @php $wD=($s['diterima']/$s['jumlah'])*100; $wT=($s['ditolak']/$s['jumlah'])*100; $wP=($s['proses']/$s['jumlah'])*100; @endphp
                                    <div class="h-full bg-emerald-500 flex items-center justify-center text-[10px] font-semibold text-white" style="width:{{$wD}}%;">@if($wD>15){{$s['diterima']}}@endif</div>
                                    <div class="h-full bg-red-400 flex items-center justify-center text-[10px] font-semibold text-white" style="width:{{$wT}}%;">@if($wT>15){{$s['ditolak']}}@endif</div>
                                    <div class="h-full bg-amber-400 flex items-center justify-center text-[10px] font-semibold text-white" style="width:{{$wP}}%;">@if($wP>15){{$s['proses']}}@endif</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100 flex items-center gap-4 text-xs text-gray-500">
                        <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span> Diterima</div>
                        <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-red-400 inline-block"></span> Ditolak</div>
                        <div class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full bg-amber-400 inline-block"></span> Proses</div>
                    </div>
                </div>

                {{-- STATUS AKHIR --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5">
                    <div class="text-base font-bold text-gray-900"><i class="bi bi-pie-chart text-[#6366F1]"></i> Ringkasan Status Akhir</div>
                    <div class="text-sm text-gray-500 mt-1">Perbandingan hasil akhir rekrutmen</div>
                    <div class="mt-5 grid grid-cols-3 gap-3">
                        <div class="rounded-xl bg-emerald-50 border border-emerald-100 p-4 text-center">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto text-lg"><i class="bi bi-check-lg"></i></div>
                            <div class="mt-2 text-2xl font-extrabold text-emerald-700">42</div>
                            <div class="text-xs text-emerald-600 font-semibold">Diterima</div>
                            <div class="text-[11px] text-gray-400 mt-0.5">33%</div>
                        </div>
                        <div class="rounded-xl bg-red-50 border border-red-100 p-4 text-center">
                            <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto text-lg"><i class="bi bi-x-lg"></i></div>
                            <div class="mt-2 text-2xl font-extrabold text-red-600">31</div>
                            <div class="text-xs text-red-600 font-semibold">Ditolak</div>
                            <div class="text-[11px] text-gray-400 mt-0.5">24%</div>
                        </div>
                        <div class="rounded-xl bg-amber-50 border border-amber-100 p-4 text-center">
                            <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto text-lg"><i class="bi bi-hourglass-split"></i></div>
                            <div class="mt-2 text-2xl font-extrabold text-amber-600">55</div>
                            <div class="text-xs text-amber-600 font-semibold">Proses</div>
                            <div class="text-[11px] text-gray-400 mt-0.5">43%</div>
                        </div>
                    </div>
                    <div class="mt-5 space-y-3">
                        <div>
                            <div class="flex items-center justify-between mb-1 text-xs"><span class="font-semibold text-emerald-700">Rasio Penerimaan</span><span class="font-bold text-gray-700">33%</span></div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5"><div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-emerald-600" style="width:33%;"></div></div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1 text-xs"><span class="font-semibold text-red-600">Rasio Penolakan</span><span class="font-bold text-gray-700">24%</span></div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5"><div class="h-full rounded-full bg-gradient-to-r from-red-400 to-red-600" style="width:24%;"></div></div>
                        </div>
                        <div>
                            <div class="flex items-center justify-between mb-1 text-xs"><span class="font-semibold text-amber-600">Masih Berlangsung</span><span class="font-bold text-gray-700">43%</span></div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5"><div class="h-full rounded-full bg-gradient-to-r from-amber-400 to-amber-500" style="width:43%;"></div></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ROW 3 --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
                {{-- SUMBER --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5">
                    <div class="text-base font-bold text-gray-900"><i class="bi bi-signpost-2 text-[#145D9E]"></i> Sumber Pelamar</div>
                    <div class="text-sm text-gray-500 mt-1">Dari mana pelamar mengetahui lowongan</div>
                    <div class="mt-5 space-y-3">
                        @php $sumberColors=[['bar'=>'from-blue-500 to-blue-700','bg'=>'bg-blue-50 text-blue-700'],['bar'=>'from-sky-400 to-sky-600','bg'=>'bg-sky-50 text-sky-700'],['bar'=>'from-teal-400 to-teal-600','bg'=>'bg-teal-50 text-teal-700'],['bar'=>'from-indigo-400 to-indigo-600','bg'=>'bg-indigo-50 text-indigo-700']]; @endphp
                        @foreach ($statSumber as $idx => $s)
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-sm font-semibold text-gray-700">{{ $s['sumber'] }}</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm font-bold text-gray-900">{{ $s['jumlah'] }}</span>
                                        <span class="inline-flex px-1.5 py-0.5 rounded text-[10px] font-semibold {{ $sumberColors[$idx]['bg'] }}">{{ $s['persen'] }}%</span>
                                    </div>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-4 overflow-hidden">
                                    <div class="h-full rounded-full bg-gradient-to-r {{ $sumberColors[$idx]['bar'] }}" style="width:{{ ($s['jumlah']/$maxSumber)*100 }}%;"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- POSISI --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-5">
                    <div class="text-base font-bold text-gray-900"><i class="bi bi-briefcase text-amber-600"></i> Posisi Paling Diminati</div>
                    <div class="text-sm text-gray-500 mt-1">5 posisi dengan pelamar terbanyak</div>
                    <div class="mt-5 space-y-3">
                        @foreach ($statPosisi as $idx => $s)
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg {{ $idx===0 ? 'bg-[#145D9E] text-white' : 'bg-gray-100 text-gray-600' }} flex items-center justify-center text-xs font-bold">{{ $idx+1 }}</div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-semibold text-gray-700">{{ $s['posisi'] }}</span>
                                        <span class="text-sm font-bold text-gray-900">{{ $s['jumlah'] }} <span class="text-xs text-gray-400 font-normal">pelamar</span></span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                                        <div class="h-full rounded-full" style="width:{{ ($s['jumlah']/$maxPosisi)*100 }}%; background:linear-gradient(90deg,#F59E0B,#D97706);"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-3 border-t border-gray-100">
                        <div class="flex items-start gap-2 text-xs text-gray-500">
                            <i class="bi bi-lightbulb text-amber-500 mt-0.5"></i>
                            <span><strong class="text-gray-700">IT Support</strong> menjadi posisi paling diminati dengan <strong class="text-gray-700">18 pelamar</strong> dalam 6 bulan terakhir.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // ==================== DATA ====================
        const ALL_DATA = [
            {nama:'Jhonson', posisi:'IT Support', dept:'HRD', tahap:'Selesai', tgl:'2025-10-05', status:'Diterima'},
            {nama:'Lina', posisi:'HR Admin', dept:'HRD', tahap:'Screening HRD', tgl:'2025-10-12', status:'Ditolak'},
            {nama:'Bram', posisi:'HSE', dept:'Operation', tahap:'Selesai', tgl:'2025-10-18', status:'Diterima'},
            {nama:'Eka', posisi:'QMS', dept:'QA', tahap:'Interview', tgl:'2025-10-25', status:'Proses'},
            {nama:'Doni', posisi:'Accounting Staff', dept:'Accounting', tahap:'Selesai', tgl:'2025-11-02', status:'Diterima'},
            {nama:'Rani', posisi:'IT Support', dept:'HRD', tahap:'Screening HOD', tgl:'2025-11-08', status:'Ditolak'},
            {nama:'Fajar', posisi:'HSE', dept:'Operation', tahap:'Selesai', tgl:'2025-11-14', status:'Diterima'},
            {nama:'Gita', posisi:'Project Admin', dept:'Engineering', tahap:'Interview', tgl:'2025-11-20', status:'Proses'},
            {nama:'Hadi', posisi:'QA Inspector', dept:'QA', tahap:'Selesai', tgl:'2025-11-28', status:'Ditolak'},
            {nama:'Indah', posisi:'HR Admin', dept:'HRD', tahap:'Screening HRD', tgl:'2025-12-03', status:'Proses'},
            {nama:'Joko', posisi:'Operator', dept:'Production', tahap:'Selesai', tgl:'2025-12-10', status:'Diterima'},
            {nama:'Kartini', posisi:'IT Support', dept:'HRD', tahap:'Screening HOD', tgl:'2025-12-18', status:'Ditolak'},
            {nama:'Utari', posisi:'HSE', dept:'Operation', tahap:'Interview', tgl:'2026-01-05', status:'Proses'},
            {nama:'Dikha', posisi:'HR Admin', dept:'HRD', tahap:'Selesai', tgl:'2026-01-12', status:'Ditolak'},
            {nama:'Ariyanto', posisi:'HSE', dept:'Operation', tahap:'Screening HOD', tgl:'2026-01-18', status:'Proses'},
            {nama:'Tiara', posisi:'QMS', dept:'QA', tahap:'Interview', tgl:'2026-01-22', status:'Proses'},
            {nama:'Ruth', posisi:'Project Admin', dept:'Engineering', tahap:'Selesai', tgl:'2026-01-28', status:'Diterima'},
            {nama:'Bayu', posisi:'Accounting Staff', dept:'Accounting', tahap:'Selesai', tgl:'2026-02-02', status:'Diterima'},
            {nama:'Sari', posisi:'QA Inspector', dept:'QA', tahap:'Screening HRD', tgl:'2026-02-08', status:'Proses'},
            {nama:'Tono', posisi:'Operator', dept:'Production', tahap:'Selesai', tgl:'2026-02-14', status:'Diterima'},
            {nama:'Wulan', posisi:'IT Support', dept:'HRD', tahap:'Interview', tgl:'2026-02-20', status:'Proses'},
            {nama:'Yudi', posisi:'HSE', dept:'Operation', tahap:'Screening HRD', tgl:'2026-02-25', status:'Ditolak'},
            {nama:'Aldi', posisi:'QMS', dept:'QA', tahap:'Selesai', tgl:'2026-03-01', status:'Diterima'},
            {nama:'Bella', posisi:'HR Admin', dept:'HRD', tahap:'Screening HOD', tgl:'2026-03-05', status:'Proses'},
            {nama:'Cahya', posisi:'Project Admin', dept:'Engineering', tahap:'Interview', tgl:'2026-03-10', status:'Proses'},
            {nama:'Dewi', posisi:'Accounting Staff', dept:'Accounting', tahap:'Selesai', tgl:'2026-03-14', status:'Ditolak'},
            {nama:'Edwin', posisi:'IT Support', dept:'HRD', tahap:'Selesai', tgl:'2026-03-18', status:'Diterima'},
            {nama:'Fitri', posisi:'Operator', dept:'Production', tahap:'Screening HRD', tgl:'2026-03-22', status:'Proses'},
        ];

        // ==================== STATE ====================
        let filteredData = [...ALL_DATA];
        let currentPage = 1;
        const perPage = 5;
        let activeStatusFilter = '';

        // ==================== TAHAP STYLES ====================
        const tahapStyle = {
            'Selesai': 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'Interview': 'bg-indigo-50 text-indigo-700 border-indigo-200',
            'Screening HRD': 'bg-sky-50 text-sky-700 border-sky-200',
            'Screening HOD': 'bg-purple-50 text-purple-700 border-purple-200',
            'Dikirim ke HOD': 'bg-orange-50 text-orange-700 border-orange-200',
            'Baru': 'bg-red-50 text-red-600 border-red-200',
        };

        const statusBadge = {
            'Diterima': '<span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-emerald-100 text-emerald-700"><i class="bi bi-check-circle-fill text-[10px]"></i> Diterima</span>',
            'Ditolak': '<span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700"><i class="bi bi-x-circle-fill text-[10px]"></i> Ditolak</span>',
            'Proses': '<span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-semibold rounded-full bg-amber-100 text-amber-700"><i class="bi bi-clock-fill text-[10px]"></i> Proses</span>',
        };

        // ==================== FILTER LOGIC ====================
        function applyFilters() {
            const dari = document.getElementById('filterDari').value;
            const sampai = document.getElementById('filterSampai').value;
            const dept = document.getElementById('filterDept').value;
            const status = document.getElementById('filterStatus').value;

            filteredData = ALL_DATA.filter(r => {
                if (dari && r.tgl < dari) return false;
                if (sampai && r.tgl > sampai) return false;
                if (dept && r.dept !== dept) return false;
                if (status && r.status !== status) return false;
                if (activeStatusFilter && r.status !== activeStatusFilter) return false;
                return true;
            });

            currentPage = 1;
            updateKPIs();
            renderTable();
            updateFilterText();
        }

        function updateFilterText() {
            const parts = [];
            const dept = document.getElementById('filterDept').value;
            const status = document.getElementById('filterStatus').value;

            if (activeStatusFilter) parts.push(activeStatusFilter);
            else if (status) parts.push(status);
            if (dept) parts.push(dept);

            document.getElementById('activeFilterText').textContent = parts.length > 0 ? parts.join(', ') : 'Semua';
            document.getElementById('resultCount').textContent = `${filteredData.length} hasil ditemukan`;
        }

        // ==================== KPI UPDATE ====================
        function updateKPIs() {
            // Counts from ALL_DATA (unfiltered by KPI click, but filtered by dropdowns/dates)
            const dari = document.getElementById('filterDari').value;
            const sampai = document.getElementById('filterSampai').value;
            const dept = document.getElementById('filterDept').value;

            const baseData = ALL_DATA.filter(r => {
                if (dari && r.tgl < dari) return false;
                if (sampai && r.tgl > sampai) return false;
                if (dept && r.dept !== dept) return false;
                return true;
            });

            const total = baseData.length;
            const diterima = baseData.filter(r => r.status === 'Diterima').length;
            const ditolak = baseData.filter(r => r.status === 'Ditolak').length;
            const proses = baseData.filter(r => r.status === 'Proses').length;

            document.getElementById('kpiTotal').textContent = total;
            document.getElementById('kpiDiterima').textContent = diterima;
            document.getElementById('kpiDitolak').textContent = ditolak;
            document.getElementById('kpiProses').textContent = proses;

            const pctD = total > 0 ? Math.round((diterima/total)*100) : 0;
            const pctT = total > 0 ? Math.round((ditolak/total)*100) : 0;
            const pctP = total > 0 ? Math.round((proses/total)*100) : 0;

            document.getElementById('kpiDiterimaPct').textContent = `${pctD}% dari total`;
            document.getElementById('kpiDiterimaRatio').textContent = `${diterima}/${total}`;
            document.getElementById('kpiDiterimaBar').style.width = `${pctD}%`;

            document.getElementById('kpiDitolakPct').textContent = `${pctT}% dari total`;
            document.getElementById('kpiDitolakRatio').textContent = `${ditolak}/${total}`;
            document.getElementById('kpiDitolakBar').style.width = `${pctT}%`;

            document.getElementById('kpiProsesPct').textContent = `${pctP}% dari total`;
            document.getElementById('kpiProsesRatio').textContent = `${proses}/${total}`;
            document.getElementById('kpiProsesBar').style.width = `${pctP}%`;

            // Mini summary (always shows baseData counts, not filtered by KPI click)
            document.getElementById('miniTotal').textContent = total;
            document.getElementById('miniDiterima').textContent = diterima;
            document.getElementById('miniDitolak').textContent = ditolak;
            document.getElementById('miniProses').textContent = proses;
        }

        // ==================== TABLE RENDER ====================
        function renderTable() {
            const tbody = document.getElementById('rekapTbody');
            const emptyState = document.getElementById('emptyState');
            tbody.innerHTML = '';

            if (filteredData.length === 0) {
                emptyState.classList.remove('hidden');
                document.getElementById('paginationInfo').textContent = 'Menampilkan 0 dari 0 data';
                document.getElementById('paginationBtns').innerHTML = '';
                return;
            }

            emptyState.classList.add('hidden');

            const totalPages = Math.ceil(filteredData.length / perPage);
            if (currentPage > totalPages) currentPage = totalPages;

            const start = (currentPage - 1) * perPage;
            const end = Math.min(start + perPage, filteredData.length);
            const pageData = filteredData.slice(start, end);

            pageData.forEach((r, i) => {
                const tStyle = tahapStyle[r.tahap] || 'bg-gray-50 text-gray-700 border-gray-200';
                const sBadge = statusBadge[r.status] || r.status;

                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50 transition';
                tr.innerHTML = `
                    <td class="px-4 py-3 text-gray-500">${start + i + 1}</td>
                    <td class="px-4 py-3 font-semibold text-gray-900">${r.nama}</td>
                    <td class="px-4 py-3 text-gray-700">${r.posisi}</td>
                    <td class="px-4 py-3 text-gray-700">${r.dept}</td>
                    <td class="px-4 py-3"><span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium ${tStyle}">${r.tahap}</span></td>
                    <td class="px-4 py-3 text-gray-500 text-xs">${r.tgl}</td>
                    <td class="px-4 py-3">${sBadge}</td>
                `;
                tbody.appendChild(tr);
            });

            // Pagination info
            document.getElementById('paginationInfo').textContent =
                `Menampilkan ${start+1}–${end} dari ${filteredData.length} data`;

            renderPagination(totalPages);
        }

        // ==================== PAGINATION ====================
        function renderPagination(totalPages) {
            const wrap = document.getElementById('paginationBtns');
            wrap.innerHTML = '';

            if (totalPages <= 1) return;

            // Prev
            const prevBtn = document.createElement('button');
            prevBtn.className = `w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center ${currentPage === 1 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 hover:bg-gray-50 cursor-pointer'}`;
            prevBtn.innerHTML = '<i class="bi bi-chevron-left text-xs"></i>';
            if (currentPage > 1) prevBtn.onclick = () => { currentPage--; renderTable(); };
            wrap.appendChild(prevBtn);

            // Page numbers
            for (let p = 1; p <= totalPages; p++) {
                const btn = document.createElement('button');
                btn.textContent = p;
                if (p === currentPage) {
                    btn.className = 'w-8 h-8 rounded-lg bg-[#145D9E] text-white flex items-center justify-center text-xs font-semibold';
                } else {
                    btn.className = 'w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-gray-50 text-xs cursor-pointer';
                    btn.onclick = () => { currentPage = p; renderTable(); };
                }
                wrap.appendChild(btn);
            }

            // Next
            const nextBtn = document.createElement('button');
            nextBtn.className = `w-8 h-8 rounded-lg border border-gray-200 flex items-center justify-center ${currentPage === totalPages ? 'text-gray-300 cursor-not-allowed' : 'text-gray-500 hover:bg-gray-50 cursor-pointer'}`;
            nextBtn.innerHTML = '<i class="bi bi-chevron-right text-xs"></i>';
            if (currentPage < totalPages) nextBtn.onclick = () => { currentPage++; renderTable(); };
            wrap.appendChild(nextBtn);
        }

        // ==================== KPI CLICK ====================
        document.querySelectorAll('.kpiCard').forEach(card => {
            card.addEventListener('click', function() {
                const status = this.dataset.filterStatus;

                // Toggle: click same = deselect
                if (activeStatusFilter === status || status === '') {
                    activeStatusFilter = '';
                    document.getElementById('filterStatus').value = '';
                } else {
                    activeStatusFilter = status;
                    document.getElementById('filterStatus').value = status;
                }

                // Highlight active card
                document.querySelectorAll('.kpiCard').forEach(c => {
                    c.classList.remove('ring-2', 'ring-[#145D9E]', 'ring-offset-2');
                });
                if (activeStatusFilter) {
                    this.classList.add('ring-2', 'ring-[#145D9E]', 'ring-offset-2');
                }

                applyFilters();
            });
        });

        // Mini summary cards also clickable
        document.querySelectorAll('.kpiMini').forEach(card => {
            card.addEventListener('click', function() {
                const status = this.dataset.filterStatus;

                if (activeStatusFilter === status || status === '') {
                    activeStatusFilter = '';
                    document.getElementById('filterStatus').value = '';
                } else {
                    activeStatusFilter = status;
                    document.getElementById('filterStatus').value = status;
                }

                document.querySelectorAll('.kpiCard').forEach(c => {
                    c.classList.remove('ring-2', 'ring-[#145D9E]', 'ring-offset-2');
                });
                if (activeStatusFilter) {
                    const matching = document.querySelector(`.kpiCard[data-filter-status="${activeStatusFilter}"]`);
                    if (matching) matching.classList.add('ring-2', 'ring-[#145D9E]', 'ring-offset-2');
                }

                applyFilters();
            });
        });

        // ==================== FILTER BUTTONS ====================
        document.getElementById('btnTerapkan').addEventListener('click', () => {
            activeStatusFilter = '';
            document.querySelectorAll('.kpiCard').forEach(c => {
                c.classList.remove('ring-2', 'ring-[#145D9E]', 'ring-offset-2');
            });
            applyFilters();
        });

        document.getElementById('btnReset').addEventListener('click', () => {
            document.getElementById('filterDari').value = '2025-10-01';
            document.getElementById('filterSampai').value = '2026-03-27';
            document.getElementById('filterDept').value = '';
            document.getElementById('filterStatus').value = '';
            activeStatusFilter = '';

            document.querySelectorAll('.kpiCard').forEach(c => {
                c.classList.remove('ring-2', 'ring-[#145D9E]', 'ring-offset-2');
            });

            applyFilters();

            Swal.fire({
                icon: 'success',
                title: 'Filter Direset',
                text: 'Semua filter dikembalikan ke default.',
                confirmButtonColor: '#145D9E',
                timer: 1500,
                showConfirmButton: false,
            });
        });

        // ==================== TABS ====================
        const tabRekap = document.getElementById('tabRekap');
        const tabStatistik = document.getElementById('tabStatistik');
        const contentRekap = document.getElementById('contentRekap');
        const contentStatistik = document.getElementById('contentStatistik');

        tabRekap.addEventListener('click', () => {
            tabRekap.classList.add('bg-[#145D9E]', 'text-white');
            tabRekap.classList.remove('border', 'border-gray-300', 'bg-white', 'text-gray-700');
            tabStatistik.classList.remove('bg-[#145D9E]', 'text-white');
            tabStatistik.classList.add('border', 'border-gray-300', 'bg-white', 'text-gray-700');
            contentRekap.classList.remove('hidden');
            contentStatistik.classList.add('hidden');
        });

        tabStatistik.addEventListener('click', () => {
            tabStatistik.classList.add('bg-[#145D9E]', 'text-white');
            tabStatistik.classList.remove('border', 'border-gray-300', 'bg-white', 'text-gray-700');
            tabRekap.classList.remove('bg-[#145D9E]', 'text-white');
            tabRekap.classList.add('border', 'border-gray-300', 'bg-white', 'text-gray-700');
            contentRekap.classList.add('hidden');
            contentStatistik.classList.remove('hidden');
        });

        // ==================== EXPORT ====================
        document.getElementById('btnExport').addEventListener('click', () => {
            Swal.fire({ icon:'success', title:'Export Excel Berhasil!', text:'File laporan_rekrutmen.xlsx telah diunduh.', confirmButtonColor:'#108460' });
        });

        document.getElementById('btnExportPdf').addEventListener('click', () => {
            Swal.fire({ icon:'success', title:'Export PDF Berhasil!', text:'File laporan_rekrutmen.pdf telah diunduh.', confirmButtonColor:'#145D9E' });
        });

        // ==================== INIT ====================
        document.addEventListener('DOMContentLoaded', () => {
            applyFilters();
        });
    </script>
@endpush

@endsection
