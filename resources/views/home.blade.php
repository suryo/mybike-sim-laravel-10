<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyBike Pro - Beyond the Ride</title>
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

        /* Premium Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-color); }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--accent); }

        /* Navigation */
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
            position: relative;
        }

        .nav-item:hover, .nav-item.active {
            color: var(--text-primary);
        }

        /* Dropdown Styles */
        .dropdown {
            position: relative;
        }

        .dropdown-content {
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%) translateY(10px);
            background: var(--card-bg);
            min-width: 200px;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.3);
            border: 1px solid rgba(255,255,255,0.1);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s;
            padding: 0.75rem;
            z-index: 100;
        }

        .dropdown:hover .dropdown-content {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }

        .dropdown-item {
            display: block;
            padding: 0.75rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background: rgba(255,255,255,0.05);
            color: var(--accent);
        }

        .btn-sim {
            background: var(--gradient);
            color: var(--bg-color);
            padding: 0.6rem 1.2rem;
            border-radius: 30px;
            font-weight: 800;
            text-decoration: none;
            font-size: 0.85rem;
            box-shadow: 0 4px 15px rgba(56, 189, 248, 0.4);
            transition: all 0.3s;
        }

        .btn-sim:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(56, 189, 248, 0.6);
        }

        /* Hero / News Section */
        .hero {
            padding: 8rem 2rem 4rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 3rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,0.1);
        }

        .news-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 4rem;
        }

        .main-news {
            position: relative;
            height: 500px;
            border-radius: 24px;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 3rem;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .main-news::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(15, 23, 42, 1) 0%, rgba(15, 23, 42, 0) 70%);
        }

        .main-news-content {
            position: relative;
            z-index: 1;
        }

        .badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            background: var(--accent);
            color: var(--bg-color);
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .main-news h2 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.1;
        }

        .news-sidebar {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .side-news-card {
            background: var(--glass);
            border-radius: 20px;
            padding: 1.5rem;
            border: 1px solid rgba(255,255,255,0.05);
            transition: all 0.3s;
            cursor: pointer;
        }

        .side-news-card:hover {
            border-color: var(--accent);
            background: rgba(30, 41, 59, 0.9);
            transform: translateX(10px);
        }

        /* Events & Destinations */
        .features-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem 6rem;
        }

        .feature-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .event-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem;
            background: rgba(255,255,255,0.02);
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.05);
            transition: all 0.3s;
        }

        .event-item:hover {
            border-color: #818cf8;
            background: rgba(129, 140, 248, 0.05);
        }

        .destination-card {
            position: relative;
            height: 250px;
            border-radius: 20px;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            margin-bottom: 1rem;
            transition: all 0.5s;
        }

        .destination-card:hover {
            transform: scale(1.02);
        }

        .destination-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: rgba(0,0,0,0.4);
        }

        .dest-info {
            position: relative;
            z-index: 1;
        }

        footer {
            background: #070b14;
            padding: 4rem 2rem;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .footer-logo {
            font-weight: 800;
            font-size: 2rem;
            margin-bottom: 1rem;
            display: block;
        }

        @media (max-width: 1024px) {
            .news-grid, .features-grid { grid-template-columns: 1fr; }
            .nav-links { display: none; }
        }
    </style>
</head>
<body>

    <nav>
        <a href="{{ route('home') }}" class="logo">MyBike Pro</a>
        <div class="nav-links">
            <a href="{{ route('home') }}" class="nav-item active">Home</a>
            <a href="{{ route('simulation') }}" class="nav-item">Simulation</a>
            <a href="{{ route('compare') }}" class="nav-item">Compare</a>
            <div class="dropdown">
                <a href="#" class="nav-item">Riders ▾</a>
                <div class="dropdown-content">
                    <a href="{{ route('admin.riders.index') }}" class="dropdown-item">My Profiles</a>
                    <a href="{{ route('admin.fitting.wizard') }}" class="dropdown-item">Fitting Wizard</a>
                </div>
            </div>
            <a href="#events" class="nav-item">Events</a>
            <a href="#destinations" class="nav-item">Destinations</a>
            
            @auth
                <a href="{{ route('admin.dashboard') }}" class="nav-item" style="color: var(--accent);">Admin</a>
                <form method="POST" action="{{ route('logout') }}" id="logout-form" style="display: none;">
                    @csrf
                </form>
                <a href="#" class="nav-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
            @else
                <a href="{{ route('login') }}" class="nav-item">Login</a>
            @endauth

            <a href="{{ route('drivetrain') }}" class="btn-sim" style="background: rgba(255,255,255,0.05); color: var(--text-primary); box-shadow: none; border: 1px solid rgba(255,255,255,0.1); margin-right: -0.5rem;">Drivetrain Lab</a>
            <a href="{{ route('simulation') }}" class="btn-sim">Route Simulation</a>
        </div>
    </nav>

    <main class="hero">
        <h1 class="section-title">Latest in Cycling</h1>
        
        <div class="news-grid">
            <div class="main-news" style="background-image: url('{{ $news[0]['image'] }}')">
                <div class="main-news-content">
                    <span class="badge">{{ $news[0]['category'] }}</span>
                    <h2>{{ $news[0]['title'] }}</h2>
                    <p style="color: var(--text-secondary); max-width: 600px;">{{ $news[0]['excerpt'] }}</p>
                </div>
            </div>
            
            <div class="news-sidebar">
                @foreach(array_slice($news, 1) as $item)
                <div class="side-news-card">
                    <span class="badge" style="background: rgba(255,255,255,0.1); color: var(--text-primary);">{{ $item['category'] }}</span>
                    <h3 style="margin: 0.5rem 0; font-size: 1.1rem;">{{ $item['title'] }}</h3>
                    <p style="font-size: 0.85rem; color: var(--text-secondary);">{{ Str::limit($item['excerpt'], 80) }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <section style="margin-bottom: 6rem; position: relative;" id="tools">
            <div style="position: absolute; inset: -40px; background: radial-gradient(circle at 50% 50%, rgba(56, 189, 248, 0.08) 0%, transparent 70%); z-index: -1;"></div>
            <h2 class="section-title">Professional Tools</h2>
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;">
                <div class="side-news-card" style="display: flex; flex-direction: column; gap: 1rem; padding: 2.5rem; background: rgba(30, 41, 59, 0.4); border-radius: 32px;">
                    <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📏</div>
                    <h3 style="margin: 0; font-size: 1.5rem; font-weight: 800;">Fitting Advisor</h3>
                    <p style="font-size: 0.95rem; color: var(--text-secondary); line-height: 1.6;">Optimize your riding position with our biomechanical wizard. Perfect comfort, maximum power output.</p>
                    <a href="{{ route('admin.fitting.wizard') }}" class="btn-sim" style="text-align: center; margin-top: auto; padding: 0.8rem;">Start Fitting</a>
                </div>
                <div class="side-news-card" style="display: flex; flex-direction: column; gap: 1rem; padding: 2.5rem; background: rgba(30, 41, 59, 0.4); border-radius: 32px;">
                    <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📊</div>
                    <h3 style="margin: 0; font-size: 1.5rem; font-weight: 800;">Gear Comparison</h3>
                    <p style="font-size: 0.95rem; color: var(--text-secondary); line-height: 1.6;">Compare different bicycle setups and rider profiles. Find the optimal combinations for any terrain.</p>
                    <a href="{{ route('compare') }}" class="btn-sim" style="text-align: center; margin-top: auto; padding: 0.8rem;">Compare Tools</a>
                </div>
                <div class="side-news-card" style="display: flex; flex-direction: column; gap: 1rem; padding: 2.5rem; background: rgba(30, 41, 59, 0.4); border-radius: 32px;">
                    <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🚴</div>
                    <h3 style="margin: 0; font-size: 1.5rem; font-weight: 800;">Physics Engine</h3>
                    <p style="font-size: 0.95rem; color: var(--text-secondary); line-height: 1.6;">Simulate real-world conditions with our custom engine. Test aerodynamics, weight, and rolling resistance.</p>
                    <a href="{{ route('simulation') }}" class="btn-sim" style="text-align: center; margin-top: auto; padding: 0.8rem;">Launch Lab</a>
                </div>
            </div>
        </section>

        <div class="features-grid">
            <section id="events">
                <h2 class="section-title" style="font-size: 1.8rem;">Upcoming Events</h2>
                <div class="feature-list">
                    @foreach($events as $event)
                    <div class="event-item">
                        <div>
                            <div style="font-weight: 800; color: var(--accent);">{{ $event['name'] }}</div>
                            <div style="font-size: 0.8rem; color: var(--text-secondary);">{{ $event['location'] }}</div>
                        </div>
                        <div style="font-family: 'JetBrains Mono', monospace; font-weight: 700; font-size: 0.9rem;">{{ $event['date'] }}</div>
                    </div>
                    @endforeach
                </div>
            </section>

            <section id="destinations">
                <h2 class="section-title" style="font-size: 1.8rem;">Explore Destinations</h2>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    @foreach($destinations as $dest)
                    <div class="destination-card" style="background-image: url('{{ $dest['image'] }}'); height: 200px;">
                        <div class="dest-info">
                            <span style="font-size: 0.7rem; font-weight: 800; color: var(--accent);">{{ strtoupper($dest['difficulty']) }}</span>
                            <div style="font-weight: 800;">{{ $dest['name'] }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </section>
        </div>
    </main>

    <footer>
        <span class="logo footer-logo">MyBike Pro</span>
        <p style="color: var(--text-secondary); font-size: 0.9rem; max-width: 400px; margin: 0 auto 2rem;">Your ultimate companion for cycling performance, planning, and community.</p>
        <div style="display: flex; justify-content: center; gap: 2rem; margin-bottom: 2rem;">
            <a href="#" class="nav-item">About Us</a>
            <a href="#" class="nav-item">Contact</a>
            <a href="#" class="nav-item">Privacy Policy</a>
        </div>
        <p style="font-size: 0.75rem; opacity: 0.3;">&copy; 2026 MyBike Simulation Pro. All rights reserved.</p>
    </footer>

</body>
</html>
