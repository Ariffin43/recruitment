const currentRole = "HRD"; // dummy

const stages = [
  { id: "submitted", label: "Baru" },
  { id: "hrd_screening", label: "Screening HRD" },
  { id: "hrd_to_hod", label: "Dikirim ke HOD" },
  { id: "hod_screening", label: "Screening HOD" },
  { id: "hod_to_hrd", label: "HOD kirim ke HRD" },
  { id: "hrd_interview", label: "Penjadwalan interview" },
  { id: "finished", label: "Selesai" },
];

const dashboardToStageMap = {
  'Baru': 0,
  'Screening HRD': 1,
  'Dikirim ke HOD': 2,
  'Screening HOD': 3,
  'HOD kirim ke HRD': 4,
  'Interview': 5,
  'Selesai': 6,
  'Ditolak': 6
};

let currentStageIndex = 0;
let currentApplicant = {};

// --- Helpers ---
function lockBodyScroll() {
  document.body.classList.add("overflow-hidden");
}
function unlockBodyScroll() {
  document.body.classList.remove("overflow-hidden");
}

// --- Role Logic ---
function canActByRole(role, stageId) {
  if (role === "HRD") {
    return ["submitted", "hrd_screening", "hod_to_hrd", "hrd_interview"].includes(stageId);
  }
  if (role === "HOD") {
    return ["hrd_to_hod", "hod_screening"].includes(stageId);
  }
  return false;
}

function getPrimaryActionForStage(stageId, role) {
  if (!canActByRole(role, stageId)) return null;

  if (role === "HRD") {
    switch (stageId) {
      case "submitted":
        return { text: "Mulai screening HRD", icon: "bi-send", nextIndex: 1 };
      case "hrd_screening":
        return { text: "Kirim ke HOD", icon: "bi-send", nextIndex: 2 };
      case "hod_to_hrd":
        return { text: "Jadwalkan interview", icon: "bi-calendar-event", nextIndex: 5 };
      case "hrd_interview":
        if (currentApplicant.isJadwalDibuat) {
          return { text: "Selesaikan / Terima", icon: "bi-check2-circle", nextIndex: 6, isJadwal: false };
        } else {
          return { text: "Buat Jadwal", icon: "bi-calendar-event", isJadwal: true };
        }
    }
  }

  if (role === "HOD") {
    switch (stageId) {
      case "hrd_to_hod":
        return { text: "Mulai screening HOD", icon: "bi-send", nextIndex: 3 };
      case "hod_screening":
        return { text: "Kirim ke HRD", icon: "bi-send", nextIndex: 4 };
    }
  }

  return null;
}

// --- Renderers ---
function renderPrimaryAction() {
  const stageId = stages[currentStageIndex]?.id;

  const actionBtn = document.getElementById("primaryActionBtn");
  const rejectBtn = document.getElementById("rejectActionBtn");
  const wrapper = document.getElementById("modalActionButtonsWrapper");

  const action = getPrimaryActionForStage(stageId, currentRole);

  if (!action) {
    if (wrapper) wrapper.style.display = 'none';
    return;
  }

  if (wrapper) wrapper.style.display = 'flex';
  if (actionBtn) actionBtn.classList.remove("hidden");
  if (rejectBtn) rejectBtn.classList.remove("hidden");

  const actionText = document.getElementById("primaryActionText");
  if (actionText) actionText.textContent = action.text;

  const icon = actionBtn?.querySelector("i");
  if (icon) icon.className = `bi ${action.icon}`;
}

function renderTimeline() {
  const wrap = document.getElementById("timelineWrap");
  if (!wrap) return;

  wrap.innerHTML = "";

  stages.forEach((s, idx) => {
    const isDone = idx < currentStageIndex;
    const isActive = idx === currentStageIndex;

    const bulletClass = isDone || isActive ? "bg-emerald-600 text-white border-emerald-600" : "bg-white text-gray-400 border-gray-200";
    const textClass = isActive ? "text-gray-900 font-semibold" : (isDone ? "text-gray-700" : "text-gray-400");
    const lineClass = isDone ? "bg-emerald-200" : "bg-gray-200";

    const el = document.createElement("div");
    el.className = "relative pl-14";

    el.innerHTML = `
      <div class="absolute left-[18px] top-10 bottom-0 w-[2px] ${idx === stages.length - 1 ? "hidden" : ""} ${lineClass}"></div>
      <div class="absolute left-0 top-0 w-10 h-10 rounded-full border flex items-center justify-center text-sm font-bold ${bulletClass}">${idx + 1}</div>
      <div class="pt-2">
        <div class="${textClass}">${s.label}</div>
        ${isActive ? `<span class="inline-flex mt-2 text-xs font-semibold px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">Tahap sekarang</span>` : ""}
      </div>
    `;

    wrap.appendChild(el);
  });

  const badge = document.getElementById("dm_stage_badge");
  if (badge) badge.textContent = `Tahap ${currentStageIndex + 1} / 7`;

  const dmStatus = document.getElementById("dm_status");
  if (dmStatus) dmStatus.textContent = stages[currentStageIndex]?.label ?? "Baru";

  renderPrimaryAction();
}

