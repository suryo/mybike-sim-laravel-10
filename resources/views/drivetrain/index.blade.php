<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Drivetrain Lab - MyBike Pro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent: #38bdf8;
            --accent-hover: #0ea5e9;
            --glass: rgba(30, 41, 59, 0.7);
            --gradient: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-primary);
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Navigation (Copied from Home) */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-weight: 800;
            font-size: 1.5rem;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            align-items: center;
        }

        .nav-item {
            text-decoration: none;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .nav-item:hover, .nav-item.active {
            color: var(--text-primary);
        }

        .btn-auth {
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.85rem;
            border: 1px solid rgba(255,255,255,0.1);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-auth:hover {
            background: rgba(255,255,255,0.05);
        }

        /* Layout Structure */
        main {
            padding-top: 80px; /* Space for fixed nav */
            max-width: 1400px;
            margin: 0 auto;
            padding-left: 2rem;
            padding-right: 2rem;
            padding-bottom: 4rem;
        }

        .stage-container {
            margin-top: 2rem;
            background: rgba(30, 41, 59, 0.4);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 24px;
            position: relative;
            overflow: hidden;
            height: 500px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* SVG Overlays (HTML) */
        .overlay-badges {
            position: absolute;
            top: 2rem;
            left: 2rem;
            display: flex;
            gap: 1rem;
            z-index: 10;
        }

        .badge {
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(8px);
            padding: 0.75rem 1.25rem;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .badge-label {
            font-size: 0.65rem;
            font-weight: 800;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            display: block;
            margin-bottom: 0.25rem;
        }

        .badge-value {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 1.1rem;
            color: var(--accent);
        }

        .status-overlay {
            position: absolute;
            top: 2rem;
            right: 2rem;
            text-align: right;
            z-index: 10;
        }

        .status-pill {
            display: inline-block;
            background: #10b981;
            color: #064e3b;
            font-size: 0.6rem;
            font-weight: 900;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.5rem;
        }

        .clock {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.5rem;
            font-weight: 800;
            opacity: 0.5;
        }

        /* Workbench Bottom Grid */
        .workbench {
            margin-top: 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 2rem;
        }

        @media (max-width: 1024px) {
            .workbench { grid-template-columns: 1fr; }
        }

        .card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 2rem;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .card-title {
            font-size: 0.75rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--accent);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Form Elements */
        .control-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase;
            margin-bottom: 0.75rem;
        }

        select {
            width: 100%;
            background: #0f172a;
            color: white;
            border: 1px solid rgba(255,255,255,0.1);
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-weight: 600;
            outline: none;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.25rem;
        }

        input[type=range] {
            width: 100%;
            height: 6px;
            background: rgba(255,255,255,0.1);
            border-radius: 10px;
            -webkit-appearance: none;
            outline: none;
        }

        input[type=range]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            background: var(--accent);
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid white;
            box-shadow: 0 0 10px rgba(56, 189, 248, 0.4);
        }

        /* Stats HUD */
        .stat-huge {
            margin-top: 1rem;
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }

        .stat-value {
            font-size: 4rem;
            font-weight: 800;
            font-style: italic;
            letter-spacing: -0.05em;
            line-height: 1;
        }

        .stat-unit {
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--text-secondary);
            text-transform: uppercase;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: rgba(0,0,0,0.3);
            border-radius: 10px;
            margin-top: 1.5rem;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--gradient);
            width: 0%;
            transition: width 0.3s;
        }

        .bike-info {
            display: flex;
            align-items: center;
            gap: 1rem;
            opacity: 0.6;
        }

        /* SVG Specific Styling */
        svg {
            display: block;
        }

        @keyframes pulse {
            0% { opacity: 0.4; }
            50% { opacity: 0.8; }
            100% { opacity: 0.4; }
        }

        .neon-rim {
            animation: pulse 3s infinite ease-in-out;
            transition: stroke 0.3s, filter 0.3s;
        }

        .neon-rim.braking {
            stroke: #f43f5e !important;
            filter: drop-shadow(0 0 10px #f43f5e);
            animation: none;
        }

        .brake-container {
            margin-top: 2rem;
        }

        .btn-brake {
            width: 100%;
            padding: 1.5rem;
            background: rgba(244, 63, 94, 0.1);
            border: 2px solid #f43f5e;
            border-radius: 16px;
            color: #f43f5e;
            font-size: 1.2rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            cursor: pointer;
            transition: all 0.2s;
            user-select: none;
            -webkit-user-select: none;
        }

        .btn-brake:active, .btn-brake.active {
            background: #f43f5e;
            color: white;
            transform: scale(0.98);
            box-shadow: 0 0 30px rgba(244, 63, 94, 0.4);
        }

        /* Toggle & Manual Controls */
        .mode-toggle-container {
            display: flex;
            background: rgba(0,0,0,0.3);
            padding: 4px;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .mode-btn {
            flex: 1;
            padding: 0.5rem;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
            color: var(--text-secondary);
        }

        .mode-btn.active {
            background: var(--accent);
            color: #0f172a;
        }

        .shift-controls {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 0.5rem;
            align-items: center;
        }

        .shift-btn {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            padding: 0.5rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 800;
            transition: all 0.2s;
        }

        .shift-btn:hover:not(:disabled) {
            background: var(--accent);
            color: #0f172a;
        }

        .shift-btn:disabled {
            opacity: 0.2;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

    <nav>
        <a href="/" class="logo">MyBike Pro</a>
        <div class="nav-links">
            <a href="/" class="nav-item">Home</a>
            <a href="/simulation" class="nav-item">Simulator</a>
            <a href="/compare" class="nav-item">Comparison</a>
            <a href="/drivetrain" class="nav-item active">Drivetrain</a>
            
            @auth
                <div class="nav-item">{{ Auth::user()->name }}</div>
            @else
                <a href="{{ route('login') }}" class="btn-auth">Log in</a>
            @endauth
        </div>
    </nav>

    <main>
        <!-- Simulation Stage -->
        <div class="stage-container">
            <div class="overlay-badges">
                <div class="badge">
                    <span class="badge-label">Kinetic Cadence</span>
                    <span id="cadence-viz-val" class="badge-value">90 RPM</span>
                </div>
                <div class="badge">
                    <span class="badge-label">Surface Grade</span>
                    <span id="gradient-viz-val" class="badge-value">0.0%</span>
                </div>
                <div class="badge">
                    <span class="badge-label">Resistance</span>
                    <span id="resistance-cat" class="badge-value" style="color: #38bdf8;">LOW</span>
                </div>
            </div>

            <div id="shift-notif" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(56, 189, 248, 0.9); color: #0f172a; padding: 1rem 2rem; border-radius: 40px; font-weight: 900; font-size: 1.5rem; text-transform: uppercase; letter-spacing: 0.1em; pointer-events: none; opacity: 0; transition: opacity 0.3s; z-index: 50; box-shadow: 0 0 30px rgba(56, 189, 248, 0.5);">
                SHIFTING
            </div>

            <div class="status-overlay">
                <div class="status-pill">Active Simulation</div>
                <div class="clock" id="clock-display">00:00:00</div>
            </div>

            <svg id="drivetrain-svg" width="100%" height="100%" viewBox="0 0 1000 500">
                <defs>
                    <radialGradient id="wheelGrad" cx="50%" cy="50%" r="50%">
                        <stop offset="0%" style="stop-color:rgb(30, 41, 59); stop-opacity:0.4" />
                        <stop offset="100%" style="stop-color:rgb(15, 23, 42); stop-opacity:0.8" />
                    </radialGradient>
                    <pattern id="grid" width="50" height="50" patternUnits="userSpaceOnUse">
                        <path d="M 50 0 L 0 0 0 50" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="1"/>
                    </pattern>
                </defs>

                <rect width="100%" height="100%" fill="url(#grid)" />
                <line x1="0" y1="480" x2="1000" y2="480" stroke="rgba(56, 189, 248, 0.1)" stroke-width="2"/>

                <!-- Rear Wheel -->
                <g id="wheel-system">
                    <circle id="wheel" cx="750" cy="240" r="230" fill="url(#wheelGrad)" stroke="rgba(255,255,255,0.02)" stroke-width="15" />
                    <g id="wheel-spokes">
                        @for($i=0; $i<24; $i++)
                            <line x1="750" y1="240" x2="{{ 750 + 230 * cos(deg2rad($i*15)) }}" y2="{{ 240 + 230 * sin(deg2rad($i*15)) }}" stroke="rgba(255,255,255,0.05)" stroke-width="0.5" />
                        @endfor
                    </g>
                    <circle cx="750" cy="240" r="232" fill="none" stroke="var(--accent)" stroke-width="1" class="neon-rim" />
                </g>

                <!-- Chain -->
                <path id="chain-path" d="M250,240 L750,240" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="5" stroke-dasharray="10,5" />

                <!-- Front Chainring Group -->
                <g id="chainring-group">
                    <circle id="cr-outer" cx="250" cy="240" r="80" fill="#020617" stroke="rgba(56, 189, 248, 0.3)" stroke-width="4" />
                    <circle cx="250" cy="240" r="15" fill="#1e293b" />
                    <text id="cr-label" x="250" y="360" text-anchor="middle" fill="var(--text-secondary)" font-weight="800" font-size="12">50T DRIVE</text>
                    
                    <!-- Crank -->
                    <rect x="246" y="130" width="8" height="110" rx="4" fill="white" />
                    <rect x="238" y="120" width="24" height="8" rx="4" fill="var(--accent)" />
                </g>

                <!-- Rear Cassette Group -->
                <g id="cassette-group">
                    <circle id="cs-outer" cx="750" cy="240" r="40" fill="#020617" stroke="rgba(56, 189, 248, 0.3)" stroke-width="3" />
                    <text id="cs-label" x="750" y="310" text-anchor="middle" fill="var(--text-secondary)" font-weight="800" font-size="12">11T HUB</text>
                </g>
            </svg>
        </div>

        <!-- Interactive Workbench -->
        <div class="workbench">
            
            <!-- Transmission Card -->
            <div class="card">
                <div class="card-title">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                    Transmission Setup
                </div>

                <div class="control-group">
                    <label>Shifting Mode</label>
                    <div class="mode-toggle-container">
                        <div id="mode-auto" class="mode-btn active" onclick="setMode(true)">Auto</div>
                        <div id="mode-manual" class="mode-btn" onclick="setMode(false)">Manual</div>
                    </div>
                </div>

                <div class="control-group">
                    <label>Chainring (Front)</label>
                    <div class="shift-controls">
                        <button class="shift-btn" id="btn-cr-down" onclick="manualShiftCR(-1)">-</button>
                        <select id="chainring" disabled>
                            @foreach($bicycle->front_gears as $cog)
                                <option value="{{ $cog }}">{{ $cog }}T</option>
                            @endforeach
                        </select>
                        <button class="shift-btn" id="btn-cr-up" onclick="manualShiftCR(1)">+</button>
                    </div>
                </div>

                <div class="control-group">
                    <label>Cassette (Rear)</label>
                    <div class="shift-controls">
                        <button class="shift-btn" id="btn-cs-down" onclick="manualShiftCS(-1)">-</button>
                        <select id="cassette" disabled>
                            @foreach($bicycle->rear_gears as $cog)
                                <option value="{{ $cog }}">{{ $cog }}T</option>
                            @endforeach
                        </select>
                        <button class="shift-btn" id="btn-cs-up" onclick="manualShiftCS(1)">+</button>
                    </div>
                    <div id="auto-logic-label" style="font-size: 0.6rem; color: var(--accent); margin-top: 0.5rem; font-weight: 800;">AUTO-SHIFT INTELLIGENCE ACTIVE</div>
                </div>

                <div class="control-group" style="margin-top: 2rem; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 1.5rem;">
                    <label>Active Laboratory Asset</label>
                    <select id="bike-selector" onchange="window.location.href='/drivetrain?bicycle_id=' + this.value">
                        @foreach($bicycles as $b)
                            <option value="{{ $b->id }}" {{ $bicycle->id == $b->id ? 'selected' : '' }}>
                                {{ $b->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="bike-info" style="margin-top: 1rem;">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 5.5c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zM5 12c-2.8 0-5 2.2-5 5s2.2 5 5 5 5-2.2 5-5-2.2-5-5-5zm0 8.5c-1.9 0-3.5-1.6-3.5-3.5s1.6-3.5 3.5-3.5 3.5 1.6 3.5 3.5-1.6 3.5-3.5 3.5zm10.5-5.5h-1c-.4 0-1.1-.1-1.4-.4l-1.6-1.6c-.3-.3-.4-1-.1-1.4L13 8c.3-.5 1-.6 1.5-.4L16 8.5V11c0 .6.4 1 1 1h2v2c0 .6-.4 1-1 1zm3.5-3c-2.8 0-5 2.2-5 5s2.2 5 5 5 5-2.2 5-5-2.2-5-5-5zm0 8.5c-1.9 0-3.5-1.6-3.5-3.5s1.6-3.5 3.5-3.5 3.5 1.6 3.5 3.5-1.6 3.5-3.5 3.5z"/></svg>
                    <span style="font-size: 0.75rem; font-weight: 700;">{{ $bicycle->name }}</span>
                </div>
            </div>

            <!-- Environmental Card -->
            <div class="card">
                <div class="card-title">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M21 16.5c0 .38-.21.71-.53.88l-7.97 4.11c-.3.15-.65.15-.95 0l-7.97-4.11c-.32-.17-.53-.5-.53-.88V7.5c0-.38.21-.71.53-.88l7.97-4.11c.3-.15.65-.15.95 0l7.97 4.11c.32.17.53.5.53.88v9z"/></svg>
                    Applied Effort & Environment
                </div>

                <div class="control-group">
                    <div style="display: flex; justify-content: space-between;">
                        <label>Applied Power (Effort)</label>
                        <span id="watts-label" style="font-size: 0.7rem; font-weight: 800; color: var(--accent);">150 W</span>
                    </div>
                    <input type="range" id="power-input" min="0" max="1000" value="150" oninput="updateEffort()">
                </div>

                <div class="control-group">
                    <div style="display: flex; justify-content: space-between;">
                        <label>Road Gradient</label>
                        <span id="gradient-label" style="font-size: 0.7rem; font-weight: 800; color: #fb923c;">0.0%</span>
                    </div>
                    <input type="range" id="gradient" min="-15" max="25" value="0" step="0.5" style="accent-color: #fb923c;" oninput="updateSim()">
                </div>

                <div class="brake-container">
                    <button id="brake-btn" class="btn-brake">Squeeze Brake</button>
                    <div style="font-size: 0.6rem; color: #94a3b8; margin-top: 0.5rem; text-align: center; font-weight: 700;">HOLD TO DECELERATE (SPACE)</div>
                </div>
            </div>

            <!-- Physics HUD Card -->
            <div class="card" style="background: #020617; border-left: 4px solid var(--accent);">
                <div class="card-title">Physics Environment</div>
                
                <div class="stat-huge">
                    <span id="speed-display" class="stat-value">0.0</span>
                    <span class="stat-unit">km/h</span>
                </div>

                <div style="margin-top: 1rem; display: flex; justify-content: space-between; font-size: 0.65rem; font-weight: 800; color: var(--text-secondary);">
                    <span>GEAR RATIO: <span id="ratio-display" style="color: white;">1.00</span></span>
                    <span>CADENCE: <span id="cadence-display" style="color: var(--accent);">0 RPM</span></span>
                </div>

                <div class="progress-bar">
                    <div id="power-bar" class="progress-fill"></div>
                </div>
                <div style="margin-top: 0.5rem; display: flex; justify-content: space-between;">
                    <span style="font-size: 0.6rem; font-weight: 800; color: var(--text-secondary);">RESISTANCE FORCE</span>
                    <span style="font-size: 0.75rem; font-weight: 900; color: var(--accent);"><span id="resistance-display">0</span> <small style="font-size: 0.5rem;">W</small></span>
                </div>
            </div>

        </div>
    </main>

    <script>
        // Physics constants
        const mass = 85; // kg (Rider + Bike)
        const gravity = 9.81;
        const dragCoeff = 0.5 * 1.2 * 0.4 * 0.6; // 0.5 * rho * Cd * A
        const rollingResist = 0.005 * mass * gravity;
        const wheelCircumference = 2.105; 

        // State variables
        let currentVelocity = 0; // m/s
        let targetPower = 150; // Watts
        let rotationAngle = 0;
        let lastTime = performance.now();
        let shiftMessageTimer = 0;
        let isBraking = false;
        
        // Gear options (sorted from lightest to heaviest)
        const frontGears = @json($bicycle->front_gears);
        const rearGears = @json($bicycle->rear_gears);
        
        // Sorting gears
        frontGears.sort((a, b) => a - b);
        rearGears.sort((a, b) => b - a); // Heaviest rear is smallest cog

        let currentCRIdx = 0; // Starting easiest (smallest chainring)
        let currentCSIdx = 0; // Starting easiest (largest cog)
        let autoShiftEnabled = true;

        const crEl = document.getElementById('chainring');
        const csEl = document.getElementById('cassette');

        function setMode(auto) {
            autoShiftEnabled = auto;
            document.getElementById('mode-auto').classList.toggle('active', auto);
            document.getElementById('mode-manual').classList.toggle('active', !auto);
            
            const logicLabel = document.getElementById('auto-logic-label');
            logicLabel.innerText = auto ? 'AUTO-SHIFT INTELLIGENCE ACTIVE' : 'MANUAL CONTROL ENABLED';
            logicLabel.style.color = auto ? 'var(--accent)' : '#f87171';

            // Visual feedback on shift buttons
            const buttons = document.querySelectorAll('.shift-btn');
            buttons.forEach(btn => btn.disabled = auto);
            
            crEl.disabled = auto;
            csEl.disabled = auto;
            crEl.style.opacity = auto ? '0.5' : '1';
            csEl.style.opacity = auto ? '0.5' : '1';
            crEl.style.cursor = auto ? 'not-allowed' : 'pointer';
            csEl.style.cursor = auto ? 'not-allowed' : 'pointer';
            
            showShiftMessage(auto ? "Auto Pilot On" : "Manual Control");
        }

        function manualShiftCR(dir) {
            if (autoShiftEnabled) return;
            const newIdx = currentCRIdx + dir;
            if (newIdx >= 0 && newIdx < frontGears.length) {
                currentCRIdx = newIdx;
                updateVisuals(true, dir > 0 ? "UPSHIFT ↑" : "DOWNSHIFT ↓");
            }
        }

        function manualShiftCS(dir) {
            if (autoShiftEnabled) return;
            const newIdx = currentCSIdx + dir;
            if (newIdx >= 0 && newIdx < rearGears.length) {
                currentCSIdx = newIdx;
                updateVisuals(true, dir > 0 ? "UPSHIFT ↑" : "DOWNSHIFT ↓");
            }
        }

        function updateVisuals(shiftOccurred, shiftType) {
            const front = frontGears[currentCRIdx];
            const rear = rearGears[currentCSIdx];

            if (shiftOccurred) {
                showShiftMessage(shiftType + " (" + front + "x" + rear + ")");
                document.getElementById('cr-label').textContent = front + 'T DRIVE';
                document.getElementById('cs-label').textContent = rear + 'T HUB';
                
                const crRadius = 40 + (front * 0.8);
                const csRadius = 15 + (rear * 0.8);
                document.getElementById('cr-outer').setAttribute('r', crRadius);
                document.getElementById('cs-outer').setAttribute('r', csRadius);

                const chainPath = `M250,${240-crRadius} L750,${240-csRadius} L750,${240+csRadius} L250,${240+crRadius} Z`;
                document.getElementById('chain-path').setAttribute('d', chainPath);
                
                crEl.value = front;
                csEl.value = rear;
            }
        }

        function updateEffort() {
            targetPower = parseInt(document.getElementById('power-input').value);
            document.getElementById('watts-label').innerText = targetPower + ' W';
        }

        function updateClock() {
            const now = new Date();
            document.getElementById('clock-display').innerText = now.toTimeString().split(' ')[0];
        }
        setInterval(updateClock, 1000);
        updateClock();

        function showShiftMessage(text) {
            const msgEl = document.getElementById('shift-notif');
            msgEl.innerText = text;
            msgEl.style.opacity = 1;
            shiftMessageTimer = 3; // Show for 3 seconds
        }

        function runPhysics(time) {
            const dt = (time - lastTime) / 1000;
            lastTime = time;

            if (dt > 0.1) {
                requestAnimationFrame(runPhysics);
                return;
            }

            // Decrement shift notification timer
            if (shiftMessageTimer > 0) {
                shiftMessageTimer -= dt;
                if (shiftMessageTimer <= 0) {
                    document.getElementById('shift-notif').style.opacity = 0;
                }
            }

            const slope = parseFloat(document.getElementById('gradient').value);
            const front = frontGears[currentCRIdx];
            const rear = rearGears[currentCSIdx];
            const ratio = front / rear;

            // 1. Calculate Forces
            const sinAlpha = slope / 100;
            const gravityForce = mass * gravity * sinAlpha;
            const dragForce = dragCoeff * Math.pow(currentVelocity, 2);
            const totalResistanceForce = gravityForce + dragForce + (currentVelocity > 0 ? rollingResist : 0);
            
            let driveForce = 0;
            if (currentVelocity < 0.2) {
                driveForce = targetPower / 0.2; 
            } else {
                driveForce = targetPower / currentVelocity;
            }

            // Apply Brake Force
            const brakeForce = isBraking ? 800 : 0; 
            const netForce = driveForce - totalResistanceForce - brakeForce;
            const acceleration = netForce / mass;

            // 2. Update Velocity
            currentVelocity = Math.max(0, currentVelocity + acceleration * dt);
            
            // 3. Derived Metrics
            const speedKmh = currentVelocity * 3.6;
            const wheelRpm = (currentVelocity * 60) / wheelCircumference;
            const cadenceRpm = wheelRpm / ratio;

            // 4. Shifting Logic
            let shiftOccurred = false;
            let shiftType = "";

            if (autoShiftEnabled) {
                // Upshift: Cadence > 100 RPM
                if (cadenceRpm > 100) {
                    if (currentCSIdx < rearGears.length - 1) {
                        currentCSIdx++;
                        shiftOccurred = true;
                        shiftType = "UPSHIFT ↑";
                    } else if (currentCRIdx < frontGears.length - 1) {
                        currentCRIdx++;
                        currentCSIdx = 0; 
                        shiftOccurred = true;
                        shiftType = "CHAINRING UP ↑↑";
                    }
                } 
                // Downshift: Cadence < 60 RPM
                else if (cadenceRpm < 60 && currentVelocity > 0.5) {
                    if (currentCSIdx > 0) {
                        currentCSIdx--;
                        shiftOccurred = true;
                        shiftType = "DOWNSHIFT ↓";
                    } else if (currentCRIdx > 0) {
                        currentCRIdx--;
                        currentCSIdx = rearGears.length - 1;
                        shiftOccurred = true;
                        shiftType = "CHAINRING DOWN ↓↓";
                    }
                }
            }

            // 5. Update UI & Visuals
            document.getElementById('speed-display').innerText = speedKmh.toFixed(1);
            document.getElementById('cadence-display').innerText = Math.round(cadenceRpm) + ' RPM';
            document.getElementById('cadence-viz-val').innerText = Math.round(cadenceRpm) + ' RPM';
            document.getElementById('gradient-viz-val').innerText = slope.toFixed(1) + '%';
            document.getElementById('gradient-label').innerText = slope.toFixed(1) + '%';
            document.getElementById('ratio-display').innerText = ratio.toFixed(2);
            
            // Resistance Category Label
            let resistCat = "LOW";
            let resistColor = "#38bdf8";
            if (cadenceRpm >= 75 && cadenceRpm <= 85) {
                resistCat = "MODERATE";
                resistColor = "#818cf8";
            } else if (cadenceRpm < 75 && currentVelocity > 2) {
                resistCat = "HIGH";
                resistColor = "#f43f5e";
            }
            const catEl = document.getElementById('resistance-cat');
            catEl.innerText = resistCat;
            catEl.style.color = resistColor;

            const resistanceWatts = totalResistanceForce * currentVelocity;
            document.getElementById('resistance-display').innerText = Math.round(Math.abs(resistanceWatts));
            const powerPct = Math.min(100, (targetPower / 1000) * 100);
            document.getElementById('power-bar').style.width = powerPct + '%';

            // External updateVisuals helper
            updateVisuals(shiftOccurred, shiftType);

            // Rotation
            const wheelRotationSpeed = (wheelRpm / 60) * 360; 
            rotationAngle = (rotationAngle + wheelRotationSpeed * dt) % 360;

            document.getElementById('wheel-spokes').setAttribute('transform', `rotate(${rotationAngle} 750 240)`);
            document.getElementById('cassette-group').setAttribute('transform', `rotate(${rotationAngle} 750 240)`);
            const crankAngle = (rotationAngle / ratio) % 360;
            document.getElementById('chainring-group').setAttribute('transform', `rotate(${crankAngle} 250 240)`);

            requestAnimationFrame(runPhysics);
        }

        // Keyboard Shortcut Shifting
        window.addEventListener('keydown', (e) => {
            if (e.code === 'Space') {
                e.preventDefault();
                setBraking(true);
            }
            
            if (autoShiftEnabled) return;
            if (e.key === '[') manualShiftCS(-1);
            if (e.key === ']') manualShiftCS(1);
            if (e.key === '1') manualShiftCR(-1);
            if (e.key === '2') manualShiftCR(1);
        });

        window.addEventListener('keyup', (e) => {
            if (e.code === 'Space') {
                setBraking(false);
            }
        });

        // Brake Button Listeners
        const brakeBtn = document.getElementById('brake-btn');
        brakeBtn.addEventListener('mousedown', () => setBraking(true));
        brakeBtn.addEventListener('mouseup', () => setBraking(false));
        brakeBtn.addEventListener('mouseleave', () => setBraking(false));
        brakeBtn.addEventListener('touchstart', (e) => { e.preventDefault(); setBraking(true); });
        brakeBtn.addEventListener('touchend', () => setBraking(false));

        function setBraking(val) {
            isBraking = val;
            brakeBtn.classList.toggle('active', val);
            document.querySelectorAll('.neon-rim').forEach(el => el.classList.toggle('braking', val));
            if (val) {
                showShiftMessage("BRAKING ACTIVE");
            }
        }

        requestAnimationFrame(runPhysics);
    </script>
</body>
</html>
