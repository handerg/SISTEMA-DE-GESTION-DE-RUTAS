<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inicio de sesión - Avante</title>
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <style>
        :root {
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            color-scheme: light;
        }
        * { box-sizing: border-box; }
        html, body { margin: 0; min-height: 100%; }
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e7effc;
            color: #0f172a;
        }
        a { color: inherit; text-decoration: none; }
        .page {
            width: min(100%, 1180px);
            padding: 1.5rem;
        }
        .login-shell {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(360px, 1fr);
            gap: 1.5rem;
            background: #ffffff;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 28px 80px rgba(15, 23, 42, 0.14);
            min-height: 620px;
        }
        .hero {
            position: relative;
            padding: 2rem;
            background: linear-gradient(180deg, #f8fbff 0%, #dee8ff 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            gap: 1.5rem;
        }
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 18% 18%, rgba(59,130,246,.25), transparent 22%),
                radial-gradient(circle at 82% 18%, rgba(14,165,233,.16), transparent 15%);
            pointer-events: none;
        }
        .hero-content { position: relative; z-index: 1; }
        .hero-heading {
            margin: 0;
            font-size: clamp(2.1rem, 3vw, 3rem);
            line-height: 1.02;
            letter-spacing: -0.03em;
        }
        .hero-subtitle {
            margin: 0;
            max-width: 44rem;
            color: #475569;
            line-height: 1.75;
            font-size: 1rem;
        }
        .hero-card {
            position: relative;
            z-index: 1;
            padding: 1.25rem;
            background: rgba(255,255,255,0.88);
            border: 1px solid rgba(148,163,184,0.22);
            border-radius: 1.25rem;
            box-shadow: 0 18px 30px rgba(15,23,42,0.08);
        }
        .hero-card strong { color: #0f172a; }
        .ship-illustration {
            width: 100%;
            min-height: 260px;
            border-radius: 1.25rem;
            background: url('data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 760 520" preserveAspectRatio="xMidYMid slice"%3E%3Cdefs%3E%3ClinearGradient id="a" x1=".5" x2=".5" y1="0" y2="1"%3E%3Cstop offset="0%25" stop-color="%23f8fafc"/%3E%3Cstop offset="100%25" stop-color="%23dbeafe"/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width="760" height="520" fill="url(%23a)"/%3E%3Cpath d="M84 431c40 0 58-6 76-17 13-8 28-20 42-21 11 0 19 4 44 14 28 12 54 30 82 43 31 15 60 18 70 18 20 0 40-7 47-22 2-4 2-20 2-39 0-10 0-25-1-36-2-15-2-22-6-29-8-17-27-22-59-22H283c-2 0-5 0-8 1-15 4-19 8-27 19-7 9-14 24-21 31-15 14-21 16-43 16-15 0-31-2-48-8-23-8-44-23-84-28-22-3-37-3-48-1-17 3-25 6-34 17-9 11-11 25-11 45 0 15 0 35 2 47 3 19 8 25 29 31v.1c10 3 27 6 46 6z" fill="%2337a8f5" opacity="0.95"/%3E%3Cpath d="M280 241c-34 5-48 34-47 75 1 35 14 63 46 87 35 26 74 37 128 31 38-4 70-19 88-43 10-13 17-28 17-47 0-17-5-29-17-40-20-20-49-20-106-23-4 0-5 0-10-1-23-2-44-5-71-5z" fill="%230f172a" opacity="0.13"/%3E%3Cpath d="M179 265c18-17 77-40 138-45 64-6 198 11 198 11s-22 39-85 70c-47 24-124 28-155 26-27-2-73-9-96-14-6-1-19-7-25-9-3-1-10-4-15-6-13-4-13-3-11-6 4-8 0-12 11-27z" fill="%230f172a" opacity="0.06"/%3E%3C/svg%3E');
            background-size: cover;
            background-position: center;
        }
        .login-panel {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 1.25rem;
            background: #0f172a;
            color: #f8fafc;
        }
        .login-panel h2 {
            margin: 0;
            font-size: clamp(1.9rem, 2.5vw, 2.4rem);
        }
        .login-panel p {
            margin: 0;
            color: #cbd5e1;
            line-height: 1.75;
        }
        .form-card {
            margin-top: 1rem;
            padding: 1.75rem;
            background: #111827;
            border: 1px solid rgba(148,163,184,0.18);
            border-radius: 1.5rem;
        }
        .input-group {
            display: grid;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        .input-group label {
            font-size: 0.95rem;
            font-weight: 600;
            color: #e2e8f0;
        }
        .input-group input {
            width: 100%;
            min-height: 3.2rem;
            padding: 0 1rem;
            border-radius: 1rem;
            border: 1px solid rgba(148,163,184,0.16);
            background: #0f172a;
            color: #f8fafc;
            font-size: 1rem;
        }
        .input-group input:focus {
            outline: none;
            border-color: #38bdf8;
            box-shadow: 0 0 0 4px rgba(56,189,248,0.14);
        }
        .button-primary {
            width: 100%;
            min-height: 3.5rem;
            border: none;
            border-radius: 999px;
            background: linear-gradient(135deg, #22d3ee, #3b82f6);
            color: #0f172a;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform .2s ease, filter .2s ease;
        }
        .button-primary:hover {
            transform: translateY(-1px);
            filter: brightness(1.02);
        }
        .link-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
            color: #94a3b8;
            font-size: 0.95rem;
        }
        .link-row a {
            color: #bfdbfe;
        }
        .error-box {
            padding: 1rem;
            border-radius: 1rem;
            background: rgba(248,113,113,0.12);
            border: 1px solid rgba(248,113,113,0.25);
            color: #fecaca;
            font-size: 0.95rem;
        }
        .field-error {
            color: #fecaca;
            font-size: 0.92rem;
        }
        @media (max-width: 900px) {
            .login-shell { grid-template-columns: 1fr; }
            .hero { min-height: 340px; }
        }
    </style>
</head>
<body>
    <main class="page">
        <section class="login-shell">
            <div class="hero">
                <div class="hero-content">
                    <img src="C:\Users\hande\Downloads\avante-logo.png" alt="Avante logo" width="140" style="display:block; margin-bottom:1.25rem;" />
                    <h1 class="hero-heading">Avante Bureau Shipping</h1>
                    <p class="hero-subtitle">Sistema de gestión de rutas, autos y conductores diseñado para el control eficiente de operaciones marítimas.</p>
                </div>
                <div class="hero-card">
                    <p><strong>Bienvenido.</strong> Ingresa con tu correo y contraseña para acceder al panel de administración.</p>
                </div>
                <div class="ship-illustration"></div>
            </div>
            <div class="login-panel">
                <div>
                    <h2>Inicio de sesión</h2>
                    <p>Introduce tus credenciales para continuar.</p>
                </div>

                @if ($errors->any())
                    <div class="error-box">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('login.post', [], false) }}" class="form-card">
                    @csrf
                    <div class="input-group">
                        <label for="email">Correo electrónico o ID de usuario</label>
                        <input id="email" name="email" type="email" placeholder="usuario@empresa.com" value="{{ old('email') }}" required autofocus />
                        @error('email')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="input-group">
                        <label for="password">Contraseña</label>
                        <input id="password" name="password" type="password" placeholder="Contraseña" required autocomplete="current-password" />
                        @error('password')<span class="field-error">{{ $message }}</span>@enderror
                    </div>
                    <button type="submit" class="button-primary">INICIAR SESIÓN</button>
                    <div class="link-row">
                        <a href="#">¿Olvidó su contraseña?</a>
                    </div>
                </form>
            </div>
        </section>
    </main>
</body>
</html>