function syncTableGlobal(applicantName, status, styleClasses) {
  if (typeof APPLICANTS !== 'undefined') {
    const aIdx = APPLICANTS.findIndex(x => x.name === applicantName);
    if (aIdx !== -1) {
      APPLICANTS[aIdx].status = status;
      if (styleClasses) APPLICANTS[aIdx].style = styleClasses;
      if (window.renderTable) window.renderTable();
    }
  }
}

// 1. Detail Modal
window.openDetailModal = function (applicant) {
  currentApplicant = { ...currentApplicant, ...applicant };

  if (currentApplicant.status) {
    currentStageIndex = dashboardToStageMap[currentApplicant.status] ?? 0;
  }

  document.getElementById("dm_name").textContent = currentApplicant.name || '-';
  document.getElementById("dm_name2").textContent = currentApplicant.name || '-';
  document.getElementById("dm_position").textContent = currentApplicant.position || '-';
  document.getElementById("dm_position2").textContent = currentApplicant.position || '-';
  document.getElementById("dm_email").textContent = currentApplicant.email || '-';
  document.getElementById("dm_source").textContent = currentApplicant.source || '-';
  document.getElementById("dm_date").textContent = currentApplicant.date || '-';

  document.getElementById("detailModal").classList.remove("hidden");
  lockBodyScroll();
  renderTimeline();
};

window.closeDetailModal = function () {
  document.getElementById("detailModal").classList.add("hidden");
  window.closeRejectModal();
  unlockBodyScroll();
};

window.handlePrimaryAction = function () {
  const stageId = stages[currentStageIndex]?.id;
  const action = getPrimaryActionForStage(stageId, currentRole);
  if (!action) return;

  if (action.isJadwal) {
    window.closeDetailModal();
    if (window.buatJadwalDashboard) window.buatJadwalDashboard();
    return;
  }

  if (action.nextIndex === 6) {
    currentApplicant.status = 'Selesai';
    syncTableGlobal(currentApplicant.name, 'Selesai', 'bg-emerald-50 text-emerald-700 border-emerald-200');
    Swal.fire({icon: 'success', title: 'Tahap Selesai', text: 'Kandidat diterima.', confirmButtonColor: '#145D9E', timer: 2000, showConfirmButton: false});
  }

  currentStageIndex = action.nextIndex;
  renderTimeline();
};

// 2. Reject Modal
window.openRejectModal = function () {
  const modal = document.getElementById("rejectModal");
  if (modal) modal.classList.remove("hidden");

  document.getElementById("rm_name").textContent = currentApplicant.name || '-';
  document.getElementById("rm_email").textContent = currentApplicant.email || '-';
  document.getElementById("rm_wa").textContent = currentApplicant.wa || "(+62**********)";

  updatePreview();
};

window.closeRejectModal = function () {
  const modal = document.getElementById("rejectModal");
  if (modal) modal.classList.add("hidden");
};

function updatePreview() {
  const reason = document.getElementById("rm_reason")?.value?.trim();
  const preview = document.getElementById("rm_preview");
  if (!preview) return;

  const msg = `Halo ${currentApplicant.name},

Terima kasih sudah melamar untuk posisi ${currentApplicant.position}. Setelah dipertimbangkan, saat ini lamaran kamu belum bisa kami lanjutkan.
${reason ? "\\nAlasan: " + reason + "\\n" : ""}
Semoga kita bisa bekerja sama di kesempatan lain`;

  preview.textContent = msg;
}

document.addEventListener("input", (e) => {
  if (e.target && e.target.id === "rm_reason") updatePreview();
});

window.sendWhatsApp = function () {
  const preview = document.getElementById("rm_preview")?.textContent ?? "";
  const phone = (currentApplicant.wa ?? "+62").replace(/[^\d+]/g, "").replace("+", "");
  window.open(`https://wa.me/${phone}?text=${encodeURIComponent(preview)}`, "_blank");
};

window.submitReject = function () {
  window.closeRejectModal();
  window.closeDetailModal();

  syncTableGlobal(currentApplicant.name, 'Ditolak', 'bg-red-50 text-red-600 border-red-200');
  Swal.fire({icon: 'success', title: 'Ditolak', html: `<b>${currentApplicant.name}</b> ditolak.`, confirmButtonColor: '#145D9E', timer: 2000, showConfirmButton: false});
};

