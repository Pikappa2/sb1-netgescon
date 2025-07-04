@extends('superadmin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Dashboard Super Admin</h2>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Benvenuto nel pannello di amministrazione del sistema</p>
        </div>
    </div>

    <!-- Riquadro stabile sopra la dashboard -->
    <div class="w-full flex flex-col items-center py-2 bg-yellow-100 dark:bg-gray-700 border-b border-yellow-300 dark:border-gray-600 sticky top-0 z-20">
        <span class="text-xs text-gray-700 dark:text-gray-300 mb-1">{{ $annoAttivo ?? date("Y") }}/{{ $gestione ?? "Ord." }}{{ isset($gestione2) ? ' - '.$gestione2 : '' }}</span>
        <div class="flex items-center gap-2 bg-yellow-200 dark:bg-gray-800 rounded px-2 py-1 shadow" style="min-width: 180px;">
            <div class="flex flex-col justify-center items-center mr-1">
                <button id="prev-stabile" class="w-6 h-6 flex items-center justify-center rounded bg-indigo-500 text-white hover:bg-indigo-700 text-xs mb-1" title="Stabile precedente">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-up" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="6 15 12 9 18 15" /></svg>
                </button>
                <button id="next-stabile" class="w-6 h-6 flex items-center justify-center rounded bg-indigo-500 text-white hover:bg-indigo-700 text-xs" title="Stabile successivo">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-chevron-down" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="6 9 12 15 18 9" /></svg>
                </button>
            </div>
            <button id="current-stabile" class="flex-1 text-base font-semibold text-gray-900 dark:text-gray-100 bg-transparent hover:bg-yellow-300 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition text-center" title="Cambia stabile">
                {{ $stabileAttivo ?? "Nessuno Stabile" }}
            </button>
        </div>
    </div>

    <!-- Statistiche -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Totale Utenti -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Totale Utenti</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ \App\Models\User::count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Totale Amministratori -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Amministratori</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ \App\Models\Amministratore::count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Utenti per Ruolo -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Super Admin</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ \App\Models\User::role('super-admin')->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Azioni Rapide -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Azioni Rapide</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('superadmin.users.create') }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded text-center transition duration-200">
                    Nuovo Utente
                </a>
                <a href="{{ route('superadmin.amministratori.create') }}" 
                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded text-center transition duration-200">
                    Nuovo Amministratore
                </a>
                <a href="{{ route('superadmin.users.index') }}" 
                   class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded text-center transition duration-200">
                    Gestisci Utenti
                </a>
                <a href="{{ route('superadmin.amministratori.index') }}" 
                   class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded text-center transition duration-200">
                    Gestisci Amministratori
                </a>
            </div>
        </div>
    </div>

    <!-- Ultimi Utenti Creati -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Ultimi Utenti Creati</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ruolo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Data Creazione</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                        @foreach(\App\Models\User::latest()->take(5)->get() as $user)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection