<div id="jadwalModal" class="fixed inset-0 z-[70] hidden">
    <div class="absolute inset-0 bg-black/40" onclick="closeJadwalModal()"></div>

    <div class="relative w-full max-w-2xl mx-auto h-full flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden flex flex-col w-full max-h-[90vh]">
            
            <div class="p-6 overflow-y-auto">
                {{-- Header --}}
                <div>
                    <h2 class="text-[22px] font-bold text-gray-900 leading-tight">Buat jadwal interview — <span id="jm_name">Jhonson</span></h2>
                    <p class="text-sm text-gray-500 mt-1">HRD wajib isi tanggal, waktu, dan detail lokasi/meeting.</p>
                </div>

                {{-- Tab Switcher --}}
                <div class="inline-flex bg-gray-50 rounded-full p-1 mt-5 border border-gray-100">
                    <button id="jm_tab_btn_offline" onclick="switchJadwalTab('offline')" class="px-5 py-1.5 rounded-full text-sm font-semibold text-white bg-[#145D9E] shadow-sm transition">Offline (Onsite)</button>
                    <button id="jm_tab_btn_online" onclick="switchJadwalTab('online')" class="px-5 py-1.5 rounded-full text-sm font-medium text-gray-500 hover:text-gray-700 transition">Online (Virtual)</button>
                </div>

                {{-- Form Offline --}}
                <div id="jm_form_offline" class="mt-6 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[13px] font-medium text-gray-600 mb-1.5">Tanggal</label>
                            <input type="date" id="jm_off_tgl" class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 outline-none focus:border-[#145D9E] focus:ring-1 focus:ring-[#145D9E] transition">
                        </div>
                        <div>
                            <label class="block text-[13px] font-medium text-gray-600 mb-1.5">Waktu</label>
                            <input type="time" id="jm_off_waktu" class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 outline-none focus:border-[#145D9E] focus:ring-1 focus:ring-[#145D9E] transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-gray-600 mb-1.5">Alamat / Ruang</label>
                        <input type="text" id="jm_off_lokasi" placeholder="Ruang Interview HRD, Lt. 2, Gedung A" class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 outline-none focus:border-[#145D9E] focus:ring-1 focus:ring-[#145D9E] transition">
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-gray-600 mb-1.5">Tautan Google Maps</label>
                        <input type="text" id="jm_off_maps" placeholder="Tempel link share dari Google Maps" class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 outline-none focus:border-[#145D9E] focus:ring-1 focus:ring-[#145D9E] transition">
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-gray-600 mb-1.5">Catatan (opsional)</label>
                        <textarea id="jm_off_catatan" placeholder="Bawa CV asli, datang 10 menit sebelum jadwal." class="w-full h-24 rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 outline-none focus:border-[#145D9E] focus:ring-1 focus:ring-[#145D9E] transition resize-none"></textarea>
                    </div>
                </div>

                {{-- Form Online --}}
                <div id="jm_form_online" class="mt-6 space-y-5 hidden">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[13px] font-medium text-gray-600 mb-1.5">Tanggal</label>
                            <input type="date" id="jm_on_tgl" class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 outline-none focus:border-[#145D9E] focus:ring-1 focus:ring-[#145D9E] transition">
                        </div>
                        <div>
                            <label class="block text-[13px] font-medium text-gray-600 mb-1.5">Waktu</label>
                            <input type="time" id="jm_on_waktu" class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 outline-none focus:border-[#145D9E] focus:ring-1 focus:ring-[#145D9E] transition">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[13px] font-medium text-gray-600 mb-1.5">Platform</label>
                            <select id="jm_on_platform" class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 outline-none focus:border-[#145D9E] focus:ring-1 focus:ring-[#145D9E] transition bg-white cursor-pointer">
                                <option>Google Meet</option>
                                <option>Zoom Meeting</option>
                                <option>Microsoft Teams</option>
                                <option>Skype</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[13px] font-medium text-gray-600 mb-1.5">Link Meeting</label>
                            <input type="text" id="jm_on_link" placeholder="https://meet.google.com/xxx-xxxx-xxx" class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 outline-none focus:border-[#145D9E] focus:ring-1 focus:ring-[#145D9E] transition">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[13px] font-medium text-gray-600 mb-1.5">Meeting ID (opsional)</label>
                            <input type="text" id="jm_on_id" placeholder="123 4567 8901" class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 outline-none focus:border-[#145D9E] focus:ring-1 focus:ring-[#145D9E] transition">
                        </div>
                        <div>
                            <label class="block text-[13px] font-medium text-gray-600 mb-1.5">Passcode (opsional)</label>
                            <input type="text" id="jm_on_passcode" placeholder="abcd1234" class="w-full rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 outline-none focus:border-[#145D9E] focus:ring-1 focus:ring-[#145D9E] transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[13px] font-medium text-gray-600 mb-1.5">Catatan (opsional)</label>
                        <textarea id="jm_on_catatan" placeholder="Syarat perangkat, background, dsb." class="w-full h-24 rounded-xl border border-gray-200 px-3.5 py-2.5 text-sm text-gray-900 outline-none focus:border-[#145D9E] focus:ring-1 focus:ring-[#145D9E] transition resize-none"></textarea>
                    </div>
                </div>

                {{-- Bottom Buttons --}}
                <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeJadwalModal()" class="px-6 py-2.5 rounded-xl border border-gray-200 bg-gray-50 text-gray-700 font-semibold text-[14px] hover:bg-gray-100 transition cursor-pointer">Batal</button>
                    <button type="button" onclick="submitJadwal('email')" class="px-6 py-2.5 rounded-xl border border-gray-300 bg-white text-gray-700 font-semibold text-[14px] hover:bg-gray-50 transition cursor-pointer flex items-center gap-2">
                        <i class="bi bi-envelope"></i> Simpan & kirim Email
                    </button>
                    <button type="button" onclick="submitJadwal('wa')" class="px-6 py-2.5 rounded-xl bg-[#145D9E] text-white font-semibold text-[14px] hover:bg-blue-800 transition cursor-pointer flex items-center gap-2">
                        <i class="bi bi-whatsapp"></i> Simpan & kirim WA
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
