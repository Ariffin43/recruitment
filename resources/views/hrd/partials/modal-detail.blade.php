<div id="detailModal" class="fixed inset-0 z-60 hidden">
    <div class="absolute inset-0 bg-black/40" onclick="closeDetailModal()"></div>

    <div class="relative w-full max-w-6xl mx-auto h-full flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden flex flex-col w-full max-h-[90vh]">

            {{-- header --}}
            <div class="px-6 pt-5 pb-4 flex items-start justify-between border-b border-gray-100">
                <div>
                    <span class="inline-flex text-xs font-semibold px-3 py-1 rounded-full bg-gray-100 text-gray-600">
                        DETAIL PELAMAR
                    </span>

                    <div class="mt-3">
                        <div id="dm_name" class="text-lg font-bold text-gray-900">-</div>
                        <div class="text-sm text-gray-500">
                            Melamar sebagai <span id="dm_position" class="font-semibold text-gray-700">-</span>
                            • Status : <span id="dm_status" class="font-semibold text-gray-700">-</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span id="dm_stage_badge"
                          class="inline-flex items-center text-xs font-semibold px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        Tahap 1 / 7
                    </span>

                    <button onclick="closeDetailModal()"
                            class="w-9 h-9 rounded-full bg-red-600 text-white flex items-center justify-center hover:opacity-90">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            </div>

            {{-- content scroll --}}
            <div class="px-6 py-5 space-y-5 overflow-y-auto">

                <div class="rounded-2xl border border-gray-200 bg-white p-5">
                    <div class="text-xl font-bold text-gray-900">Data Pelamar</div>
                    <div class="text-sm text-gray-500 mt-1">Informasi dari form pendaftaran</div>

                    <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Nama</div>
                                <div id="dm_name2" class="text-sm text-gray-500">-</div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Posisi dilamar</div>
                                <div id="dm_position2" class="text-sm text-gray-500">-</div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Tanggal masuk</div>
                                <div id="dm_date" class="text-sm text-gray-500">-</div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Email</div>
                                <div id="dm_email" class="text-sm text-gray-500">-</div>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">Sumber</div>
                                <div id="dm_source" class="text-sm text-gray-500">-</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xl font-bold text-gray-900">Timeline progress</div>
                            <div class="text-sm text-gray-500 mt-1">Status proses rekrutmen kandidat</div>
                        </div>

                        <span class="text-xs font-semibold px-3 py-1 rounded-full bg-gray-100 text-gray-600">
                            Otomatis
                        </span>
                    </div>

                    <div class="mt-5">
                        <div id="timelineWrap" class="space-y-4"></div>
                    </div>
                </div>

                <div id="modalActionButtonsWrapper" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                    <button
                        id="primaryActionBtn"
                        type="button"
                        class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold text-white"
                        style="background:#0F7A55;"
                        onclick="handlePrimaryAction()"
                    >
                        <i class="bi bi-send"></i>
                        <span id="primaryActionText">Mulai screening HRD</span>
                    </button>

                    <button
                        id="rejectActionBtn"
                        type="button"
                        class="sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold border border-red-300 text-red-700 bg-red-50 hover:bg-red-100"
                        onclick="openRejectModal()"
                    >
                        <i class="bi bi-x-lg"></i>
                        Tolak
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>