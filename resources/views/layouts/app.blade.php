<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $pageTitle ?? 'Avante' }} - Avante</title>
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <style>
        :root {
            color-scheme: light;
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            background: #f8fbff;
            color: #071933;
        }
        .layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            height: 100vh;
            overflow: hidden;
        }
        .sidebar {
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            background: #081126;
            border-right: 1px solid rgba(148,163,184,0.12);
            display: flex;
            flex-direction: column;
            padding: 2rem 1.5rem;
        }
        .brand {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }
        .brand-mark {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, #38bdf8, #60a5fa);
            display: grid;
            place-items: center;
            color: #0f172a;
            font-weight: 700;
        }
        .brand-text strong { font-size: 1.05rem; }
        .brand-text span { color: #94a3b8; font-size: 0.95rem; }
        .nav-list {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            margin-top: 1.5rem;
            flex: 1;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 0.95rem 1rem;
            border-radius: 0.95rem;
            color: #cbd5e1;
            text-decoration: none;
            font-weight: 500;
            transition: background .2s ease, color .2s ease;
        }
        .nav-item:hover,
        .nav-item.active {
            background: rgba(59,130,246,0.14);
            color: #ffffff;
        }
        .nav-icon {
            width: 1.35rem;
            height: 1.35rem;
            display: grid;
            place-items: center;
            font-size: 0.95rem;
        }
        .sidebar-footer {
            margin-top: auto;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .sidebar-footer .card {
            padding: 1.25rem;
            border-radius: 1.25rem;
            background: rgba(15,23,42,0.8);
            border: 1px solid rgba(148,163,184,0.08);
        }
        .sidebar-footer .card h2 {
            margin: 0 0 0.5rem;
            font-size: 0.95rem;
        }
        .sidebar-footer .card p {
            margin: 0;
            color: #cbd5e1;
            line-height: 1.6;
        }
        .logout-form button {
            width: 100%;
            border: none;
            border-radius: 0.95rem;
            padding: 0.95rem 1rem;
            background: #0f172a;
            color: #f8fafc;
            font-size: 1rem;
            cursor: pointer;
            transition: background .2s ease;
        }
        .logout-form button:hover { background: #17233c; }
        .content {
            padding: 2rem;
            background: #f8fbff;
            overflow-y: auto;
            height: 100vh;
        }
        .top-bar {
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            align-items: center;
            margin-bottom: 2rem;
            padding-top: 1rem;
            padding-bottom: 1rem;
            backdrop-filter: blur(8px);
            background: rgba(255,255,255,0.96);
            border-bottom: 1px solid #1680A7;
        }
        .page-title {
            margin: 0;
            font-size: clamp(2rem, 2.5vw, 2.6rem);
            letter-spacing: -0.04em;
            color: #071933;
        }
        .subtitle {
            margin: 0.5rem 0 0;
            color: #334155;
            max-width: 680px;
            line-height: 1.7;
        }
        .top-right-user {
            display: flex;
            align-items: center;
            gap: 0.95rem;
            padding: 0.85rem 1rem;
            border-radius: 1rem;
            background: #ffffff;
            border: 1px solid #1680A7;
        }
        .top-right-user strong {
            display: block;
            font-size: 0.95rem;
        }
        .top-right-user span {
            color: #94a3b8;
            font-size: 0.9rem;
        }
        .panel {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.5rem;
        }
        .card {
            background: #ffffff;
            border: 1px solid #1680A7;
            border-radius: 1.25rem;
            padding: 1.75rem;
            box-shadow: 0 18px 40px rgba(22, 40, 63, 0.08);
        }
        .card h2 {
            margin: 0 0 0.75rem;
            font-size: 1.125rem;
        }
        .card p { margin: 0; color: #334155; line-height: 1.75; }
        .section-card {
            background: #ffffff;
            border: 1px solid #1680A7;
            border-radius: 1.5rem;
            padding: 2rem;
            box-shadow: 0 18px 40px rgba(22, 40, 63, 0.08);
        }
        .section-card h2 {
            margin-top: 0;
            font-size: 1.8rem;
        }
        .section-card p {
            margin: 1rem 0 0;
            color: #334155;
            line-height: 1.75;
        }
        .section-header {
            display:flex;
            justify-content:space-between;
            align-items:center;
            gap:1rem;
            flex-wrap:wrap;
            padding-bottom:1rem;
            border-bottom:1px solid rgba(22,128,167,0.12);
            margin-bottom:1rem;
        }
        .section-header h2 {
            margin:0;
            font-size:1.75rem;
            color:#0f172a;
        }
        .section-header p {
            margin:0.35rem 0 0;
            color:#475569;
        }
        .table-shell {
            margin-top:1rem;
            background:#ffffff;
            border:1px solid rgba(22,128,167,0.18);
            border-radius:1.25rem;
            overflow:hidden;
            box-shadow:0 10px 30px rgba(22,128,167,0.08);
        }
        .table-list {
            width:100%;
            border-collapse:collapse;
            min-width:720px;
        }
        .table-list th,
        .table-list td {
            padding:1rem;
            border-bottom:1px solid rgba(22,128,167,0.12);
            text-align:left;
            color:#071933;
        }
        .table-list th {
            color:#165a8a;
            font-weight:600;
            letter-spacing:0.01em;
        }
        .clickable-row {
            cursor:pointer;
            transition:background .2s ease, transform .2s ease;
        }
        .clickable-row:hover {
            background:rgba(59,130,246,0.08);
            transform:translateY(-1px);
        }
        .filter-panel {
            margin-top:1rem;
            display:grid;
            gap:0.75rem;
            width:100%;
            padding:1rem;
            border-radius:1.25rem;
            background:#f8fbff;
        }
        .filter-form {
            display:grid;
            gap:0.75rem;
            grid-template-columns:minmax(210px,1.8fr) minmax(110px,0.95fr) minmax(110px,0.95fr) minmax(110px,0.95fr) minmax(120px,1fr) minmax(110px,0.85fr);
            align-items:end;
            width:100%;
        }
        .filter-group {
            padding:0.85rem 0.95rem;
            border-radius:1rem;
            background:#ffffff;
        }
        .filter-form label {
            margin-bottom:0.35rem;
            color:#334155;
            display:block;
            font-size:0.95rem;
        }
        .filter-input {
            width:100%;
            height:3rem;
            padding:0 0.85rem;
            border:1px solid #cbd5e1;
            border-radius:1rem;
            background:#fff;
            color:#0f172a;
        }
        .filter-button {
            min-width:110px;
            padding:0 1rem;
            height:3rem;
            align-self:center;
        }
        .status-pill {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding:0.4rem 0.8rem;
            border-radius:9999px;
            font-size:0.8rem;
            font-weight:600;
            color:#0f172a;
            background:#dbeafe;
        }
        .field-error {
            display:block;
            margin-top:0.4rem;
            color:#b91c1c;
            font-size:0.88rem;
        }
        .modal-overlay {
            position:fixed;
            inset:0;
            z-index:50;
            background:rgba(0,0,0,0.65);
            display:grid;
            place-items:center;
            padding:1.5rem;
        }
        .modal-card {
            width:min(900px,100%);
            max-height:calc(100vh - 3rem);
            overflow-y:auto;
            background:#ffffff;
            border:1px solid #1680A7;
            border-radius:1.5rem;
            position:relative;
            padding:1.75rem;
        }
        .modal-close {
            position:absolute;
            top:1rem;
            right:1rem;
            border:none;
            background:transparent;
            color:#071933;
            font-size:1.5rem;
            cursor:pointer;
        }
        .modal-actions {
            display:flex;
            justify-content:flex-end;
            gap:0.75rem;
            padding:1rem 1.5rem 1.5rem;
            background:#f8fbff;
            border-top:1px solid rgba(22,128,167,0.12);
        }
        .button-primary,
        .button-secondary,
        .button-danger {
            border:none;
            border-radius:1rem;
            padding:.95rem 1.25rem;
            cursor:pointer;
            transition:background .2s ease, transform .2s ease;
        }
        .button-primary:hover,
        .button-secondary:hover,
        .button-danger:hover {
            transform:translateY(-1px);
        }
        .button-primary {
            background:#2563eb;
            color:#fff;
        }
        .button-secondary {
            background:#eaf4ff;
            color:#071933;
            border:1px solid #1680A7;
        }
        .button-danger {
            background:#dc2626;
            color:#fff;
        }
        .info-grid {
            display:grid;
            gap:1rem;
        }
        .info-card {
            padding:1.15rem;
            border-radius:1.25rem;
            background:#f8fbff;
            border:1px solid #a7d8f2;
        }
        .info-label {
            margin:0 0 0.5rem;
            font-size:0.75rem;
            letter-spacing:0.08em;
            text-transform:uppercase;
            color:#475569;
        }
        .info-value {
            margin:0;
            font-size:1rem;
            font-weight:600;
            color:#0f172a;
        }
        @media (max-width: 920px) {
            .layout { grid-template-columns: 1fr; }
            .sidebar {
                flex-direction: row;
                overflow-x: auto;
                padding: 1rem;
            }
            .brand { margin-bottom: 1rem; }
            .nav-list { flex-direction: row; gap: 0.75rem; margin-top: 0; }
            .sidebar-footer { margin-top: 1rem; }
            .panel { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-mark">A</div>
                <div class="brand-text">
                    <strong>Avante</strong>
                    <span>Gestión de rutas</span>
                </div>
            </div>

            <nav class="nav-list">
                <a href="{{ route('dashboard', [], false) }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Panel principal
                </a>
                <a href="{{ route('autos', [], false) }}" class="nav-item {{ request()->routeIs('autos') ? 'active' : '' }}">
                    Autos
                </a>
                <a href="{{ route('conductores', [], false) }}" class="nav-item {{ request()->routeIs('conductores') ? 'active' : '' }}">
                    Conductores
                </a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('usuarios', [], false) }}" class="nav-item {{ request()->routeIs('usuarios') ? 'active' : '' }}">
                        Usuarios
                    </a>
                @endif
                <a href="{{ route('reportes', [], false) }}" class="nav-item {{ request()->routeIs('reportes') ? 'active' : '' }}">
                    Reportes
                </a>
                <a href="{{ route('configuracion', [], false) }}" class="nav-item {{ request()->routeIs('configuracion') ? 'active' : '' }}">
                    Configuración
                </a>
            </nav>

            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout', [], false) }}" class="logout-form">
                    @csrf
                    <button type="submit">Cerrar sesión</button>
                </form>
            </div>
        </aside>

        <main class="content">
            <header class="top-bar">
                <div>
                    <h1 class="page-title">{{ $pageTitle ?? 'Panel principal' }}</h1>
                    <p class="subtitle">{{ $pageSubtitle ?? 'Bienvenido al sistema Avante. Desde aquí puedes acceder rápidamente a la gestión de autos, conductores y reportes.' }}</p>
                </div>
                <div class="top-right-user">
                    <div>
                        <strong>{{ auth()->user()->name ?? 'Administrador' }}</strong>
                        <span>{{ ucfirst(auth()->user()->role?->nombre_rol ?? 'Usuario') }}</span>
                    </div>
                </div>
            </header>

            @yield('content')
        </main>
    </div>
</body>
</html>
