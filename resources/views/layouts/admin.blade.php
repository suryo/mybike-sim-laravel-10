<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MyBike Pro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0f172a;
            --sidebar-bg: rgba(15, 23, 42, 0.95);
            --card-bg: #1e293b;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent: #38bdf8;
            --danger: #ef4444;
            --border: rgba(255,255,255,0.05);
            --gradient: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%);
            --glass: rgba(30, 41, 59, 0.7);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { 
            background-color: var(--bg-color); 
            color: var(--text-primary); 
            display: flex; 
            min-height: 100vh;
            background-image: radial-gradient(circle at 0% 0%, rgba(56, 189, 248, 0.05) 0%, transparent 40%),
                              radial-gradient(circle at 100% 100%, rgba(129, 140, 248, 0.05) 0%, transparent 40%);
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            padding: 2.5rem 0;
            position: fixed;
            height: 100vh;
            z-index: 1000;
        }

        .sidebar-logo {
            padding: 0 2rem 3rem;
            font-weight: 800;
            font-size: 1.5rem;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .nav-group { margin-bottom: 2rem; }
        .nav-label {
            padding: 0 2.5rem 0.75rem;
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            opacity: 0.5;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.85rem 2.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 3px solid transparent;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.03);
            color: var(--text-primary);
            padding-left: 2.75rem;
        }

        .nav-link.active {
            background: rgba(56, 189, 248, 0.08);
            color: var(--accent);
            border-left-color: var(--accent);
            font-weight: 600;
        }

        .nav-link svg { margin-right: 12px; transition: transform 0.3s; }
        .nav-link:hover svg { transform: scale(1.1); }

        /* Main Content */
        .main {
            flex: 1;
            margin-left: 260px;
            padding: 2rem;
            max-width: 100%;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4rem;
        }

        .page-title h1 { font-size: 2.25rem; font-weight: 800; letter-spacing: -0.02em; }
        .page-title p { color: var(--text-secondary); font-size: 1rem; margin-top: 0.5rem; }

        .card {
            background: var(--glass);
            backdrop-filter: blur(12px);
            border-radius: 24px;
            padding: 2rem;
            border: 1px solid var(--border);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            border: none;
        }

        .btn-primary { 
            background: var(--gradient); 
            color: var(--bg-color);
            box-shadow: 0 10px 15px -3px rgba(56, 189, 248, 0.3);
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 15px 20px -3px rgba(56, 189, 248, 0.5); }

        .btn-ghost { 
            background: rgba(255,255,255,0.03); 
            color: var(--text-secondary); 
            border: 1px solid var(--border); 
        }
        .btn-ghost:hover { color: var(--text-primary); border-color: var(--text-secondary); background: rgba(255,255,255,0.05); }

        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 4rem; }
        .stat-card { display: flex; flex-direction: column; gap: 0.75rem; border-color: rgba(56, 189, 248, 0.1); }
        .stat-label { font-size: 0.75rem; font-weight: 700; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.05em; }
        .stat-value { font-size: 2rem; font-weight: 800; color: var(--text-primary); }

        @media (max-width: 1100px) {
            .sidebar { width: 80px; }
            .sidebar-logo span, .nav-link span, .nav-label { display: none; }
            .main { margin-left: 80px; padding: 2rem; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
    @yield('styles')
</head>
<body>

    <aside class="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"></path></svg>
            <span>MyBike Admin</span>
        </a>

        <div class="nav-group">
            <div class="nav-label">Main</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px;"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                <span>Dashboard</span>
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-label">Catalog</div>
            <a href="{{ route('admin.bicycles.index') }}" class="nav-link {{ request()->is('admin/bicycles*') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px;"><path d="M5.5 17a2.5 2.5 0 100-5 2.5 2.5 0 000 5zM18.5 17a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"></path><path d="M15 6H9.5L5.5 12h13z"></path></svg>
                <span>Bicycles</span>
            </a>
            <a href="{{ route('admin.riders.index') }}" class="nav-link {{ request()->is('admin/riders*') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                <span>Riders</span>
            </a>
            <a href="#" class="nav-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px;"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"></path></svg>
                <span>Categories</span>
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-label">Simulation</div>
            <a href="{{ route('simulation') }}" class="nav-link" target="_blank">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px;"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                <span>Live View</span>
            </a>
            <a href="{{ route('admin.fitting.wizard') }}" class="nav-link {{ request()->routeIs('admin.fitting.wizard') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px;"><path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.77 3.77z"></path></svg>
                <span>Fitting Wizard</span>
            </a>
            <a href="{{ route('compare') }}" class="nav-link" target="_blank">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px;"><path d="M16 4l3 3-7 7-3-3 7-7zM21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h7"></path></svg>
                <span>Compare Tool</span>
            </a>
            <a href="{{ route('drivetrain') }}" class="nav-link {{ request()->routeIs('drivetrain') ? 'active' : '' }}">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px;"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                <span>Drivetrain</span>
            </a>
        </div>

        <div class="nav-group">
            <div class="nav-label">Content</div>
            <a href="#" class="nav-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px;"><path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2z"></path><path d="M14 2v6h6"></path><path d="M16 13H8"></path><path d="M16 17H8"></path><path d="M10 9H8"></path></svg>
                <span>News</span>
            </a>
            <a href="#" class="nav-link">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 12px;"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                <span>Events</span>
            </a>
        </div>

        <div style="margin-top: auto; padding: 0 1.5rem;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-ghost" style="width: 100%; justify-content: flex-start;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="main">
        <header class="header">
            <div class="page-title">
                <h1>@yield('title', 'Dashboard')</h1>
                <p>@yield('subtitle', 'Welcome back to the command center.')</p>
            </div>
            <div class="header-actions">
                @yield('actions')
            </div>
        </header>

        @if(session('success'))
            <div class="card" style="border-color: #10b981; background: rgba(16, 185, 129, 0.05); color: #10b981; margin-bottom: 2rem;">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>
