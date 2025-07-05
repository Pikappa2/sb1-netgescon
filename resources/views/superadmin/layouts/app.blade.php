<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Super Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- FontAwesome (per icone menu) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-papm6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q6Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
</head>
<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 dark:text-white transition-colors duration-300" id="main-body">
    <div class="min-h-screen flex">
        <!-- Colonna launcher (icone) -->
        <aside class="w-16 bg-red-800 dark:bg-gray-800 flex-shrink-0 flex flex-col items-center justify-between">
            @include('components.menu.launcher')
        </aside>
        <!-- Sidebar (gialla) -->
        <aside class="w-56 bg-yellow-300 dark:bg-gray-700 border-r-4 border-indigo-500 flex-shrink-0 relative transition-all duration-300" id="sidebar-menu">
            @include('components.menu.sidebar')
            <!-- Pulsante per ridimensionare/nascondere -->
            <button id="toggle-sidebar" class="absolute top-2 right-0 -mr-3 bg-indigo-500 text-white rounded-full p-1 shadow hover:bg-indigo-700 transition">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
        </aside>
        <!-- Colonna viola sottile -->
        <aside class="w-3 bg-indigo-700 flex-shrink-0 flex flex-col items-center justify-center">
            <button id="show-sidebar" class="text-white opacity-70 hover:opacity-100 transition" style="display:none">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </aside>
        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Navigation -->
            <nav class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('superadmin.dashboard') }}" class="text-xl font-bold text-gray-800">
                                    Super Admin Panel
                                </a>
                            </div>
                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <a href="{{ route('superadmin.dashboard') }}" 
                                   class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('superadmin.dashboard') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium">
                                    Dashboard
                                </a>
                                <a href="{{ route('superadmin.users.index') }}" 
                                   class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('superadmin.users.*') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium">
                                    Utenti
                                </a>
                                <a href="{{ route('superadmin.amministratori.index') }}" 
                                   class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('superadmin.amministratori.*') ? 'border-indigo-400 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium">
                                    Amministratori
                                </a>
                            </div>
                        </div>
                        <!-- Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <div class="ml-3 relative">
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-700">{{ Auth::user()->name }}</span>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Page Content -->
            <main class="py-6 flex-1">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <!-- Flash Messages -->
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('status'))
                        <div class="mb-4 bg-blue-100 border-blue-400 text-blue-700 px-4 py-3 rounded">
                            {{ session('status') }}
                        </div>
                    @endif
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        // Nascondi/mostra barra gialla
        const sidebar = document.getElementById('sidebar-menu');
        const toggleBtn = document.getElementById('toggle-sidebar');
        const showBtn = document.getElementById('show-sidebar');
        if(toggleBtn && sidebar && showBtn) {
            toggleBtn.addEventListener('click', function() {
                sidebar.style.display = 'none';
                showBtn.style.display = 'flex';
            });
            showBtn.addEventListener('click', function() {
                sidebar.style.display = 'block';
                showBtn.style.display = 'none';
            });
        }

        // Dark mode toggle globale
        const darkBtn = document.getElementById('toggle-darkmode');
        darkBtn.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            document.getElementById('main-body').classList.toggle('dark-mode');
        });
    </script>
</body>
</html>