// 3. Global Escape Key Handler
document.addEventListener("keydown", (e) => {
  if (e.key !== "Escape") return;

  const rejectOpen = !document.getElementById("rejectModal")?.classList.contains("hidden");
  const detailOpen = !document.getElementById("detailModal")?.classList.contains("hidden");
  const jadwalOpen = !document.getElementById("jadwalModal")?.classList.contains("hidden");

  if (rejectOpen) window.closeRejectModal();
  else if (jadwalOpen) window.closeJadwalModal();
  else if (detailOpen) window.closeDetailModal();
});

// ==========================
// Jadwal Modal Logic untuk Dashboard
// ==========================
window.buatJadwalDashboard = function() {
    document.getElementById('jm_name').textContent = currentApplicant.name;
    document.getElementById('jadwalModal').classList.remove('hidden');
    lockBodyScroll();
};

window.closeJadwalModal = function () {
    document.getElementById('jadwalModal').classList.add('hidden');
    // Ensure inputs are reset
    document.querySelectorAll('#jadwalModal input, #jadwalModal textarea').forEach(el => el.value = '');
    // Unlock and revert to detail modal if appropriate
    unlockBodyScroll();
};

window.switchJadwalTab = function (tab) {
    const btnOff = document.getElementById('jm_tab_btn_offline');
    const btnOn = document.getElementById('jm_tab_btn_online');
    const formOff = document.getElementById('jm_form_offline');
    const formOn = document.getElementById('jm_form_online');

    if (tab === 'offline') {
        btnOff.className = "px-5 py-1.5 rounded-full text-sm font-semibold text-white bg-[#145D9E] shadow-sm transition";
        btnOn.className = "px-5 py-1.5 rounded-full text-sm font-medium text-gray-500 hover:text-gray-700 transition cursor-pointer";
        formOff.classList.remove('hidden');
        formOn.classList.add('hidden');
    } else {
        btnOn.className = "px-5 py-1.5 rounded-full text-sm font-semibold text-white bg-[#145D9E] shadow-sm transition";
        btnOff.className = "px-5 py-1.5 rounded-full text-sm font-medium text-gray-500 hover:text-gray-700 transition cursor-pointer";
        formOn.classList.remove('hidden');
        formOff.classList.add('hidden');
    }
};

window.submitJadwal = function (method) {
    // Collect specific datetimes to prove functionality
    let isOnline = !document.getElementById('jm_form_online').classList.contains('hidden');
    let tgl = isOnline ? document.getElementById('jm_on_tgl').value : document.getElementById('jm_off_tgl').value;
    let waktu = isOnline ? document.getElementById('jm_on_waktu').value : document.getElementById('jm_off_waktu').value;
    
    if (!tgl || !waktu) {
        Swal.fire({icon: 'warning', title: 'Data belum lengkap', text: 'Tanggal dan Waktu wajib diisi.', confirmButtonColor: '#145D9E'});
        return;
    }

    let msg = '';
    if (isOnline) {
        msg = `Undangan Interview Online via ${document.getElementById('jm_on_platform').value}\nTgl: ${tgl}\nWaktu: ${waktu}\nLink: ${document.getElementById('jm_on_link').value}`;
    } else {
        msg = `Undangan Interview Onsite\nTgl: ${tgl}\nWaktu: ${waktu}\nLokasi: ${document.getElementById('jm_off_lokasi').value}`;
    }

    if (method === 'wa') {
        const phone = (currentApplicant.wa ?? "+62").replace(/[^\d+]/g, "").replace("+", "");
        window.open(`https://wa.me/${phone}?text=${encodeURIComponent(msg)}`, '_blank');
        Swal.fire({icon: 'success', title: 'Terkirim', text: 'Undangan diteruskan ke WhatsApp', confirmButtonColor: '#145D9E', timer: 2000, showConfirmButton: false});
    } else if (method === 'email') {
        window.open(`mailto:${currentApplicant.email}?subject=Undangan Interview&body=${encodeURIComponent(msg)}`, '_blank');
        Swal.fire({icon: 'success', title: 'Terkirim', text: 'Undangan diteruskan ke Email', confirmButtonColor: '#145D9E', timer: 2000, showConfirmButton: false});
    }
    
    // Mark as scheduled in dummy state
    currentApplicant.isJadwalDibuat = true;
    if (typeof APPLICANTS !== 'undefined') {
        const aIdx = APPLICANTS.findIndex(x => x.name === currentApplicant.name);
        if (aIdx !== -1) {
            APPLICANTS[aIdx].isJadwalDibuat = true;
            if (window.renderTable) window.renderTable();
        }
    }
    
    window.closeJadwalModal();

    window.openDetailModal(currentApplicant);
};