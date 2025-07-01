<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Amministratore') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Statistiche Principali -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Stabili Gestiti</dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $stats['stabili_gestiti'] }}</dd>
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
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Stabili Attivi</dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $stats['stabili_attivi'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Ticket Aperti</dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $stats['ticket_aperti'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Ticket Urgenti</dt>
                                    <dd class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $stats['ticket_urgenti'] }}</dd>
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
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        <a href="{{ route('admin.stabili.create') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded text-center transition duration-200">
                            Nuovo Stabile
                        </a>
                        <a href="{{ route('admin.tickets.create') }}" 
                           class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-4 rounded text-center transition duration-200">
                            Nuovo Ticket
                        </a>
                        <a href="{{ route('admin.fornitori.create') }}" 
                           class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded text-center transition duration-200">
                            Nuovo Fornitore
                        </a>
                        <a href="{{ route('admin.soggetti.create') }}" 
                           class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded text-center transition duration-200">
                            Nuovo Soggetto
                        </a>
                        <a href="{{ route('admin.contabilita.registrazione') }}" 
                           class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-3 px-4 rounded text-center transition duration-200">
                            Registrazione
                        </a>
                        <a href="{{ route('admin.documenti.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded text-center transition duration-200">
                            Documenti
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contenuto principale in due colonne -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Ticket da Lavorare -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Ticket da Lavorare</h3>
                            <a href="{{ route('admin.tickets.index') }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                Vedi tutti
                            </a>
                        </div>
                        
                        <div class="space-y-3">
                            @forelse($ticketsAperti as $ticket)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                <a href="{{ route('admin.tickets.show', $ticket) }}" class="hover:text-blue-600">
                                                    {{ $ticket->titolo }}
                                                </a>
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $ticket->stabile->denominazione }}
                                            </p>
                                        </div>
                                        <div class="flex flex-col items-end space-y-1">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                                @switch($ticket->priorita)
                                                    @case('Urgente') bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100 @break
                                                    @case('Alta') bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100 @break
                                                    @case('Media') bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100 @break
                                                    @default bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                                @endswitch">
                                                {{ $ticket->priorita }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $ticket->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Nessun ticket aperto</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Ultimi Documenti -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Ultimi Documenti</h3>
                            <a href="{{ route('admin.documenti.index') }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                Vedi tutti
                            </a>
                        </div>
                        
                        <div class="space-y-3">
                            @forelse($ultimiDocumenti as $documento)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $documento->nome_file }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $documento->tipo_documento }} - {{ $documento->documentable->denominazione ?? 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="flex flex-col items-end space-y-1">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $documento->created_at->diffForHumans() }}
                                            </span>
                                            <a href="{{ $documento->url_download }}" class="text-blue-600 hover:text-blue-900 text-xs">
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400 text-center py-4">Nessun documento caricato</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ultimi Movimenti Contabili -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Ultimi Movimenti Contabili</h3>
                        <a href="{{ route('admin.contabilita.movimenti') }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                            Vedi tutti
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Descrizione</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fornitore</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Importo</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($ultimiMovimenti as $movimento)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $movimento->data_registrazione->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                            {{ $movimento->descrizione }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $movimento->fornitore->ragione_sociale ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <span class="font-medium {{ $movimento->tipo_movimento === 'entrata' ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $movimento->tipo_movimento === 'entrata' ? '+' : '-' }}€ {{ number_format($movimento->importo_totale, 2, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Nessun movimento registrato
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>