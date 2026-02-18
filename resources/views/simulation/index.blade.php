<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyBike Simulation Pro v2.1</title>
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
            --danger: #ef4444;
            --success: #22c55e;
            --radius: 12px;
            --glass: rgba(30, 41, 59, 0.7);
            --fatigue: #f97316;
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

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 1rem;
        }

        h1 {
            font-weight: 800;
            font-size: 2rem;
            background: linear-gradient(to right, #38bdf8, #818cf8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .main-layout {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 2rem;
        }

        .simulation-area {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .canvas-container {
            position: relative;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            border: 1px solid rgba(255,255,255,0.05);
            height: 550px;
        }

        canvas {
            width: 100%;
            height: 100%;
            display: block;
        }

        .global-controls {
            background: var(--glass);
            backdrop-filter: blur(10px);
            padding: 1.5rem;
            border-radius: var(--radius);
            border: 1px solid rgba(255,255,255,0.1);
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            align-items: center;
            gap: 2rem;
        }

        .control-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        label {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-secondary);
        }

        input[type="range"] {
            width: 100%;
            accent-color: var(--accent);
        }

        .value-display {
            font-family: 'JetBrains Mono', monospace;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--accent);
        }

        .bike-sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .bike-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            max-height: 850px;
            overflow-y: auto;
            padding-right: 0.5rem;
        }

        .bike-card {
            background: var(--card-bg);
            padding: 1.25rem;
            border-radius: var(--radius);
            border: 1px solid rgba(255,255,255,0.05);
            position: relative;
            transition: transform 0.2s;
        }

        .delete-btn {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            background: none;
            border: none;
            color: var(--danger);
            cursor: pointer;
            opacity: 0.5;
            transition: opacity 0.2s;
        }

        .delete-btn:hover {
            opacity: 1;
        }

        .bike-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .bike-color {
            width: 14px;
            height: 14px;
            border-radius: 50%;
        }

        .bike-name {
            font-weight: 600;
            font-size: 1rem;
        }

        .gear-controls {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        select {
            background: #0f172a;
            color: white;
            border: 1px solid rgba(255,255,255,0.1);
            padding: 0.4rem;
            border-radius: 6px;
            outline: none;
            width: 100%;
            font-size: 0.8rem;
        }

        .bike-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
            font-size: 0.8rem;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            background: rgba(0,0,0,0.2);
            padding: 0.4rem;
            border-radius: 4px;
        }

        .stat-label {
            color: var(--text-secondary);
            font-size: 0.7rem;
        }

        .stat-value {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
        }

        .admin-panel {
            background: var(--glass);
            padding: 1.5rem;
            border-radius: var(--radius);
            border: 1px solid rgba(255,255,255,0.1);
        }

        form.bike-form {
            display: grid;
            gap: 1rem;
        }

        input.form-control {
            background: #0f172a;
            color: white;
            border: 1px solid rgba(255,255,255,0.1);
            padding: 0.6rem;
            border-radius: 6px;
            width: 100%;
        }

        .btn {
            background: var(--accent);
            color: var(--bg-color);
            border: none;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }

        .btn:active {
            transform: scale(0.98);
        }

        .btn:hover {
            background: var(--accent-hover);
        }

        .btn-outline {
            background: transparent;
            color: var(--text-primary);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .btn-outline:hover {
            background: rgba(255,255,255,0.05);
        }

        /* Notifications */
        .toast {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: var(--success);
            color: white;
            padding: 1rem 2rem;
            border-radius: var(--radius);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(0); }
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

        /* Tooltip Style */
        .tooltip-container {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        .tooltip-text {
            visibility: hidden;
            width: 200px;
            background-color: #334155;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px;
            position: absolute;
            z-index: 100;
            bottom: 125%;
            left: 50%;
            margin-left: -100px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.75rem;
            pointer-events: none;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .tooltip-container:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.8);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: var(--card-bg);
            margin: 5% auto;
            padding: 2rem;
            border: 1px solid rgba(255,255,255,0.1);
            width: 90%;
            max-width: 600px;
            border-radius: var(--radius);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .close {
            color: var(--text-secondary);
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: white;
        }

        /* Bonk Alert */
        .bonk-overlay {
            position: absolute;
            inset: 0;
            background: rgba(153, 27, 27, 0.8);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius);
            z-index: 10;
            backdrop-filter: blur(4px);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }

        .bonk-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .bonk-title {
            color: white;
            font-weight: 800;
            font-size: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 0.5rem;
        }

        .btn-refuel {
            background: #f59e0b;
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .btn-refuel:hover { transform: scale(1.05); background: #d97706; }

        /* Playback Controls */
        .playback-header {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
            background: rgba(30, 41, 59, 0.4);
            padding: 0.75rem;
            border-radius: var(--radius);
            border: 1px solid rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
        }

        .playback-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            padding: 0.6rem 1.5rem;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-weight: 700;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .btn-play { background: var(--success); }
        .btn-pause { background: #f59e0b; }
        .btn-reset { background: var(--danger); }

        .playback-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
        }

        .playback-btn:active { transform: translateY(0); }
        .playback-btn svg { width: 16px; height: 16px; }

        .playback-btn.active {
            outline: 2px solid white;
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    @if(session('success'))
        <div class="toast" id="toast">
            {{ session('success') }}
        </div>
        <script>setTimeout(() => document.getElementById('toast').remove(), 3000)</script>
    @endif

    <div class="container">
        <header>
            <h1>MyBike SIM Pro <small style="font-size: 0.5em; opacity: 0.5; vertical-align: middle;">v2.1</small></h1>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <div class="status-badge" style="background: var(--success); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold;">LIVE RACING</div>
            </div>
        </header>

        <div class="main-layout">
            <div class="simulation-area">
                <div class="playback-header">
                    <div class="tooltip-container" style="display: flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.05); padding: 5px 15px; border-radius: 20px;">
                        <label style="font-size: 0.75rem; white-space: nowrap;">Global Refuel:</label>
                        <input type="number" id="globalRefuelDist" value="1.0" step="0.1" min="0.1" style="width: 50px; background: transparent; border: 1px solid rgba(255,255,255,0.2); color: white; padding: 2px 5px; border-radius: 4px; font-size: 0.75rem;">
                        <span style="font-size: 0.75rem;">km</span>
                        <span class="tooltip-text">Interval for riders with "Auto-Refuel" enabled</span>
                    </div>
                    <div style="flex-grow: 1;"></div>
                    <button id="playBtn" class="playback-btn btn-play active" title="Start Simulation">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        Play
                    </button>
                    <button id="pauseBtn" class="playback-btn btn-pause" title="Pause Simulation">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                        Stop
                    </button>
                    <button id="resetPlayBtn" class="playback-btn btn-reset" title="Reset Simulation">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="M17.65 6.35A7.958 7.958 0 0012 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08A5.99 5.99 0 0112 18c-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg>
                        Reset
                    </button>
                </div>

                <div class="canvas-container">
                    <canvas id="simCanvas"></canvas>
                </div>

                <div class="global-controls">
                    <div class="control-group tooltip-container">
                        <label for="slopeInput">Road Incline</label>
                        <input type="range" id="slopeInput" min="-10" max="25" step="0.5" value="0">
                        <div class="value-display"><span id="slopeValue">0</span>% Grade</div>
                        <span class="tooltip-text" style="bottom: 100%;">Adjust the elevation of the road (positive = uphill, negative = downhill)</span>
                    </div>
                    <div class="control-group tooltip-container">
                        <label for="windInput">Wind Speed</label>
                        <input type="range" id="windInput" min="-50" max="50" step="1" value="0">
                        <div class="value-display"><span id="windValue">0</span> km/h</div>
                        <span class="tooltip-text" style="bottom: 100%;">Specify wind speed (headwind is negative, tailwind is positive)</span>
                    </div>
                </div>

                <div class="admin-panel" id="add-bike-section">
                    <h3 style="margin-bottom: 1rem; font-size: 1.1rem;">Add New Bicycle</h3>
                    <form action="{{ route('bicycles.store') }}" method="POST" class="bike-form">
                        @csrf
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <div class="tooltip-container">
                                <input type="text" name="name" placeholder="Bicycle Name" class="form-control" required>
                                <span class="tooltip-text">Give your bicycle setup a unique name (e.g., Mountain Beast)</span>
                            </div>
                            <div class="tooltip-container">
                                <input type="color" name="color" value="#38bdf8" class="form-control" style="height: 42px; padding: 2px;">
                                <span class="tooltip-text">Choose the color for your bicycle and rider</span>
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr 1fr; gap: 1rem;">
                            <div class="tooltip-container">
                                <input type="number" name="bicycle_weight" placeholder="Bike (kg)" step="0.1" class="form-control" required>
                                <span class="tooltip-text">Mass of the bicycle (kg)</span>
                            </div>
                            <div class="tooltip-container">
                                <input type="number" name="rider_weight" placeholder="Rider (kg)" step="0.1" class="form-control" required>
                                <span class="tooltip-text">Mass of the rider (kg)</span>
                            </div>
                            <div class="tooltip-container">
                                <input type="number" name="max_hr" placeholder="Max HR" value="190" class="form-control" required>
                                <span class="tooltip-text">Rider's Maximum Heart Rate (BPM)</span>
                            </div>
                            <div class="tooltip-container">
                                <input type="number" name="ftp" placeholder="FTP (W)" value="250" class="form-control" required>
                                <span class="tooltip-text">Functional Threshold Power (Sustainable for 1 hour)</span>
                            </div>
                            <div class="tooltip-container">
                                <input type="number" name="efficiency" placeholder="Eff (0.9-1.0)" step="0.01" min="0.5" max="1" value="1.0" class="form-control" required>
                                <span class="tooltip-text">Mechanical efficiency (1.0 is perfect)</span>
                            </div>
                        </div>
                        <div class="tooltip-container">
                                <input type="text" name="front_gears" placeholder="Front: 50,34" class="form-control" required>
                                <span class="tooltip-text">Chainring sizes, comma separated (e.g., 50,34)</span>
                            </div>
                            <div class="tooltip-container">
                                <input type="text" name="rear_gears" placeholder="Rear: 11,12..." class="form-control" required>
                                <span class="tooltip-text">Cassette cog sizes, comma separated (e.g., 11,12,13,14,15,17)</span>
                            </div>
                        <button type="submit" class="btn">Register Rider</button>
                    </form>
                </div>
            </div>

            <div class="bike-sidebar">
                <h2 style="font-size: 1.25rem;">Racedeck</h2>
                <div class="bike-list">
                    @forelse($bicycles as $bike)
                    <div class="bike-card" id="bike-{{ $bike->id }}">
                        <!-- Bonk Alert Overlay -->
                        <div id="bonk-overlay-{{ $bike->id }}" class="bonk-overlay">
                            <div class="bonk-title">BONKED!</div>
                            <p style="color: rgba(255,255,255,0.8); font-size: 0.8rem; margin-bottom: 1rem;">Out of fuel. Slowing down...</p>
                            <button onclick="refuelRider({{ $bike->id }})" class="btn-refuel">
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-6h2v6zm4 0h-2v-6h2v6z"/></svg>
                                Eat Power Gel (+500 kcal)
                            </button>
                        </div>

                        <div style="position: absolute; top: 0.75rem; right: 0.75rem; display: flex; gap: 0.5rem;">
                            <button onclick="openEditModal({{ json_encode($bike) }})" class="edit-btn" style="background: none; border: none; color: var(--accent); cursor: pointer; opacity: 0.5; transition: opacity 0.2s;" title="Edit Rider">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </button>
                            <form action="{{ route('bicycles.destroy', $bike->id) }}" method="POST" onsubmit="return confirm('Remove rider?')" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn" style="position: static;">✕</button>
                            </form>
                        </div>

                        <div class="bike-header">
                            <div class="bike-color" style="background-color: {{ $bike->color }}"></div>
                            <span class="bike-name">{{ $bike->name }}</span>
                            <span style="font-size: 0.7rem; opacity: 0.5;">(B:{{ $bike->bicycle_weight }}kg + R:{{ $bike->rider_weight }}kg / {{ $bike->efficiency * 100 }}%)</span>
                        </div>

                        <div class="control-group tooltip-container" style="margin-bottom: 1rem;">
                            <label>Rider Power</label>
                            <input type="range" class="bike-power-input" data-bike-id="{{ $bike->id }}" min="0" max="1000" value="200">
                            <div class="value-display" style="font-size: 0.9rem;"><span id="power-display-{{ $bike->id }}">200</span> Watts</div>
                            <span class="tooltip-text" style="bottom: 110%;">Target wattage for this rider. High power depletes stamina!</span>
                        </div>
                        
                        <div class="gear-controls">
                            <div class="control-group">
                                <label>Chainring</label>
                                <select class="front-gear-select" data-bike-id="{{ $bike->id }}">
                                    @foreach($bike->front_gears as $gear)
                                        <option value="{{ $gear }}">{{ $gear }}T</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="control-group">
                                <label>Cassette</label>
                                <select class="rear-gear-select" data-bike-id="{{ $bike->id }}">
                                    @foreach($bike->rear_gears as $gear)
                                        <option value="{{ $gear }}">{{ $gear }}T</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="control-group" style="margin-top: 1rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                                <label style="margin: 0; font-size: 0.75rem; opacity: 0.7;">Nutrition (Fuel)</label>
                                <span style="font-size: 0.75rem; font-weight: bold; color: var(--text-secondary);"><span id="cal-num-{{ $bike->id }}">2000</span> kcal</span>
                            </div>
                            <div class="stamina-container" style="height: 8px;">
                                <div id="stamina-bar-{{ $bike->id }}" class="stamina-bar" style="width: 100%;"></div>
                            </div>
                            <div style="margin-top: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
                                <label class="tooltip-container" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.7rem; opacity: 0.8;">
                                    <input type="checkbox" id="auto-refuel-{{ $bike->id }}" style="accent-color: var(--success);"> 
                                    Auto-Refuel
                                    <span class="tooltip-text">Automatically refuel at the global interval set in the header</span>
                                </label>
                                <button onclick="refuelRider({{ $bike->id }})" class="btn-refuel">
                                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-6h2v6zm4 0h-2v-6h2v6z"/></svg>
                                    Fuel Up
                                </button>
                            </div>
                        </div>

                        <div class="control-group" style="margin-top: 1rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                                <label style="margin: 0; font-size: 0.75rem; opacity: 0.7;">Stamina (Anaerobic Tank W')</label>
                                <span style="font-size: 0.75rem; font-weight: bold; color: var(--accent);"><span id="stamina-num-{{ $bike->id }}">100</span>%</span>
                            </div>
                            <div class="stamina-container" style="height: 8px; background: rgba(56, 189, 248, 0.1);">
                                <div id="stamina-tank-{{ $bike->id }}" class="stamina-bar" style="width: 100%; background: var(--accent);"></div>
                            </div>
                        </div>

                        <div class="control-group tooltip-container" style="margin-top: 1rem;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                                <label style="margin: 0; font-size: 0.75rem; opacity: 0.7;">Fatigue (Exhaustion Level)</label>
                                <span style="font-size: 0.75rem; font-weight: bold; color: var(--fatigue);"><span id="fatigue-num-{{ $bike->id }}">0</span>%</span>
                            </div>
                            <div class="stamina-container" style="height: 8px; background: rgba(249, 115, 22, 0.1);">
                                <div id="fatigue-bar-{{ $bike->id }}" class="stamina-bar" style="width: 0%; background: var(--fatigue);"></div>
                            </div>
                            <span class="tooltip-text" style="bottom: 110%;">Fatigue increases with high power, low cadence, steep hills, and headwinds. High fatigue caps your max power and slows recovery!</span>
                        </div>

                        
                        <div class="bike-stats">
                            <div class="stat-item">
                                <span class="stat-label">Heart Rate <span id="hr-zone-{{ $bike->id }}" style="font-size: 0.7em; opacity: 0.7; margin-left: 4px;">Z1</span></span>
                                <div class="stat-value"><span id="hr-{{ $bike->id }}">70</span> <span style="font-size: 0.6em;">BPM</span></div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Velocity</span>
                                <div class="stat-value"><span id="speed-{{ $bike->id }}">0.0</span> <span style="font-size: 0.6em;">km/h</span></div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Cadence</span>
                                <span class="stat-value"><span id="cadence-{{ $bike->id }}">0</span> <small>RPM</small></span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Ratio</span>
                                <span class="stat-value" id="ratio-{{ $bike->id }}">1.00</span>
                            </div>
                            <div class="stat-item" style="grid-column: span 2;">
                                <span class="stat-label">Combined Progress</span>
                                <div class="stat-value" style="font-size: 0.9rem;">
                                    <span id="dist-m-{{ $bike->id }}">0</span> m | 
                                    <span id="dist-km-{{ $bike->id }}">0.00</span> km
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                        <p style="color: var(--text-secondary); text-align: center; margin-top: 2rem;">No riders registered.</p>
                    @endforelse
                </div>
            </div>
    </div>

    <!-- Debug Info Overlay -->
    <div id="sim-debug-overlay" style="position: absolute; top: 10px; left: 10px; background: rgba(15, 23, 42, 0.85); color: #22c55e; padding: 10px; border-radius: 8px; font-family: 'JetBrains Mono', monospace; font-size: 10px; z-index: 100; border: 1px solid #22c55e; pointer-events: none; line-height: 1.4;">
        <div>SIM STATUS: <span id="debug-status">Initializing...</span></div>
        <div>RIDERS: <span id="debug-riders">0</span></div>
        <div>RESOLUTION: <span id="debug-res">0x0</span></div>
        <div>FRAMES: <span id="debug-frames">0</span></div>
        <div>CANVAS: <span id="debug-canvas">Checking...</span></div>
    </div>

    <div id="js-error-log" style="position: fixed; top: 10px; right: 10px; background: rgba(220, 38, 38, 1); color: white; padding: 20px; border-radius: 12px; z-index: 99999; display: none; max-width: 450px; font-size: 0.9rem; box-shadow: 0 10px 30px rgba(0,0,0,0.5); border: 2px solid white;">
        <strong style="font-size: 1.1rem; border-bottom: 2px solid rgba(255,255,255,0.3); display: block; margin-bottom: 10px; padding-bottom: 5px;">🛑 CRITICAL JAVASCRIPT ERROR</strong>
        <div id="error-msg" style="margin-top: 5px; font-family: 'JetBrains Mono', monospace; white-space: pre-wrap; word-break: break-all;"></div>
        <button onclick="this.parentElement.style.display='none'" style="margin-top: 15px; background: white; color: black; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-weight: bold; width: 100%;">Dismiss Error</button>
    </div>

    <script>
        // GLOBAL ERROR CATCHER
        window.onerror = function(msg, url, lineNo, columnNo, error) {
            const log = document.getElementById('js-error-log');
            const msgElem = document.getElementById('error-msg');
            if (log && msgElem) {
                log.style.display = 'block';
                msgElem.innerText = `${msg}\n\nLocation: ${url}:${lineNo}:${columnNo}`;
            }
            console.error("Antigravity Sim Error:", msg, error);
            return false;
        };

        // WRAP EVERYTHING IN ONLOAD
        window.onload = function() {
            const bikesData = @json($bicycles);
            const debugRiders = document.getElementById('debug-riders');
            const debugStatus = document.getElementById('debug-status');
            const debugRes = document.getElementById('debug-res');
            const debugFrames = document.getElementById('debug-frames');
            const debugCanvas = document.getElementById('debug-canvas');
            
            if (debugRiders) debugRiders.innerText = bikesData.length;
            
            const canvas = document.getElementById('simCanvas');
            if (!canvas) {
                if (debugStatus) debugStatus.innerText = "CRASHED (No Canvas)";
                return;
            }
            
            const ctx = canvas.getContext('2d');
            if (!ctx) {
                if (debugStatus) debugStatus.innerText = "CRASHED (No Context)";
                return;
            }
            if (debugCanvas) debugCanvas.innerText = "Ready";

            const slopeInput = document.getElementById('slopeInput');
            const slopeValue = document.getElementById('slopeValue');
            const windInput = document.getElementById('windInput');
            const windValue = document.getElementById('windValue');
            const globalRefuelDist = document.getElementById('globalRefuelDist');

            let currentPower = 200;
            let currentSlope = 0; 
            let currentWind = 0; 
            let frameCount = 0;
            let animationFrame;
            let isPlaying = true;

            const RHO = 1.225; 
            const CRR = 0.005; 
            const G = 9.81;    
            const CDA = 0.32;  

            const clouds = Array.from({length: 6}, () => ({
                x: Math.random() * 2000,
                y: 50 + Math.random() * 80,
                size: 30 + Math.random() * 50,
                speed: 0.1 + Math.random() * 0.2
            }));

            const bikeState = bikesData.map(bike => {
                const frontGears = Array.isArray(bike.front_gears) ? bike.front_gears : [50, 34];
                const rearGears = Array.isArray(bike.rear_gears) ? bike.rear_gears : [11, 28];
                
                return {
                    ...bike,
                    currentFrontGear: frontGears[0] || 50,
                    currentRearGear: rearGears[0] || 11,
                    power: 200,
                    position: 0,
                    speed: 0,
                    distance: 0,
                    cadence: 0,
                    hr: 70,
                    max_hr: bike.max_hr || 190,
                    ftp: bike.ftp || 250,
                    staminaW: 100,
                    calories: 2000, 
                    maxCalories: 2000,
                    fatigue: 0,
                    totalJoules: 0,
                    lastRefuelDistance: 0,
                    isBonking: false,
                    isDrafting: false
                };
            });

        function calculateSpeed(power, weight, efficiency, slopePercent, windKmh, draftFactor = 1.0) {
            const effectivePower = power * efficiency;
            if (effectivePower <= 0) return 0;
            
            const slopeAngle = Math.atan(slopePercent / 100);
            const m = weight + 75; 
            const windMs = windKmh / 3.6;

            // F_gravity = m * g * sin(theta)
            // F_roll = Crr * m * g * cos(theta)
            // F_drag = 0.5 * rho * (v - v_wind)^2 * CdA * draftFactor
            
            const C_gravity = m * G * Math.sin(slopeAngle);
            const C_roll = CRR * m * G * Math.cos(slopeAngle);
            const C_drag_base = 0.5 * RHO * CDA * draftFactor;

            let low = 0;
            let high = 60; 
            for(let i=0; i<30; i++) {
                let mid = (low + high) / 2;
                // Power = (F_gravity + F_roll + F_drag) * v
                // Note: v is relative to ground, v_air = v - v_wind
                let relativeAirSpeed = mid - windMs;
                let dragForce = C_drag_base * relativeAirSpeed * Math.abs(relativeAirSpeed); 
                let p = (C_gravity + C_roll + dragForce) * mid;
                
                if (p < effectivePower) low = mid;
                else high = mid;
            }
            return low;
        }

        function updateSimulation() {
            if (debugStatus) debugStatus.innerText = isPlaying ? "RUNNING" : "STOPPED";
            
            // Adjust canvas size safely
            const parent = canvas.parentElement;
            const targetWidth = parent ? parent.clientWidth : 800;
            const targetHeight = parent ? parent.clientHeight : 550;
            
            if (canvas.width !== targetWidth || canvas.height !== targetHeight) {
                canvas.width = targetWidth || 800;
                canvas.height = targetHeight || 550;
            }
            
            if (debugRes) debugRes.innerText = `${canvas.width}x${canvas.height}`;
            frameCount++;
            if (debugFrames) debugFrames.innerText = frameCount;

            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            const maxDist = (bikeState.length > 0) ? Math.max(...bikeState.map(b => b.distance), 0.001) : 0.001;
            const slopeAngle = Math.atan(currentSlope / 100);
            
            ctx.save();
            ctx.translate(canvas.width / 2, canvas.height / 2);
            ctx.rotate(-slopeAngle * 0.4); 
            ctx.translate(-canvas.width / 2, -canvas.height / 2);

            // Sky Elements
            ctx.fillStyle = 'rgba(255,255,255,0.05)';
            clouds.forEach(c => {
                const drawX = (c.x - maxDist * 5 * c.speed) % (canvas.width + 400) - 200;
                ctx.beginPath();
                ctx.arc(drawX, c.y, c.size, 0, Math.PI * 2);
                ctx.arc(drawX + 40, c.y, c.size*0.7, 0, Math.PI * 2);
                ctx.fill();
            });

            // Drafting Check
            // Sort bikes by distance to find who is ahead of whom
            const sortedBikes = [...bikeState].sort((a, b) => b.distance - a.distance);
            
            bikeState.forEach((bike, index) => {
                // Find someone directly ahead (within certain distance and same/nearby lanes)
                // For simplified visual, we check anyone with distance > current + within 5m
                const ahead = sortedBikes.find(b => b.id !== bike.id && b.distance > bike.distance && b.distance - bike.distance < 5);
                bike.isDrafting = !!ahead;
                const draftFactor = bike.isDrafting ? 0.7 : 1.0; // 30% reduction in drag

                const laneY = 180 + (index * 85);
                
                // Draw road
                ctx.fillStyle = bike.isDrafting ? 'rgba(56, 189, 248, 0.15)' : 'rgba(30, 41, 59, 0.4)';
                ctx.fillRect(-2000, laneY - 10, 5000, 40);
                
                // Road markings
                ctx.strokeStyle = bike.isDrafting ? 'rgba(56, 189, 248, 0.3)' : 'rgba(255,255,255,0.08)';
                ctx.lineWidth = 2;
                ctx.setLineDash([25, 35]);
                ctx.lineDashOffset = (maxDist * 30) % 60;
                ctx.beginPath();
                ctx.moveTo(-2000, laneY + 20);
                ctx.lineTo(5000, laneY + 20);
                ctx.stroke();
                ctx.setLineDash([]);

                // Bioenergetics Model
                let effectiveRiderPower = bike.power;
                
                // Fatigue Impact: Power Ceiling
                // Max power drops as fatigue increases. At 100% fatigue, max power is 60% of original.
                const fatigueMultiplier = 1 - (bike.fatigue / 100) * 0.4;
                effectiveRiderPower = Math.min(effectiveRiderPower, 1000 * fatigueMultiplier);

                if (bike.isBonking) effectiveRiderPower *= 0.35; // 65% power drop when bonking

                // Calculate Physics
                let speed = 0;
                if (isPlaying) {
                    const totalWeight = bike.bicycle_weight + bike.rider_weight;
                    speed = calculateSpeed(effectiveRiderPower, totalWeight, bike.efficiency, currentSlope, currentWind, draftFactor);
                    bike.speed = speed;
                    bike.distance += speed * (1/60); 

                    // Calorie Expenditure: 1W = 1 J/s. 1 kcal = 4184 J. 
                    // Considering Human Efficiency (~24%), kcal/s = Power / 4184 / 0.24
                    const kcalPerSec = (bike.power / 4184 / 0.24);
                    bike.calories = Math.max(0, bike.calories - kcalPerSec);

                    // Stamina (W' model)
                    // If power > FTP, stamina depletes. If power < FTP, it recharges.
                    const ftp = bike.ftp;
                    if (bike.power > ftp) {
                        // Deplete stamina tank based on intensity over FTP
                        const intensity = (bike.power - ftp) / 1000; 
                        bike.staminaW = Math.max(0, bike.staminaW - intensity);
                    } else {
                        // Recharge stamina tank - FATIGUE slows down recovery by up to 50%
                        const recoveryMultiplier = 1 - (bike.fatigue / 100) * 0.5;
                        const recharge = ((ftp - bike.power) / 2000) * recoveryMultiplier;
                        bike.staminaW = Math.min(100, bike.staminaW + recharge);
                    }

                    // FATIGUE ACCUMULATION LOGIC
                    // 1. Work Accumulation (Joules = Watts * seconds)
                    const frameJoules = bike.power * (1/60);
                    bike.totalJoules += frameJoules;
                    const workFatigue = (frameJoules / 100000) * 0.5; // Every 100kJ spent adds 0.5%
                    
                    // 2. Intensity Stress (Low Stamina/High HR)
                    const intensityStress = (100 - bike.staminaW) > 50 ? 0.01 : 0;
                    
                    // 3. Neuro-muscular Penalty (Grinding: low cadence, high power)
                    let grindingPenalty = 0;
                    if (bike.cadence < 60 && bike.power > ftp) {
                        grindingPenalty = ( (60 - bike.cadence) / 60 ) * (bike.power / ftp) * 0.02;
                    }

                    // 4. Environmental Stress (Steep Slope/Wind)
                    const envStress = (Math.abs(currentSlope) > 8 ? 0.005 : 0) + (currentWind < -20 ? 0.005 : 0);

                    bike.fatigue = Math.min(100, bike.fatigue + workFatigue + intensityStress + grindingPenalty + envStress);
                    
                    if (bike.calories < 100 && !bike.isBonking) {
                        bike.isBonking = true;
                    } else if (bike.calories >= 200 && bike.isBonking) {
                        bike.isBonking = false;
                    }

                    // Auto-Refuel logic (based on global interval)
                    const globalInterval = (globalRefuelDist ? parseFloat(globalRefuelDist.value) : 1) * 1000;
                    const autoRefuelCheck = document.getElementById(`auto-refuel-${bike.id}`);
                    if (autoRefuelCheck && autoRefuelCheck.checked) {
                        if (bike.distance - bike.lastRefuelDistance >= globalInterval) {
                            refuelRider(bike.id);
                            bike.lastRefuelDistance = bike.distance;
                        }
                    }

                    // Heart Rate simulation
                    // Base HR + intensity + strain + FATIGUE DRIFT
                    const powerPerKg = bike.power / totalWeight;
                    const fatiguingHr = (100 - bike.staminaW) * 0.15;
                    const fatigueDrift = bike.fatigue * 0.2; // Fatigue adds up to 20 BPM drift
                    const targetHr = 60 + (powerPerKg * 35) + fatiguingHr + fatigueDrift + (bike.isBonking ? 10 : 0);
                    bike.hr += (targetHr - bike.hr) * 0.005;
                    if (bike.hr > bike.max_hr) bike.hr = bike.max_hr;
                } else {
                    speed = 0;
                    bike.speed = 0;
                }
                
                const ratio = bike.currentFrontGear / bike.currentRearGear;
                const circ = (bike.wheel_diameter * Math.PI) / 1000;
                // Cadence (RPM)
                bike.cadence = (bike.speed / (ratio * circ)) * 60;

                const screenPos = 350 + (bike.distance - maxDist) * 80;

                // UI Update
                if (Math.floor(Date.now() / 100) % 2 === 0) {
                    const sElem = document.getElementById(`speed-${bike.id}`);
                    const cElem = document.getElementById(`cadence-${bike.id}`);
                    const dmElem = document.getElementById(`dist-m-${bike.id}`);
                    const dkmElem = document.getElementById(`dist-km-${bike.id}`);
                    const hrElem = document.getElementById(`hr-${bike.id}`);
                    const hrZoneElem = document.getElementById(`hr-zone-${bike.id}`);
                    const fuelElem = document.getElementById(`stamina-bar-${bike.id}`);
                    const stamTankElem = document.getElementById(`stamina-tank-${bike.id}`);
                    const stamNumElem = document.getElementById(`stamina-num-${bike.id}`);
                    const calElem = document.getElementById(`cal-num-${bike.id}`);
                    const fatigueBarElem = document.getElementById(`fatigue-bar-${bike.id}`);
                    const fatigueNumElem = document.getElementById(`fatigue-num-${bike.id}`);
                    const bonkElem = document.getElementById(`bonk-overlay-${bike.id}`);
                    
                    if(sElem) sElem.innerText = (speed * 3.6).toFixed(1);
                    if(cElem) {
                        cElem.innerText = Math.round(bike.cadence);
                        cElem.style.color = bike.cadence > 110 ? 'var(--danger)' : (bike.cadence < 60 ? '#f59e0b' : '#38bdf8');
                        if (bike.cadence > 110) cElem.title = "Cadence too high! Shift up.";
                        else if (bike.cadence < 60) cElem.title = "Cadence too low! Shift down.";
                    }

                    if(hrElem) {
                        hrElem.innerText = Math.round(bike.hr);
                        const hrP = (bike.hr / bike.max_hr) * 100;
                        let zone = "Z1";
                        let zoneColor = "#94a3b8"; 
                        if (hrP >= 90) { zone = "Z5"; zoneColor = "#ef4444"; }
                        else if (hrP >= 80) { zone = "Z4"; zoneColor = "#f97316"; }
                        else if (hrP >= 70) { zone = "Z3"; zoneColor = "#f59e0b"; }
                        else if (hrP >= 60) { zone = "Z2"; zoneColor = "#22c55e"; }
                        
                        hrElem.style.color = zoneColor;
                        if(hrZoneElem) {
                            hrZoneElem.innerText = zone;
                            hrZoneElem.style.color = zoneColor;
                        }
                    }

                    if(calElem) calElem.innerText = Math.round(bike.calories);
                    
                    if(bonkElem) {
                        if(bike.isBonking) bonkElem.classList.add('active');
                        else bonkElem.classList.remove('active');
                    }

                    if(fuelElem) {
                        const perc = (bike.calories / bike.maxCalories) * 100;
                        fuelElem.style.width = `${perc}%`;
                        fuelElem.style.background = perc < 15 ? 'var(--danger)' : (perc < 40 ? '#f59e0b' : 'var(--success)');
                    }

                    if(stamTankElem) {
                        stamTankElem.style.width = `${bike.staminaW}%`;
                        if(stamNumElem) stamNumElem.innerText = Math.round(bike.staminaW);
                    }

                    if(fatigueBarElem) {
                        fatigueBarElem.style.width = `${bike.fatigue}%`;
                        if(fatigueNumElem) fatigueNumElem.innerText = Math.round(bike.fatigue);
                        // Add glow if fatigue high
                        if (bike.fatigue > 80) fatigueBarElem.style.boxShadow = '0 0 10px var(--fatigue)';
                        else fatigueBarElem.style.boxShadow = 'none';
                    }
                    
                    if(dmElem) dmElem.innerText = Math.floor(bike.distance);
                    if(dkmElem) dkmElem.innerText = (bike.distance / 1000).toFixed(2);
                }

                drawBike(screenPos, laneY, bike.color, bike.cadence, bike.name, bike.isDrafting);
            });
            ctx.restore();

            try {
                animationFrame = requestAnimationFrame(updateSimulation);
            } catch (e) {
                console.error("Simulation Loop Error:", e);
                const log = document.getElementById('js-error-log');
                const msgElem = document.getElementById('error-msg');
                if (log && msgElem) {
                    log.style.display = 'block';
                    msgElem.innerText = "Loop Error: " + e.message;
                }
            }
        }

        function drawBike(x, y, color, cadence, name, isDrafting) {
            const legAngle = (Date.now() / 1000) * (cadence / 60) * Math.PI * 2;
            const scale = 0.85;
            
            // Drafting visual (sparkle/slipstream trailing)
            if (isDrafting) {
                ctx.strokeStyle = 'rgba(56, 189, 248, 0.4)';
                ctx.lineWidth = 1;
                for(let i=0; i<3; i++) {
                    const trailX = x - 20 - Math.random() * 30;
                    const trailY = y - 10 - Math.random() * 20;
                    ctx.beginPath();
                    ctx.moveTo(trailX, trailY);
                    ctx.lineTo(trailX - 10, trailY);
                    ctx.stroke();
                }
            }

            ctx.fillStyle = color;
            ctx.strokeStyle = color;
            ctx.lineWidth = 3 * scale;

            ctx.beginPath();
            ctx.moveTo(x, y);
            ctx.lineTo(x + 50*scale, y);
            ctx.lineTo(x + 25*scale, y - 45*scale);
            ctx.closePath();
            ctx.stroke();

            ctx.beginPath();
            ctx.arc(x, y, 18*scale, 0, Math.PI * 2);
            ctx.stroke();
            ctx.beginPath();
            ctx.arc(x + 50*scale, y, 18*scale, 0, Math.PI * 2);
            ctx.stroke();

            ctx.fillStyle = "#f8fafc";
            ctx.beginPath();
            ctx.arc(x + 35*scale, y - 60*scale, 7*scale, 0, Math.PI * 2); 
            ctx.fill();

            ctx.strokeStyle = "#f8fafc";
            ctx.beginPath();
            ctx.moveTo(x + 25*scale, y - 40*scale);
            ctx.lineTo(x + 35*scale, y - 60*scale);
            ctx.stroke();

            ctx.strokeStyle = color;
            ctx.beginPath();
            ctx.moveTo(x + 25*scale, y - 25*scale); 
            const footX = x + 25*scale + Math.cos(legAngle) * 12*scale;
            const footY = y - 25*scale + Math.sin(legAngle) * 12*scale;
            ctx.lineTo(footX, footY);
            ctx.stroke();

            ctx.fillStyle = "rgba(255,255,255,0.7)";
            ctx.font = "bold 11px Inter";
            ctx.textAlign = "center";
            const displayName = (name || "Rider").toString();
            ctx.fillText(displayName.substring(0, 15), x + 25*scale, y + 45*scale);
        }

        document.addEventListener('input', (e) => {
            if (e.target.classList.contains('bike-power-input')) {
                const id = e.target.getAttribute('data-bike-id');
                const bike = bikeState.find(b => b.id == id);
                if(bike) {
                    bike.power = parseInt(e.target.value);
                    const display = document.getElementById(`power-display-${id}`);
                    if(display) display.innerText = bike.power;
                }
            }
        });

        if (slopeInput) {
            slopeInput.oninput = (e) => {
                currentSlope = parseFloat(e.target.value);
                if (slopeValue) slopeValue.innerText = currentSlope;
            };
        }

        if (windInput) {
            windInput.oninput = (e) => {
                currentWind = parseFloat(e.target.value);
                if (windValue) windValue.innerText = currentWind;
            };
        }

        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('front-gear-select')) {
                const id = e.target.getAttribute('data-bike-id');
                const bike = bikeState.find(b => b.id == id);
                if(bike) bike.currentFrontGear = parseInt(e.target.value);
            }
            if (e.target.classList.contains('rear-gear-select')) {
                const id = e.target.getAttribute('data-bike-id');
                const bike = bikeState.find(b => b.id == id);
                if(bike) bike.currentRearGear = parseInt(e.target.value);
            }
        });

        function resetSimulation() {
            bikeState.forEach(b => {
                b.distance = 0;
            });
        }

        window.openEditModal = (bike) => {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editBikeForm');
            
            form.action = `/bicycles/${bike.id}`;
            document.getElementById('edit-name').value = bike.name;
            document.getElementById('edit-color').value = bike.color;
            document.getElementById('edit-bicycle_weight').value = bike.bicycle_weight;
            document.getElementById('edit-rider_weight').value = bike.rider_weight;
            document.getElementById('edit-max_hr').value = bike.max_hr || 190;
            document.getElementById('edit-ftp').value = bike.ftp || 250;
            document.getElementById('edit-efficiency').value = bike.efficiency;
            document.getElementById('edit-front_gears').value = bike.front_gears.join(',');
            document.getElementById('edit-rear_gears').value = bike.rear_gears.join(',');
            
            modal.style.display = "block";
        };

        window.closeEditModal = () => {
            document.getElementById('editModal').style.display = "none";
        };

        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };

        // Playback Events
        const playBtn = document.getElementById('playBtn');
        const pauseBtn = document.getElementById('pauseBtn');
        const resetPlayBtn = document.getElementById('resetPlayBtn');

        playBtn.onclick = () => {
            isPlaying = true;
            playBtn.classList.add('active');
            pauseBtn.classList.remove('active');
        };

        pauseBtn.onclick = () => {
            isPlaying = false;
            pauseBtn.classList.add('active');
            playBtn.classList.remove('active');
        };

        resetPlayBtn.onclick = () => {
            isPlaying = false;
            bikeState.forEach(b => {
                b.distance = 0;
                b.calories = b.maxCalories;
                b.hr = 70;
                b.isBonking = false;
            });
            playBtn.classList.remove('active');
            pauseBtn.classList.add('active');
        };

        window.refuelRider = (bikeId) => {
            const bike = bikeState.find(b => b.id == bikeId);
            if (bike) {
                bike.calories = Math.min(bike.maxCalories, bike.calories + 500);
                if (bike.calories > 200) bike.isBonking = false;
                
                // Visual feedback (toast)
                const toast = document.createElement('div');
                toast.className = 'toast';
                toast.innerText = `+500 kcal for ${bike.name}!`;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 2000);
            }
        };

            updateSimulation();
        }; // End window.onload
    </script>
    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Bicycle Setup</h2>
                <span class="close" onclick="closeEditModal()">&times;</span>
            </div>
            <form id="editBikeForm" method="POST" class="bike-form">
                @csrf
                @method('PATCH')
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="tooltip-container">
                        <input type="text" name="name" id="edit-name" placeholder="Bicycle Name" class="form-control" required>
                        <span class="tooltip-text">Give your bicycle setup a unique name</span>
                    </div>
                    <div class="tooltip-container">
                        <input type="color" name="color" id="edit-color" class="form-control" style="height: 42px; padding: 2px;">
                        <span class="tooltip-text">Choose the color for your bicycle and rider</span>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                    <div class="tooltip-container">
                        <input type="number" name="bicycle_weight" id="edit-bicycle_weight" placeholder="Bike (kg)" step="0.1" class="form-control" required>
                        <span class="tooltip-text">The mass of the bicycle itself</span>
                    </div>
                    <div class="tooltip-container">
                        <input type="number" name="rider_weight" id="edit-rider_weight" placeholder="Rider (kg)" step="0.1" class="form-control" required>
                        <span class="tooltip-text">Total mass of the rider including gear</span>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                    <div class="tooltip-container">
                        <input type="number" name="max_hr" id="edit-max_hr" placeholder="Max HR" class="form-control" required>
                        <span class="tooltip-text">Maximum Heart Rate</span>
                    </div>
                    <div class="tooltip-container">
                        <input type="number" name="ftp" id="edit-ftp" placeholder="FTP (W)" class="form-control" required>
                        <span class="tooltip-text">Functional Threshold Power</span>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                    <div class="tooltip-container">
                        <input type="number" name="efficiency" id="edit-efficiency" placeholder="Eff (0.9-1.0)" step="0.01" min="0.5" max="1" class="form-control" required>
                        <span class="tooltip-text">Drivetrain mechanical efficiency</span>
                    </div>
                    <div class="tooltip-container">
                        <input type="text" name="front_gears" id="edit-front_gears" placeholder="Front: 50,34" class="form-control" required>
                        <span class="tooltip-text">Chainring sizes, comma separated</span>
                    </div>
                    <div class="tooltip-container">
                        <input type="text" name="rear_gears" id="edit-rear_gears" placeholder="Rear: 11,12..." class="form-control" required>
                        <span class="tooltip-text">Cassette cog sizes, comma separated</span>
                    </div>
                </div>
                <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem;">
                    <button type="button" class="btn btn-outline" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
