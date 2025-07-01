{{-- filepath: resources/views/errors/403.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <title>403 - Accesso Negato</title>
    <style>
        body { font-family: sans-serif; padding: 2em; }
        .container { max-width: 800px; margin: auto; border: 1px solid #ccc; padding: 1em; border-radius: 8px; }
        .code { background-color: #f4f4f4; padding: 0.5em; border-radius: 4px; font-family: monospace; }
    </style>
</head>
<body>
    <div class="container">
        <h1>403 - Accesso Negato</h1>
        <p>Questa azione non è autorizzata.</p>
        <hr>
        <h3>Informazioni di Debug:</h3>
        @if(Auth::check())
            <p><strong>Utente Autenticato:</strong> {{ Auth::user()->name }} ({{ Auth::user()->email }})</p>
            <p><strong>Ruolo nel Database:</strong> <span class="code">{{ Auth::user()->role }}</span></p>
            <p><strong>Route:</strong> {{ request()->path() }}</p>
            @php
                $route = request()->route();
                $middlewares = $route ? $route->gatherMiddleware() : [];
            @endphp
            @if($middlewares)
                <p><strong>Middleware attivi:</strong> {{ implode(', ', $middlewares) }}</p>
            @endif
            <p><strong>Gate access-super-admin-panel:</strong>
                @can('access-super-admin-panel')
                    <span style="color: green;">OK</span>
                @else
                    <span style="color: red;">NO</span>
                @endcan
            </p>
            <p><strong>Gate access-admin-panel:</strong>
                @can('access-admin-panel')
                    <span style="color: green;">OK</span>
                @else
                    <span style="color: red;">NO</span>
                @endcan
            </p>
        @else
            <p><strong>Utente Autenticato:</strong> <span style="color: red;">Nessuno</span></p>
        @endif

        @isset($exception)
            <p><strong>Messaggio dell'Eccezione:</strong></p>
            <pre class="code">{{ $exception->getMessage() }}</pre>
        @endisset
    </div>
</body>
</html>
