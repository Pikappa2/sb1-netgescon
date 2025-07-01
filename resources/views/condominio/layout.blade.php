{{-- filepath: u:\home\michele\netgescon\netgescon-laravel\resources\views\condomino\layout.blade.php --}}
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Dashboard Condominio')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <style>
        body { background: #f8f9fa; }
        .sidebar { width: 220px; background: #fff; border-right: 1px solid #eee; min-height: 100vh; position: fixed; }
        .sidebar ul { list-style: none; padding: 0; }
        .sidebar li { margin: 0; }
        .sidebar a { display: block; padding: 1em; color: #333; text-decoration: none; border-left: 4px solid transparent; }
        .sidebar a.active, .sidebar a:hover { background: #f0f0f0; border-left: 4px solid #d32f2f; color: #d32f2f; }
        .main { margin-left: 240px; padding: 2em; }
        .logo { font-weight: bold; font-size: 1.5em; color: #d32f2f; margin: 1em; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo">MioCondominio</div>
        <ul>
            <li><a href="{{ route('condomino.dashboard') }}" class="{{ request()->routeIs('condomino.dashboard') ? 'active' : '' }}">Avvisi e scadenze</a></li>
            <li><a href="{{ route('condomino.scadenze') }}" class="{{ request()->routeIs('condomino.scadenze') ? 'active' : '' }}">Scadenze e pagamenti</a></li>
            <li><a href="{{ route('condomino.comunicazioni') }}" class="{{ request()->routeIs('condomino.comunicazioni') ? 'active' : '' }}">Comunicazioni personali</a></li>
            <li><a href="{{ route('condomino.avvisi') }}" class="{{ request()->routeIs('condomino.avvisi') ? 'active' : '' }}">Avvisi</a></li>
            <li><a href="{{ route('condomino.guasti') }}" class="{{ request()->routeIs('condomino.guasti') ? 'active' : '' }}">Guasti e problemi</a></li>
            <li><a href="{{ route('condomino.documenti') }}" class="{{ request()->routeIs('condomino.documenti') ? 'active' : '' }}">Documenti</a></li>
            <li><a href="{{ route('condomino.contabilita') }}" class="{{ request()->routeIs('condomino.contabilita') ? 'active' : '' }}">Registro contabilità</a></li>
            <li><a href="{{ route('condomino.fornitori') }}" class="{{ request()->routeIs('condomino.fornitori') ? 'active' : '' }}">Fornitori</a></li>
            <li><a href="{{ route('condomino.bacheca') }}" class="{{ request()->routeIs('condomino.bacheca') ? 'active' : '' }}">Bacheca comune</a></li>
            <li><a href="{{ route('condomino.sondaggi') }}" class="{{ request()->routeIs('condomino.sondaggi') ? 'active' : '' }}">Sondaggi</a></li>
        </ul>
    </div>
    <div class="main">
        @yield('content')
    </div>
</body>
</html>