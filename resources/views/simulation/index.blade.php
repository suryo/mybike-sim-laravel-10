<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyBike Simulation Pro v2.1</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <!-- Leaflet Map CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
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
            width: 100%;
            max-width: 1920px;
            margin: 0 auto;
            padding: 1.5rem;
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
            grid-template-columns: 1fr 420px;
            gap: 1.5rem;
            margin-bottom: 3rem;
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
            max-height: 100%;
        }

        .bike-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .bike-card {
            background: rgba(30, 41, 59, 0.4);
            padding: 0.75rem; /* More compact */
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,0.05);
            position: relative;
            transition: all 0.2s;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .bike-card:hover { border-color: var(--primary); background: rgba(30, 41, 59, 0.6); }

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
            gap: 0.5rem; /* Reduced gap */
            margin-bottom: 0.75rem;
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
            background: rgba(0,0,0,0.25);
            padding: 0.35rem 0.5rem;
            border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.03);
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

        /* Progress Bar Styles */
        .progress-container {
            width: 100%;
            height: 12px;
            background: rgba(0,0,0,0.3);
            border-radius: 6px;
            overflow: hidden;
            margin: 1rem 0;
            border: 1px solid rgba(255,255,255,0.05);
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary), var(--accent));
            width: 0%;
            transition: width 0.3s ease-out;
            position: relative;
        }

        .progress-text {
            position: absolute;
            width: 100%;
            text-align: center;
            font-size: 0.65rem;
            font-weight: 700;
            color: white;
            line-height: 12px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.5);
            z-index: 1;
        }

        .finish-badge {
            display: none;
            background: var(--success);
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-left: auto;
        }

        .bike-card.finished {
            border-color: var(--success);
            box-shadow: 0 0 15px rgba(34, 197, 94, 0.2);
        }

        .bike-card.finished .finish-badge {
            display: inline-block;
        }

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

        .meal-overlay {
            position: absolute;
            inset: 0;
            background: rgba(14, 165, 233, 0.9); /* Blue-ish */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius);
            z-index: 15;
            backdrop-filter: blur(6px);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
            border: 2px solid var(--accent);
        }

        .meal-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        .meal-title {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
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

        .btn-stat-badge {
            font-size: 0.6rem;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            pointer-events: none;
            transition: all 0.3s ease;
        }
        }

        .btn-refuel:hover { transform: scale(1.05); background: #d97706; }

        /* Playback Controls - Sleeker Bar */
        .playback-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            background: #1e293b;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
        }

        .playback-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.4rem 1rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            transition: all 0.2s;
            color: white;
            min-height: 34px;
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

        /* Playback Speed Controls */
        .speed-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 3px;
            margin: 0 10px;
            padding: 3px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            max-width: 300px;
        }

        .speed-btn {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--text-secondary);
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.75rem;
            font-family: 'JetBrains Mono', monospace;
            transition: all 0.2s;
            min-width: 45px;
            text-align: center;
        }

        .speed-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .speed-btn.active {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
            box-shadow: 0 0 10px rgba(56, 189, 248, 0.3);
        }

        .conflict-alert {
            background: rgba(220, 38, 38, 0.15);
            color: #f87171;
            border: 1px solid rgba(220, 38, 38, 0.4);
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 0.75rem;
            margin: 0.5rem 0;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
            border-left: 3px solid #ef4444;
        }

        .timer-display {
            font-family: 'JetBrains Mono', monospace;
            background: rgba(15, 23, 42, 0.6);
            color: var(--success);
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            border: 1px solid rgba(34, 197, 94, 0.3);
            box-shadow: inset 0 0 10px rgba(34, 197, 94, 0.1);
            min-width: 100px;
            text-align: center;
        }

        /* Phase 6: Log & Cadence Lock Styles */
        .log-section {
            margin-top: 1rem;
            background: rgba(15, 23, 42, 0.4);
            border-radius: 8px;
            padding: 0.8rem;
            border: 1px solid rgba(255,255,255,0.05);
        }
        .log-header {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            opacity: 0.6;
            margin-bottom: 0.5rem;
            display: flex;
            justify-content: space-between;
        }
        .log-list {
            max-height: 80px;
            overflow-y: auto;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }
        .log-list::-webkit-scrollbar { width: 4px; }
        .log-list::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }
        .log-item {
            padding: 2px 0;
            border-bottom: 1px solid rgba(255,255,255,0.03);
            display: flex;
            justify-content: space-between;
        }
        .log-item:last-child { border-bottom: none; }
        .log-time { color: var(--text-secondary); opacity: 0.7; }
        .log-val { color: var(--primary); font-weight: bold; }

        .cadence-clickable {
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }
        .cadence-clickable:hover {
            background: rgba(56, 189, 248, 0.15) !important;
            box-shadow: 0 0 10px rgba(56, 189, 248, 0.2);
        }
        .lock-indicator {
            font-size: 0.6rem;
            background: var(--primary);
            color: #0f172a;
            padding: 1px 5px;
            border-radius: 4px;
            font-weight: 800;
            position: absolute;
            top: 5px;
            right: 5px;
        }
        .sidebar-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        /* Segment Planner Styles */
        .segment-panel {
            background: rgba(15, 23, 42, 0.6);
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(8px);
        }
        .segment-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 8px;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            position: relative;
        }
        .segment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .segment-controls {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 0.5rem;
        }
        .segment-delete {
            color: var(--danger);
            cursor: pointer;
            padding: 2px;
            opacity: 0.6;
        }
        .segment-delete:hover { opacity: 1; }
        .segment-zone-color {
            width: 8px;
            height: 100%;
            position: absolute;
            left: 0;
            top: 0;
            border-radius: 8px 0 0 8px;
        }

        /* Session History Modal */
        .session-list {
            display: grid;
            gap: 0.75rem;
            max-height: 400px;
            overflow-y: auto;
            padding-right: 5px;
        }
        .session-item {
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 12px;
            padding: 1rem;
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 2px;
        }
        .session-item:hover {
            background: rgba(255,255,255,0.05);
            border-color: rgba(56, 189, 248, 0.3);
            transform: translateX(4px);
        }
        .session-info h4 { margin: 0; font-size: 0.95rem; font-weight: 700; color: white; }
        .session-info p { margin: 4px 0 8px; font-size: 0.75rem; opacity: 0.5; font-weight: 500; }
        .session-stats { display: flex; gap: 1rem; font-size: 0.75rem; font-family: 'JetBrains Mono'; color: var(--text-secondary); }
        .session-stats span { background: rgba(0,0,0,0.2); padding: 2px 8px; border-radius: 4px; }
        .session-actions { display: flex; gap: 0.5rem; }
        .btn-sm-danger { background: rgba(239, 68, 68, 0.2); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); padding: 4px 8px; border-radius: 4px; font-size: 0.65rem; cursor: pointer; }
        .btn-sm-danger:hover { background: rgba(239, 68, 68, 0.3); }

        .btn-add-segment {
            width: 100%;
            background: rgba(255,255,255,0.05);
            border: 1px dashed rgba(255,255,255,0.2);
            color: white;
            padding: 0.5rem;
            border-radius: 8px;
            font-size: 0.75rem;
            cursor: pointer;
            margin-top: 0.5rem;
        }
        .btn-add-segment:hover { background: rgba(255,255,255,0.1); }
        .sidebar-title::after {
            content: "";
            flex-grow: 1;
            height: 1px;
            background: rgba(255,255,255,0.1);
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
            <div style="display: flex; gap: 1rem; align-items: center;">
                <a href="{{ route('home') }}" class="playback-btn" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); font-size: 0.75rem; padding: 6px 14px; height: auto; width: auto; font-weight: bold; color: white; display: flex; align-items: center; gap: 6px; text-decoration: none;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                    HOME
                </a>
                <h1>MyBike SIM Pro <small style="font-size: 0.5em; opacity: 0.5; vertical-align: middle;">v2.1</small></h1>
            </div>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <button onclick="openHistoryModal()" class="playback-btn" style="background: rgba(56, 189, 248, 0.1); border: 1px solid rgba(56, 189, 248, 0.2); font-size: 0.75rem; padding: 6px 14px; height: auto; width: auto; font-weight: bold; color: var(--accent); display: flex; align-items: center; gap: 6px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    SAVED RESULTS
                </button>
                <div class="status-badge" style="background: var(--success); color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold;">LIVE RACING</div>
            </div>
        </header>

        <div class="main-layout">
            <div class="simulation-area">
                <div class="playback-header">
                    <div style="display: flex; gap: 0.5rem; align-items: center; border-right: 1px solid rgba(255,255,255,0.1); padding-right: 1rem; margin-right: 0.5rem;">
                        <div class="tooltip-container" style="display: flex; align-items: center; gap: 0.4rem;">
                            <label style="font-size: 0.65rem; color: var(--text-secondary); text-transform: uppercase;">Refuel</label>
                            <input type="number" id="globalRefuelDist" value="1.0" step="0.1" min="0.1" style="width: 45px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: white; padding: 2px 4px; border-radius: 4px; font-size: 0.7rem;">
                            <span style="font-size: 0.65rem; opacity: 0.5;">km</span>
                        </div>
                        <div class="tooltip-container" style="display: flex; align-items: center; gap: 0.4rem;">
                            <label style="font-size: 0.65rem; color: var(--text-secondary); text-transform: uppercase;">Meal</label>
                            <input type="number" id="mealInterval" value="10" step="1" min="1" style="width: 45px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: white; padding: 2px 4px; border-radius: 4px; font-size: 0.7rem;">
                            <span style="font-size: 0.65rem; opacity: 0.5;">min</span>
                        </div>
                    </div>

                    <div class="timer-display" id="globalTimer" style="min-width: 80px; font-size: 0.85rem; padding: 3px 8px;">00:00:00</div>

                    <div style="display: flex; gap: 10px; font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; align-items: center; border-left: 1px solid rgba(255,255,255,0.1); padding-left: 1rem;">
                        <span>Track: <input type="number" id="trackGoalInput" step="1" value="10" style="width: 50px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 4px; padding: 2px; font-size: 0.7rem;"> km</span>
                        <button onclick="openMapModal()" class="playback-btn" style="background: var(--accent); padding: 2px 6px; border-radius: 4px; display: flex; align-items: center; gap: 4px; font-size: 0.65rem; height: 22px; width: auto;" title="Plan on Map">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                            MAP
                        </button>
                        <span>Ascent: <input type="number" id="ascentGoalInput" step="1" value="200" style="width: 50px; background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 4px; padding: 2px; font-size: 0.7rem;"> m</span>
                    </div>

                    <div style="flex-grow: 1;"></div>
                    
                    <div class="speed-controls" id="speedControls">
                        <button class="speed-btn" data-speed="0.5">0.5x</button>
                        <button class="speed-btn active" data-speed="1">1.0x</button>
                        <button class="speed-btn" data-speed="2">2.0x</button>
                        <button class="speed-btn" data-speed="4">4.0x</button>
                        <button class="speed-btn" data-speed="10">10x</button>
                        <button class="speed-btn" data-speed="20">20x</button>
                        <button class="speed-btn" data-speed="40">40x</button>
                        <button class="speed-btn" data-speed="50">50x</button>
                        <button class="speed-btn" data-speed="60">60x</button>
                        <button class="speed-btn" data-speed="80">80x</button>
                        <button class="speed-btn" data-speed="100">100x</button>
                        <button class="speed-btn" data-speed="200">200x</button>
                    </div>

                    <div style="display: flex; gap: 5px;">
                        <button id="playBtn" class="playback-btn btn-play" title="Start Pedaling" style="background: var(--success); width: auto; padding: 0 10px; font-size: 0.7rem; font-weight: bold; gap: 5px;">
                            <svg fill="currentColor" viewBox="0 0 24 24" style="width:12px;"><path d="M8 5v14l11-7z"/></svg>
                            START
                        </button>
                        <button id="stopBtn" class="playback-btn" title="Stop/Coast" style="background: #ef4444; width: auto; padding: 0 10px; font-size: 0.7rem; font-weight: bold; gap: 5px;">
                            <svg fill="currentColor" viewBox="0 0 24 24" style="width:12px;"><path d="M6 6h12v12H6z"/></svg>
                            STOP
                        </button>
                        <button id="pauseBtn" class="playback-btn btn-pause" title="Freeze Simulation" style="background: #f59e0b;">
                            <svg fill="currentColor" viewBox="0 0 24 24" style="width:12px;"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                        </button>
                        <button id="resetPlayBtn" class="playback-btn btn-reset" title="Reset All" onclick="resetSimulation()">
                            <svg fill="currentColor" viewBox="0 0 24 24" style="width:14px;"><path d="M17.65 6.35A7.958 7.958 0 0012 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08A5.99 5.99 0 0112 18c-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Relocated Sim Debug Overlay -->
                <div id="sim-debug-overlay" style="margin-top: 10px; background: rgba(15, 23, 42, 0.4); color: #22c55e; padding: 12px 15px; border-radius: 10px; font-family: 'JetBrains Mono', monospace; font-size: 10px; z-index: 100; border: 1px solid rgba(34, 197, 94, 0.2); line-height: 1.6; display: flex; flex-wrap: wrap; gap: 20px; backdrop-filter: blur(4px);">
                    <div style="display: flex; gap: 6px;">
                        <span style="opacity: 0.5;">SIM STATUS:</span>
                        <span id="debug-status" style="font-weight: 800; text-transform: uppercase;">Initializing...</span>
                    </div>
                    <div style="display: flex; gap: 6px;">
                        <span style="opacity: 0.5;">ATHELETES:</span>
                        <span id="debug-riders" style="font-weight: 800; color: white;">0</span>
                    </div>
                    <div style="display: flex; gap: 6px;">
                        <span style="opacity: 0.5;">RES:</span>
                        <span id="debug-res" style="font-weight: 800; color: white;">0x0</span>
                    </div>
                    <div style="display: flex; gap: 6px;">
                        <span style="opacity: 0.5;">FRAMES:</span>
                        <span id="debug-frames" style="font-weight: 800; color: white;">0</span>
                    </div>
                    <div style="display: flex; gap: 6px;">
                        <span style="opacity: 0.5;">CANVAS:</span>
                        <span id="debug-canvas" style="font-weight: 800;">Checking...</span>
                    </div>
                </div>

                <div style="margin: 10px 0; display: flex; justify-content: flex-end;">
                     <button onclick="openHistoryModal()" class="playback-btn" style="background: rgba(56, 189, 248, 0.1); border: 1px solid rgba(56, 189, 248, 0.2); font-size: 0.65rem; padding: 4px 10px; height: auto; width: auto; font-weight: bold; color: var(--accent);">
                        📋 VIEW HISTORY
                    </button>
                </div>

                <!-- Integrated Map Section (Always Visible) -->
                <div id="map-section" style="display: grid; height: 640px; background: rgba(15, 23, 42, 0.4); border-bottom: 1px solid rgba(255,255,255,0.1); padding: 0.75rem; gap: 0.75rem; grid-template-columns: 1fr 280px; backdrop-filter: blur(4px); box-shadow: inset 0 0 40px rgba(0,0,0,0.2);">
                    <div id="map-container" style="position: relative; height: 100%; border-radius: 12px; overflow: hidden; border: 1px solid rgba(255,255,255,0.08);">
                        <div id="map" style="height: 100%; width: 100%;"></div>
                        <div id="map-instructions" style="position: absolute; top: 10px; left: 10px; z-index: 1000; background: rgba(15, 23, 42, 0.85); padding: 8px 12px; border-radius: 8px; font-size: 0.7rem; border: 1px solid rgba(255,255,255,0.1); width: auto; pointer-events: none; backdrop-filter: blur(8px); display: flex; gap: 15px; align-items: center; color: var(--text-secondary);">
                            <span style="font-weight: 800; color: var(--accent); letter-spacing: 0.05em;">TRACK PLANNER</span>
                            <span>• Click map to set <b>START</b> & <b>CPs</b></span>
                            <span>• Last point is <b>GOAL</b></span>
                        </div>
                    </div>
                    
                    <div class="route-panel" style="background: rgba(255,255,255,0.03); border-radius: 12px; padding: 1rem; display: flex; flex-direction: column; border: 1px solid rgba(255,255,255,0.08); backdrop-filter: blur(4px); overflow: hidden;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 0.75rem;">
                            <h4 style="font-size: 0.75rem; text-transform: uppercase; color: var(--accent); letter-spacing: 0.1em; margin: 0; font-weight: 800;">📍 WAYPOINTS</h4>
                            <div style="display: flex; flex-direction: column; align-items: flex-end;">
                                <span id="map-total-dist-display" style="color: white; font-family: 'JetBrains Mono', monospace; font-size: 1rem; font-weight: 800;">0.00 <small style="font-size: 0.6rem; opacity: 0.5;">km</small></span>
                                <span style="font-size: 0.65rem; color: var(--text-secondary); opacity: 0.8;"><span id="map-total-ascent">0</span> m Ascent</span>
                            </div>
                        </div>
                        
                        <div id="waypoints-container" style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1; overflow-y: auto; margin-bottom: 1rem; scrollbar-width: thin; padding-right: 4px;">
                            <!-- Points populated here -->
                            <div style="height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; opacity: 0.3; text-align: center; padding: 1rem;">
                                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="margin-bottom: 8px;"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <p style="font-size: 0.7rem;">Click map to add waypoints</p>
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 0.5rem; display: flex; align-items: center; justify-content: space-between;">
                            <span style="font-size: 0.65rem; opacity: 0.4; text-transform: uppercase; letter-spacing: 0.05em; font-weight: bold;">Elevation Profile</span>
                        </div>
                        <div id="elevation-profile-container" style="height: 150px; background: rgba(0,0,0,0.2); border-radius: 8px; margin-bottom: 1rem; border: 1px solid rgba(255,255,255,0.05); position: relative; overflow: hidden;">
                            <canvas id="elevationChart"></canvas>
                        </div>
                        
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <div style="display: flex; gap: 0.5rem;">
                                <button onclick="openSaveRouteModal()" class="btn btn-outline" style="flex: 1; font-size: 0.7rem; padding: 10px; border-color: var(--accent); color: var(--accent); background: rgba(56, 189, 248, 0.05);">💾 SAVE</button>
                                <button onclick="openLoadRouteModal()" class="btn btn-outline" style="flex: 1; font-size: 0.7rem; padding: 10px; border-color: #a855f7; color: #a855f7; background: rgba(168, 85, 247, 0.05);">📂 LOAD</button>
                            </div>
                            <div style="display: flex; gap: 0.5rem;">
                                <button onclick="saveRoute()" class="btn" style="flex: 2; font-size: 0.8rem; padding: 12px; font-weight: 800; background: var(--accent); box-shadow: 0 4px 12px rgba(56, 189, 248, 0.3);">APPLY TO RACE</button>
                                <button onclick="clearRoute()" class="btn btn-outline" style="flex: 1; font-size: 0.7rem; padding: 12px; border-color: rgba(239, 68, 68, 0.3); color: #ef4444;">CLEAR</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="canvas-container">
                    <canvas id="simCanvas"></canvas>
                </div>

                <!-- NEW: Route Segment Planner Panel -->
                <div class="segment-panel" id="segment-planner" style="display: none; margin-bottom: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <h3 style="margin: 0; font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                            <svg width="16" height="16" fill="var(--accent)" viewBox="0 0 24 24"><path d="M21 11.5e-1c-.55 0-1 .45-1 1s.45 1 1 1 1-.45 1-1-.45-1-1-1zm-10 1c-.55 0-1 .45-1 1s.45 1 1 1 1-.45 1-1-.45-1-1-1zm-6 2c-.55 0-1 .45-1 1s.45 1 1 1 1-.45 1-1-.45-1-1-1zm14 16h-2v-4h-2v4H5v-2h12v-2H5V5h12v2h2V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-2h-2v2z"/></svg>
                            Route Segment Strategy
                        </h3>
                        <span style="font-size: 0.7rem; opacity: 0.6;">Apply specific power/gears to parts of the race</span>
                    </div>
                    
                    <div id="segments-container">
                        <!-- Segments populated by JS -->
                    </div>
                    
                    <button class="btn-add-segment" onclick="addSegment()">
                        + Add Segment Divider
                    </button>
                    
                    <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
                         <label class="tooltip-container" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.7rem; opacity: 0.8;">
                            <input type="checkbox" id="useSegmentsToggle" checked onchange="toggleSegmentsUsage()" style="accent-color: var(--accent);">
                            Enable Segment Strategies
                        </label>
                        <button onclick="resetSegments()" style="background: none; border: none; color: var(--danger); font-size: 0.7rem; cursor: pointer; opacity: 0.6;">Reset All</button>
                    </div>
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
                <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1rem;">
                    <h2 class="sidebar-title" style="margin-bottom: 0;">Racedeck</h2>
                    <div class="control-group">
                        <label style="font-size: 0.7rem;">Active Rider Profile</label>
                        <select id="globalRiderSelect" class="form-control" style="background: rgba(56, 189, 248, 0.1); border-color: var(--accent); font-weight: 700;">
                            @foreach($riders as $rider)
                                <option value="{{ $rider->id }}" data-rider="{{ json_encode($rider) }}">
                                    👤 {{ $rider->name }} ({{ $rider->weight_kg }}kg / {{ $rider->ftp }}W)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="sidebar-section" style="padding: 1.25rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                        <h3 style="margin: 0; font-size: 0.9rem; font-weight: 800; letter-spacing: 0.05em; color: var(--text-secondary); text-transform: uppercase;">Racedeck</h3>
                        <div style="display: flex; gap: 8px;">
                            <select id="bikeLibrarySelect" onchange="addBikeToDeck(this.value)" style="background: #1e293b; border: 1px solid rgba(255,255,255,0.1); color: white; font-size: 0.7rem; padding: 4px 8px; border-radius: 6px; cursor: pointer; max-width: 140px;">
                                <option value="" style="background: #1e293b; color: white;">+ Add to Deck</option>
                                @foreach($bicycles as $libBike)
                                    <option value="{{ $libBike->id }}" style="background: #1e293b; color: white;">{{ $libBike->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="bike-list" id="bikeList">
                    @forelse($bicycles as $bike)
                    <div class="bike-card" id="bike-card-{{ $bike->id }}" style="{{ $loop->index >= 2 ? 'display: none;' : '' }}">
                        <!-- Bonk Alert Overlay -->
                        <div id="bonk-overlay-{{ $bike->id }}" class="bonk-overlay">
                            <div class="bonk-title">BONKED!</div>
                            <p style="color: rgba(255,255,255,0.8); font-size: 0.8rem; margin-bottom: 1rem;">Out of fuel. Slowing down...</p>
                            <button onclick="refuelRider({{ $bike->id }})" class="btn-refuel">
                                <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-6h2v6zm4 0h-2v-6h2v6z"/></svg>
                                Eat Power Gel (+500 kcal)
                            </button>
                        </div>

                        <!-- Meal Break Overlay -->
                        <div id="meal-overlay-{{ $bike->id }}" class="meal-overlay">
                            <div class="meal-title">MEAL BREAK</div>
                            <div style="font-size: 2rem; font-weight: 800; color: white; font-family: 'JetBrains Mono', monospace;" id="meal-timer-{{ $bike->id }}">00:00</div>
                            <p id="meal-status-{{ $bike->id }}" style="color: rgba(255,255,255,0.9); font-size: 0.8rem; margin-top: 0.5rem; font-weight: 600;">Refueling...</p>
                        </div>

                        <div style="position: absolute; top: 0.75rem; right: 0.75rem; display: flex; gap: 0.5rem; z-index: 50;">
                            <div class="btn-stat-badge" id="pedal-status-{{ $bike->id }}" style="background: rgba(255,255,255,0.05); color: rgba(255,255,255,0.4); border: 1px solid rgba(255,255,255,0.1);">
                                IDLE
                            </div>
                            <button onclick="showSummary({{ $bike->id }})" class="edit-btn" style="background: none; border: none; color: var(--success); cursor: pointer; opacity: 0.5; transition: opacity 0.2s;" title="Show Summary">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20V10M18 20V4M6 20v-4"/></svg>
                            </button>
                            <button onclick="openEditModal({{ json_encode($bike) }})" class="edit-btn" style="background: none; border: none; color: var(--accent); cursor: pointer; opacity: 0.5; transition: opacity 0.2s;" title="Edit Rider">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </button>
                            <button onclick="removeBikeFromDeck({{ $bike->id }})" class="delete-btn" style="position: static;" title="Remove from Deck">✕</button>
                        </div>

                        <div class="bike-header" style="flex-direction: column; align-items: flex-start;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <div class="bike-color" style="background-color: {{ $bike->color }}"></div>
                                <span class="bike-name">{{ $bike->name }}</span>
                                <div id="finish-badge-{{ $bike->id }}" class="finish-badge">Finished</div>
                            </div>
                            <div style="font-size: 0.7rem; opacity: 0.5; margin-top: 2px; margin-left: 18px;">
                                (B:{{ $bike->bicycle_weight }}kg + Rider <span class="rider-name-display">...</span> / {{ $bike->efficiency * 100 }}%)
                            </div>
                        </div>

                        <!-- Track Progress Bar -->
                        <div class="progress-container">
                            <div id="progress-text-{{ $bike->id }}" class="progress-text">0% Complete</div>
                            <div id="progress-fill-{{ $bike->id }}" class="progress-fill"></div>
                        </div>

                        <div id="conflict-alert-{{ $bike->id }}" class="conflict-alert" style="display:none;">
                            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/></svg>
                            Locked Power vs Cadence Conflict
                        </div>

                        <div class="control-group tooltip-container" style="margin-bottom: 1rem;">
                            <label style="display: flex; justify-content: space-between;">
                                Rider Power
                                <span id="power-lock-{{ $bike->id }}" class="lock-indicator" style="display:none; position: static;">LOCK</span>
                            </label>
                            <input type="range" class="bike-power-input" data-bike-id="{{ $bike->id }}" min="0" max="1000" value="200">
                            <div class="value-display cadence-clickable" style="font-size: 0.9rem; margin-top: 5px; border-radius: 4px; padding: 2px 10px;" onclick="window.openPowerModal({{ $bike->id }})">
                                <span id="power-display-{{ $bike->id }}">200</span> Watts
                            </div>
                            <span class="tooltip-text" style="bottom: 110%;">Target wattage for this rider. High power depletes stamina! Click to LOCK.</span>
                        </div>
                        
                        <div class="gear-controls" style="margin-bottom: 0.5rem;">
                            <div class="control-group">
                                <label style="font-size: 0.65rem; margin-bottom: 1px;">Efficiency (%)</label>
                                <input type="number" name="efficiency" value="{{ $bike->efficiency * 100 }}" step="1" data-bike-id="{{ $bike->id }}" class="form-control bike-efficiency-input" style="height: 28px; font-size: 0.75rem; padding: 2px 8px;">
                            </div>
                            <div class="control-group">
                                <label style="font-size: 0.65rem; margin-bottom: 1px;">Start Dist (km)</label>
                                <input type="number" name="initial_distance" value="{{ $bike->initial_distance }}" data-bike-id="{{ $bike->id }}" step="0.1" class="form-control bike-dist-input" style="height: 28px; font-size: 0.75rem; padding: 2px 8px;">
                            </div>
                        </div>

                        <div class="gear-controls" style="margin-bottom: 0.75rem; align-items: flex-end;">
                            <div class="control-group">
                                <label style="font-size: 0.65rem; margin-bottom: 1px;">Start EG (m)</label>
                                <input type="number" name="initial_elevation" value="{{ $bike->initial_elevation }}" data-bike-id="{{ $bike->id }}" step="1" class="form-control bike-elev-input" style="height: 28px; font-size: 0.75rem; padding: 2px 8px;">
                            </div>
                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4px; height: 28px;">
                                <div style="display: flex; background: rgba(0,0,0,0.3); border-radius: 6px; padding: 2px; border: 1px solid rgba(255,255,255,0.05);">
                                    <button class="mode-btn-sm active" id="mode-auto-{{ $bike->id }}" onclick="setBikeMode({{ $bike->id }}, true)" style="flex: 1; padding: 0; font-size: 0.55rem; font-weight: 800; border: none; border-radius: 4px; cursor: pointer; background: var(--accent); color: #0f172a;">AUTO</button>
                                    <button class="mode-btn-sm" id="mode-manual-{{ $bike->id }}" onclick="setBikeMode({{ $bike->id }}, false)" style="flex: 1; padding: 0; font-size: 0.55rem; font-weight: 800; border: none; border-radius: 4px; cursor: pointer; background: transparent; color: rgba(255,255,255,0.4);">MAN</button>
                                </div>
                                <button id="brake-btn-{{ $bike->id }}" class="btn-brake-sm" 
                                    onmousedown="setBikeBraking({{ $bike->id }}, true)"
                                    onmouseup="setBikeBraking({{ $bike->id }}, false)"
                                    onmouseleave="setBikeBraking({{ $bike->id }}, false)"
                                    ontouchstart="event.preventDefault(); setBikeBraking({{ $bike->id }}, true);"
                                    ontouchend="setBikeBraking({{ $bike->id }}, false)"
                                    style="background: rgba(244, 63, 94, 0.1); border: 1px solid #f43f5e; color: #f43f5e; border-radius: 6px; padding: 0; font-weight: 800; font-size: 0.55rem; cursor: pointer;">BRAKE</button>
                            </div>
                        </div>

                        <div class="gear-controls" style="margin-bottom: 0.75rem;">
                            <div class="control-group">
                                <label style="font-size: 0.65rem; margin-bottom: 2px;">Chainring (Front)</label>
                                <div style="display: flex; gap: 2px; align-items: center;">
                                    <button class="shift-btn-sm" onclick="manualShiftBikeCR({{ $bike->id }}, -1)" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; width: 20px; height: 22px; border-radius: 4px; cursor: pointer; font-size: 0.7rem;">-</button>
                                    <select class="front-gear-select" data-bike-id="{{ $bike->id }}" disabled style="height: 22px; padding: 0 2px; font-size: 0.65rem; background: #0f172a; color: white; border: 1px solid rgba(255,255,255,0.1); border-radius: 4px; flex: 1;">
                                        @php
                                            $sortedFront = $bike->front_gears;
                                            sort($sortedFront);
                                        @endphp
                                        @foreach($sortedFront as $gear)
                                            <option value="{{ $gear }}">{{ $gear }}T</option>
                                        @endforeach
                                    </select>
                                    <button class="shift-btn-sm" onclick="manualShiftBikeCR({{ $bike->id }}, 1)" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; width: 20px; height: 22px; border-radius: 4px; cursor: pointer; font-size: 0.7rem;">+</button>
                                </div>
                            </div>
                            <div class="control-group">
                                <label style="font-size: 0.65rem; margin-bottom: 2px;">Cassette (Rear)</label>
                                <div style="display: flex; gap: 2px; align-items: center;">
                                    <button class="shift-btn-sm" onclick="manualShiftBikeCS({{ $bike->id }}, -1)" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; width: 20px; height: 22px; border-radius: 4px; cursor: pointer; font-size: 0.7rem;">-</button>
                                    <select class="rear-gear-select" data-bike-id="{{ $bike->id }}" disabled style="height: 22px; padding: 0 2px; font-size: 0.65rem; background: #0f172a; color: white; border: 1px solid rgba(255,255,255,0.1); border-radius: 4px; flex: 1;">
                                        @php
                                            $sortedRear = $bike->rear_gears;
                                            rsort($sortedRear);
                                        @endphp
                                        @foreach($sortedRear as $gear)
                                            <option value="{{ $gear }}">{{ $gear }}T</option>
                                        @endforeach
                                    </select>
                                    <button class="shift-btn-sm" onclick="manualShiftBikeCS({{ $bike->id }}, 1)" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: white; width: 20px; height: 22px; border-radius: 4px; cursor: pointer; font-size: 0.7rem;">+</button>
                                </div>
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
                            <div class="stat-item cadence-clickable" onclick="window.openCadenceModal({{ $bike->id }})">
                                <span class="stat-label">Cadence <span id="cadence-lock-{{ $bike->id }}" class="lock-indicator" style="display:none;">LOCK</span></span>
                                <div class="stat-value">
                                    <span id="cadence-{{ $bike->id }}">0</span> <small>RPM</small>
                                    <div id="eff-indicator-{{ $bike->id }}" style="font-size: 0.65rem; color: var(--success); font-weight: 800; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.5px;">Eff: 100%</div>
                                </div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Terrain</span>
                                <div class="stat-value" id="slope-display-{{ $bike->id }}">0.0%</div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Elevation Gain</span>
                                <span class="stat-value"><span id="elev-{{ $bike->id }}">0</span> <small>m</small></span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Avg Power</span>
                                <div class="stat-value"><span id="avgp-{{ $bike->id }}">0</span> <small style="font-size: 0.6em;">W</small></div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Norm Power (NP)</span>
                                <div class="stat-value" style="color: var(--success);"><span id="np-{{ $bike->id }}">0</span> <small style="font-size: 0.6em;">W</small></div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">TSS</span>
                                <div class="stat-value" style="color: #f59e0b;"><span id="tss-{{ $bike->id }}">0</span> <small style="font-size: 0.6em;">pts</small></div>
                            </div>
                            <div class="stat-item">
                                <span class="stat-label">Distance</span>
                                <div class="stat-value"><span id="dist-km-{{ $bike->id }}">0.00</span> <small>km</small></div>
                            </div>
                        </div>

                        <div class="log-section" style="margin-top: 0.75rem;">
                            <div class="log-header" style="font-size: 0.6rem; padding: 4px 0; opacity: 0.5; border-bottom: 1px solid rgba(255,255,255,0.05);">
                                <span>ACTIVITY LOG</span>
                                <span>T | D | S</span>
                            </div>
                            <div id="log-list-{{ $bike->id }}" class="log-list" style="max-height: 60px; font-size: 0.65rem;">
                                <div style="opacity: 0.3; font-style: italic; text-align: center; margin-top: 5px;">Empty</div>
                            </div>
                        </div>
                    </div>
                    @empty
                        <p style="color: var(--text-secondary); text-align: center; margin-top: 2rem;">No riders registered.</p>
                    @endforelse
                </div>
            </div>
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

        window.showToast = (msg, type = 'success') => {
            const toast = document.createElement('div');
            toast.className = 'toast';
            if (type === 'error') toast.style.background = 'var(--danger)';
            toast.innerText = msg;
            document.body.appendChild(toast);
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.5s ease-out';
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        };

        // ==========================================
        // PHASE 27: MAP ROUTE PLANNER LOGIC
        // ==========================================
        let routeMap = null;
        let routeMarkers = [];
        let routePolyline = null;
        let routeDistance = 0;
        let routeCheckpointPercentages = []; // NEW: Store CP positions as 0-1 decimals
        let elevationChart = null;
        let routeProfile = []; // Array of {dist, elev, slope}
        let totalElevationGain = 0;
        let lastRouteCoordinates = []; // NEW: Store polyline coordinates for saving


        function initMap() {
            if (!routeMap) {
                // Initialize map - centered on Surabaya (Example Start: -7.3068, 112.7930)
                routeMap = L.map('map', { scrollWheelZoom: true }).setView([-7.3068, 112.7930], 11);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(routeMap);

                routeMap.on('click', function(e) {
                    addWaypoint(e.latlng);
                });
            }
            setTimeout(() => routeMap.invalidateSize(), 200);
        }

        function openMapModal() {
            const section = document.getElementById('map-section');
            const isHidden = section.style.display === 'none' || section.style.display === '';
            
            section.style.display = isHidden ? 'grid' : 'none';
            
            if (isHidden) {
                initMap();
            }
        }

        function closeMapModal() {
            document.getElementById('map-section').style.display = 'none';
        }

        function addWaypoint(latlng) {
            const marker = L.marker(latlng, { draggable: true }).addTo(routeMap);
            routeMarkers.push(marker);
            
            marker.on('dragend', updateRoute);
            updateRoute();
        }

        function clearRoute() {
            routeMarkers.forEach(m => m.remove());
            routeMarkers = [];
            if (routePolyline) routePolyline.remove();
            routePolyline = null;
            routeDistance = 0;
            totalElevationGain = 0; // Reset elevation gain
            routeProfile = []; // Reset route profile
            updateRouteUI();
            updateElevationChart(); // Clear chart
        }

        async function updateRoute() {
            if (routeMarkers.length < 2) {
                if (routePolyline) routePolyline.remove();
                routePolyline = null;
                routeDistance = 0;
                totalElevationGain = 0; // Reset elevation gain
                routeProfile = []; // Reset route profile
                updateRouteUI();
                updateElevationChart(); // Clear chart
                return;
            }

            // Prepare coordinates for OSRM (lon,lat)
            const coords = routeMarkers.map(m => {
                const ll = m.getLatLng();
                return `${ll.lng},${ll.lat}`;
            }).join(';');

            try {
                const response = await fetch(`https://router.project-osrm.org/route/v1/driving/${coords}?overview=full&geometries=geojson`);
                const data = await response.json();

                if (data.code === 'Ok') {
                    const route = data.routes[0];
                    routeDistance = route.distance / 1000; // km

                    if (routePolyline) routePolyline.remove();
                    routePolyline = L.geoJSON(route.geometry, {
                        style: { color: '#38bdf8', weight: 5, opacity: 0.8 }
                    }).addTo(routeMap);

                    updateRouteUI();
                    
                    // NEW: Store geometry for saving
                    lastRouteCoordinates = route.geometry.coordinates;

                    // NEW: Fetch Elevation Data
                    await fetchElevationProfile(route.geometry.coordinates);
                }
            } catch (err) {
                console.error("Routing Error:", err);
            }
        }

        async function fetchElevationProfile(coordinates) {
            // Sample coordinates to avoid huge API requests (approx every 1km or max 100 points)
            const sampleCount = Math.min(coordinates.length, 100);
            const sampled = [];
            for (let i = 0; i < sampleCount; i++) {
                const idx = Math.floor((i / (sampleCount - 1)) * (coordinates.length - 1));
                sampled.push({
                    latitude: coordinates[idx][1],
                    longitude: coordinates[idx][0]
                });
            }

            try {
                const response = await fetch('https://api.open-elevation.com/api/v1/lookup', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ locations: sampled })
                });
                const data = await response.json();

                if (data.results) {
                    processElevationData(data.results);
                }
            } catch (err) {
                console.error("Elevation API Error:", err);
            }
        }

        function processElevationData(results) {
            routeProfile = [];
            totalElevationGain = 0;
            let currentDist = 0;

            results.forEach((pt, i) => {
                if (i > 0) {
                    const prev = results[i-1];
                    const d = L.latLng(prev.latitude, prev.longitude).distanceTo(L.latLng(pt.latitude, pt.longitude));
                    currentDist += d;
                    
                    const elevDiff = pt.elevation - prev.elevation;
                    if (elevDiff > 0) totalElevationGain += elevDiff;
                    
                    const slope = (elevDiff / d) * 100;
                    routeProfile.push({
                        dist: currentDist,
                        elev: pt.elevation,
                        baseElev: results[0].elevation, // Keep start elevation as reference
                        relElev: pt.elevation - results[0].elevation,
                        slope: slope
                    });
                } else {
                    routeProfile.push({ dist: 0, elev: pt.elevation, baseElev: pt.elevation, relElev: 0, slope: 0 });
                }
            });

            updateElevationChart();
            document.getElementById('ascentGoalInput').value = Math.round(totalElevationGain);
        }

        function updateElevationChart() {
            const ctx = document.getElementById('elevationChart').getContext('2d');
            const labels = routeProfile.map(p => (p.dist / 1000).toFixed(1) + ' km');
            const data = routeProfile.map(p => p.elev);

            if (elevationChart) {
                elevationChart.destroy();
            }

            elevationChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Elevation (m)',
                        data: data,
                        borderColor: '#38bdf8',
                        backgroundColor: 'rgba(56, 189, 248, 0.2)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            titleColor: '#38bdf8',
                            bodyColor: '#fff',
                            borderColor: 'rgba(56, 189, 248, 0.3)',
                            borderWidth: 1
                        }
                    },
                    scales: {
                        x: { display: false },
                        y: {
                            grid: { color: 'rgba(255,255,255,0.05)', drawBorder: false },
                            ticks: { color: 'rgba(255,255,255,0.4)', font: { size: 10 } }
                        }
                    }
                }
            });
        }

        function updateRouteUI() {
            const distDisplay = document.getElementById('map-total-dist-display');
            if (distDisplay) distDisplay.innerHTML = `${routeDistance.toFixed(2)} <small style="font-size: 0.6rem; opacity: 0.5;">km</small>`;
            
            const ascentDisplay = document.getElementById('map-total-ascent');
            if (ascentDisplay) ascentDisplay.innerText = Math.round(totalElevationGain);

            const container = document.getElementById('waypoints-container');
            
            if (routeMarkers.length === 0) {
                container.innerHTML = `
                    <div style="height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; opacity: 0.2; text-align: center; padding: 2rem;">
                        <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom: 12px;"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <p style="font-size: 0.75rem; font-weight: 500;">No waypoints yet</p>
                        <p style="font-size: 0.65rem; margin-top: 4px;">Click the map to start planning your route</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = '';
            routeMarkers.forEach((m, i) => {
                const label = i === 0 ? 'START' : (i === routeMarkers.length - 1 ? 'FINISH' : `CP ${i}`);
                const color = i === 0 ? '#22c55e' : (i === routeMarkers.length - 1 ? '#ef4444' : '#38bdf8');
                const bgColor = i === 0 ? 'rgba(34, 197, 94, 0.05)' : (i === routeMarkers.length - 1 ? 'rgba(239, 68, 68, 0.05)' : 'rgba(56, 189, 248, 0.05)');
                
                const div = document.createElement('div');
                div.style = `background: ${bgColor}; padding: 10px 12px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center; border: 1px solid rgba(255,255,255,0.03); border-left: 3px solid ${color}; transition: all 0.2s;`;
                
                let reorderHtml = `
                    <div style="display: flex; flex-direction: column; gap: 0px; margin-right: 12px; opacity: 0.5;">
                        <button onclick="moveWaypoint(${i}, -1)" ${i === 0 ? 'disabled style="opacity:0.1; cursor:default;"' : 'style="cursor:pointer; padding: 2px;"'} title="Move Up" style="background:none; border:none; color:white; font-size: 0.7rem; line-height: 1;">▴</button>
                        <button onclick="moveWaypoint(${i}, 1)" ${i === routeMarkers.length - 1 ? 'disabled style="opacity:0.1; cursor:default;"' : 'style="cursor:pointer; padding: 2px;"'} title="Move Down" style="background:none; border:none; color:white; font-size: 0.7rem; line-height: 1;">▾</button>
                    </div>
                `;

                div.innerHTML = `
                    <div style="display: flex; align-items: center;">
                        ${reorderHtml}
                        <div style="display: flex; flex-direction: column; gap: 2px;">
                            <span style="font-size: 0.6rem; font-weight: 900; color: ${color}; letter-spacing: 0.1em; text-transform: uppercase;">${label}</span>
                            <span style="font-size: 0.6rem; opacity: 0.4; font-family: 'JetBrains Mono'; font-weight: 500;">${m.getLatLng().lat.toFixed(5)}, ${m.getLatLng().lng.toFixed(5)}</span>
                        </div>
                    </div>
                    <button onclick="removeWaypoint(${i})" style="background: rgba(239, 68, 68, 0.08); border: none; color: #ef4444; cursor: pointer; font-size: 0.8rem; border-radius: 6px; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; transition: all 0.2s; border: 1px solid rgba(239, 68, 68, 0.1);" onmouseover="this.style.background='rgba(239, 68, 68, 0.2)'" onmouseout="this.style.background='rgba(239, 68, 68, 0.08)'">
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                    </button>
                `;
                container.appendChild(div);
            });
        }

        function removeWaypoint(index) {
            routeMarkers[index].remove();
            routeMarkers.splice(index, 1);
            updateRoute();
        }

        function moveWaypoint(index, direction) {
            const newIndex = index + direction;
            if (newIndex < 0 || newIndex >= routeMarkers.length) return;
            
            // Swap in array
            const temp = routeMarkers[index];
            routeMarkers[index] = routeMarkers[newIndex];
            routeMarkers[newIndex] = temp;
            
            updateRoute();
        }

        // ==========================================
        // PHASE 28: ROUTE SEGMENTS & PLANNING
        // ==========================================
        window.routeSegments = [];
        window.useSegments = true;

        function toggleSegmentsUsage() {
            window.useSegments = document.getElementById('useSegmentsToggle').checked;
        }

        function resetSegments() {
            if (confirm('Are you sure you want to reset all segment strategies?')) {
                window.routeSegments = [];
                // Create initial full segment
                addSegment(0, 100);
            }
        }

        function addSegment(start = null, end = null) {
            const id = Date.now() + Math.random();
            
            // If no start/end provided, try to split existing last segment or start with 0-100
            if (start === null) {
                if (window.routeSegments.length === 0) {
                    start = 0;
                    end = 100;
                } else {
                    const last = window.routeSegments[window.routeSegments.length - 1];
                    const mid = Math.round((last.start_pct + last.end_pct) / 2);
                    const oldEnd = last.end_pct;
                    last.end_pct = mid;
                    start = mid;
                    end = oldEnd;
                }
            }

            window.routeSegments.push({
                id: id,
                name: `Segment ${window.routeSegments.length + 1}`,
                start_pct: start,
                end_pct: end,
                objective: 'speed', // 'speed' | 'stamina' | 'custom'
                front_gear: null,
                rear_gear: null,
                power_target_w: null
            });

            // Sort by start_pct
            window.routeSegments.sort((a,b) => a.start_pct - b.start_pct);
            renderSegments();
        }

        function deleteSegment(id) {
            if (window.routeSegments.length <= 1) return;
            const idx = window.routeSegments.findIndex(s => s.id === id);
            if (idx === -1) return;
            
            const deleted = window.routeSegments[idx];
            // Give its portion to the previous or next segment
            if (idx > 0) {
                window.routeSegments[idx-1].end_pct = deleted.end_pct;
            } else {
                window.routeSegments[idx+1].start_pct = deleted.start_pct;
            }
            
            window.routeSegments.splice(idx, 1);
            renderSegments();
        }

        function updateSegment(id, field, value) {
            const seg = window.routeSegments.find(s => s.id === id);
            if (!seg) return;
            
            if (field === 'start_pct' || field === 'end_pct' || field === 'power_target_w') {
                seg[field] = parseFloat(value);
            } else {
                seg[field] = value === 'null' ? null : value;
            }
        }

        function renderSegments() {
            const container = document.getElementById('segments-container');
            if (!container) return;

            const colors = ['#38bdf8', '#fbbf24', '#f87171', '#4ade80', '#c084fc', '#fb923c'];

            container.innerHTML = window.routeSegments.map((seg, i) => `
                <div class="segment-card">
                    <div class="segment-zone-color" style="background: ${colors[i % colors.length]}"></div>
                    <div class="segment-header">
                        <input type="text" value="${seg.name}" onchange="updateSegment(${seg.id}, 'name', this.value)" 
                               style="background:none; border:none; color:white; font-size:0.8rem; font-weight:bold; width: 60%;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 0.7rem; opacity: 0.6;">${seg.start_pct}% - ${seg.end_pct}%</span>
                            <span class="segment-delete" onclick="deleteSegment(${seg.id})">&times;</span>
                        </div>
                    </div>
                    <div class="segment-controls">
                        <div>
                            <label style="font-size: 0.6rem; opacity: 0.5; display:block; margin-bottom:2px;">Strategy</label>
                            <select onchange="updateSegment(${seg.id}, 'objective', this.value)">
                                <option value="speed" ${seg.objective === 'speed' ? 'selected' : ''}>🚀 Speed</option>
                                <option value="stamina" ${seg.objective === 'stamina' ? 'selected' : ''}>🫀 Stamina</option>
                                <option value="custom" ${seg.objective === 'custom' ? 'selected' : ''}>⚙️ Custom</option>
                            </select>
                        </div>
                        <div>
                            <label style="font-size: 0.6rem; opacity: 0.5; display:block; margin-bottom:2px;">Front Gear</label>
                            <select onchange="updateSegment(${seg.id}, 'front_gear', this.value)">
                                <option value="null">Auto</option>
                                <option value="50" ${seg.front_gear == 50 ? 'selected' : ''}>50T</option>
                                <option value="34" ${seg.front_gear == 34 ? 'selected' : ''}>34T</option>
                            </select>
                        </div>
                        <div>
                            <label style="font-size: 0.6rem; opacity: 0.5; display:block; margin-bottom:2px;">Pwr Override</label>
                            <input type="number" value="${seg.power_target_w || ''}" placeholder="Slider" 
                                   onchange="updateSegment(${seg.id}, 'power_target_w', this.value)"
                                   style="width:100%; height:28px; background:#0f172a; border:1px solid rgba(255,255,255,0.1); border-radius:4px; color:white; font-size:0.7rem; padding: 0 4px;">
                        </div>
                    </div>
                    <div style="margin-top: 8px;">
                         <input type="range" min="0" max="100" value="${seg.end_pct}" 
                                oninput="handleSegmentEndChange(${seg.id}, this.value)"
                                style="width: 100%; height: 4px; accent-color: ${colors[i % colors.length]}">
                    </div>
                </div>
            `).join('');
        }

        function handleSegmentEndChange(id, value) {
            const idx = window.routeSegments.findIndex(s => s.id === id);
            if (idx === -1 || idx === window.routeSegments.length - 1) return;
            
            const newVal = parseFloat(value);
            const seg = window.routeSegments[idx];
            const next = window.routeSegments[idx+1];
            
            // Boundary checks
            if (newVal > seg.start_pct + 1 && newVal < next.end_pct - 1) {
                seg.end_pct = newVal;
                next.start_pct = newVal;
                renderSegments();
            }
        }


        function saveRoute() {
            if (routeDistance > 0) {
                document.getElementById('trackGoalInput').value = routeDistance.toFixed(2);
                document.getElementById('ascentGoalInput').value = Math.round(totalElevationGain);
                
                window.activeCheckpoints = routeMarkers.map((m, i) => i / (routeMarkers.length - 1));
                
                document.getElementById('trackGoalInput').dispatchEvent(new Event('input'));
                document.getElementById('ascentGoalInput').dispatchEvent(new Event('input'));
                
                window.routeElevationProfile = routeProfile;
                window.useRouteElevation = routeProfile.length > 0;

                // SHOW SEGMENT PLANNER
                document.getElementById('segment-planner').style.display = 'block';
                if (window.routeSegments.length === 0) {
                    addSegment(0, 100);
                }

                showToast(`Route Applied: ${routeDistance.toFixed(2)} km`);
            }
        }

        // ==========================================
        // PHASE 29: ROUTE SAVING & LOADING (SERVER)
        // ==========================================
        
        window.openSaveRouteModal = () => {
            if (routeMarkers.length < 2) {
                showToast('❌ Please plan a route first!');
                return;
            }
            document.getElementById('preview-route-dist').innerText = routeDistance.toFixed(2) + ' km';
            document.getElementById('preview-route-ascent').innerText = Math.round(totalElevationGain) + ' m';
            document.getElementById('saveRouteModal').style.display = 'block';
        };

        window.closeSaveRouteModal = () => {
            document.getElementById('saveRouteModal').style.display = 'none';
        }

        window.submitSaveRoute = async () => {
            const name = document.getElementById('plannedRouteNameInput').value;
            if (!name) {
                showToast('❌ Route name is required');
                return;
            }

            const payload = {
                name: name,
                distance_km: typeof routeDistance === 'number' ? routeDistance : 0,
                ascent_m: typeof totalElevationGain === 'number' ? totalElevationGain : 0,
                waypoints: (routeMarkers || []).map(m => {
                    const ll = m.getLatLng();
                    return { lat: ll.lat, lng: ll.lng };
                }),
                coordinates: lastRouteCoordinates || [],
                elevation_profile: routeProfile || []
            };

            console.log('Saving Route Payload:', payload);
            try {
                const response = await fetch('/planned-routes', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                if (response.ok) {
                    const resData = await response.json();
                    console.log('Route Saved Response:', resData);
                    showToast('✅ Route saved successfully!');
                    closeSaveRouteModal();
                } else {
                    const err = await response.json();
                    console.error('Save Route Error Response:', err);
                    showToast('❌ Failed to save route: ' + (err.message || 'Validation error'));
                }
            } catch (err) {
                console.error(err);
                showToast('❌ Error saving route');
            }
        };

        window.openLoadRouteModal = () => {
            document.getElementById('loadRouteModal').style.display = 'block';
            fetchSavedRoutes();
        };

        window.closeLoadRouteModal = () => {
            document.getElementById('loadRouteModal').style.display = 'none';
        };

        async function fetchSavedRoutes() {
            const list = document.getElementById('saved-routes-list');
            const loading = document.getElementById('route-history-loading');
            
            list.innerHTML = '';
            loading.style.display = 'block';

            try {
                const response = await fetch('/planned-routes');
                const data = await response.json();
                loading.style.display = 'none';

                if (data.routes && data.routes.length > 0) {
                    list.innerHTML = data.routes.map(r => `
                        <div class="session-item" style="margin-bottom: 10px; padding: 12px;">
                            <div class="session-info" style="flex: 1;">
                                <div style="font-weight: bold; color: white; margin-bottom: 4px;">${r.name}</div>
                                <div style="font-size: 0.7rem; opacity: 0.6;">
                                    ${r.distance_km} km • ${Math.round(r.ascent_m)} m Ascent
                                </div>
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <button class="btn btn-sm" style="background: var(--accent); color: var(--bg-color);" onclick="loadRouteFromServer(${r.id})">Load</button>
                                <button class="btn btn-sm" style="background: rgba(239, 68, 68, 0.1); color: #ef4444;" onclick="deleteRouteFromServer(${r.id})">Delete</button>
                            </div>
                        </div>
                    `).join('');
                } else {
                    list.innerHTML = '<p style="text-align: center; opacity: 0.4; padding: 2rem;">No saved routes found.</p>';
                }
            } catch (err) {
                console.error(err);
                loading.style.display = 'none';
                list.innerHTML = '<p style="text-align: center; color: #ef4444; padding: 2rem;">Error fetching routes.</p>';
            }
        }

        window.loadRouteFromServer = async (id) => {
            try {
                const response = await fetch('/planned-routes');
                const data = await response.json();
                const route = data.routes.find(r => r.id === id);
                
                if (!route) return;

                // Clear current route
                clearRoute();

                // Restore Markers
                route.waypoints.forEach(wp => {
                    const marker = L.marker([wp.lat, wp.lng], { draggable: true }).addTo(routeMap);
                    routeMarkers.push(marker);
                    marker.on('dragend', updateRoute);
                });

                // Restore Stats & Profile
                routeDistance = parseFloat(route.distance_km);
                totalElevationGain = parseFloat(route.ascent_m);
                routeProfile = route.elevation_profile;
                lastRouteCoordinates = route.coordinates;

                // Draw Polyline
                if (routePolyline) routePolyline.remove();
                routePolyline = L.geoJSON({
                    type: "Feature",
                    geometry: {
                        type: "LineString",
                        coordinates: route.coordinates
                    }
                }, {
                    style: { color: '#38bdf8', weight: 5, opacity: 0.8 }
                }).addTo(routeMap);

                updateRouteUI();
                updateElevationChart();
                
                closeLoadRouteModal();
                showToast('✅ Route loaded: ' + route.name);
            } catch (err) {
                console.error(err);
                showToast('❌ Failed to load route');
            }
        };

        window.deleteRouteFromServer = async (id) => {
            if (!confirm('Are you sure you want to delete this route?')) return;
            
            try {
                const response = await fetch(`/planned-routes/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                if (response.ok) {
                    showToast('✅ Route deleted');
                    fetchSavedRoutes();
                }
            } catch (err) {
                console.error(err);
                showToast('❌ Failed to delete route');
            }
        };


        // ==========================================
        // PHASE 30: INITIALIZATION & STATE
        // ==========================================
        window.onload = function() {
            window.bikesData = @json($bicycles);
            window.pendingRemoveBikeId = null;
            const ridersData = @json($riders);
            const globalRiderSelect = document.getElementById('globalRiderSelect');
            
            // Get initial rider
            let selectedRider = JSON.parse(globalRiderSelect.options[globalRiderSelect.selectedIndex].getAttribute('data-rider'));

            globalRiderSelect.onchange = (e) => {
                const opt = e.target.options[e.target.selectedIndex];
                selectedRider = JSON.parse(opt.getAttribute('data-rider'));
                updateRiderDisplays();
                showToast(`Switched to Rider: ${selectedRider.name}`);
            };

            function updateRiderDisplays() {
                document.querySelectorAll('.rider-name-display').forEach(el => {
                    el.innerText = `${selectedRider.name} (${selectedRider.weight_kg}kg)`;
                });
                
                // Update internal states if simulation is running? 
                // Better to just reactive it on next cycle or restart.
                window.bikeState.forEach(bike => {
                    bike.rider_id = selectedRider.id;
                    bike.rider_weight = selectedRider.weight_kg;
                    bike.ftp = selectedRider.ftp;
                    bike.max_hr = selectedRider.max_hr;
                    bike.anaerobic_threshold_w = selectedRider.anaerobic_threshold_w;
                });
            }
            const debugRiders = document.getElementById('debug-riders');
            const debugStatus = document.getElementById('debug-status');
            const debugRes = document.getElementById('debug-res');
            const debugFrames = document.getElementById('debug-frames');
            const debugCanvas = document.getElementById('debug-canvas');
            
            if (debugRiders) debugRiders.innerText = window.bikesData.length;
            
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

            // Initialize Map on Load
            if (typeof initMap === 'function') initMap();

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
            let isPlaying = false; // NEW: Simulation starts paused
            let timeScale = 1.0;
            let elapsedSeconds = 0;

            const RHO = 1.2; 
            const CRR = 0.005; 
            const G = 9.81;    
            const CDA = 0.24;  

            function formatSimulationTime(seconds) {
                const h = Math.floor(seconds / 3600);
                const m = Math.floor((seconds % 3600) / 60);
                const s = Math.floor(seconds % 60);
                return [h, m, s].map(v => v.toString().padStart(2, '0')).join(':');
            }


            function getBikeInitialState(bike, rider) {
                return {
                    ...bike,
                    front_gears: (Array.isArray(bike.front_gears) ? bike.front_gears : [52, 34]).sort((a,b) => a - b),
                    rear_gears: (Array.isArray(bike.rear_gears) ? bike.rear_gears : [11, 28]).sort((a,b) => b - a),
                    speed: 0,
                    power: 150,
                    cadence: 80,
                    currentFrontGear: null,
                    currentRearGear: null,
                    wheel_diameter: 700, 
                    wheel_circumference: 2.105,
                    efficiency: bike.efficiency || 0.24,
                    calories: parseFloat(bike.initial_fuel || (parseFloat(bike.ftp || 200) * 10)),
                    maxCalories: parseFloat(bike.initial_fuel || (parseFloat(bike.ftp || 200) * 10)),
                    staminaW: 100,
                    fatigue: 0,
                    totalJoules: 0,
                    totalKm: 0,
                    lastLogTime: 0,
                    lastMealTime: 0, 
                    lockedCadence: null,
                    lockedPower: null,
                    hasConflict: false,
                    isFinished: false,
                    startLogged: false, 
                    isEating: false,    
                    mealEndTime: 0,    
                    finishTime: null,  
                    autoShiftEnabled: true, 
                    isBraking: false,      
                    elevationGain: parseFloat(bike.initial_elevation || 0),
                    distance: parseFloat(bike.initial_distance || 0) * 1000, 
                    currentSlope: 0,
                    rider_id: rider.id,
                    rider_weight: rider.weight_kg,
                    ftp: rider.ftp,
                    max_hr: rider.max_hr,
                    anaerobic_threshold_w: rider.anaerobic_threshold_w,
                    logs: [],
                    history: [], 
                    lastHistorySample: 0,
                    hr: 70, 
                    npAcc: 0, 
                    isPedaling: false,
                    avgPowerAcc: 0,
                    sampleCount: 0.001, 
                    hrZonesTime: { Z1: 0, Z2: 0, Z3: 0, Z4: 0, Z5: 0 },
                    segmentResults: {} 
                };
            }

            window.bikeState = window.bikesData.slice(0, 2).map(bike => getBikeInitialState(bike, selectedRider));

        // Set initial gears to Index 0 (Easiest)
        window.bikeState.forEach(b => {
            b.currentFrontGear = b.front_gears[0];
            b.currentRearGear = b.rear_gears[0];
            
            const fSelect = document.querySelector(`.front-gear-select[data-bike-id="${b.id}"]`);
            const rSelect = document.querySelector(`.rear-gear-select[data-bike-id="${b.id}"]`);
            if (fSelect) fSelect.value = b.currentFrontGear;
            if (rSelect) rSelect.value = b.currentRearGear;
        });

        // ── MANUAL CONTROL HELPERS ──────────────────────────────────────────
        window.setBikeMode = function(bikeId, auto) {
            const bike = window.bikeState.find(b => b.id == bikeId);
            if (!bike) return;
            
            bike.autoShiftEnabled = auto;
            
            const autoBtn = document.getElementById(`mode-auto-${bikeId}`);
            const manualBtn = document.getElementById(`mode-manual-${bikeId}`);
            
            if (autoBtn) {
                autoBtn.style.background = auto ? 'var(--accent)' : 'transparent';
                autoBtn.style.color = auto ? '#0f172a' : 'rgba(255,255,255,0.4)';
            }
            if (manualBtn) {
                manualBtn.style.background = !auto ? 'var(--accent)' : 'transparent';
                manualBtn.style.color = !auto ? '#0f172a' : 'rgba(255,255,255,0.4)';
            }

            // Disable/Enable selectors
            const fSelect = document.querySelector(`.front-gear-select[data-bike-id="${bikeId}"]`);
            const rSelect = document.querySelector(`.rear-gear-select[data-bike-id="${bikeId}"]`);
            
            [fSelect, rSelect].forEach(el => {
                if (el) {
                    el.disabled = auto;
                    el.style.opacity = auto ? '0.5' : '1';
                    el.style.cursor = auto ? 'not-allowed' : 'pointer';
                }
            });
        }

        function manualShiftBikeCR(bikeId, dir) {
            const bike = window.bikeState.find(b => b.id == bikeId);
            if (!bike || bike.autoShiftEnabled) return;
            
            const gears = bike.front_gears;
            const currentIdx = gears.indexOf(bike.currentFrontGear);
            const newIdx = currentIdx + dir;
            
            if (newIdx >= 0 && newIdx < gears.length) {
                bike.currentFrontGear = gears[newIdx];
                const select = document.querySelector(`.front-gear-select[data-bike-id="${bikeId}"]`);
                if (select) select.value = bike.currentFrontGear;
                
                const timeStr = formatSimulationTime(elapsedSeconds);
                bike.logs.unshift({ time: timeStr, msg: `⚙️ Shift CR: ${bike.currentFrontGear}T` });
            }
        }

        function manualShiftBikeCS(bikeId, dir) {
            const bike = window.bikeState.find(b => b.id == bikeId);
            if (!bike || bike.autoShiftEnabled) return;
            
            const gears = bike.rear_gears;
            const currentIdx = gears.indexOf(bike.currentRearGear);
            const newIdx = currentIdx + dir;
            
            if (newIdx >= 0 && newIdx < gears.length) {
                bike.currentRearGear = gears[newIdx];
                const select = document.querySelector(`.rear-gear-select[data-bike-id="${bikeId}"]`);
                if (select) select.value = bike.currentRearGear;
                
                const timeStr = formatSimulationTime(elapsedSeconds);
                bike.logs.unshift({ time: timeStr, msg: `⚙️ Shift CS: ${bike.currentRearGear}T` });
            }
        }

        window.setBikeBraking = function(bikeId, braking) {
            const bike = window.bikeState.find(b => b.id == bikeId);
            if (!bike) return;
            
            bike.isBraking = braking;
            const btn = document.getElementById(`brake-btn-${bikeId}`);
            if (btn) {
                btn.style.background = braking ? '#f43f5e' : 'rgba(244, 63, 94, 0.1)';
                btn.style.color = braking ? 'white' : '#f43f5e';
            }
        }

        function getCadenceEfficiency(cadence) {
            // Gaussian bell curve centered at 90 RPM
            // Sigma of 35 allows for a reasonably wide range but drops off 
            // significantly under 50 or over 130.
            const optimal = 90;
            const sigma = 35;
            const factor = Math.exp(-Math.pow(cadence - optimal, 2) / (2 * Math.pow(sigma, 2)));
            return Math.max(0.15, factor); // Minimum efficiency floor
        }

        function calculateSpeed(power, weight, slopePercent, windKmh, draftFactor = 1.0, brakeForce = 0) {
            // Pure physics: given mechanical power (watts at wheel), find terminal velocity.
            // Cadence efficiency is applied upstream by the caller, not here.
            const mechanicalEfficiency = 1.0; // Synced with lab (100% mechanical efficiency)
            const effectivePower = power * mechanicalEfficiency;
            if (effectivePower <= 0 && brakeForce <= 0) return 0;

            const slopeAngle = Math.atan(slopePercent / 100);
            const m = weight; // kg, bike + rider
            const windMs = windKmh / 3.6;

            const C_gravity  = m * G * Math.sin(slopeAngle);
            const C_roll     = CRR * m * G * Math.cos(slopeAngle);
            const C_drag_base = 0.5 * RHO * CDA * draftFactor;

            // Binary search for terminal velocity where P_available == P_required
            let low = 0, high = 60;
            for (let i = 0; i < 35; i++) {
                const mid = (low + high) / 2;
                const relativeAirSpeed = mid - windMs;
                const dragForce = C_drag_base * relativeAirSpeed * Math.abs(relativeAirSpeed);
                const p_req = (C_gravity + C_roll + dragForce + brakeForce) * mid;
                if (p_req < effectivePower) low = mid;
                else high = mid;
            }
            return low;
        }

        function updateSimulation() {
            if (debugStatus) debugStatus.innerText = isPlaying ? "RUNNING" : "STOPPED";
            
            // Expose removal function globally
            window.removeBikeFromDeck = function(bikeId) {
                window.pendingRemoveBikeId = bikeId;
                const modal = document.getElementById('confirmRemovalModal');
                if (modal) modal.style.display = 'block';
            };

            window.closeConfirmRemovalModal = function() {
                const modal = document.getElementById('confirmRemovalModal');
                if (modal) modal.style.display = 'none';
                window.pendingRemoveBikeId = null;
            };

            window.confirmRemoval = function() {
                const bikeId = window.pendingRemoveBikeId;
                if (!bikeId) return;

                // Remove from state
                const idx = window.bikeState.findIndex(b => b.id == bikeId);
                if (idx !== -1) {
                    window.bikeState.splice(idx, 1);
                    
                    // Instead of removing, we hide and splice from state
                    const card = document.getElementById(`bike-card-${bikeId}`);
                    if (card) {
                        card.style.opacity = '0';
                        card.style.transform = 'translateX(20px)';
                        card.style.transition = 'all 0.3s ease';
                        setTimeout(() => {
                            card.style.display = 'none';
                            // Reset styles for potential re-addition
                            card.style.opacity = '';
                            card.style.transform = '';
                        }, 300);
                    }
                    
                    showToast('🚲 Removed from Racedeck');
                }

                window.closeConfirmRemovalModal();
            };

            window.addBikeToDeck = function(bikeId) {
            if (!bikeId) return;
            
            const bData = window.bikesData.find(x => x.id == bikeId);
            if (!bData) return;
            
            // Check if already in deck (active state)
            if (window.bikeState.find(x => x.id == bikeId)) {
                showToast('🚲 Athlete is already in the simulation.');
                document.getElementById('bikeLibrarySelect').value = '';
                return;
            }
            
            // Add to state
            const newState = getBikeInitialState(bData, selectedRider);
            // Set initial gears
            newState.currentFrontGear = newState.front_gears[0];
            newState.currentRearGear = newState.rear_gears[0];
            
            window.bikeState.push(newState);
            
            // Show the card
            const card = document.getElementById(`bike-card-${bikeId}`);
            if (card) {
                card.style.display = 'block';
                card.style.opacity = '0';
                card.style.transform = 'translateY(-10px)';
                card.style.transition = 'all 0.3s ease';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 10);

                // Sync gear selects
                const fSelect = card.querySelector('.front-gear-select');
                const rSelect = card.querySelector('.rear-gear-select');
                if (fSelect) fSelect.value = newState.currentFrontGear;
                if (rSelect) rSelect.value = newState.currentRearGear;
                
                showToast(`🚲 ${bData.name} added to Racedeck`);
            }

            // Reset select
            document.getElementById('bikeLibrarySelect').value = '';
        };

            const bikesForUpdate = window.bikeState || [];
            if (debugRiders) debugRiders.innerText = bikesForUpdate.length;
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
            
            const maxDist = (window.bikeState && window.bikeState.length > 0) ? Math.max(...window.bikeState.map(b => b.distance), 0.001) : 0.001;
            
            // Helper for dynamic slope
            const getRouteSlope = (distMeters) => {
                if (!window.useRouteElevation || !window.routeElevationProfile.length) return currentSlope;
                const profile = window.routeElevationProfile;
                for (let i = 1; i < profile.length; i++) {
                    if (distMeters <= profile[i].dist) return profile[i].slope;
                }
                return profile[profile.length - 1].slope;
            };

            // NEW: Helper for visual elevation offset
            const getRouteRelElev = (distMeters) => {
                if (!window.useRouteElevation || !window.routeElevationProfile.length) return 0;
                const profile = window.routeElevationProfile;
                for (let i = 1; i < profile.length; i++) {
                    if (distMeters <= profile[i].dist) {
                        // Linear interpolation for smooth visual movement
                        const prev = profile[i-1];
                        const curr = profile[i];
                        const ratio = (distMeters - prev.dist) / (curr.dist - prev.dist);
                        return prev.relElev + (curr.relElev - prev.relElev) * ratio;
                    }
                }
                return profile[profile.length - 1].relElev;
            };
            const trackGoalKm = parseFloat(document.getElementById('trackGoalInput').value) || 10;
            const trackGoalMeters = trackGoalKm * 1000;
            const canvasPadding = 50;
            const trackWidth = canvas.width - (canvasPadding * 2);
            
            ctx.save();
            // Removed slope tilt for absolute track perspective


            // Drafting Check
            // Sort bikes by distance to find who is ahead of whom
            const sortedBikes = [...window.bikeState].sort((a, b) => b.distance - a.distance);
            

            // Draw Progress Guides
            ctx.save();
            ctx.strokeStyle = 'rgba(255,255,255,0.05)';
            ctx.setLineDash([5, 10]);
            [0.25, 0.5, 0.75].forEach(p => {
                const x = canvasPadding + p * trackWidth;
                ctx.beginPath();
                ctx.moveTo(x, 40);
                ctx.lineTo(x, canvas.height - 40);
                ctx.stroke();
            });

            // Draw MAP CHECKPOINTS
            if (window.activeCheckpoints && window.activeCheckpoints.length > 2) {
                ctx.save();
                ctx.strokeStyle = '#f43f5e'; // Rose color for checkpoints
                ctx.lineWidth = 1;
                window.activeCheckpoints.forEach((p, i) => {
                    if (i === 0 || i === window.activeCheckpoints.length - 1) return; // Skip Start/End
                    const x = canvasPadding + p * trackWidth;
                    ctx.beginPath();
                    ctx.moveTo(x, 40);
                    ctx.lineTo(x, canvas.height - 40);
                    ctx.stroke();
                    
                    ctx.fillStyle = '#f43f5e';
                    ctx.font = '8px "JetBrains Mono"';
                    ctx.fillText('CP'+i, x - 10, 35);
                });
                ctx.restore();
            }

            // Draw TERRAIN PROFILE (Background)
            if (window.routeElevationProfile && window.routeElevationProfile.length > 0) {
                const profile = window.routeElevationProfile;

                // Dynamic scaleY: make the tallest point fill 65% of canvas height (with padding)
                const maxRelElev = Math.max(...profile.map(p => p.relElev), 1);
                const usableHeight = canvas.height * 0.65; // 65% of canvas for elevation range
                const scaleY = usableHeight / maxRelElev;
                // Base Y: where the flat section sits (bottom quarter of canvas)
                const baseY  = canvas.height - 60;

                ctx.save();

                // Lane guide lines (one per potential rider lane)
                [0, 85, 170].forEach((laneOffset, idx) => {
                    ctx.beginPath();
                    ctx.strokeStyle = `rgba(56, 189, 248, ${0.1 - (idx * 0.02)})`;
                    ctx.lineWidth = 1;
                    ctx.setLineDash([2, 4]);
                    profile.forEach((pt, i) => {
                        const x = canvasPadding + (Math.min(pt.dist, trackGoalMeters) / trackGoalMeters) * trackWidth;
                        const y = (baseY - laneOffset * 0.5) - (pt.relElev * scaleY);
                        if (i === 0) ctx.moveTo(x, y);
                        else ctx.lineTo(x, y);
                    });
                    ctx.stroke();
                });

                // Main thick filled profile line
                ctx.setLineDash([]);
                ctx.beginPath();
                ctx.strokeStyle = 'rgba(56, 189, 248, 0.4)';
                ctx.lineWidth = 3;
                profile.forEach((pt, i) => {
                    const x = canvasPadding + (Math.min(pt.dist, trackGoalMeters) / trackGoalMeters) * trackWidth;
                    const y = baseY - (pt.relElev * scaleY);
                    if (i === 0) ctx.moveTo(x, y);
                    else ctx.lineTo(x, y);
                });
                ctx.stroke();

                // Fill ground below the profile
                ctx.lineTo(canvasPadding + trackWidth, canvas.height);
                ctx.lineTo(canvasPadding, canvas.height);
                ctx.closePath();
                ctx.fillStyle = 'rgba(56, 189, 248, 0.05)';
                ctx.fill();

                ctx.restore();

                // NEW: DRAW SEGMENT DIVIDERS
                if (window.useSegments && window.routeSegments.length > 0) {
                    window.routeSegments.forEach((seg, i) => {
                        const startX = canvasPadding + (seg.start_pct / 100) * trackWidth;
                        const endX = canvasPadding + (seg.end_pct / 100) * trackWidth;
                        
                        // Draw segment label
                        ctx.save();
                        ctx.fillStyle = 'rgba(255,255,255,0.4)';
                        ctx.font = 'bold 8px Inter';
                        ctx.fillText(seg.name, startX + 5, baseY + 15);
                        
                        // Draw vertical divider
                        ctx.beginPath();
                        ctx.strokeStyle = i === 0 ? 'rgba(255,255,255,0.1)' : 'rgba(255,255,255,0.3)';
                        ctx.setLineDash([5, 5]);
                        ctx.moveTo(startX, 0);
                        ctx.lineTo(startX, canvas.height);
                        ctx.stroke();
                        ctx.restore();
                    });
                }

                // Store scaleY & baseY on window so the bike-rendering loop uses the SAME scale
                window._terrainScaleY = scaleY;
                window._terrainBaseY  = baseY;
            } else {
                // No elevation profile — use flat defaults
                window._terrainScaleY = 0;
                window._terrainBaseY  = canvas.height - 60;
            }
            ctx.restore();

            if (isPlaying) {
                elapsedSeconds += (1/60) * timeScale;
            }

            if (window.bikeState) window.bikeState.forEach((bike, index) => {
                // Terrain and Physics Info (Calculated every frame for smooth visuals)
                const riderSlope = getRouteSlope(bike.distance);
                const slopeAngleRider = Math.atan(riderSlope / 100);
                const relElev = getRouteRelElev(bike.distance);

                // Terrain-relative Y position for the bike sprite
                // Uses the same scaleY that the terrain profile was drawn with → always aligned
                const terrScaleY = window._terrainScaleY || 0;
                const terrBaseY  = window._terrainBaseY  || (canvas.height - 60);
                const bikeLaneOffset = index * 20;  // small vertical separation between riders
                const rawBikeY    = terrBaseY - bikeLaneOffset - (relElev * terrScaleY) - 18; // 18px above ground line
                const bikeTopPad  = 40;  // keep bike at least 40px from canvas top/bottom
                const adjustedY   = Math.max(bikeTopPad, Math.min(canvas.height - bikeTopPad, rawBikeY));
                const laneY       = terrBaseY - bikeLaneOffset - 18; // flat reference line

                const ahead = sortedBikes.find(b => b.id !== bike.id && b.distance > bike.distance && b.distance - bike.distance < 5);
                bike.isDrafting = !!ahead;
                const draftFactor = bike.isDrafting ? 0.7 : 1.0; 

                // Bioenergetics Model
                // Read target watts from the slider EACH FRAME so we never read stale bike.power
                const powerSlider = document.querySelector(`.bike-power-input[data-bike-id="${bike.id}"]`);
                const sliderTargetWatts = powerSlider ? parseFloat(powerSlider.value) : (bike.power || 200);
                
                let effectiveRiderPower = bike.isPedaling ? sliderTargetWatts : 0;

                // NEW: APPLY SEGMENT OVERRIDES
                let currentSegment = null;
                if (window.useSegments && window.routeSegments.length > 0) {
                    const distPct = (bike.distance / trackGoalMeters) * 100;
                    currentSegment = window.routeSegments.find(s => distPct >= s.start_pct && distPct < s.end_pct) 
                                   || window.routeSegments[window.routeSegments.length - 1];
                    
                    if (currentSegment) {
                        // Power Override
                        if (currentSegment.power_target_w) {
                            effectiveRiderPower = currentSegment.power_target_w;
                        }

                        // Objective Modifier
                        if (currentSegment.objective === 'stamina') {
                            // Efficiency mode: Cap power at FTP to avoid dipping too much into W'
                            effectiveRiderPower = Math.min(effectiveRiderPower, bike.ftp || 200);
                        } else if (currentSegment.objective === 'speed') {
                            // Speed mode: No cap, use full requested power
                        }

                        // Gear Override (Locking)
                        if (currentSegment.front_gear) {
                            bike.currentFrontGear = parseInt(currentSegment.front_gear);
                        }
                    }
                }

                const windKmh = 0; // No wind UI yet; extend here later
                const fatigueMultiplier = 1 - (bike.fatigue / 100) * 0.4;
                effectiveRiderPower *= fatigueMultiplier;
                if (bike.isBonking) effectiveRiderPower *= 0.35;

                let speed = 0;
                let hasConflict = false;
                const delta = (1/60) * timeScale;

                if (isPlaying && !bike.isFinished && !bike.isEating) {
                    const ratio = bike.currentFrontGear / bike.currentRearGear;
                    const circ = bike.wheel_circumference || 2.105;
                    const mass = (bike.rider_weight || 75) + (bike.bicycle_weight || 10);
                    const windMs = currentWind / 3.6;
                    
                    // 1. Calculate Forces (Synced with Drivetrain Lab)
                    const slopeAngle = Math.atan(riderSlope / 100);
                    const gravityForce = mass * G * Math.sin(slopeAngle);
                    const dragForce = (bike.drag_coefficient || 0.4) * draftFactor * Math.pow(bike.speed - windMs, 2) * Math.sign(bike.speed - windMs);
                    const rollingResistForce = (bike.rolling_coefficient || 0.005) * mass * G * Math.cos(slopeAngle);
                    const totalResistance = gravityForce + dragForce + (bike.speed > 0.1 ? rollingResistForce : 0);

                    // 2. Drive Force
                    let driveForce = 0;
                    if (bike.speed < 0.2) {
                        driveForce = effectiveRiderPower / 0.2;
                    } else {
                        driveForce = effectiveRiderPower / bike.speed;
                    }

                    const brakeForce = bike.isBraking ? 800 : 0;
                    const netForce = driveForce - totalResistance - brakeForce;
                    const acceleration = netForce / mass;

                    // UI Updates for status
                    const pedalBadge = document.getElementById(`pedal-status-${bike.id}`);
                    if (pedalBadge) {
                        pedalBadge.innerText = bike.isPedaling ? 'PEDALING' : (bike.speed > 0.1 ? 'COASTING' : 'IDLE');
                        pedalBadge.style.background = bike.isPedaling ? 'var(--success)' : (bike.speed > 0.1 ? 'var(--accent)' : 'rgba(255,255,255,0.05)');
                        pedalBadge.style.color = (bike.isPedaling || bike.speed > 0.1) ? '#0f172a' : 'rgba(255,255,255,0.4)';
                    }
                    // 3. Update Velocity
                    bike.speed = Math.max(0, bike.speed + acceleration * delta);
                    speed = bike.speed; // Update local speed variable for loop-wide use
                    
                    // 4. Update Distance & Elevation
                    bike.distance += bike.speed * delta;
                    bike.elevationGain += (bike.speed * delta) * Math.sin(slopeAngle);
                    bike.currentSlope = riderSlope;

                    // 5. Derived Metrics
                    const wheelRpm = (bike.speed * 60) / circ;
                    bike.cadence = wheelRpm / ratio;

                    // Heart Rate (Matches lab feel)
                    const targetHr = 70 + (effectiveRiderPower / (bike.ftp || 250)) * 110 + (bike.fatigue * 0.5);
                    bike.hr += (targetHr - bike.hr) * 0.02;

                    // Calorie Burn
                    const metabolicEff = bike.efficiency || 0.24;
                    const joulesThisFrame = (effectiveRiderPower * delta) / metabolicEff;
                    const kcalThisFrame = joulesThisFrame / 4184;
                    const kcalPerSec = (effectiveRiderPower / metabolicEff) / 4184;
                    bike.totalJoules += joulesThisFrame;
                    bike.calories = Math.max(0, bike.calories - kcalThisFrame);
                    
                    if (bike.calories < 100 && !bike.isBonking && !bike.isEating) {
                        bike.isBonking = true;
                        bike.logs.unshift({ time: formatSimulationTime(elapsedSeconds), msg: "⚠️ Low Fuel! (Bonking)" });
                    }

                    // Stamina & Fatigue
                    const ftp = bike.ftp || 250;
                    if (effectiveRiderPower > ftp) {
                        bike.staminaW = Math.max(0, bike.staminaW - ((effectiveRiderPower - ftp) / 1000) * delta);
                    } else {
                        bike.staminaW = Math.min(100, bike.staminaW + ((ftp - effectiveRiderPower) / 2000) * delta);
                    }
                    bike.fatigue = Math.min(100, bike.fatigue + (effectiveRiderPower * delta / 200000));

                    // Segment Tracking
                    if (currentSegment && bike._lastSegId !== currentSegment.id) {
                        bike.logs.unshift({ time: formatSimulationTime(elapsedSeconds), msg: `📍 Entering ${currentSegment.name}` });
                        bike._lastSegId = currentSegment.id;
                    }

                    if (bike.distance >= trackGoalMeters) {
                        bike.distance = trackGoalMeters;
                        bike.isFinished = true;
                        bike.finishTime = elapsedSeconds;
                        
                        // Log finish
                        const timeStr = formatSimulationTime(elapsedSeconds);
                        const distStr = (bike.distance / 1000).toFixed(2);
                        bike.logs.unshift({ time: timeStr, msg: `🏁 Race Finished at ${distStr}km` });
                        
                        // Show finish badge immediately
                        const fBadge = document.getElementById(`finish-badge-${bike.id}`);
                        if (fBadge) fBadge.style.display = 'inline-block';
                        
                        // Check if all riders are finished
                        const allFinished = window.bikeState.every(b => b.isFinished);
                        if (allFinished) {
                            isPlaying = false;
                            const playBtn = document.getElementById('playBtn');
                            const pauseBtn = document.getElementById('pauseBtn');
                            if (playBtn) playBtn.classList.remove('active');
                            if (pauseBtn) pauseBtn.classList.add('active');
                            
                            // Show Global Summary after a short delay
                            if (typeof showGlobalSummary === 'function') {
                                setTimeout(() => showGlobalSummary(), 2000);
                            }
                        }
                    }


                    


                    // History Sampling (Every 5 simulation seconds)
                    if (elapsedSeconds - bike.lastHistorySample >= 5) {
                        bike.history.push({
                            t: Math.round(elapsedSeconds),
                            p: Math.round(bike.power),
                            hr: Math.round(bike.hr),
                            d: (bike.distance / 1000).toFixed(2),
                            s: (bike.speed * 3.6).toFixed(1)
                        });
                        bike.lastHistorySample = elapsedSeconds;
                    }
                    
                    // Auto-Refuel Logic
                    const autoRefuelCheckbox = document.getElementById(`auto-refuel-${bike.id}`);
                    const refuelInterval = parseFloat(document.getElementById('globalRefuelDist').value) || 1.0;
                    if (autoRefuelCheckbox && autoRefuelCheckbox.checked) {
                        // Check if we passed a refuel distance threshold
                        const lastKm = Math.floor((bike.distance - speed * delta) / (refuelInterval * 1000));
                        const currentKm = Math.floor(bike.distance / (refuelInterval * 1000));
                        if (currentKm > lastKm) {
                            refuelRider(bike.id);
                        }
                    }

                    // Meal Logic
                    if (bike.calories <= 0 && !bike.isEating && !bike.isFinished) {
                        bike.isEating = true;
                        bike.mealEndTime = elapsedSeconds + ( (parseFloat(document.getElementById('mealInterval').value) || 10) * 60 );
                        const timeStr = formatSimulationTime(elapsedSeconds);
                        const distStr = (bike.distance / 1000).toFixed(2);
                        bike.logs.unshift({ time: timeStr, msg: `🛑 Meal Break at ${distStr}km` });
                        
                        const mOverlay = document.getElementById(`meal-overlay-${bike.id}`);
                        if (mOverlay) mOverlay.classList.add('active');
                    }

                    // NEW: Accumulate Per-Segment Results
                    if (currentSegment) {
                        if (!bike.segmentResults[currentSegment.id]) {
                            bike.segmentResults[currentSegment.id] = { time: 0, dist: 0, pwr_acc: 0, kcal_acc: 0, sample_count: 0 };
                        }
                        const s = bike.segmentResults[currentSegment.id];
                        s.time += delta;
                        s.dist += speed * delta;
                        s.pwr_acc += bike.power * delta;
                        s.kcal_acc += kcalPerSec * delta;
                        s.sample_count += delta;
                    }
                }

                if (bike.isEating && elapsedSeconds >= bike.mealEndTime) {
                    bike.isEating = false;
                    bike.calories = 1000; // Refill to 1000 after a meal break
                    const mOverlay = document.getElementById(`meal-overlay-${bike.id}`);
                    if (mOverlay) mOverlay.classList.remove('active');
                    
                    const timeStr = formatSimulationTime(elapsedSeconds);
                    bike.logs.unshift({ time: timeStr, msg: "🍖 Refuel Complete" });
                }

                // ── STEP 1: Auto-Shifting (runs BEFORE speed so we use the best gear THIS frame)
                if (bike.autoShiftEnabled) {
                    const shiftCirc = bike.wheel_circumference || 2.096;
                    const shiftWeight  = bike.bicycle_weight + bike.rider_weight;
                    const frontOptions = bike.front_gears;
                    const rearOptions  = bike.rear_gears;

                    // On flat: speed dominates; on climb (≥10%): cadence efficiency dominates
                    const climbFactor   = Math.min(1, Math.max(0, riderSlope / 10));
                    const speedWeight   = 1 - (climbFactor * 0.8);
                    const staminaWeight = climbFactor * 0.8;

                    let bestFront = bike.currentFrontGear;
                    let bestRear  = bike.currentRearGear;
                    let bestScore = -Infinity;

                    // Calculate the pure-physics speed at current power (gear-independent)
                    const pureSpeed = calculateSpeed(effectiveRiderPower, shiftWeight, riderSlope, windKmh, draftFactor);

                    frontOptions.forEach(f => {
                        rearOptions.forEach(r => {
                            const gR = f / r;
                            // Cadence each gear would produce at pureSpeed
                            const cad = pureSpeed > 0 ? (pureSpeed * 60) / (gR * shiftCirc) : 0;
                            const cadEff = getCadenceEfficiency(cad);
                            // Speed score normalised 0–1 at 20 m/s cap
                            const speedScore = Math.min(1, pureSpeed / 20);
                            const score = (speedWeight * speedScore) + (staminaWeight * cadEff);
                            if (score > bestScore) {
                                bestScore = score;
                                bestFront = f;
                                bestRear  = r;
                            }
                        });
                    });

                    if (bestFront !== bike.currentFrontGear || bestRear !== bike.currentRearGear) {
                        bike.currentFrontGear = bestFront;
                        bike.currentRearGear  = bestRear;
                        const fSelect = document.querySelector(`.front-gear-select[data-bike-id="${bike.id}"]`);
                        const rSelect = document.querySelector(`.rear-gear-select[data-bike-id="${bike.id}"]`);
                        if (fSelect) fSelect.value = bestFront;
                        if (rSelect) rSelect.value = bestRear;
                    }
                }

                // ── STEP 2: Gear ratio & cadence from CURRENT (possibly just-updated) gears
                const ratio = bike.currentFrontGear / bike.currentRearGear;
                const circ  = bike.wheel_circumference || 2.096;
                bike.cadence = bike.lockedCadence || (speed > 0 ? (speed * 60) / (ratio * circ) : 0);

                // UI Throttle
                if (Math.floor(Date.now() / 100) % 2 === 0) {
                    const gtElem = document.getElementById('globalTimer');
                    if (gtElem) gtElem.innerText = formatSimulationTime(elapsedSeconds);

                    const sElem = document.getElementById(`speed-${bike.id}`);
                    if(sElem) sElem.innerText = (bike.speed * 3.6).toFixed(1);
                    const cElem = document.getElementById(`cadence-${bike.id}`);
                    if(cElem) cElem.innerText = Math.round(bike.cadence);

                    // --- Slope / Terrain display ---
                    const slopeElem = document.getElementById(`slope-display-${bike.id}`);
                    if (slopeElem) {
                        const slopePct = riderSlope;
                        const sign     = slopePct > 0 ? '▲' : (slopePct < 0 ? '▼' : '—');
                        slopeElem.innerText = `${sign} ${Math.abs(slopePct).toFixed(1)}%`;
                        slopeElem.style.color = slopePct > 5 ? 'var(--danger)'
                                              : slopePct > 2 ? '#f59e0b'
                                              : slopePct < -2 ? 'var(--success)'
                                              : 'inherit';
                    }
                    
                    const effElem = document.getElementById(`eff-indicator-${bike.id}`);
                    if(effElem) {
                        const effPercent = Math.round(getCadenceEfficiency(bike.cadence) * 100);
                        effElem.innerText = `Eff: ${effPercent}%`;
                        if (effPercent > 85) effElem.style.color = 'var(--success)';
                        else if (effPercent > 60) effElem.style.color = '#f59e0b';
                        else effElem.style.color = 'var(--danger)';
                    }

                    // --- Nutrition (Fuel / Calories) ---
                    const calNumElem = document.getElementById(`cal-num-${bike.id}`);
                    if (calNumElem) calNumElem.innerText = Math.max(0, Math.round(bike.calories));
                    const nutritionBar = document.getElementById(`stamina-bar-${bike.id}`);
                    if (nutritionBar) {
                        const calPct = Math.max(0, (bike.calories / (bike.maxCalories || 2000)) * 100);
                        nutritionBar.style.width = `${calPct}%`;
                        nutritionBar.style.background = calPct < 20 ? 'var(--danger)' : calPct < 50 ? '#f59e0b' : 'var(--success)';
                    }

                    // --- Stamina (W') ---
                    const staminaNumElem = document.getElementById(`stamina-num-${bike.id}`);
                    if (staminaNumElem) staminaNumElem.innerText = Math.round(bike.staminaW);
                    const staminaWBar = document.getElementById(`stamina-w-bar-${bike.id}`);
                    if (staminaWBar) staminaWBar.style.width = `${Math.max(0, bike.staminaW)}%`;

                    // --- Fatigue ---
                    const fatigueNumElem = document.getElementById(`fatigue-num-${bike.id}`);
                    if (fatigueNumElem) fatigueNumElem.innerText = Math.round(bike.fatigue);
                    const fatigueBar = document.getElementById(`fatigue-bar-${bike.id}`);
                    if (fatigueBar) fatigueBar.style.width = `${Math.min(100, bike.fatigue)}%`;

                    const eElem = document.getElementById(`elev-${bike.id}`);
                    if(eElem) eElem.innerText = Math.round(bike.elevationGain);
                    const dElem = document.getElementById(`dist-km-${bike.id}`);
                    if(dElem) dElem.innerText = (bike.distance / 1000).toFixed(2);
                    const hElem = document.getElementById(`hr-${bike.id}`);
                    if(hElem) hElem.innerText = Math.round(bike.hr);
                    
                    const apElem = document.getElementById(`avgp-${bike.id}`);
                    if(apElem) apElem.innerText = Math.round(bike.avgPowerAcc / bike.sampleCount) || 0;
                    
                    const npElem = document.getElementById(`np-${bike.id}`);
                    const currentNp = Math.pow(bike.npAcc / bike.sampleCount, 0.25) || 0;
                    if(npElem) npElem.innerText = Math.round(currentNp);
                    
                    const tssElem = document.getElementById(`tss-${bike.id}`);
                    if(tssElem) {
                        const ftp = bike.ftp || 250;
                        const ifFactor = (currentNp / ftp);
                        const tss = Math.round((elapsedSeconds * currentNp * ifFactor) / (ftp * 3600) * 100);
                        tssElem.innerText = tss;
                    }

                    const hzElem = document.getElementById(`hr-zone-${bike.id}`);
                    if(hzElem) {
                        const maxHr = bike.max_hr || 190;
                        if (bike.hr >= maxHr * 0.9) hzElem.innerText = 'Z5';
                        else if (bike.hr >= maxHr * 0.8) hzElem.innerText = 'Z4';
                        else if (bike.hr >= maxHr * 0.7) hzElem.innerText = 'Z3';
                        else if (bike.hr >= maxHr * 0.6) hzElem.innerText = 'Z2';
                        else hzElem.innerText = 'Z1';
                    }
                    
                    const tGoal = parseFloat(document.getElementById('trackGoalInput').value) || 10;
                    const progressRatio = (bike.distance / 1000) / tGoal;
                    const totalProgress = Math.min(100, Math.floor(progressRatio * 100));
                    const pFill = document.getElementById(`progress-fill-${bike.id}`);
                    if (pFill) pFill.style.width = `${totalProgress}%`;
                    


                    // Update Meal Timer if eating
                    if (bike.isEating) {
                        const mTimer = document.getElementById(`meal-timer-${bike.id}`);
                        if (mTimer) {
                            const remaining = Math.max(0, bike.mealEndTime - elapsedSeconds);
                            const mins = Math.floor(remaining / 60);
                            const secs = Math.floor(remaining % 60);
                            mTimer.innerText = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                        }
                    }

                    // Render Logs
                    const logList = document.getElementById(`log-list-${bike.id}`);
                    if (logList) {
                        logList.innerHTML = bike.logs.map(log => `
                            <div class="log-item">
                                <span class="log-time">${log.time}</span>
                                <span class="log-msg">${log.msg}</span>
                            </div>
                        `).join('');
                    }
                }

                // Visual Drawing
                const screenX = canvasPadding + (Math.min(bike.distance, trackGoalMeters) / trackGoalMeters) * trackWidth;
                drawBike(ctx, screenX, adjustedY, bike.color, bike.name, bike.isFinished, bike.isDrafting, riderSlope, bike.cadence, bike.isBraking);
            });

            // Removed Global Totals update logic as these inputs are now configurable Targets

            // Draw Static Infrastructure Labels
            ctx.fillStyle = 'rgba(255,255,255,0.6)';
            ctx.font = 'bold 12px Inter';
            ctx.textAlign = 'center';
            ctx.fillText('START', 50, 160);
            ctx.fillText('GOAL', canvas.width - 50, 160);

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

        function drawBike(ctx, x, y, color, name, isFinished, isDrafting, slope = 0, cadence = 80, isBraking = false) {
            ctx.save();
            ctx.translate(x, y);
            
            // Tilt the bike based on slope
            const tilt = -Math.atan(slope / 100);
            ctx.rotate(tilt);

            const legAngle = (Date.now() / 1000) * (cadence / 60) * Math.PI * 2;
            const scale = 0.85;

            // Drafting visual (slipstream trailing)
            if (isDrafting) {
                ctx.strokeStyle = 'rgba(56, 189, 248, 0.4)';
                ctx.lineWidth = 1;
                for(let i=0; i<3; i++) {
                    const trailX = -20 - (Math.random() * 30);
                    const trailY = -10 - (Math.random() * 20);
                    ctx.beginPath();
                    ctx.moveTo(trailX, trailY);
                    ctx.lineTo(trailX - 10, trailY);
                    ctx.stroke();
                }
            }

            ctx.fillStyle = color;
            ctx.strokeStyle = color;
            ctx.lineWidth = 3 * scale;

            // Frame
            ctx.beginPath();
            ctx.moveTo(0, 0);
            ctx.lineTo(50 * scale, 0);
            ctx.lineTo(25 * scale, -45 * scale);
            ctx.closePath();
            ctx.stroke();

            // Wheels
            ctx.beginPath();
            ctx.arc(0, 0, 18 * scale, 0, Math.PI * 2);
            ctx.stroke();
            if (isBraking) {
                ctx.save();
                ctx.strokeStyle = '#f43f5e';
                ctx.lineWidth = (4 + Math.sin(Date.now()/50)*2) * scale;
                ctx.stroke();
                ctx.restore();
            }

            ctx.beginPath();
            ctx.arc(50 * scale, 0, 18 * scale, 0, Math.PI * 2);
            ctx.stroke();
            if (isBraking) {
                ctx.save();
                ctx.strokeStyle = '#f43f5e';
                ctx.lineWidth = (4 + Math.sin(Date.now()/50)*2) * scale;
                ctx.stroke();
                ctx.restore();
            }

            // Head
            ctx.fillStyle = "#f8fafc";
            ctx.beginPath();
            ctx.arc(35 * scale, -60 * scale, 7 * scale, 0, Math.PI * 2);
            ctx.fill();

            // Body
            ctx.strokeStyle = "#f8fafc";
            ctx.beginPath();
            ctx.moveTo(25 * scale, -40 * scale);
            ctx.lineTo(35 * scale, -60 * scale);
            ctx.stroke();

            // Pedals/Legs
            ctx.strokeStyle = color;
            ctx.beginPath();
            ctx.moveTo(25 * scale, -25 * scale);
            const footX = (25 * scale) + Math.cos(legAngle) * 12 * scale;
            const footY = (-25 * scale) + Math.sin(legAngle) * 12 * scale;
            ctx.lineTo(footX, footY);
            ctx.stroke();

            // Name
            ctx.fillStyle = "rgba(255,255,255,0.7)";
            ctx.font = "bold 11px Inter";
            ctx.textAlign = "center";
            ctx.fillText(name.substring(0, 15), 25 * scale, 45 * scale);

            ctx.restore();
        }

        document.addEventListener('input', (e) => {
            if (e.target.classList.contains('bike-power-input')) {
                const id = e.target.getAttribute('data-bike-id');
                const bike = window.bikeState.find(b => b.id == id);
                if(bike) {
                    bike.power = parseInt(e.target.value);
                    const display = document.getElementById(`power-display-${id}`);
                    if(display) display.innerText = bike.power;
                }
            }
            if (e.target.classList.contains('bike-efficiency-input')) {
                const id = e.target.getAttribute('data-bike-id');
                const bike = window.bikeState.find(b => b.id == id);
                if(bike) {
                    bike.efficiency = parseFloat(e.target.value) / 100;
                }
            }
            if (e.target.classList.contains('bike-dist-input')) {
                const id = e.target.getAttribute('data-bike-id');
                const bike = window.bikeState.find(b => b.id == id);
                if(bike) {
                    bike.distance = parseFloat(e.target.value) * 1000;
                }
            }
            if (e.target.classList.contains('bike-elev-input')) {
                const id = e.target.getAttribute('data-bike-id');
                const bike = window.bikeState.find(b => b.id == id);
                if(bike) {
                    bike.elevationGain = parseFloat(e.target.value);
                }
            }
        });


        // ── Keyboard Shortcuts (for Active Rider) ──────────────────────────
        window.addEventListener('keydown', (e) => {
            const activeId = document.getElementById('globalRiderSelect')?.value;
            // The activeId in globalRiderSelect is rider_id, but window.bikeState uses bike_id (from $bike->id)
            // Actually, in this sim, each rider profile is registered as a "bike-card" with its own bike.id
            // Let's find the bike that has this rider_id or is linked to the active selection
            const riderId = parseInt(activeId);
            // In simulation.index, $bicycles are loop-rendered. Each has a unique $bike->id.
            // Wait, the globalRiderSelect chooses a rider PROFILE. 
            // The bike cards are rendered from foreach($bicycles as $bike).
            // Let's assume the user wants shortcuts to affect the bike they are interacting with or the first one.
            // A better way is to find which bike card is "active" or just focus on the first one for now.
            // Or use the hover state. Let's stick to the first bike for simplicity, or find by rider name.
            
            const bike = window.bikeState ? window.bikeState[0] : null; // Default to first athlete for shortcuts
            if (!bike) return;

            if (e.code === 'Space') {
                e.preventDefault();
                setBikeBraking(bike.id, true);
            }
            
            if (bike.autoShiftEnabled) return;
            if (e.key === '[') manualShiftBikeCS(bike.id, -1);
            if (e.key === ']') manualShiftBikeCS(bike.id, 1);
            if (e.key === '1') manualShiftBikeCR(bike.id, -1);
            if (e.key === '2') manualShiftBikeCR(bike.id, 1);
        });

        window.addEventListener('keyup', (e) => {
            const bike = window.bikeState ? window.bikeState[0] : null;
            if (!bike) return;
            if (e.code === 'Space') {
                setBikeBraking(bike.id, false);
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
                const bike = window.bikeState.find(b => b.id == id);
                if(bike) bike.currentFrontGear = parseInt(e.target.value);
            }
            if (e.target.classList.contains('rear-gear-select')) {
                const id = e.target.getAttribute('data-bike-id');
                const bike = window.bikeState.find(b => b.id == id);
                if(bike) bike.currentRearGear = parseInt(e.target.value);
            }
        });

        function resetSimulation() {
            window.bikeState.forEach(b => {
                b.distance = 0;
                b.elevationGain = 0;
                b.speed = 0;
                b.isPedaling = false;
                b.isFinished = false;
                
                // Force UI update
                const dkm = document.getElementById(`dist-km-${b.id}`);
                const elv = document.getElementById(`elev-${b.id}`);
                const spd = document.getElementById(`speed-${b.id}`);
                const pedalBadge = document.getElementById(`pedal-status-${b.id}`);
                const pFill = document.getElementById(`progress-fill-${b.id}`);
                const pText = document.getElementById(`progress-text-${b.id}`);
                const card = document.getElementById(`bike-card-${b.id}`);
                const fBadge = document.getElementById(`finish-badge-${b.id}`);

                if (dkm) dkm.innerText = "0.00";
                if (elv) elv.innerText = "0";
                if (spd) spd.innerText = "0.0";
                if (pedalBadge) {
                    pedalBadge.innerText = 'IDLE';
                    pedalBadge.style.background = 'rgba(255,255,255,0.05)';
                    pedalBadge.style.color = 'rgba(255,255,255,0.4)';
                }
                if (pFill) pFill.style.width = "0%";
                if (pText) pText.innerText = "0% Complete";
                if (card) card.classList.remove('finished');
                if (fBadge) fBadge.style.display = 'none';
                
                b.startLogged = false; // Reset log state
                const list = document.getElementById(`log-list-${b.id}`);
                if (list) list.innerHTML = ''; // Clear logs on reset
                b.logs = [];
            });
            elapsedSeconds = 0;
            isPlaying = false; // Pause simulation on reset
            
            // Sync buttons
            playBtn.classList.remove('active');
            stopBtn.classList.remove('active');
            pauseBtn.classList.add('active');

            const timerElem = document.getElementById('globalTimer');
            if (timerElem) timerElem.innerText = "00:00:00";
            
            const gDist = document.getElementById('globalDistInput');
            const gElev = document.getElementById('globalElevInput');
            if (gDist) gDist.value = "0.00";
            if (gElev) gElev.value = "0";
        }

        window.applyGlobalDist = (val) => {
            const meters = parseFloat(val) * 1000;
            if (!isNaN(meters) && window.bikeState) {
                window.bikeState.forEach(b => b.distance = meters);
            }
        };

        window.applyGlobalElev = (val) => {
            const meters = parseFloat(val);
            if (!isNaN(meters) && window.bikeState) {
                window.bikeState.forEach(b => b.elevationGain = meters);
            }
        };

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
            
            const fg = Array.isArray(bike.front_gears) ? bike.front_gears : [52, 34];
            const rg = Array.isArray(bike.rear_gears) ? bike.rear_gears : [11, 28];
            document.getElementById('edit-front_gears').value = fg.join(',');
            document.getElementById('edit-rear_gears').value = rg.join(',');
            
            document.getElementById('edit-dist').value = (bike.distance / 1000).toFixed(2);
            document.getElementById('edit-elev').value = Math.round(bike.elevationGain);
            document.getElementById('edit-fuel').value = bike.maxCalories || 2500;
            
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

        // NEW: Gear Lock Listeners
        document.querySelectorAll('.gear-lock-front').forEach(cb => {
            cb.onchange = (e) => {
                const bid = e.target.getAttribute('data-bike-id');
                const b = window.bikeState.find(x => x.id == bid);
                if (b) b.frontGearLocked = e.target.checked;
            };
        });
        document.querySelectorAll('.gear-lock-rear').forEach(cb => {
            cb.onchange = (e) => {
                const bid = e.target.getAttribute('data-bike-id');
                const b = window.bikeState.find(x => x.id == bid);
                if (b) b.rearGearLocked = e.target.checked;
            };
        });

        // Playback Events
        const playBtn = document.getElementById('playBtn');
        const pauseBtn = document.getElementById('pauseBtn');
        const stopBtn = document.getElementById('stopBtn');
        const resetPlayBtn = document.getElementById('resetPlayBtn');

        // Initial Button State Sync
        playBtn.classList.remove('active');
        pauseBtn.classList.add('active');

        playBtn.onclick = () => {
            if (elapsedSeconds === 0) {
                 // First start logic
                 window.bikeState.forEach(b => {
                     if (!b.startLogged) {
                         const timeStr = formatSimulationTime(0);
                         b.logs.unshift({ time: timeStr, msg: "🚀 Race Started!" });
                         b.startLogged = true;
                         const list = document.getElementById(`log-list-${b.id}`);
                         if(list) {
                             const item = document.createElement('div');
                             item.className = 'log-item';
                             item.innerHTML = `<span class="log-time">${timeStr}</span><span class="log-val" style="color:var(--accent); font-weight:bold;">🚀 Race Started!</span>`;
                             list.prepend(item);
                         }
                     }
                 });
            }
            isPlaying = true;
            if (window.bikeState) window.bikeState.forEach(b => b.isPedaling = true);
            playBtn.classList.add('active');
            pauseBtn.classList.remove('active');
            if (stopBtn) stopBtn.classList.remove('active');
            showToast('🚴 Start Pedaling!');
        };

        pauseBtn.onclick = () => {
            isPlaying = false;
            pauseBtn.classList.add('active');
            playBtn.classList.remove('active');
            if (stopBtn) stopBtn.classList.remove('active');
            showToast('⏸️ Simulation Paused');
        };

        if (stopBtn) {
            stopBtn.onclick = () => {
                if (window.bikeState) window.bikeState.forEach(b => b.isPedaling = false);
                stopBtn.classList.add('active');
                playBtn.classList.remove('active');
                pauseBtn.classList.remove('active');
                showToast('🛑 Stop Pedaling (Coasting)');
            };
        }

        const speedBtns = document.querySelectorAll('.speed-btn');
        speedBtns.forEach(btn => {
            btn.onclick = () => {
                timeScale = parseFloat(btn.getAttribute('data-speed'));
                speedBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                // Visual feedback
                const toast = document.createElement('div');
                toast.className = 'toast';
                toast.innerText = `Simulation speed: ${timeScale}x`;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 2000);
            };
        });

        // Phase 6: Interactive Mechanics
        window.openCadenceModal = (bikeId) => {
            const bike = window.bikeState.find(b => b.id == bikeId);
            if (!bike) return;
            
            const modal = document.getElementById('cadenceModal');
            document.getElementById('cadence-bike-id').value = bikeId;
            document.getElementById('target-cadence-input').value = bike.lockedCadence || 90;
            modal.style.display = "block";
        };

        window.closeCadenceModal = () => {
            document.getElementById('cadenceModal').style.display = "none";
        };

        window.saveCadenceLock = () => {
            const bikeId = document.getElementById('cadence-bike-id').value;
            const rpm = parseInt(document.getElementById('target-cadence-input').value);
            const bike = bikeState.find(b => b.id == bikeId);
            
            if (bike && !isNaN(rpm) && rpm > 0) {
                // Save current power before locking if we haven't already
                if (bike.lockedCadence === null) {
                    bike.preLockPower = bike.power;
                }
                
                bike.lockedCadence = rpm;
                const indicator = document.getElementById(`cadence-lock-${bikeId}`);
                if (indicator) indicator.style.display = "inline-block";
                window.closeCadenceModal();
                
                // Visual feedback
                const toast = document.createElement('div');
                toast.className = 'toast';
                toast.innerText = `ERGO MODE: Cadence locked at ${rpm} RPM for ${bike.name}`;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 2000);
            }
        };

        window.openPowerModal = (bikeId) => {
            const bike = window.bikeState.find(b => b.id == bikeId);
            if (!bike) return;
            
            const modal = document.getElementById('powerModal');
            document.getElementById('power-bike-id').value = bikeId;
            document.getElementById('target-power-input').value = Math.round(bike.lockedPower || bike.power);
            modal.style.display = "block";
        };

        window.closePowerModal = () => {
            document.getElementById('powerModal').style.display = "none";
        };

        window.savePowerLock = () => {
            const bikeId = document.getElementById('power-bike-id').value;
            const watts = parseInt(document.getElementById('target-power-input').value);
            const bike = window.bikeState.find(b => b.id == bikeId);
            
            if (bike && !isNaN(watts) && watts >= 0) {
                bike.lockedPower = watts;
                bike.power = watts;
                
                const indicator = document.getElementById(`power-lock-${bikeId}`);
                if (indicator) indicator.style.display = "inline-block";
                
                // Update UI elements
                const pInput = document.querySelector(`.bike-power-input[data-bike-id="${bikeId}"]`);
                if (pInput) pInput.value = watts;
                const pDisplay = document.getElementById(`power-display-${bikeId}`);
                if (pDisplay) pDisplay.innerText = watts;
                
                window.closePowerModal();
                
                // Visual feedback
                const toast = document.createElement('div');
                toast.className = 'toast';
                toast.innerText = `Power locked at ${watts}W for ${bike.name}`;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 2000);
            }
        };

        window.releasePowerLock = () => {
            const bikeId = document.getElementById('power-bike-id').value;
            const bike = window.bikeState.find(b => b.id == bikeId);
            if (bike) {
                bike.lockedPower = null;
                const indicator = document.getElementById(`power-lock-${bikeId}`);
                if (indicator) indicator.style.display = "none";
                window.closePowerModal();
            }
        };

        window.releaseCadenceLock = () => {
            const bikeId = document.getElementById('cadence-bike-id').value;
            const bike = window.bikeState.find(b => b.id == bikeId);
            if (bike) {
                if (bike.preLockPower !== undefined) {
                    bike.power = bike.preLockPower;
                    const pInput = document.querySelector(`.bike-power-input[data-bike-id="${bikeId}"]`);
                    if (pInput) pInput.value = bike.power;
                    const pDisplay = document.getElementById(`power-display-${bikeId}`);
                    if (pDisplay) pDisplay.innerText = Math.round(bike.power);
                }
                bike.lockedCadence = null;
                const indicator = document.getElementById(`cadence-lock-${bikeId}`);
                if (indicator) indicator.style.display = "none";
                window.closeCadenceModal();
            }
        };

            resetPlayBtn.onclick = () => {
                isPlaying = false;
                elapsedSeconds = 0; // NEW: Reset the clock
                const timerElem = document.getElementById('globalTimer');
                if (timerElem) timerElem.innerText = "00:00:00"; // Force UI update
                
                window.bikeState.forEach(b => {
                    b.distance = 0;
                    b.calories = b.maxCalories;
                    b.hr = 70;
                    b.npAcc = 0;
                    b.avgPowerAcc = 0;
                    b.sampleCount = 0;
                    b.history = [];
                    b.hrZonesTime = { Z1: 0, Z2: 0, Z3: 0, Z4: 0, Z5: 0 };

                    b.isBonking = false;
                    b.isFinished = false;
                    b.finishTime = null; // Reset finish time
                    b.startLogged = false;
                    b.logs = [];
                    const list = document.getElementById(`log-list-${b.id}`);
                    if (list) list.innerHTML = '';
                    
                    // Reset UI
                    const pFill = document.getElementById(`progress-fill-${b.id}`);
                    if (pFill) pFill.style.width = "0%";
                    
                    const apElem = document.getElementById(`avgp-${b.id}`);
                    if(apElem) apElem.innerText = "0";
                    const npElem = document.getElementById(`np-${b.id}`);
                    if(npElem) npElem.innerText = "0";
                    const tssElem = document.getElementById(`tss-${b.id}`);
                    if(tssElem) tssElem.innerText = "0";
                });
                playBtn.classList.remove('active');
                pauseBtn.classList.add('active');
            };

        window.refuelRider = (bikeId) => {
            const bike = window.bikeState.find(b => b.id == bikeId);
            if (bike) {
                bike.calories = Math.min(bike.maxCalories, bike.calories + 500);
                if (bike.calories > 200) bike.isBonking = false;
                
                // Logging
                const timeStr = formatSimulationTime(elapsedSeconds);
                const distStr = (bike.distance / 1000).toFixed(2);
                const logEntry = {
                    time: timeStr,
                    msg: `⚡ Refuel (+500 kcal) at ${distStr}km`
                };
                bike.logs.unshift(logEntry);
                if (bike.logs.length > 20) bike.logs.pop();
                
                // UI feedback (toast)
                const toast = document.createElement('div');
                toast.className = 'toast';
                toast.innerText = `+500 kcal for ${bike.name}!`;
                document.body.appendChild(toast);
                setTimeout(() => toast.remove(), 2000);
            }
        };

        window.showSummary = (bikeId) => {
            // This is now called per-finish but we wait for all to stop simulation
            // We can show a toast or something, but the global summary replaces the final modal
        };

        window.showGlobalSummary = () => {
            const modal = document.getElementById('summaryModal');
            if (!modal) return;

            const tableBody = document.getElementById('global-summary-body');
            if (tableBody) {
                let html = '';
                window.bikeState.forEach(bike => {
                    const raceTime = bike.finishTime || elapsedSeconds;
                    const avgSpd = ( (bike.distance / 1000) / (raceTime / 3600) ) || 0;
                    const currentNp = Math.pow(bike.npAcc / bike.sampleCount, 0.25) || 0;
                    const ftp = bike.ftp || 250;
                    const ifFactor = (currentNp / ftp);
                    const tss = Math.round((raceTime * currentNp * ifFactor) / (ftp * 3600) * 100);

                    html += `
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); transition: background 0.2s; background: rgba(0,0,0,0.1);" onmouseover="this.style.background='rgba(255,255,255,0.03)'" onmouseout="this.style.background='rgba(0,0,0,0.1)'">
                            <td style="padding: 12px 8px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 12px; height: 12px; border-radius: 50%; background: ${bike.color};"></div>
                                    <span style="font-weight: 800; color: white;">${bike.name}</span>
                                    ${(window.routeSegments && window.routeSegments.length > 0) ? '<span style="font-size: 0.7rem; opacity: 0.5;">(Total)</span>' : ''}
                                </div>
                            </td>
                            <td style="padding: 12px 8px; font-family: 'JetBrains Mono'; color: white;">${formatSimulationTime(raceTime)}</td>
                            <td style="padding: 12px 8px; color: white;">${(bike.distance / 1000).toFixed(2)} km</td>
                            <td style="padding: 12px 8px; font-weight: bold;">${avgSpd.toFixed(1)} <small style="opacity:0.5; font-weight:normal;">km/h</small></td>
                            <td style="padding: 12px 8px; color: white;">${Math.round(bike.avgPowerAcc / bike.sampleCount) || 0}W</td>
                            <td style="padding: 12px 8px; color: var(--success); font-weight: 700;">${Math.round(currentNp)}W</td>
                            <td style="padding: 12px 8px; color: #f59e0b; font-weight: 700;">${Math.max(0, tss)}</td>
                        </tr>
                    `;

                    if (window.routeSegments && window.routeSegments.length > 0 && bike.segmentResults) {
                        window.routeSegments.forEach(seg => {
                            const res = bike.segmentResults[seg.id];
                            if (res) {
                                const sTime = res.time;
                                const sDist = res.dist;
                                const sAvgSpd = ( (sDist / 1000) / (sTime / 3600) ) || 0;
                                const sAvgPwr = Math.round(res.pwr_acc / Math.max(1, res.sample_count)) || 0;
                                
                                html += `
                                    <tr style="border-bottom: 1px dashed rgba(255,255,255,0.02); font-size: 0.75rem;">
                                        <td style="padding: 6px 8px 6px 30px; color: rgba(255,255,255,0.6); display: flex; align-items: center; gap: 6px;">
                                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18l6-6-6-6"/></svg>
                                            ${seg.name}
                                        </td>
                                        <td style="padding: 6px 8px; font-family: 'JetBrains Mono'; color: rgba(255,255,255,0.5);">${formatSimulationTime(sTime)}</td>
                                        <td style="padding: 6px 8px; color: rgba(255,255,255,0.5);">${(sDist / 1000).toFixed(2)} km</td>
                                        <td style="padding: 6px 8px; color: rgba(255,255,255,0.5); font-weight: bold;">${sAvgSpd.toFixed(1)} <small style="opacity:0.4; font-weight:normal;">km/h</small></td>
                                        <td style="padding: 6px 8px; color: rgba(255,255,255,0.5);">${sAvgPwr}W</td>
                                        <td style="padding: 6px 8px; color: rgba(255,255,255,0.2);">-</td>
                                        <td style="padding: 6px 8px; color: rgba(255,255,255,0.2);">-</td>
                                    </tr>
                                `;
                            }
                        });
                    }
                });
                tableBody.innerHTML = html;
            }

            // NEW: Add "Save Results" button to summary
            const footer = modal.querySelector('div[style*="justify-content: flex-end"]');
            if (footer && !document.getElementById('save-results-btn')) {
                const saveBtn = document.createElement('button');
                saveBtn.id = 'save-results-btn';
                saveBtn.className = 'btn';
                saveBtn.style.background = 'var(--accent)';
                saveBtn.style.boxShadow = '0 4px 14px rgba(56, 189, 248, 0.4)';
                saveBtn.innerText = '💾 Save Results';
                saveBtn.onclick = () => {
                    closeSummaryModal();
                    openSaveModal();
                };
                footer.prepend(saveBtn);

                // Add "View History" button too
                const histBtn = document.createElement('button');
                histBtn.className = 'btn';
                histBtn.style.background = 'rgba(255,255,255,0.05)';
                histBtn.style.border = '1px solid rgba(255,255,255,0.1)';
                histBtn.style.marginRight = 'auto';
                histBtn.innerText = '🕒 View Past Results';
                histBtn.onclick = () => {
                    closeSummaryModal();
                    openHistoryModal();
                };
                footer.prepend(histBtn);
            }

            modal.style.display = "block";
        };

        window.toggleSegmentBreakdown = () => {
            const view = document.getElementById('segment-breakdown-view');
            const content = document.getElementById('segment-breakdown-content');
            
            if (view.style.display === 'block') {
                view.style.display = 'none';
                return;
            }
            
            if (window.bikeState.length === 0) return;

            content.innerHTML = window.bikeState.map(bike => {
                const results = Object.keys(bike.segmentResults).map(segId => {
                    const s = bike.segmentResults[segId];
                    const rs = window.routeSegments.find(r => r.id == segId);
                    return {
                        name: rs ? rs.name : 'Unknown',
                        avgSpd: ( (s.dist / 1000) / (s.time / 3600) ) || 0,
                        avgPwr: Math.round(s.pwr_acc / s.sample_count) || 0,
                        time: formatSimulationTime(s.time),
                        kcal: Math.round(s.kcal_acc)
                    };
                });
                
                return `
                    <div style="margin-bottom: 1.5rem; background: rgba(0,0,0,0.2); border-radius: 12px; padding: 15px; border: 1px solid rgba(255,255,255,0.05);">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <div style="width: 10px; height: 10px; border-radius: 50%; background: ${bike.color};"></div>
                            <span style="font-weight: 700; color: white; font-size: 0.9rem;">${bike.name} Performance</span>
                        </div>
                        <table style="width: 100%; border-collapse: collapse; font-size: 0.75rem;">
                            <thead>
                                <tr style="text-align: left; opacity: 0.5;">
                                    <th style="padding: 8px;">Segment</th>
                                    <th style="padding: 8px;">Time</th>
                                    <th style="padding: 8px;">Avg Speed</th>
                                    <th style="padding: 8px;">Avg Power</th>
                                    <th style="padding: 8px;">Energy</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${results.map(r => `
                                    <tr style="border-top: 1px solid rgba(255,255,255,0.03);">
                                        <td style="padding: 8px; font-weight: 600; color: white;">${r.name}</td>
                                        <td style="padding: 8px; font-family: 'JetBrains Mono'; color: rgba(255,255,255,0.8);">${r.time}</td>
                                        <td style="padding: 8px; font-weight: bold; color: var(--accent);">${r.avgSpd.toFixed(1)} km/h</td>
                                        <td style="padding: 8px; color: var(--success); font-weight: 600;">${r.avgPwr} W</td>
                                        <td style="padding: 8px; color: #f87171;">${r.kcal} kcal</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            }).join('');
            
            view.style.display = 'block';
        };

        // ==========================================
        // PHASE 29: SESSION PERSISTENCE (SAVE/LOAD)
        // ==========================================
        
        window.openSaveModal = () => {
            const modal = document.getElementById('saveSessionModal');
            const preview = document.getElementById('sessionSummaryPreview');
            
            const now = new Date();
            document.getElementById('sessionNameInput').value = `Race ${now.toLocaleDateString()} ${now.toLocaleTimeString()}`;
            
            const b = window.bikeState[0];
            const raceTime = b.finishTime || elapsedSeconds;
            const distKm = (b.distance / 1000).toFixed(2);
            const avgSpd = ( (b.distance / 1000) / (raceTime / 3600) ).toFixed(1);
            
            preview.innerHTML = `
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div><span style="opacity: 0.6;">Rider:</span> <b>${b.name}</b></div>
                    <div><span style="opacity: 0.6;">Time:</span> <b>${formatSimulationTime(raceTime)}</b></div>
                    <div><span style="opacity: 0.6;">Distance:</span> <b>${distKm} km</b></div>
                    <div><span style="opacity: 0.6;">Avg Speed:</span> <b>${avgSpd} km/h</b></div>
                </div>
            `;
            
            modal.style.display = 'block';
        };

        window.closeSaveModal = () => document.getElementById('saveSessionModal').style.display = 'none';

        window.submitSession = async () => {
             const btn = document.getElementById('confirmSaveBtn');
             btn.disabled = true;
             btn.innerText = 'Saving...';
             
             const b = window.bikeState[0];
             const raceTime = b.finishTime || elapsedSeconds;
             const avgPwr = Math.round(b.avgPowerAcc / b.sampleCount) || 0;
             let avgSpd = ( (b.distance / 1000) / ( (raceTime || 1) / 3600) ) || 0;
             if (!isFinite(avgSpd)) avgSpd = 0;

             const currentNp = Math.pow(b.npAcc / (b.sampleCount || 1), 0.25) || 0;
             const ftp = b.ftp || 250;
             const ifFactor = (currentNp / ftp);
             let tssValue = Math.round((raceTime * currentNp * ifFactor) / (ftp * 3600) * 100) || 0;
             if (!isFinite(tssValue)) tssValue = 0;

             const payload = {
                 name: document.getElementById('sessionNameInput').value,
                 bicycle_id: b.id,
                 rider_id: b.rider_id,
                 route_name: window.useRouteElevation ? "Custom Route" : "Flat Road",
                 total_distance_km: parseFloat((b.distance / 1000).toFixed(2)),
                 total_time_seconds: Math.round(raceTime),
                 avg_speed_kmh: parseFloat(avgSpd.toFixed(2)),
                 avg_power_w: avgPwr,
                 total_calories_burned: Math.round(b.maxCalories - b.calories) || 0,
                 total_elevation_m: b.elevationGain || 0,
                 tss: tssValue,
                 segments: window.routeSegments || [],
                 route_elevation_profile: window.routeElevationProfile || [],
                 segment_results: Object.keys(b.segmentResults || {}).map(segId => {
                     const s = b.segmentResults[segId];
                     const rs = (window.routeSegments || []).find(r => r.id == segId);
                     let sSpd = ( (s.dist / 1000) / ( (s.time || 1) / 3600) ) || 0;
                     if (!isFinite(sSpd)) sSpd = 0;
                     return {
                         segment_id: parseInt(segId),
                         name: rs ? rs.name : 'Unknown',
                         avg_speed_kmh: parseFloat(sSpd.toFixed(2)),
                         avg_power_w: Math.round(s.pwr_acc / (s.sample_count || 1)) || 0,
                         time_seconds: Math.round(s.time),
                         calories: Math.round(s.kcal_acc),
                         start_pct: rs ? rs.start_pct : 0,
                         end_pct: rs ? rs.end_pct : 100
                     };
                 })
             };

             try {
                 const response = await fetch('/sessions', {
                     method: 'POST',
                     headers: { 
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': '{{ csrf_token() }}',
                         'Accept': 'application/json'
                     },
                     body: JSON.stringify(payload)
                 });
                 if (response.ok) {
                     closeSaveModal();
                     showToast(`
                        <span style="display:flex; align-items:center; gap:8px;">✅ Session Saved!</span>
                        <button onclick="openHistoryModal()" style="background:var(--accent); color:var(--bg-color); border:none; padding:4px 8px; border-radius:4px; font-size:0.7rem; font-weight:bold; cursor:pointer;">VIEW HISTORY</button>
                     `, 5000);
                 } else {
                     const errData = await response.json();
                     alert(`Failed to save: ${errData.message || 'Unknown error'}`);
                 }
             } catch (err) {
                 console.error("Save Error:", err);
                 alert("Failed to save session.");
             } finally {
                 btn.disabled = false;
                 btn.innerText = 'Confirm Save';
             }
        };

        window.openHistoryModal = () => {
            document.getElementById('historyModal').style.display = 'block';
            fetchHistory();
        };

        window.closeHistoryModal = () => document.getElementById('historyModal').style.display = 'none';

        window.fetchHistory = async () => {
            const list = document.getElementById('session-history-list');
            const loader = document.getElementById('session-history-loading');
            
            list.innerHTML = '';
            loader.style.display = 'block';

            try {
                const response = await fetch('{{ route("sessions.index") }}');
                const data = await response.json();
                loader.style.display = 'none';

                if (data.sessions.length === 0) {
                    list.innerHTML = '<p style="text-align: center; opacity: 0.5; padding: 2rem;">No saved simulation sessions found.</p>';
                } else {
                    window._lastSessions = data.sessions; // Cache for detail view
                    list.innerHTML = data.sessions.map(s => `
                        <div class="session-item" id="session-row-${s.id}">
                            <div class="session-info" onclick="openSessionDetail(${s.id})" style="cursor:pointer;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="width: 8px; height: 8px; border-radius: 50%; background: ${s.bicycle_color}"></div>
                                    <h4 style="color:white;">${s.name}</h4>
                                    <small style="opacity:0.5; font-size: 0.7rem;">👤 ${s.rider_name || 'Generic Rider'}</small>
                                </div>
                                <p>${s.route_name || 'Generic Route'} • ${s.created_at}</p>
                                <div class="session-stats">
                                    <span>${s.total_distance_km}km</span>
                                    <span>${s.formatted_time}</span>
                                    <span style="color:var(--accent);">${s.avg_speed_kmh}km/h</span>
                                    <span>${s.avg_power_w}W</span>
                                </div>
                            </div>
                            <div class="session-actions" style="display: flex; gap: 8px;">
                                <button class="btn btn-sm" style="padding: 4px 10px; background: rgba(56, 189, 248, 0.1); color: var(--accent); border: 1px solid rgba(56, 189, 248, 0.2); font-size: 0.7rem;" onclick="openSessionDetail(${s.id})">Info</button>
                                <button class="btn btn-sm" style="padding: 4px 10px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.2); font-size: 0.7rem;" onclick="deleteSession(${s.id})">Delete</button>
                            </div>
                        </div>
                    `).join('');
                }
            } catch (err) {
                console.error("Fetch Error:", err);
                loader.innerHTML = '<p style="color:red;">Error loading history.</p>';
            }
        };

        window.openSessionDetail = (id) => {
            const s = window._lastSessions.find(x => x.id == id);
            if (!s) return;
            
            document.getElementById('detail-session-name').innerText = s.name;
            document.getElementById('detail-session-date').innerText = `${s.bicycle_name} • ${s.created_at}`;
            document.getElementById('detail-time').innerText = s.formatted_time;
            document.getElementById('detail-dist').innerText = `${s.total_distance_km} km`;
            document.getElementById('detail-speed').innerText = `${s.avg_speed_kmh} km/h`;
            document.getElementById('detail-tss').innerText = s.tss;
            
            const body = document.getElementById('detail-segments-body');
            if (s.segment_results && s.segment_results.length > 0) {
                body.innerHTML = s.segment_results.map(r => `
                    <tr style="border-bottom: 1px solid rgba(255,255,255,0.03);">
                        <td style="padding: 12px 10px; font-weight: 600; color: white;">${r.name || 'Segment'}</td>
                        <td style="padding: 12px 10px; font-family: 'JetBrains Mono'; color: rgba(255,255,255,0.8);">${formatSimulationTime(r.time_seconds)}</td>
                        <td style="padding: 12px 10px; font-weight: bold; color: var(--accent);">${parseFloat(r.avg_speed_kmh).toFixed(1)} km/h</td>
                        <td style="padding: 12px 10px; color: var(--success); font-weight: 600;">${Math.round(r.avg_power_w)} W</td>
                        <td style="padding: 12px 10px; color: #f87171;">${Math.round(r.calories)} kcal</td>
                    </tr>
                `).join('');
            } else {
                body.innerHTML = '<tr><td colspan="5" style="padding: 20px; text-align: center; opacity: 0.5;">No segment data for this session.</td></tr>';
            }
            
            document.getElementById('sessionDetailModal').style.display = 'block';
        };

        window.closeDetailModal = () => document.getElementById('sessionDetailModal').style.display = 'none';

        window.deleteSession = async (id) => {
            if (!confirm('Are you sure you want to delete this result?')) return;
            
            try {
                const response = await fetch(`/sessions/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                });
                const result = await response.json();
                if (result.success) {
                    const row = document.getElementById(`session-row-${id}`);
                    if (row) row.remove();
                    showToast('Session deleted.');
                }
            } catch (err) { console.error(err); }
        };

        function showToast(msg, duration = 3000) {
            const t = document.createElement('div');
            t.className = 'toast';
            t.style.display = 'flex';
            t.style.alignItems = 'center';
            t.style.gap = '12px';
            t.innerHTML = msg;
            document.body.appendChild(t);
            setTimeout(() => {
                t.style.opacity = '0';
                t.style.transform = 'translateY(10px)';
                setTimeout(() => t.remove(), 500);
            }, duration);
        }

        window.closeSummaryModal = () => {
            document.getElementById('summaryModal').style.display = 'none';
        };

        window.downloadCSV = (bikeId) => {
            const bike = window.bikeState.find(b => b.id == bikeId);
            if (!bike || !bike.history.length) return;

            let csv = "Time (s),Power (W),HR (BPM),Distance (km),Speed (km/h)\n";
            bike.history.forEach(h => {
                csv += `${h.t},${h.p},${h.hr},${h.d},${h.s}\n`;
            });

            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.setAttribute('hidden', '');
            a.setAttribute('href', url);
            a.setAttribute('download', `race_data_${bike.name.replace(/\s+/g, '_')}.csv`);
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        };

            updateSimulation();
        }; // End window.onload
    </script>
    <!-- Cadence Modal -->
    <div id="cadenceModal" class="modal">
        <div class="modal-content" style="max-width: 400px; border-top: 4px solid var(--primary);">
            <div class="modal-header">
                <h2>Set Cadence Lock</h2>
                <span class="close" onclick="window.closeCadenceModal()">&times;</span>
            </div>
            <div style="padding: 1rem 0;">
                <p style="font-size: 0.85rem; opacity: 0.7; margin-bottom: 1.5rem; line-height: 1.4;">
                    Locking cadence overrides rider power. The bike will move at exactly the speed required to maintain this RPM in the current gear.
                </p>
                <input type="hidden" id="cadence-bike-id">
                <div class="control-group">
                    <label style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;">Target RPM</label>
                    <input type="number" id="target-cadence-input" class="form-control" placeholder="e.g. 90" min="0" max="250" style="font-size: 1.2rem; font-family: 'JetBrains Mono'; text-align: center; letter-spacing: 0.1em;">
                </div>
            </div>
            <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem;">
                <button type="button" class="btn btn-outline" style="border-color: #ef4444; color: #ef4444;" onclick="window.releaseCadenceLock()">Release Lock</button>
                <button type="button" class="btn" style="background: var(--primary); box-shadow: 0 4px 14px rgba(56, 189, 248, 0.4);" onclick="window.saveCadenceLock()">Set Lock</button>
            </div>
        </div>
    </div>

    <!-- Power Modal -->
    <div id="powerModal" class="modal">
        <div class="modal-content" style="max-width: 400px; border-top: 4px solid var(--accent);">
            <div class="modal-header">
                <h2>Set Power Lock</h2>
                <span class="close" onclick="window.closePowerModal()">&times;</span>
            </div>
            <div style="padding: 1rem 0;">
                <p style="font-size: 0.85rem; opacity: 0.7; margin-bottom: 1.5rem; line-height: 1.4;">
                    Locking power will ignore the slider input. This ensures the rider maintains a constant effort regardless of UI interactions.
                </p>
                <input type="hidden" id="power-bike-id">
                <div class="control-group">
                    <label style="font-family: 'Inter', sans-serif; font-weight: 600; font-size: 0.8rem; text-transform: uppercase;">Target Watts</label>
                    <input type="number" id="target-power-input" class="form-control" placeholder="e.g. 250" min="0" max="1500" style="font-size: 1.2rem; font-family: 'JetBrains Mono'; text-align: center; letter-spacing: 0.1em;">
                </div>
            </div>
            <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem;">
                <button type="button" class="btn btn-outline" style="border-color: #ef4444; color: #ef4444;" onclick="window.releasePowerLock()">Release Lock</button>
                <button type="button" class="btn" style="background: var(--accent); box-shadow: 0 4px 14px rgba(56, 189, 248, 0.4);" onclick="window.savePowerLock()">Set Lock</button>
            </div>
        </div>
    </div>

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
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                    <div class="control-group">
                        <label>Current Distance (km)</label>
                        <input type="number" name="initial_distance" id="edit-dist" step="0.1" class="form-control">
                    </div>
                    <div class="control-group">
                        <label>Current Elevation (m)</label>
                        <input type="number" name="initial_elevation" id="edit-elev" step="1" class="form-control">
                    </div>
                    <div class="control-group">
                        <label>Initial Fuel (kcal)</label>
                        <input type="number" name="initial_fuel" id="edit-fuel" step="1" class="form-control">
                    </div>
                </div>

                <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
                    <button type="submit" class="btn">Update Rider</button>
                    <button type="button" class="btn btn-outline" onclick="closeEditModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Map Modal Removed - Integrated Inline -->
    
    <!-- Session Summary Modal -->
    <div id="summaryModal" class="modal">
        <div class="modal-content" style="max-width: 800px; border-top: 6px solid var(--success); background: #0f172a;">
            <div class="modal-header">
                <div>
                    <h2 style="margin: 0; display: flex; align-items: center; gap: 10px;">
                        🏁 Race Results - Global Summary
                    </h2>
                    <p style="font-size: 0.8rem; opacity: 0.6; margin-top: 4px;">All riders have completed the course</p>
                </div>
                <span class="close" onclick="closeSummaryModal()">&times;</span>
            </div>
            
            <div style="margin-top: 1.5rem; overflow-x: auto; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem; text-align: left;">
                    <thead>
                        <tr style="background: rgba(255,255,255,0.03); border-bottom: 2px solid rgba(255,255,255,0.05);">
                            <th style="padding: 15px 8px; color: var(--text-secondary); text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em;">Rider</th>
                            <th style="padding: 15px 8px; color: var(--text-secondary); text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em;">Time</th>
                            <th style="padding: 15px 8px; color: var(--text-secondary); text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em;">Dist</th>
                            <th style="padding: 15px 8px; color: var(--text-secondary); text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em;">Avg Spd</th>
                            <th style="padding: 15px 8px; color: var(--text-secondary); text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em;">Avg Power</th>
                            <th style="padding: 15px 8px; color: var(--text-secondary); text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; color: var(--success);">NP</th>
                            <th style="padding: 15px 8px; color: var(--text-secondary); text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; color: #f59e0b;">TSS</th>
                        </tr>
                    </thead>
                    <tbody id="global-summary-body">
                        <!-- Populated by JS -->
                    </tbody>
                </table>
            </div>

            <div id="segment-breakdown-view" style="display: none; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.1);">
                <h3 style="font-size: 0.9rem; margin-bottom: 1rem; color: var(--accent); display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 1.2rem;">📊</span> Per-Segment Performance
                </h3>
                <div id="segment-breakdown-content">
                    <!-- Segment breakdown tables will be injected here -->
                </div>
            </div>

            <div style="margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem;">
                <button type="button" class="btn btn-outline" style="border-color: var(--accent); color: var(--accent);" onclick="toggleSegmentBreakdown()">📋 Segment Breakdown</button>
                <button class="btn" style="background: var(--success); box-shadow: 0 4px 14px rgba(34, 197, 94, 0.4);" onclick="closeSummaryModal()">Close Results</button>
            </div>
        </div>
    </div>

    <!-- SAVE SESSION MODAL -->
    <div id="saveSessionModal" class="modal">
        <div class="modal-content" style="max-width: 500px; border-top: 6px solid var(--accent); background: #0f172a;">
            <div class="modal-header">
                <div>
                    <h2 style="margin: 0; display: flex; align-items: center; gap: 10px;">💾 Save Simulation Result</h2>
                    <p style="font-size: 0.8rem; opacity: 0.6; margin-top: 4px;">Store this race result to your history</p>
                </div>
                <span class="close" onclick="closeSaveModal()">&times;</span>
            </div>
            
            <div style="margin: 1.5rem 0;">
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.8rem; opacity: 0.7;">Session Name</label>
                <input type="text" id="sessionNameInput" class="form-control" style="width: 100%; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.2); padding: 10px; border-radius: 8px; color: white;" placeholder="e.g., Morning Climb on Route X">
                
                <div id="sessionSummaryPreview" style="margin-top: 1.5rem; background: rgba(255,255,255,0.03); border-radius: 8px; padding: 1rem; font-size: 0.8rem;">
                    <!-- Data preview populated by JS -->
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <button class="btn btn-outline" onclick="closeSaveModal()">Cancel</button>
                <button class="btn" style="background: var(--accent);" id="confirmSaveBtn" onclick="submitSession()">Confirm Save</button>
            </div>
        </div>
    </div>

    <!-- SESSION HISTORY MODAL -->
    <div id="historyModal" class="modal">
        <div class="modal-content" style="max-width: 700px; border-top: 6px solid var(--accent); background: #0f172a;">
            <div class="modal-header">
                <div>
                    <h2 style="margin: 0; display: flex; align-items: center; gap: 10px;">📋 Simulation History</h2>
                    <p style="font-size: 0.8rem; opacity: 0.6; margin-top: 4px;">Past simulation results and strategies</p>
                </div>
                <span class="close" onclick="closeHistoryModal()">&times;</span>
            </div>
            
            <div id="session-history-loading" style="text-align: center; padding: 2rem; display: none;">
                 <div class="spinner" style="margin: 0 auto;"></div>
                 <p style="margin-top: 10px; font-size: 0.8rem; opacity: 0.5;">Fetching your past races...</p>
            </div>

            <div id="session-history-list" class="session-list" style="margin-top: 1.5rem;">
                <!-- Populated by JS -->
            </div>

            <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                <button class="btn btn-outline" onclick="closeHistoryModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- SESSION DETAIL MODAL (PAST RUNS) -->
    <div id="sessionDetailModal" class="modal" style="z-index: 2100;">
        <div class="modal-content" style="max-width: 800px; border-top: 6px solid var(--accent); background: #0f172a;">
            <div class="modal-header">
                <div>
                    <h2 id="detail-session-name" style="margin: 0; color: white;">Session Detail</h2>
                    <p id="detail-session-date" style="font-size: 0.8rem; opacity: 0.6; margin-top: 4px;"></p>
                </div>
                <span class="close" onclick="closeDetailModal()">&times;</span>
            </div>
            
            <div id="detail-summary-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-top: 1.5rem; background: rgba(0,0,0,0.2); padding: 15px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                <div class="stat-item"><label style="opacity: 0.5; font-size: 0.7rem; display: block;">Time</label><span id="detail-time" style="font-weight: bold; color: white; font-size: 0.9rem;"></span></div>
                <div class="stat-item"><label style="opacity: 0.5; font-size: 0.7rem; display: block;">Distance</label><span id="detail-dist" style="font-weight: bold; color: white; font-size: 0.9rem;"></span></div>
                <div class="stat-item"><label style="opacity: 0.5; font-size: 0.7rem; display: block;">Avg Speed</label><span id="detail-speed" style="font-weight: bold; color: var(--accent); font-size: 0.9rem;"></span></div>
                <div class="stat-item"><label style="opacity: 0.5; font-size: 0.7rem; display: block;">TSS</label><span id="detail-tss" style="font-weight: bold; color: #f59e0b; font-size: 0.9rem;"></span></div>
            </div>

            <div style="margin-top: 1.5rem;">
                <h3 style="font-size: 0.9rem; margin-bottom: 1rem; color: var(--accent); display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 1.2rem;">🏁</span> Segment Breakdown
                </h3>
                <div id="detail-segments-container" style="overflow-x: auto; background: rgba(255,255,255,0.02); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05);">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.8rem; text-align: left;">
                        <thead>
                            <tr style="background: rgba(255,255,255,0.03); border-bottom: 2px solid rgba(255,255,255,0.05);">
                                <th style="padding: 12px 10px; color: rgba(255,255,255,0.5); text-transform: uppercase; font-size: 0.65rem;">Segment</th>
                                <th style="padding: 12px 10px; color: rgba(255,255,255,0.5); text-transform: uppercase; font-size: 0.65rem;">Time</th>
                                <th style="padding: 12px 10px; color: rgba(255,255,255,0.5); text-transform: uppercase; font-size: 0.65rem;">Avg Speed</th>
                                <th style="padding: 12px 10px; color: rgba(255,255,255,0.5); text-transform: uppercase; font-size: 0.65rem;">Avg Power</th>
                                <th style="padding: 12px 10px; color: rgba(255,255,255,0.5); text-transform: uppercase; font-size: 0.65rem;">Energy</th>
                            </tr>
                        </thead>
                        <tbody id="detail-segments-body"></tbody>
                    </table>
                </div>
            </div>

            <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                <button class="btn" onclick="closeDetailModal()">Close Detail</button>
            </div>
        </div>
    </div>

    <!-- SAVE ROUTE MODAL -->
    <div id="saveRouteModal" class="modal" style="z-index: 2200;">
        <div class="modal-content" style="max-width: 450px; border-top: 6px solid var(--accent); background: #0f172a;">
            <div class="modal-header">
                <div>
                    <h2 style="margin: 0; display: flex; align-items: center; gap: 10px;">💾 Save Planned Route</h2>
                    <p style="font-size: 0.8rem; opacity: 0.6; margin-top: 4px;">Store this route for future use</p>
                </div>
                <span class="close" onclick="closeSaveRouteModal()">&times;</span>
            </div>
            
            <div style="margin: 1.5rem 0;">
                <label style="display: block; margin-bottom: 0.5rem; font-size: 0.8rem; opacity: 0.7;">Route Name</label>
                <input type="text" id="plannedRouteNameInput" class="form-control" style="width: 100%; border: 1px solid rgba(255,255,255,0.1); background: rgba(0,0,0,0.2); padding: 10px; border-radius: 8px; color: white;" placeholder="e.g. Surabaya City Loop">
                
                <div id="plannedRouteSummaryPreview" style="margin-top: 1.5rem; background: rgba(56, 189, 248, 0.05); border-radius: 8px; padding: 1rem; font-size: 0.8rem; border: 1px solid rgba(56, 189, 248, 0.1);">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span>Distance:</span> <b id="preview-route-dist" style="color:white;">0.00 km</b>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Ascent:</span> <b id="preview-route-ascent" style="color:var(--accent);">0 m</b>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <button class="btn btn-outline" onclick="closeSaveRouteModal()">Cancel</button>
                <button class="btn" style="background: var(--accent);" onclick="submitSaveRoute()">Save Route</button>
            </div>
        </div>
    </div>

    <!-- LOAD ROUTE MODAL -->
    <div id="loadRouteModal" class="modal" style="z-index: 2200;">
        <div class="modal-content" style="max-width: 600px; border-top: 6px solid #a855f7; background: #0f172a;">
            <div class="modal-header">
                <div>
                    <h2 style="margin: 0; display: flex; align-items: center; gap: 10px;">📂 Load Saved Route</h2>
                    <p style="font-size: 0.8rem; opacity: 0.6; margin-top: 4px;">Select a previously saved route</p>
                </div>
                <span class="close" onclick="closeLoadRouteModal()">&times;</span>
            </div>
            
            <div id="route-history-loading" style="text-align: center; padding: 2rem; display: none;">
                 <div class="spinner" style="margin: 0 auto;"></div>
                 <p style="margin-top: 10px; font-size: 0.8rem; opacity: 0.5;">Loading routes...</p>
            </div>

            <div id="saved-routes-list" style="margin-top: 1.5rem; max-height: 400px; overflow-y: auto;">
                <!-- Populated by JS -->
            </div>

            <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                <button class="btn btn-outline" onclick="closeLoadRouteModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- CONFIRM REMOVAL MODAL -->
    <div id="confirmRemovalModal" class="modal" style="z-index: 2500;">
        <div class="modal-content" style="max-width: 400px; border-top: 6px solid #ef4444; background: #0f172a;">
            <div class="modal-header">
                <div>
                    <h2 style="margin: 0; display: flex; align-items: center; gap: 10px;">⚠️ Confirm Removal</h2>
                </div>
                <span class="close" onclick="closeConfirmRemovalModal()">&times;</span>
            </div>
            
            <div style="margin: 1.5rem 0;">
                <p style="font-size: 0.9rem; line-height: 1.5; color: rgba(255,255,255,0.8);">
                    Are you sure you want to remove this rider from the simulation deck? 
                    <br><br>
                    <span style="font-size: 0.8rem; opacity: 0.6;">Note: This will not delete the bicycle from the database.</span>
                </p>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <button class="btn btn-outline" onclick="closeConfirmRemovalModal()">Cancel</button>
                <button class="btn" style="background: #ef4444; box-shadow: 0 4px 14px rgba(239, 68, 68, 0.4);" onclick="confirmRemoval()">Remove Rider</button>
            </div>
        </div>
    </div>

    <!-- Leaflet Map JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </div>
</body>
</html>
