@props(['progress' => []])

@once
<script>
    function timelineEscapeHtml(value) {
        return String(value ?? '-')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function renderTimeline(progress = []) {
        if (!Array.isArray(progress) || progress.length === 0) {
            return `
                <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-gray-300 bg-gray-50 py-12 px-5 text-center">
                    <div class="w-16 h-16 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-400 text-2xl shadow-sm">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <div class="mt-4 text-sm font-semibold text-gray-700">Belum ada progress</div>
                    <div class="text-xs text-gray-500 mt-1">Timeline rekrutmen akan muncul di sini.</div>
                </div>
            `;
        }

        let html = '<div class="relative">';

        progress.forEach((item, index) => {
            const status = item.status ?? 'menunggu';
            const isDone = status === 'selesai';
            const isCurrent = status === 'proses';
            const isRejected = String(item.tahap ?? '').toLowerCase().includes('ditolak');
            const isLast = index === progress.length - 1;

            const circleClass = isRejected
                ? 'bg-red-500 shadow-red-200'
                : (isDone
                    ? 'bg-indigo-600 shadow-indigo-200'
                    : (isCurrent ? 'bg-amber-500 shadow-amber-200' : 'bg-gray-300'));

            const circleIcon = isRejected
                ? '<i class="bi bi-x-lg text-sm"></i>'
                : (isDone
                    ? '<i class="bi bi-check-lg text-sm"></i>'
                    : (isCurrent
                        ? '<i class="bi bi-hourglass-split text-sm"></i>'
                        : `<span class="text-xs font-bold">${index + 1}</span>`));

            const badgeHtml = isRejected
                ? `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-red-50 text-red-700 border border-red-200">
                       <i class="bi bi-x-circle-fill text-[10px]"></i> Ditolak
                   </span>`
                : (isDone
                    ? `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                           <i class="bi bi-check-circle-fill text-[10px]"></i> Selesai
                       </span>`
                    : (isCurrent
                        ? `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                               <i class="bi bi-hourglass-split text-[10px]"></i> Sedang Diproses
                           </span>`
                        : `<span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-500 border border-gray-200">
                               <i class="bi bi-clock text-[10px]"></i> Menunggu
                           </span>`));

            const catatanHtml = item.catatan
                ? `<p class="text-sm text-gray-500 mt-1 leading-relaxed">${timelineEscapeHtml(item.catatan)}</p>`
                : '';

            const tanggalHtml = item.tanggal
                ? `<div class="flex items-center gap-1.5 text-xs text-gray-400 mt-2">
                       <i class="bi bi-clock"></i>
                       <span>${timelineEscapeHtml(item.tanggal)}</span>
                   </div>`
                : '';

            const lineHtml = !isLast
                ? `<div class="absolute left-5 top-10 w-[2px] bg-gray-200" style="height: calc(100% - 2.5rem);"></div>`
                : '';

            html += `
                <div class="relative flex gap-4 ${!isLast ? 'pb-6' : ''}">
                    ${lineHtml}
                    <div class="shrink-0 w-10 h-10 rounded-full flex items-center justify-center text-white shadow-md ${circleClass} z-10">
                        ${circleIcon}
                    </div>
                    <div class="flex-1 min-w-0 pt-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="font-semibold text-gray-900 text-sm">${timelineEscapeHtml(item.tahap ?? '-')}</span>
                            ${badgeHtml}
                        </div>
                        ${catatanHtml}
                        ${tanggalHtml}
                    </div>
                </div>
            `;
        });

        html += '</div>';

        return html;
    }
</script>
@endonce