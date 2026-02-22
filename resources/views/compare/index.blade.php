<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bike Geometry Comparison - MyBike Pro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0d1929;
            --surface: #111f35;
            --card: #162032;
            --border: rgba(56, 189, 248, 0.08);
            --accent: #38bdf8;
            --accent2: #818cf8;
            --text: #e2e8f0;
            --muted: #64748b;
            --highlight: rgba(56, 189, 248, 0.12);
            --canvas-bg: #1a3354;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: var(--bg); color: var(--text); font-family: 'Inter', sans-serif; min-height: 100vh; }

        /* ─── Header ─────────────────────────────── */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 2rem;
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 50;
        }
        .page-header .brand { display: flex; align-items: center; gap: 0.75rem; }
        .page-header .brand svg { opacity: 0.8; }
        .page-header .title { font-size: 0.85rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--accent); }
        .nav-links { display: flex; gap: 1.5rem; align-items: center; }
        .nav-links a { color: var(--muted); text-decoration: none; font-size: 0.85rem; font-weight: 500; transition: color 0.2s; }
        .nav-links a:hover { color: var(--text); }
        .nav-links .btn-home { background: var(--highlight); color: var(--accent); padding: 0.4rem 1rem; border-radius: 6px; border: 1px solid var(--border); }

        /* ─── Layout ──────────────────────────────── */
        .layout { display: grid; grid-template-columns: 280px 1fr; min-height: calc(100vh - 57px); }

        /* ─── Sidebar ─────────────────────────────── */
        .sidebar {
            background: var(--surface);
            border-right: 1px solid var(--border);
            padding: 1.5rem;
            overflow-y: auto;
        }
        .sidebar-title { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--muted); margin-bottom: 1rem; }
        .bike-list { display: flex; flex-direction: column; gap: 0.4rem; }
        .bike-item {
            padding: 0.75rem 1rem;
            border-radius: 10px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.15s;
            display: flex; align-items: center; justify-content: space-between;
        }
        .bike-item:hover { background: var(--highlight); border-color: var(--border); }
        .bike-item.selected { background: var(--highlight); border-color: var(--accent); }
        .bike-item .bike-name { font-size: 0.85rem; font-weight: 600; }
        .bike-item .bike-cat { font-size: 0.72rem; color: var(--muted); margin-top: 2px; }
        .bike-swatch { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; display: none; }
        .bike-item.selected .bike-swatch { display: block; }

        .sidebar-info { margin-top: 1.5rem; padding: 1rem; background: rgba(56,189,248,0.04); border-radius: 10px; border: 1px solid var(--border); font-size: 0.75rem; color: var(--muted); line-height: 1.6; }

        /* ─── Main area ───────────────────────────── */
        .main { display: flex; flex-direction: column; overflow: hidden; }

        /* ─── Canvas card ─────────────────────────── */
        .canvas-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1rem 1.5rem;
            background: var(--canvas-bg);
            border-bottom: 2px solid rgba(56,189,248,0.15);
        }
        .canvas-header .canvas-title { font-size: 0.75rem; font-weight: 700; letter-spacing: 0.15em; text-transform: uppercase; color: #93c5fd; }
        .canvas-legend { display: flex; gap: 1rem; align-items: center; }
        .legend-item { display: flex; align-items: center; gap: 0.4rem; font-size: 0.75rem; color: #93c5fd; }
        .legend-dot { width: 10px; height: 10px; border-radius: 50%; }

        .canvas-wrap {
            background: var(--canvas-bg);
            flex-shrink: 0;
            width: 100%;
        }
        canvas { display: block; width: 100%; aspect-ratio: 1400 / 620; height: auto; }

        .empty-canvas {
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            height: 100%; gap: 1rem; opacity: 0.4; text-align: center;
        }
        .empty-canvas p { font-size: 0.9rem; color: #93c5fd; }

        /* ─── Comparison table ────────────────────── */
        .table-section { flex: 1; overflow-y: auto; padding: 2rem; }
        .table-section h2 { font-size: 0.7rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--muted); margin-bottom: 1.5rem; }

        /* ─── Bike selector cards ─────────────────── */
        .selector-bar {
            display: flex; gap: 1rem; padding: 1.25rem 1.5rem;
            background: var(--surface); border-bottom: 1px solid var(--border);
            overflow-x: auto; flex-shrink: 0;
        }
        .selector-bar::-webkit-scrollbar { height: 4px; }
        .selector-bar::-webkit-scrollbar-track { background: transparent; }
        .selector-bar::-webkit-scrollbar-thumb { background: var(--border); border-radius: 4px; }
        .bike-card {
            min-width: 200px; max-width: 240px;
            background: var(--card); border-radius: 14px;
            border: 1px solid var(--border); overflow: hidden;
            flex-shrink: 0; position: relative;
        }
        .bike-card .card-header {
            padding: 0.7rem 1rem 0.5rem;
            border-bottom: 3px solid;
        }
        .bike-card .brand-label { font-size: 0.65rem; color: var(--muted); text-transform: uppercase; letter-spacing: 0.08em; }
        .bike-card .bike-name-label { font-size: 0.95rem; font-weight: 700; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .bike-card .card-body { padding: 0.75rem 1rem; font-size: 0.78rem; color: var(--muted); line-height: 1.7; }
        .bike-card .card-body strong { color: var(--text); }
        .bike-card .rm-btn {
            position: absolute; top: 6px; right: 8px;
            background: none; border: none; color: var(--muted); cursor: pointer;
            font-size: 1.1rem; line-height: 1; padding: 2px 4px; border-radius: 4px;
        }
        .bike-card .rm-btn:hover { background: rgba(255,255,255,0.08); color: var(--text); }
        .add-card-btn {
            min-width: 160px; height: auto;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 0.4rem; padding: 1rem;
            background: transparent; border: 2px dashed var(--border);
            border-radius: 14px; color: var(--muted); cursor: pointer;
            transition: all 0.2s; font-size: 0.8rem; flex-shrink: 0;
        }
        .add-card-btn:hover { border-color: var(--accent); color: var(--accent); }
        .add-card-btn svg { opacity: 0.6; }

        /* ─── Insights ────────────────────────────── */
        .insights-section {
            padding: 1.25rem 1.5rem;
            background: var(--bg); border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }
        .insights-title { font-size: 0.65rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: var(--muted); margin-bottom: 1rem; }
        .insight-cards { display: flex; gap: 1rem; flex-wrap: wrap; }
        .insight-card {
            flex: 1; min-width: 200px;
            background: var(--card); border: 1px solid var(--border);
            border-radius: 12px; padding: 1rem 1.25rem;
        }
        .insight-card .ic-label { font-size: 0.72rem; font-weight: 700; color: var(--text); margin-bottom: 0.3rem; }
        .insight-card .ic-sub { font-size: 0.72rem; color: var(--muted); }
        .insight-bar { height: 5px; border-radius: 3px; margin: 0.6rem 0; overflow: hidden; display: flex; gap: 2px; }
        .insight-bar-seg { height: 100%; border-radius: 3px; }
        .insight-badge {
            display: inline-block; font-size: 0.68rem; font-weight: 700;
            padding: 2px 8px; border-radius: 20px;
        }
        .badge-aero    { background: rgba(56,189,248,0.15); color: #38bdf8; }
        .badge-comfort { background: rgba(52,211,153,0.15); color: #34d399; }
        .badge-balance { background: rgba(251,191,36,0.15); color: #fbbf24; }
        .badge-upright { background: rgba(129,140,248,0.15); color: #818cf8; }


        .section-label {
            font-size: 0.65rem; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase;
            color: var(--muted); padding: 0.85rem 1rem; background: rgba(255,255,255,0.02);
            border-bottom: 1px solid var(--border);
        }

        table { width: 100%; border-collapse: collapse; }
        thead { position: sticky; top: 0; background: var(--card); z-index: 10; }
        th { padding: 1rem 1.25rem; text-align: left; font-size: 0.72rem; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--muted); border-bottom: 2px solid var(--border); }
        th.bike-col { font-weight: 800; font-size: 0.8rem; text-transform: none; letter-spacing: 0; }
        td { padding: 0.8rem 1.25rem; border-bottom: 1px solid var(--border); font-size: 0.9rem; vertical-align: middle; }
        tr:hover td { background: rgba(255,255,255,0.015); }
        .row-label { font-weight: 500; color: var(--muted); font-size: 0.82rem; width: 220px; }
        .mono { font-family: 'JetBrains Mono', monospace; font-weight: 600; font-size: 0.88rem; }
        .diff-pos { color: #4ade80; }
        .diff-neg { color: #f87171; }
        .diff-neutral { color: var(--accent); }

        /* section group row */
        .section-row td { background: rgba(255,255,255,0.02); padding: 0.5rem 1.25rem; border-top: 1px solid var(--border); }
        .section-row td span { font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; color: var(--muted); }
    </style>
</head>
<body>

<header class="page-header">
    <div class="brand">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18.5" cy="17.5" r="3.5"/><circle cx="5.5" cy="17.5" r="3.5"/><path d="M15 6H9.5L5.5 12h13z"/><path d="M8 6l7 11.5"/></svg>
        <span class="title">Bike-On-Bike Geometry</span>
    </div>
    <nav class="nav-links">
        <a href="{{ route('home') }}" class="btn-home">← Home</a>
        <a href="{{ route('simulation') }}">Simulation</a>
    </nav>
</header>

<div class="layout">
    <!-- ── Sidebar ── -->
    <aside class="sidebar">
        <div class="sidebar-title">Bicycle Library</div>
        <div class="bike-list">
            @foreach($bicycles as $bike)
            <div class="bike-item" id="bike-item-{{ $bike->id }}" onclick="toggleBike({{ $bike->id }})">
                <div>
                    <div class="bike-name">{{ $bike->name }}</div>
                    <div class="bike-cat">{{ $bike->category->name ?? 'Road' }} · {{ $bike->frame_material ?? '–' }}</div>
                </div>
                <div class="bike-swatch" id="swatch-{{ $bike->id }}"></div>
            </div>
            @endforeach
        </div>
        <div class="sidebar-info">
            Select up to <strong>5 bikes</strong> to compare side-by-side. The canvas overlay shows how each frame relates in absolute size.
        </div>
    </aside>

    <!-- ── Main ── -->
    <main class="main">
        <!-- Canvas -->
        <div class="canvas-header">
            <span class="canvas-title">Geometry Overlay</span>
            <div class="canvas-legend" id="legend"></div>
        </div>
        <div class="canvas-wrap">
            <canvas id="geometryCanvas" width="1400" height="800"></canvas>
        </div>
        <div id="empty-canvas-msg" class="empty-canvas" style="display:none; position:absolute;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#93c5fd" stroke-width="1.5"><circle cx="18.5" cy="17.5" r="3.5"/><circle cx="5.5" cy="17.5" r="3.5"/><path d="M15 6H9.5L5.5 12h13z"/></svg>
            <p>Select bikes from the sidebar to visualise</p>
        </div>

        <!-- Bike selector cards below canvas -->
        <div class="selector-bar" id="selector-bar">
            <div id="selected-cards"></div>
            <button class="add-card-btn" onclick="openBikeModal()">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                Add Bike
            </button>
        </div>

        <!-- Insights -->
        <div class="insights-section" id="insights-section" style="display:none">
            <div class="insights-title">⚡ Insights</div>
            <div class="insight-cards" id="insight-cards"></div>
        </div>

        <!-- Data Table -->
        <div class="table-section">
            <table id="comparison-table">
                <thead>
                    <tr id="table-header-row">
                        <th class="row-label">Spec / Geometry</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    <tr><td colspan="10" style="color:var(--muted);text-align:center;padding:3rem;font-size:0.85rem;">Select bikes to compare specifications</td></tr>
                </tbody>
            </table>
        </div>
    </main>
</div>

<script>
const bicycles = @json($bicycles);
let selectedBikes = [];
const PALETTE = ['#38bdf8','#818cf8','#f472b6','#34d399','#fbbf24'];

// ─────────────────────────────────────────────────────────────────────
// Toggle selection
// ─────────────────────────────────────────────────────────────────────
function toggleBike(id) {
    const idx = selectedBikes.indexOf(id);
    const item = document.getElementById(`bike-item-${id}`);
    const swatch = document.getElementById(`swatch-${id}`);

    if (idx === -1) {
        if (selectedBikes.length >= 5) { alert('Max 5 bikes at once.'); return; }
        selectedBikes.push(id);
        const color = PALETTE[selectedBikes.length - 1];
        item.classList.add('selected');
        swatch.style.background = color;
    } else {
        selectedBikes.splice(idx, 1);
        item.classList.remove('selected');
        swatch.style.background = '';
        selectedBikes.forEach((bid, i) => {
            document.getElementById(`swatch-${bid}`).style.background = PALETTE[i];
        });
    }
    renderAll();
}

// ── Open a quick modal/dropdown to pick a bike to add ──
function openBikeModal() {
    // Build a floating picker anchored to the button
    const existing = document.getElementById('bike-picker-popup');
    if (existing) { existing.remove(); return; }

    const popup = document.createElement('div');
    popup.id = 'bike-picker-popup';
    popup.style.cssText = `
        position:fixed; bottom:0; left:0; right:0; z-index:200;
        background:var(--surface); border-top:1px solid var(--border);
        padding:1rem 1.5rem; display:flex; flex-wrap:wrap; gap:0.5rem;
        max-height:40vh; overflow-y:auto;
    `;

    const title = document.createElement('div');
    title.style.cssText = 'width:100%;font-size:0.65rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:var(--muted);margin-bottom:.5rem;';
    title.textContent = '⚡ Select a bike to add';
    popup.appendChild(title);

    bicycles.forEach(b => {
        const btn = document.createElement('button');
        const isSelected = selectedBikes.includes(b.id);
        btn.style.cssText = `
            padding:.45rem .9rem; border-radius:8px; border:1px solid ${isSelected ? PALETTE[selectedBikes.indexOf(b.id)] : 'var(--border)'};
            background:${isSelected ? 'rgba(56,189,248,0.1)' : 'var(--card)'}; color:var(--text);
            cursor:pointer; font-size:.8rem; font-weight:600; transition:all .15s;
        `;
        btn.textContent = b.name;
        btn.onclick = () => { toggleBike(b.id); popup.remove(); };
        popup.appendChild(btn);
    });

    const closeBtn = document.createElement('button');
    closeBtn.textContent = '✕ Close';
    closeBtn.style.cssText = 'padding:.45rem .9rem;border-radius:8px;border:1px solid var(--border);background:transparent;color:var(--muted);cursor:pointer;font-size:.8rem;font-weight:600;';
    closeBtn.onclick = () => popup.remove();
    popup.appendChild(closeBtn);

    document.body.appendChild(popup);
}

// ── Render selected bike cards below canvas ──
function renderSelectedCards() {
    const container = document.getElementById('selected-cards');
    container.innerHTML = '';
    container.style.display = 'flex';
    container.style.gap = '1rem';

    selectedBikes.forEach((id, i) => {
        const bike = bicycles.find(b => b.id === id);
        const color = PALETTE[i];
        const n = v => v != null ? parseFloat(v).toFixed(1) : '–';

        const card = document.createElement('div');
        card.className = 'bike-card';
        card.innerHTML = `
            <div class="card-header" style="border-color:${color}">
                <div class="brand-label">Polygon Bikes</div>
                <div class="bike-name-label" style="color:${color}">${bike.name}</div>
            </div>
            <div class="card-body">
                <strong>${bike.category?.name ?? bike.type ?? '–'}</strong> · ${bike.frame_material ?? '–'}<br>
                Stack <strong>${n(bike.stack)}</strong> / Reach <strong>${n(bike.reach)}</strong><br>
                Weight <strong>${n(bike.weight_kg)} kg</strong>
            </div>
            <button class="rm-btn" onclick="toggleBike(${id})" title="Remove">✕</button>
        `;
        container.appendChild(card);
    });
}

// ── Render insights section ──
function renderInsights() {
    const section = document.getElementById('insights-section');
    const container = document.getElementById('insight-cards');

    if (selectedBikes.length < 1) { section.style.display = 'none'; return; }
    section.style.display = 'block';
    container.innerHTML = '';

    const data = selectedBikes.map((id, i) => ({
        bike: bicycles.find(b => b.id === id),
        color: PALETTE[i]
    }));

    const n = v => parseFloat(v) || 0;

    data.forEach(({ bike, color }) => {
        const ht  = n(bike.head_tube_angle);
        const fo  = n(bike.fork_offset || 45);
        const wR  = (n(bike.wheel_diameter) || 700) / 2;
        const htR = ht * Math.PI / 180;
        const trail = (wR * Math.cos(htR) - fo) / Math.sin(htR);
        const reach = n(bike.reach);
        const stack = n(bike.stack);
        const ratio = reach ? (stack / reach).toFixed(3) : '–';

        // Categorise handling
        let handlingLabel, handlingBadge;
        if (trail > 70)       { handlingLabel = 'Stable / Comfortable'; handlingBadge = '<span class="insight-badge badge-comfort">Comfort</span>'; }
        else if (trail > 60)  { handlingLabel = 'Balanced Handling';     handlingBadge = '<span class="insight-badge badge-balance">Balanced</span>'; }
        else if (trail > 50)  { handlingLabel = 'Responsive / Agile';    handlingBadge = '<span class="insight-badge badge-aero">Agile</span>'; }
        else                  { handlingLabel = 'Race-oriented';          handlingBadge = '<span class="insight-badge badge-aero">Race</span>'; }

        // Stack/reach position (low ratio = aggressive, high = relaxed)
        let posLabel, posBadge;
        const ratiof = parseFloat(ratio);
        if (ratiof > 1.55)     { posLabel = 'Relaxed / Upright position';  posBadge = '<span class="insight-badge badge-upright">Relaxed</span>'; }
        else if (ratiof > 1.48){ posLabel = 'Endurance / Balanced';        posBadge = '<span class="insight-badge badge-balance">Endurance</span>'; }
        else                   { posLabel = 'Aggressive / Aero position';  posBadge = '<span class="insight-badge badge-aero">Aggressive</span>'; }

        // Bar: reach vs stack
        const maxVal = Math.max(reach, stack);
        const reachPct = ((reach / maxVal) * 100).toFixed(0);
        const stackPct = ((stack / maxVal) * 100).toFixed(0);

        const card = document.createElement('div');
        card.className = 'insight-card';
        card.innerHTML = `
            <div class="ic-label" style="color:${color}">${bike.name}</div>
            <div class="insight-bar" style="margin-top:.6rem">
                <div class="insight-bar-seg" style="width:${reachPct}%;background:${color};opacity:.7"></div>
                <div class="insight-bar-seg" style="width:${stackPct}%;background:${color};opacity:.35"></div>
            </div>
            <div class="ic-sub">Stack/Reach ratio: <strong style="color:${color}">${ratio}</strong> · ${posLabel}</div>
            <div style="margin-top:.5rem">${posBadge}</div>
            <hr style="border:none;border-top:1px solid var(--border);margin:.75rem 0">
            <div class="ic-sub">Trail: <strong style="color:${color}">${trail.toFixed(1)} mm</strong> · ${handlingLabel}</div>
            <div style="margin-top:.4rem">${handlingBadge}</div>
        `;
        container.appendChild(card);
    });

    // Weight diff insight (only when 2+ bikes)
    if (data.length >= 2) {
        const weights = data.map(d => n(d.bike.weight_kg));
        const lightest = Math.min(...weights);
        const heaviest = Math.max(...weights);
        const diff = (heaviest - lightest).toFixed(1);

        const card = document.createElement('div');
        card.className = 'insight-card';
        card.innerHTML = `
            <div class="ic-label">⚖️ Weight Comparison</div>
            <div class="insight-bar" style="margin-top:.6rem">${data.map((d,i) => {
                const pct = ((n(d.bike.weight_kg) / heaviest) * 100).toFixed(0);
                return `<div class="insight-bar-seg" style="width:${pct}%;background:${d.color}"></div>`;
            }).join('')}</div>
            ${data.map((d,i)=>`<div class="ic-sub" style="color:${d.color}">${d.bike.name}: <strong>${n(d.bike.weight_kg)} kg</strong></div>`).join('')}
            <div style="margin-top:.5rem"><span class="insight-badge badge-balance">Δ ${diff} kg between lightest & heaviest</span></div>
        `;
        container.appendChild(card);
    }
}

// ─────────────────────────────────────────────────────────────────────
// renderAll
// ─────────────────────────────────────────────────────────────────────
function renderAll() {
    updateLegend();
    renderSelectedCards();
    renderInsights();
    renderCanvas();
    renderTable();
}

function updateLegend() {
    const lg = document.getElementById('legend');
    lg.innerHTML = '';
    selectedBikes.forEach((id, i) => {
        const bike = bicycles.find(b => b.id === id);
        const div = document.createElement('div');
        div.className = 'legend-item';
        div.innerHTML = `<div class="legend-dot" style="background:${PALETTE[i]}"></div><span>${bike.name}</span>`;
        lg.appendChild(div);
    });
}

// ─────────────────────────────────────────────────────────────────────
// Canvas renderer  ── core geometry math
// ─────────────────────────────────────────────────────────────────────
function renderCanvas() {
    const canvas = document.getElementById('geometryCanvas');
    const ctx = canvas.getContext('2d');
    const W = canvas.width;
    const H = canvas.height;
    ctx.clearRect(0, 0, W, H);

    // Background gradient (Bike-Insights blue feel)
    const grad = ctx.createLinearGradient(0, 0, 0, H);
    grad.addColorStop(0, '#1a3a5c');
    grad.addColorStop(1, '#0d2340');
    ctx.fillStyle = grad;
    ctx.fillRect(0, 0, W, H);

    if (selectedBikes.length === 0) return;

    const data = selectedBikes.map(id => bicycles.find(b => b.id === id)).filter(b => b && b.stack && b.reach && b.wheelbase);
    if (data.length === 0) return;

    // ── Determine global scale so all bikes fit without clipping ──
    const WHEEL_R_MM = 350; // standard 700c radius in mm
    const MT = 30; // top margin px
    const MB = 20; // bottom margin px
    const MH = 20; // horizontal margin px

    const maxWheelbase = Math.max(...data.map(b => +b.wheelbase || 1000));
    const maxStack     = Math.max(...data.map(b => +b.stack     || 600));
    const minBBDrop    = Math.min(...data.map(b => +b.bb_drop   || 70));

    // Vertical extent in mm:
    //  From ground contact (bottom of wheel) → top of HT:
    //   = wheelR (ground to axle) + wheelR - bbDrop (axle to BB is bbDrop DOWN, so BB is wheelR-bbDrop above ground)
    //   + stack (BB to HT top)
    //   = 2*wheelR - bbDrop + stack
    // Use minBBDrop (smallest drop = highest BB = tallest bike)
    const totalH_mm = 2 * WHEEL_R_MM - minBBDrop + maxStack;
    const totalW_mm = WHEEL_R_MM + maxWheelbase + WHEEL_R_MM;

    const scaleH = (H - MT - MB) / totalH_mm;
    const scaleW = (W - MH * 2)  / totalW_mm;
    const scale  = Math.min(scaleH, scaleW);

    // Ground contact line Y (bottom of wheels touch here)
    const groundContactY = H - MB;
    // Axle Y (wheel center is wheelR above ground contact)
    const axleY = groundContactY - WHEEL_R_MM * scale;

    // Center horizontally
    const bikeW  = totalW_mm * scale;
    const startX = (W - bikeW) / 2 + WHEEL_R_MM * scale; // rear axle X

    data.forEach((bike, i) => {
        const color = PALETTE[i];
        ctx.save();

        const n = v => parseFloat(v) || 0;
        const wheelR_mm   = 350; // 700c
        const wheelR      = wheelR_mm * scale;
        const bbDrop      = n(bike.bb_drop || 70) * scale;
        const chainstay   = n(bike.chainstay_length || 415) * scale;
        const wheelbase   = n(bike.wheelbase) * scale;
        const stack       = n(bike.stack) * scale;
        const reach       = n(bike.reach) * scale;
        const stLen       = n(bike.seat_tube_length || 450) * scale;
        const htLen       = n(bike.head_tube_length || 140) * scale;

        // Angles – head tube and seat tube angles measured from horizontal
        const htaRad = n(bike.head_tube_angle || 73) * Math.PI / 180;
        const staRad = n(bike.seat_tube_angle  || 73) * Math.PI / 180;

        // ── Wheel axle positions (both at same axleY = same height) ──
        const rearAxle  = { x: startX,             y: axleY };
        const frontAxle = { x: startX + wheelbase, y: axleY };

        // ── Bottom Bracket ──
        // BB Drop: BB is bbDrop mm BELOW the axle center line
        // BB is (wheelR - bbDrop) mm ABOVE ground contact
        const bbY  = axleY + bbDrop;  // in canvas: lower = larger y
        // chainstay hypotenuse from rearAxle to BB; bbDrop is the vertical component
        const csRun = Math.sqrt(Math.max(0, chainstay*chainstay - bbDrop*bbDrop));
        const bb = { x: rearAxle.x + csRun, y: bbY };

        // ── Head Tube Top: Stack = vertical from BB, Reach = horizontal from BB ──
        const htTop = { x: bb.x + reach, y: bb.y - stack };

        // ── Head Tube Bottom ──
        // HT angle measured from horizontal. From HT Top going down+forward:
        //   Δx = htLen * sin(90°-HTA) = htLen * cos(HTA)  [small, forward]
        //   Δy = htLen * cos(90°-HTA) = htLen * sin(HTA)  [large, downward]
        const htBottom = {
            x: htTop.x + htLen * Math.cos(htaRad),
            y: htTop.y + htLen * Math.sin(htaRad)
        };

        // ── Seat Tube Top ──
        // STA measured from horizontal; seat tube leans backward from BB:
        //   Δx = -stLen * cos(STA)   [backward = smaller x]
        //   Δy = -stLen * sin(STA)   [upward = smaller y in canvas]
        const stTopX = bb.x - stLen * Math.cos(staRad);
        const stTopY = bb.y - stLen * Math.sin(staRad);

        // ─────────────────── DRAW ───────────────────

        // Helper
        const line = (x1, y1, x2, y2) => {
            ctx.beginPath(); ctx.moveTo(x1, y1); ctx.lineTo(x2, y2); ctx.stroke();
        };
        const circle = (cx, cy, r) => {
            ctx.beginPath(); ctx.arc(cx, cy, r, 0, Math.PI*2); ctx.stroke();
        };
        const dot = (cx, cy, r=3.5) => {
            ctx.beginPath(); ctx.arc(cx, cy, r, 0, Math.PI*2); ctx.fill();
        };

        // Wheels — three rings for realistic look
        ctx.setLineDash([]);
        ctx.lineWidth = 2;
        ctx.strokeStyle = color + '55';
        circle(rearAxle.x,  rearAxle.y,  wheelR);
        circle(frontAxle.x, frontAxle.y, wheelR);
        ctx.lineWidth = 1;
        ctx.strokeStyle = color + '33';
        circle(rearAxle.x,  rearAxle.y,  wheelR * 0.92);
        circle(frontAxle.x, frontAxle.y, wheelR * 0.92);
        ctx.strokeStyle = color + '20';
        circle(rearAxle.x,  rearAxle.y,  wheelR * 0.15); // hub
        circle(frontAxle.x, frontAxle.y, wheelR * 0.15);

        // Rear triangle (chainstay + seatstay)
        ctx.lineWidth = 2;
        ctx.strokeStyle = color + 'BB';
        line(bb.x, bb.y, rearAxle.x, rearAxle.y);         // chainstay
        line(stTopX, stTopY, rearAxle.x, rearAxle.y);      // seatstay

        // ── Frame tubes ──
        ctx.lineWidth = 2.5;
        ctx.strokeStyle = color;

        // Seat tube: BB → Seat Tube Top
        line(bb.x, bb.y, stTopX, stTopY);

        // Top tube: Seat Tube Top → HT Top
        line(stTopX, stTopY, htTop.x, htTop.y);

        // Down tube: BB → HT Bottom  (NOT htTop — this is the quadrilateral, not a triangle!)
        line(bb.x, bb.y, htBottom.x, htBottom.y);

        // Head tube (thick): HT Top → HT Bottom
        ctx.lineWidth = 5;
        line(htTop.x, htTop.y, htBottom.x, htBottom.y);

        // Fork: HT Bottom → Front Axle
        ctx.lineWidth = 2.5;
        ctx.strokeStyle = color + 'CC';
        line(htBottom.x, htBottom.y, frontAxle.x, frontAxle.y);

        // ── Stack annotation (vertical dashed line) ──
        ctx.lineWidth = 1;
        ctx.setLineDash([4, 5]);
        ctx.strokeStyle = color + '77';
        // Draw from ground level (at htTop.x horizontally, bb.y vertically) up to htTop
        line(htTop.x, bb.y, htTop.x, htTop.y);
        // Horizontal reach line at htTop.y from bb.x across to htTop.x
        line(bb.x, htTop.y, htTop.x, htTop.y);
        ctx.setLineDash([]);

        // Key point dots
        ctx.fillStyle = color;
        [bb, htTop, htBottom, {x:stTopX, y:stTopY}, rearAxle, frontAxle].forEach(p => dot(p.x, p.y, 3));

        // ── Labels ──
        ctx.font = `600 11px Inter, sans-serif`;
        ctx.fillStyle = color;
        // Stack label (right of the dashed vertical, at midpoint)
        ctx.textAlign = 'left';
        ctx.fillText(`S: ${Math.round(n(bike.stack))}`, htTop.x + 4, (bb.y + htTop.y) / 2 + 4);
        // Reach label (above the dashed horizontal)
        ctx.textAlign = 'center';
        ctx.fillText(`R: ${Math.round(n(bike.reach))}`, (bb.x + htTop.x) / 2, htTop.y - 6);

        ctx.restore();
    });

    // Ground contact line
    ctx.strokeStyle = 'rgba(255,255,255,0.07)';
    ctx.lineWidth = 1;
    ctx.setLineDash([6,6]);
    ctx.beginPath();
    ctx.moveTo(MH, groundContactY);
    ctx.lineTo(W - MH, groundContactY);
    ctx.stroke();
    ctx.setLineDash([]);
}

// ─────────────────────────────────────────────────────────────────────
// Comparison Table
// ─────────────────────────────────────────────────────────────────────
function renderTable() {
    const hRow = document.getElementById('table-header-row');
    const tbody = document.getElementById('table-body');
    hRow.innerHTML = '<th class="row-label">Spec / Geometry</th>';
    tbody.innerHTML = '';

    if (selectedBikes.length === 0) {
        tbody.innerHTML = '<tr><td colspan="10" style="color:var(--muted);text-align:center;padding:3rem;font-size:0.85rem;">Select bikes to compare specifications</td></tr>';
        return;
    }

    const data = selectedBikes.map((id, i) => ({ bike: bicycles.find(b => b.id === id), color: PALETTE[i] }));

    // Headers
    data.forEach(({ bike, color }) => {
        const th = document.createElement('th');
        th.className = 'bike-col';
        th.style.color = color;
        th.innerText = bike.name;
        hRow.appendChild(th);
    });
    if (data.length > 1) {
        const th = document.createElement('th');
        th.innerText = 'Δ Diff';
        th.style.color = 'var(--muted)';
        hRow.appendChild(th);
    }

    const n = (v, dec=2) => v != null ? parseFloat(v).toFixed(dec) : '–';
    const raw = v => parseFloat(v) || null;

    function tr(label, key, unit='', dec=2, compute=null) {
        const row = document.createElement('tr');
        let vals = data.map(({ bike }) => compute ? compute(bike) : raw(bike[key]));
        row.innerHTML = `<td class="row-label">${label}${unit ? ` <span style="color:var(--muted);font-weight:400;font-size:0.75em">(${unit})</span>` : ''}</td>`;
        data.forEach(({ bike, color }, i) => {
            const td = document.createElement('td');
            td.className = 'mono';
            td.style.color = color;
            td.innerText = vals[i] != null ? parseFloat(vals[i]).toFixed(dec) : '–';
            row.appendChild(td);
        });
        if (data.length >= 2) {
            const td = document.createElement('td');
            td.className = 'mono';
            const a = vals[0], b = vals[1];
            if (a != null && b != null) {
                const d = (b - a).toFixed(dec);
                const cls = d > 0 ? 'diff-pos' : d < 0 ? 'diff-neg' : 'diff-neutral';
                td.className = `mono ${cls}`;
                td.innerText = (d > 0 ? '+' : '') + d;
            } else { td.innerText = '–'; }
            row.appendChild(td);
        }
        tbody.appendChild(row);
    }

    function section(label) {
        const row = document.createElement('tr');
        row.className = 'section-row';
        row.innerHTML = `<td colspan="${data.length + 2}"><span>${label}</span></td>`;
        tbody.appendChild(row);
    }

    function strRow(label, key) {
        const row = document.createElement('tr');
        row.innerHTML = `<td class="row-label">${label}</td>`;
        data.forEach(({ bike, color }) => {
            const td = document.createElement('td');
            td.className = 'mono';
            td.style.color = color;
            td.innerText = bike[key] ?? '–';
            row.appendChild(td);
        });
        if (data.length >= 2) { const td = document.createElement('td'); td.innerText = ''; row.appendChild(td); }
        tbody.appendChild(row);
    }

    function catRow() {
        const row = document.createElement('tr');
        row.innerHTML = `<td class="row-label">Category</td>`;
        data.forEach(({ bike, color }) => {
            const td = document.createElement('td');
            td.className = 'mono'; td.style.color = color;
            td.innerText = bike.category?.name ?? bike.type ?? '–';
            row.appendChild(td);
        });
        if (data.length >= 2) { const td = document.createElement('td'); td.innerText = ''; row.appendChild(td); }
        tbody.appendChild(row);
    }

    // ── Sections ──

    section('🔧 Build');
    catRow();
    strRow('Frame Material', 'frame_material');
    strRow('Fork Material', 'fork_material');
    tr('Weight', 'weight_kg', 'kg', 1);
    tr('Tire Width', 'tire_width', 'mm', 0);

    section('📐 Key Geometry');
    tr('Stack', 'stack', 'mm', 1);
    tr('Reach', 'reach', 'mm', 1);
    // Head-to-Stack Ratio
    const row_hsr = document.createElement('tr');
    row_hsr.innerHTML = '<td class="row-label">Head-to-Stack Ratio</td>';
    const hsr_vals = data.map(({bike}) => {
        const ht = parseFloat(bike.head_tube_length), st = parseFloat(bike.stack);
        return (ht && st) ? (ht/st) : null;
    });
    data.forEach(({bike,color},i) => {
        const td = document.createElement('td'); td.className='mono'; td.style.color=color;
        td.innerText = hsr_vals[i] != null ? hsr_vals[i].toFixed(3) : '–';
        row_hsr.appendChild(td);
    });
    if (data.length >= 2) { const td = document.createElement('td'); td.innerText = ''; row_hsr.appendChild(td); }
    tbody.appendChild(row_hsr);

    section('📏 Tube Lengths');
    tr('Head Tube Length', 'head_tube_length', 'mm', 0);
    tr('Seat Tube Length', 'seat_tube_length', 'mm', 0);
    tr('Top Tube Length', 'top_tube_length', 'mm', 1);

    section('📐 Angles');
    tr('Head Tube Angle', 'head_tube_angle', '°', 2);
    tr('Seat Tube Angle', 'seat_tube_angle', '°', 2);

    section('📊 Frame Distances');
    tr('Wheelbase', 'wheelbase', 'mm', 1);
    tr('Chainstay Length', 'chainstay_length', 'mm', 1);
    tr('BB Drop', 'bb_drop', 'mm', 1);
    tr('Fork Offset / Rake', 'fork_offset', 'mm', 1);
}

// ─── Initial state ───────────────────────────────────────────────────
renderCanvas();
</script>
</body>
</html>
