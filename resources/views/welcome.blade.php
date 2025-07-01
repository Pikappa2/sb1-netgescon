<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gestionale Condomini</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="bg-gray-50 text-black/50">
            <div class="relative min-h-screen flex flex-col items-center justify-center">
                <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                    <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                        <div class="flex lg:justify-center lg:col-start-2">
                           <x-application-logo class="h-12 w-auto text-gray-600" />
                        </div>
                        @if (Route::has('login'))
                            <nav class="-mx-3 flex flex-1 justify-end">
                                @auth
                                    <a href="{{ url('/dashboard') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70">Dashboard</a>
                                @else
                                    <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70">Log in</a>
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70">Register</a>
                                    @endif
                                @endauth
                            </nav>
                        @endif
                    </header>
                    <main class="mt-6 text-center">
                        <h1 class="text-3xl font-bold text-gray-800">Gestionale Condomini</h1>
                        <p class="mt-2 text-gray-600">La soluzione open-source per l'amministrazione condominiale moderna.</p>
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
