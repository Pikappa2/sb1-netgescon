<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestione Preventivi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Statistiche -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Preventivi Bozza</dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $stats['preventivi_bozza'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Preventivi Approvati</dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $stats['preventivi_approvati'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Importo Anno Corrente</dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">€ {{ number_format($stats['importo_totale_anno'], 2, ',', '.') }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Azioni Rapide -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Azioni Rapide</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <a href="{{ route('admin.preventivi.create') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded text-center transition duration-200">
                            Nuovo Preventivo
                        </a>
                        <a href="{{ route('admin.preventivi.pianificazione') }}" 
                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded text-center transition duration-200">
                            Pianificazione
                        </a>
                        <a href="{{ route('admin.preventivi.template') }}" 
                           class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded text-center transition duration-200">
                            Template
                        </a>
                        <a href="{{ route('admin.preventivi.report') }}" 
                           class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded text-center transition duration-200">
                            Report
                        </a>
                    </div>
                </div>
            </div>

            <!-- Lista Preventivi -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Preventivi</h3>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Anno/Tipo
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Stabile
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Descrizione
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Importo
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Stato
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Versione
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Azioni
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($preventivi as $preventivo)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <div>
                                                <div class="font-medium">{{ $preventivo->anno_gestione }}</div>
                                                <div class="text-gray-500 dark:text-gray-400">{{ ucfirst($preventivo->tipo_gestione) }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $preventivo->stabile->denominazione }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $preventivo->descrizione }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <span class="font-medium">€ {{ number_format($preventivo->importo_totale, 2, ',', '.') }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                @switch($preventivo->stato)
                                                    @case('bozza') bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100 @break
                                                    @case('provvisorio') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @break
                                                    @case('definitivo') bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 @break
                                                    @case('approvato') bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100 @break
                                                    @case('archiviato') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 @break
                                                @endswitch">
                                                {{ ucfirst($preventivo->stato) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            v{{ $preventivo->versione }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('admin.preventivi.show', $preventivo) }}" 
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                Visualizza
                                            </a>
                                            @if($preventivo->stato === 'bozza')
                                                <a href="{{ route('admin.preventivi.edit', $preventivo) }}" 
                                                   class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                    Modifica
                                                </a>
                                            @endif
                                            <a href="{{ route('admin.preventivi.storico', $preventivo) }}" 
                                               class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                Storico
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Nessun preventivo trovato
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginazione -->
                    <div class="mt-6">
                        {{ $preventivi->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>