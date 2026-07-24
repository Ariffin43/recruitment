@include('components.timeline-progress')

<div id="detailModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="dm_name">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-200"
        onclick="closeDetailModal()" aria-hidden="true">
    </div>

    <div class="relative w-full h-full flex items-center justify-center p-3 sm:p-4 pointer-events-none">
        <div
            class="pointer-events-auto bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden flex flex-col w-full max-w-5xl max-h-[92vh]">
            <div
                class="px-5 sm:px-6 pt-5 pb-4 flex items-start justify-between gap-3 border-b border-gray-100 shrink-0">
                <div class="min-w-0">
                    <span
                        class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">
                        <i class="bi bi-person-vcard-fill"></i>
                        DETAIL PELAMAR
                    </span>

                    <div class="mt-3">
                        <h2 id="dm_name" class="text-lg font-bold text-gray-900 truncate">-</h2>
                        <p class="text-sm text-gray-500 mt-0.5">
                            Melamar sebagai
                            <span id="dm_position" class="font-semibold text-gray-700">-</span>
                            &nbsp;•&nbsp; Status:
                            <span id="dm_status" class="font-semibold text-gray-700">-</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <span id="dm_stage_badge"
                        class="hidden sm:inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <i class="bi bi-list-ol"></i>
                        Tahap 1 / 7
                    </span>

                    <button type="button" onclick="closeDetailModal()"
                        class="w-9 h-9 rounded-full bg-red-500 text-white flex items-center justify-center hover:bg-red-600 active:scale-95 transition-all"
                        title="Tutup" aria-label="Tutup modal">
                        <i class="bi bi-x-lg text-sm"></i>
                    </button>
                </div>
            </div>

            <div class="px-5 sm:px-6 py-5 space-y-5 overflow-y-auto flex-1">
                <div class="rounded-2xl border border-gray-200 bg-white p-5">
                    <div class="flex items-center gap-2 mb-5">
                        <div class="w-8 h-8 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center">
                            <i class="bi bi-person-fill text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Data Pelamar</h3>
                            <p class="text-xs text-gray-500">Informasi dari form pendaftaran</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                        <div class="space-y-4">
                            <div>
                                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Nama
                                    Lengkap</div>
                                <div id="dm_name2" class="text-sm font-semibold text-gray-800">-</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Posisi
                                    Dilamar</div>
                                <div id="dm_position2" class="text-sm font-semibold text-gray-800">-</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Tanggal
                                    Masuk</div>
                                <div id="dm_date" class="text-sm text-gray-600 flex items-center gap-1.5">
                                    <i class="bi bi-calendar-event text-gray-400"></i>
                                    <span>-</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Email
                                </div>
                                <div class="text-sm text-gray-600 flex items-center gap-1.5">
                                    <i class="bi bi-envelope text-gray-400"></i>
                                    <a href="#" id="dm_email_link" class="hover:text-indigo-600 transition">-</a>
                                </div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">No. HP
                                </div>
                                <div id="dm_phone" class="text-sm text-gray-600 flex items-center gap-1.5">
                                    <i class="bi bi-telephone text-gray-400"></i>
                                    <span>-</span>
                                </div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Tahap Saat
                                    Ini</div>
                                <div id="dm_stage_inline" class="text-sm text-gray-600 flex items-center gap-1.5">
                                    <i class="bi bi-diagram-3 text-gray-400"></i>
                                    <span>-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-5">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center">
                            <i class="bi bi-folder2-open text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Dokumen Pendukung</h3>
                            <p class="text-xs text-gray-500">Berkas yang diunggah oleh pelamar</p>
                        </div>
                    </div>

                    <div id="dokumenGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3"></div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-white p-5">
                    <div class="flex items-start justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <div
                                class="w-8 h-8 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center">
                                <i class="bi bi-clock-history text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-900">Timeline Progress</h3>
                                <p class="text-xs text-gray-500">Riwayat proses rekrutmen kandidat</p>
                            </div>
                        </div>
                        <span
                            class="text-xs font-semibold px-3 py-1 rounded-full bg-gray-100 text-gray-500 border border-gray-200">
                            <i class="bi bi-robot text-xs mr-1"></i>Otomatis
                        </span>
                    </div>

                    <div id="timelineDynamic" class="mt-5"></div>
                </div>

                <div id="modalActionButtonsWrapper"
                    class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center pt-1">
                    <button id="primaryActionBtn" type="button" onclick="handlePrimaryAction()"
                        class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 font-semibold text-white bg-emerald-700 hover:bg-emerald-800 active:scale-[0.98] transition-all shadow-sm">
                        <i id="primaryActionIcon" class="bi bi-send-fill"></i>
                        <span id="primaryActionText">Setujui & Kirim ke HRD</span>
                    </button>

                    <button id="rejectActionBtn" type="button" onclick="openRejectModal()"
                        class="sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 font-semibold border border-red-300 text-red-700 bg-red-50 hover:bg-red-100 active:scale-[0.98] transition-all">
                        <i class="bi bi-x-circle"></i>
                        Tolak Kandidat
                    </button>

                    <button id="interviewActionBtn" type="button" onclick="handleInterviewAction()" style="display:none" 
                    class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 font-semibold text-white bg-indigo-700 hover:bg-indigo-800 active:scale-[0.98] transition-all shadow-sm">
                        <i class="bi bi-calendar2-week"></i>
                        Jadwalkan Interview
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@once
    <script>
        let currentDetailIdx = null;
        let currentDetailAction = null;

        const DOKUMEN_CONFIG = {
            ktp: { label: 'KTP', icon: 'bi-person-vcard', color: 'indigo' },
            kk: { label: 'Kartu Keluarga', icon: 'bi-house-door', color: 'sky' },
            cv: { label: 'CV / Resume', icon: 'bi-file-person', color: 'violet' },
            ijazah: { label: 'Ijazah', icon: 'bi-mortarboard', color: 'emerald' },
            sertifikat: { label: 'Sertifikat', icon: 'bi-patch-check', color: 'amber' },
        };

        const COLOR_MAP = {
            indigo: { bg: 'bg-indigo-50', border: 'border-indigo-200', icon: 'text-indigo-500', badge: 'bg-indigo-600 hover:bg-indigo-700' },
            sky: { bg: 'bg-sky-50', border: 'border-sky-200', icon: 'text-sky-500', badge: 'bg-sky-600 hover:bg-sky-700' },
            violet: { bg: 'bg-violet-50', border: 'border-violet-200', icon: 'text-violet-500', badge: 'bg-violet-600 hover:bg-violet-700' },
            emerald: { bg: 'bg-emerald-50', border: 'border-emerald-200', icon: 'text-emerald-500', badge: 'bg-emerald-600 hover:bg-emerald-700' },
            amber: { bg: 'bg-amber-50', border: 'border-amber-200', icon: 'text-amber-500', badge: 'bg-amber-500 hover:bg-amber-600' },
        };

        function escapeHtml(value) {
            return String(value ?? '-')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function formatDetailDate(value) {
            if (!value) return '-';
            const date = new Date(value);
            if (isNaN(date)) return value;
            return date.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
        }

        function dokumenUrl(path) {
            if (!path) return null;
            if (String(path).startsWith('http://') || String(path).startsWith('https://') || String(path).startsWith('/')) return path;
            return `/storage/${path}`;
        }

        function setText(id, value) {
            const el = document.getElementById(id);
            if (el) el.textContent = value ?? '-';
        }

        function setTextInside(id, value) {
            const el = document.getElementById(id);
            if (!el) return;
            const target = el.querySelector('span');
            if (target) target.textContent = value ?? '-';
            else el.textContent = value ?? '-';
        }

        function renderDokumen(dokumen = {}) {
            const grid = document.getElementById('dokumenGrid');
            if (!grid) return;

            let html = '';

            Object.entries(DOKUMEN_CONFIG).forEach(([key, cfg]) => {
                const url = dokumenUrl(dokumen?.[key]);
                const c = COLOR_MAP[cfg.color];

                if (url) {
                    html += `
                        <div class="flex items-center gap-3 rounded-xl border ${c.border} ${c.bg} p-3.5">
                            <div class="shrink-0 w-10 h-10 rounded-xl bg-white border ${c.border} flex items-center justify-center shadow-sm">
                                <i class="bi ${cfg.icon} ${c.icon} text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-semibold text-gray-700 truncate">${escapeHtml(cfg.label)}</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">Tersedia</div>
                            </div>
                            <a href="${escapeHtml(url)}" target="_blank" rel="noopener noreferrer"
                               class="shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold text-white ${c.badge} transition-all active:scale-95 shadow-sm">
                                <i class="bi bi-download text-[10px]"></i> Unduh
                            </a>
                        </div>
                    `;
                } else {
                    html += `
                        <div class="flex items-center gap-3 rounded-xl border border-gray-200 bg-gray-50/60 p-3.5 opacity-60">
                            <div class="shrink-0 w-10 h-10 rounded-xl bg-white border border-gray-200 flex items-center justify-center">
                                <i class="bi ${cfg.icon} text-gray-300 text-lg"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-xs font-semibold text-gray-500 truncate">${escapeHtml(cfg.label)}</div>
                                <div class="text-[11px] text-gray-400 mt-0.5">Tidak ada</div>
                            </div>
                            <span class="shrink-0 inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-[11px] font-semibold text-gray-400 bg-gray-100 border border-gray-200 cursor-not-allowed">
                                <i class="bi bi-slash-circle text-[10px]"></i> Kosong
                            </span>
                        </div>
                    `;
                }
            });

            grid.innerHTML = html;
        }

        function openDetailKandidat(idx) {
            const data = window.kandidatData?.[idx] ?? kandidatData?.[idx];
            if (!data) return;

            currentDetailIdx = idx;

            setText('dm_name', data.nama ?? '-');
            setText('dm_name2', data.nama ?? '-');
            setText('dm_position', data.posisi_dibutuhkan ?? '-');
            setText('dm_position2', data.posisi_dibutuhkan ?? '-');
            setText('dm_status', data.tahap ?? '-');
            setTextInside('dm_stage_inline', data.tahap ?? '-');
            setTextInside('dm_phone', data.no_hp ?? '-');

            const dateEl = document.getElementById('dm_date');
            if (dateEl) {
                const span = dateEl.querySelector('span');
                if (span) span.textContent = formatDetailDate(data.tgl);
            }

            const emailLink = document.getElementById('dm_email_link');
            if (emailLink) {
                emailLink.textContent = data.email ?? '-';
                emailLink.href = data.email ? `mailto:${data.email}` : '#';
            }

            const stageBadge = document.getElementById('dm_stage_badge');
            if (stageBadge) {
                stageBadge.innerHTML = `<i class="bi bi-list-ol"></i> Tahap ${data.step ?? 1} / 7`;
            }

            renderDokumen(data.dokumen ?? {});

            const timelineEl = document.getElementById('timelineDynamic');
            if (timelineEl) timelineEl.innerHTML = renderTimeline(data.progress ?? []);

            renderModalActions(data);

            document.getElementById('detailModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function renderModalActions(data) {
            const wrapper = document.getElementById('modalActionButtonsWrapper');
            const primaryBtn = document.getElementById('primaryActionBtn');
            const rejectBtn = document.getElementById('rejectActionBtn');
            const primaryText = document.getElementById('primaryActionText');
            const primaryIcon = document.getElementById('primaryActionIcon');

            if (!wrapper || !primaryBtn || !rejectBtn || !primaryText || !primaryIcon) return;

            currentDetailAction = null;
            wrapper.style.display = 'none';
            primaryBtn.style.display = 'none';
            rejectBtn.style.display = 'none';

            if (data.viewOnly) {
                return;
            }

            if (['Dikirim ke HOD', 'Screening HOD'].includes(data.tahap) && !data.isApproved) {
                currentDetailAction = 'setujui';
                wrapper.style.display = 'flex';
                primaryBtn.style.display = 'inline-flex';
                rejectBtn.style.display = 'inline-flex';
                primaryText.textContent = 'Setujui Kandidat';
                primaryIcon.className = 'bi bi-check-lg';
                primaryBtn.className = 'flex-1 inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 font-semibold text-white bg-emerald-700 hover:bg-emerald-800 active:scale-[0.98] transition-all shadow-sm';
                return;
            }

            if (data.tahap === 'Menunggu Interview') {
                currentDetailAction = 'kirim-hrd';
                wrapper.style.display = 'flex';
                primaryBtn.style.display = 'inline-flex';
                rejectBtn.style.display = 'none';
                primaryText.textContent = 'Kirim ke HRD';
                primaryIcon.className = 'bi bi-send-fill';
                primaryBtn.className = 'flex-1 inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 font-semibold text-white bg-[#003366] hover:bg-slate-800 active:scale-[0.98] transition-all shadow-sm';
                return;
            }

            if (data.tahap === 'Interview' && data.canScheduleInterview) {
                currentDetailAction = 'jadwalkan-interview';
                wrapper.style.display = 'flex';
                primaryBtn.style.display = 'inline-flex';
                rejectBtn.style.display = 'none';
                primaryText.textContent = 'Jadwalkan Interview';
                primaryIcon.className = 'bi bi-calendar2-week';
                primaryBtn.className = 'flex-1 inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 font-semibold text-white bg-indigo-700 hover:bg-indigo-800 active:scale-[0.98] transition-all shadow-sm';
                return;
            }
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            if (modal) modal.classList.add('hidden');
            document.body.style.overflow = '';
            currentDetailIdx = null;
            currentDetailAction = null;
        }

        function handlePrimaryAction() {
            if (currentDetailIdx === null || !currentDetailAction) return;

            const data = window.kandidatData?.[currentDetailIdx] ?? kandidatData?.[currentDetailIdx];
            if (!data) return;

            if (currentDetailAction === 'jadwalkan-interview') {
                closeDetailModal();
                if (typeof openInterviewModal === 'function') {
                    openInterviewModal(data);
                }
                return;
            }


            closeDetailModal();

            if (typeof konfirmasiAksi === 'function') {
                konfirmasiAksi(currentDetailAction, data.id, data.nama);
            }
        }

        function openRejectModal() {
            if (currentDetailIdx === null) return;

            const data = window.kandidatData?.[currentDetailIdx] ?? kandidatData?.[currentDetailIdx];
            if (!data) return;

            closeDetailModal();

            if (typeof konfirmasiAksi === 'function') {
                konfirmasiAksi('tolak', data.id, data.nama);
            }
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeDetailModal();
        });
    </script>
@endonce