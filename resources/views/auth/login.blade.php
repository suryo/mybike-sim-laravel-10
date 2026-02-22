<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - MyBike Pro</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent: #38bdf8;
            --gradient: linear-gradient(135deg, #38bdf8 0%, #818cf8 100%);
            --border: rgba(255,255,255,0.05);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { 
            background-color: var(--bg-color); 
            color: var(--text-primary); 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh;
            background-image: radial-gradient(circle at 50% 50%, rgba(56, 189, 248, 0.05) 0%, transparent 50%);
        }

        .login-card {
            background: var(--card-bg);
            width: 100%;
            max-width: 440px;
            padding: 3rem;
            border-radius: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .logo {
            font-weight: 800;
            font-size: 1.75rem;
            text-align: center;
            margin-bottom: 2rem;
            display: block;
            background: var(--gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none;
        }

        h1 { font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; text-align: center; }
        p.subtitle { color: var(--text-secondary); text-align: center; margin-bottom: 2.5rem; font-size: 0.9rem; }

        .form-group { margin-bottom: 1.5rem; }
        label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.5rem; }
        
        input {
            width: 100%;
            padding: 0.85rem 1rem;
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            transition: all 0.2s;
        }

        input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 1rem;
            background: var(--gradient);
            border: none;
            border-radius: 12px;
            color: var(--bg-color);
            font-weight: 800;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(56, 189, 248, 0.4);
        }

        .alert-demo {
            margin-top: 2rem;
            padding: 1rem;
            background: rgba(56, 189, 248, 0.05);
            border: 1px dashed var(--accent);
            border-radius: 12px;
            font-size: 0.85rem;
        }

        .alert-demo strong { color: var(--accent); }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.2s;
        }

        .back-link:hover { color: var(--text-primary); }

        .error-msg {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <a href="/" class="logo">MyBike Pro</a>
        <h1>Welcome Back</h1>
        <p class="subtitle">Enter your credentials to access the command center.</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
                @error('email') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required autocomplete="current-password">
                @error('password') <div class="error-msg">{{ $message }}</div> @enderror
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; margin-bottom: 0;">
                    <input type="checkbox" name="remember" style="width: auto;">
                    <span style="font-size: 0.8rem; font-weight: 500;">Remember me</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="color: var(--accent); text-decoration: none; font-size: 0.8rem; font-weight: 600;">Forgot?</a>
                @endif
            </div>

            <button type="submit" class="btn-login">Sign In to Admin</button>
        </form>

        <div class="alert-demo">
            <p><strong>Trial Credentials:</strong></p>
            <p style="margin-top: 0.25rem;">Email: <strong>admin@mybike.pro</strong></p>
            <p>Password: <strong>password</strong></p>
        </div>

        <a href="/" class="back-link">← Back to Homepage</a>
    </div>

</body>
</html>
