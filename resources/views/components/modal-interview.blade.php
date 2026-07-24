{{-- resources/views/components/modal-interview.blade.php --}}
@once
<div id="interviewModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="interviewTitle">

    <div class="absolute inset-0 bg-black/55 backdrop-blur-sm" onclick="closeInterviewModal()"></div>

    <div class="relative w-full h-full flex items-center justify-center p-3 sm:p-4">
        <div class="pointer-events-auto w-full max-w-lg max-h-[88vh] overflow-hidden rounded-2xl bg-white shadow-xl border-2 border-slate-300 flex flex-col">

            {{-- Header --}}
            <div class="flex items-center justify-between gap-3 px-5 py-4 border-b-2 border-slate-200 bg-slate-50">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-xl bg-indigo-50 border-2 border-indigo-200 text-indigo-600 flex items-center justify-center shrink-0">
                        <i class="bi bi-calendar-event text-sm"></i>
                    </div>
                    <div class="min-w-0">
                        <p id="interviewTitle" class="text-sm font-bold text-slate-900 truncate">-</p>
                        <p class="text-xs text-slate-500 truncate">
                            <span id="interviewPosition">-</span>
                            &nbsp;·&nbsp;
                            <span id="interviewStatus">-</span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <span class="hidden sm:inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-full bg-indigo-50 text-indigo-700 border-2 border-indigo-200">
                        <i class="bi bi-calendar2-week text-xs"></i>
                        Jadwalkan Interview
                    </span>
                    <button type="button" onclick="closeInterviewModal()"
                        class="w-8 h-8 rounded-full bg-red-50 border-2 border-red-300 text-red-600 flex items-center justify-center hover:bg-red-100 transition"
                        aria-label="Tutup modal">
                        <i class="bi bi-x-lg text-xs"></i>
                    </button>
                </div>
            </div>

            {{-- Form --}}
            <form id="formInterview" class="flex flex-col flex-1 overflow-hidden" onsubmit="submitInterview(event)">
                @csrf
                <input type="hidden" id="interviewId" name="id">

                <div class="flex-1 overflow-y-auto px-5 py-4 space-y-3 bg-white">

                    {{-- Info kandidat --}}
                    <div class="grid grid-cols-2 gap-2">
                        <div class="rounded-xl bg-slate-50 border-2 border-slate-200 px-4 py-3">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 mb-1">Nama Kandidat</p>
                            <p id="interviewName" class="text-sm font-bold text-slate-800 truncate">-</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 border-2 border-slate-200 px-4 py-3">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 mb-1">Posisi Dilamar</p>
                            <p id="interviewPosition2" class="text-sm font-bold text-slate-800 truncate">-</p>
                        </div>
                    </div>

                    {{-- Metode + Tanggal --}}
                    <div class="grid grid-cols-2 gap-2">
                        <div class="rounded-xl bg-white border-2 border-indigo-200 px-4 py-3">
                            <label for="interviewMethod" class="text-[10px] font-semibold uppercase tracking-wider text-indigo-500 mb-1 flex items-center gap-1">
                                <i class="bi bi-sliders"></i> Metode
                            </label>
                            <select id="interviewMethod" name="metode_interview" onchange="toggleInterviewFields()"
                                class="w-full bg-transparent border-none text-sm font-bold text-slate-800 focus:outline-none p-0 cursor-pointer">
                                <option value="online">Online</option>
                                <option value="offline">Offline</option>
                            </select>
                        </div>
                        <div class="rounded-xl bg-white border-2 border-emerald-200 px-4 py-3">
                            <label for="interviewDate" class="text-[10px] font-semibold uppercase tracking-wider text-emerald-600 mb-1 flex items-center gap-1">
                                <i class="bi bi-calendar3"></i> Tanggal
                            </label>
                            <input type="date" id="interviewDate" name="tanggal_interview"
                                class="w-full bg-transparent border-none text-sm font-bold text-slate-800 focus:outline-none p-0">
                        </div>
                    </div>

                    {{-- Jam + Link/Lokasi --}}
                    <div class="grid grid-cols-2 gap-2">
                        <div class="rounded-xl bg-white border-2 border-emerald-200 px-4 py-3">
                            <label for="interviewTime" class="text-[10px] font-semibold uppercase tracking-wider text-emerald-600 mb-1 flex items-center gap-1">
                                <i class="bi bi-clock"></i> Jam
                            </label>
                            <input type="time" id="interviewTime" name="jam_interview"
                                class="w-full bg-transparent border-none text-sm font-bold text-slate-800 focus:outline-none p-0">
                        </div>

                        <div id="onlineFields" class="rounded-xl bg-sky-50 border-2 border-sky-300 px-4 py-3">
                            <label for="interviewLink" class="text-[10px] font-semibold uppercase tracking-wider text-sky-600 mb-1 flex items-center gap-1">
                                <i class="bi bi-camera-video"></i> Link Meeting
                            </label>
                            <input type="url" id="interviewLink" name="link" placeholder="https://meet.google.com/..."
                                class="w-full bg-transparent border-none text-sm text-slate-800 focus:outline-none p-0 placeholder:text-slate-300">
                        </div>

                        <div id="offlineFields" class="hidden rounded-xl bg-amber-50 border-2 border-amber-300 px-4 py-3">
                            <label for="interviewLocation" class="text-[10px] font-semibold uppercase tracking-wider text-amber-600 mb-1 flex items-center gap-1">
                                <i class="bi bi-geo-alt"></i> Lokasi
                            </label>
                            <input type="text" id="interviewLocation" name="lokasi_interview" placeholder="Ruang Meeting HRD"
                                class="w-full bg-transparent border-none text-sm text-slate-800 focus:outline-none p-0 placeholder:text-slate-300">
                        </div>
                    </div>

                    {{-- Catatan --}}
                    <div class="rounded-xl bg-white border-2 border-slate-200 px-4 py-3">
                        <label for="interviewNotes" class="text-[10px] font-semibold uppercase tracking-wider text-slate-400 mb-1 flex items-center gap-1">
                            <i class="bi bi-chat-left-text"></i>
                            Catatan <span class="normal-case font-normal tracking-normal ml-1">(opsional)</span>
                        </label>
                        <textarea id="interviewNotes" name="catatan_interview" rows="2"
                            placeholder="Tulis catatan tambahan jika diperlukan..."
                            class="w-full bg-transparent border-none text-sm text-slate-800 focus:outline-none p-0 resize-none placeholder:text-slate-300"></textarea>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="border-t-2 border-slate-200 px-5 py-3 bg-slate-50 flex justify-end gap-2">
                    <button type="button" onclick="closeInterviewModal()"
                        class="inline-flex items-center gap-1.5 rounded-xl border-2 border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 transition">
                        <i class="bi bi-x-lg text-xs"></i>
                        Batal
                    </button>
                    <button type="submit"
                        class="inline-flex items-center gap-1.5 rounded-xl border-2 border-indigo-700 bg-indigo-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-800 transition">
                        <i class="bi bi-check2-circle"></i>
                        Simpan Jadwal
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endonce