// ============================================================
// MyBike Pro — Shared JavaScript Utilities
// Template documentation extracted from blade views
// ============================================================

// ─── Toast Notification ──────────────────────────────────────
function showToast(message, type = "info", duration = 3000) {
    const toast = document.createElement("div");
    toast.style.cssText = `
        position: fixed; bottom: 2rem; right: 2rem; z-index: 99999;
        background: ${type === "success" ? "#22c55e" : type === "error" ? "#ef4444" : "#38bdf8"};
        color: #0f172a; padding: 0.85rem 1.5rem; border-radius: 12px;
        font-weight: 700; font-size: 0.9rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        transform: translateY(20px); opacity: 0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    requestAnimationFrame(() => {
        toast.style.transform = "translateY(0)";
        toast.style.opacity = "1";
    });
    setTimeout(() => {
        toast.style.opacity = "0";
        toast.style.transform = "translateY(20px)";
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// ─── Modal ────────────────────────────────────────────────────
function openModal(modalId) {
    const m = document.getElementById(modalId);
    if (m) {
        m.style.display = "flex";
        document.body.style.overflow = "hidden";
    }
}
function closeModal(modalId) {
    const m = document.getElementById(modalId);
    if (m) {
        m.style.display = "none";
        document.body.style.overflow = "";
    }
}

// Close modal on overlay click
document.addEventListener("click", (e) => {
    if (e.target.classList.contains("modal-overlay")) {
        e.target.style.display = "none";
        document.body.style.overflow = "";
    }
});

// ─── Active Nav ──────────────────────────────────────────────
function setActiveNav() {
    const path = window.location.pathname;
    document.querySelectorAll(".nav-link, .nav-item").forEach((link) => {
        const href = link.getAttribute("href") || "";
        link.classList.toggle("active", href !== "#" && path.startsWith(href));
    });
}
setActiveNav();

// ─── Dropdown (touch support) ─────────────────────────────────
document.querySelectorAll(".dropdown").forEach((dropdown) => {
    dropdown.addEventListener("click", () => {
        const content = dropdown.querySelector(".dropdown-content");
        if (content) {
            const open = content.style.opacity === "1";
            content.style.opacity = open ? "0" : "1";
            content.style.visibility = open ? "hidden" : "visible";
        }
    });
});

// ─── Smooth scroll for anchor links ──────────────────────────
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
        const target = document.querySelector(this.getAttribute("href"));
        if (target) {
            e.preventDefault();
            target.scrollIntoView({ behavior: "smooth" });
        }
    });
});

// ─── Navbar scroll effect ─────────────────────────────────────
const topNav = document.querySelector(".top-nav, nav");
if (topNav) {
    window.addEventListener("scroll", () => {
        topNav.style.background =
            window.scrollY > 50
                ? "rgba(15, 23, 42, 0.98)"
                : "rgba(15, 23, 42, 0.8)";
    });
}

// ─── Table row hover ─────────────────────────────────────────
document.querySelectorAll("table tbody tr").forEach((row) => {
    row.addEventListener(
        "mouseenter",
        () => (row.style.background = "rgba(255,255,255,0.02)"),
    );
    row.addEventListener("mouseleave", () => (row.style.background = ""));
});

// ─── Formatters ──────────────────────────────────────────────
function formatNumber(n) {
    return new Intl.NumberFormat("id-ID").format(n);
}
function formatTime(seconds) {
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    const s = Math.floor(seconds % 60);
    return [h, m, s].map((v) => v.toString().padStart(2, "0")).join(":");
}

// ─── Speed / Unit conversions ─────────────────────────────────
function mpsToKmh(mps) {
    return (mps * 3.6).toFixed(1);
}
function kmhToMps(kmh) {
    return kmh / 3.6;
}
function wattsToKcal(watts, seconds) {
    return (watts * seconds * 0.239006) / 1000;
}

// ─── Color palette for chart/bike colors ─────────────────────
const BIKE_COLORS = [
    "#38bdf8",
    "#818cf8",
    "#22c55e",
    "#f59e0b",
    "#ef4444",
    "#ec4899",
    "#14b8a6",
    "#a78bfa",
];
function getBikeColor(index) {
    return BIKE_COLORS[index % BIKE_COLORS.length];
}
