<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dettagli Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $ticket->titolo }}</h3>
                        <div class="space-x-2">
                            <a href="{{ route('admin.tickets.edit', $ticket) }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Modifica
                            </a>
                            <a href="{{ route('admin.tickets.index') }}" 
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Torna alla Lista
                            </a>
                        </div>
                    </div>

                    <!-- Informazioni Principali -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Informazioni Generali</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Ticket:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->id }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Titolo:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->titolo }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Stabile:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->stabile->denominazione ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Categoria:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->categoriaTicket->nome ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Stato e Priorità</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Stato:</dt>
                                    <dd class="text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @switch($ticket->stato)
                                                @case('Aperto')
                                                    bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                                    @break
                                                @case('Preso in Carico')
                                                    bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                                    @break
                                                @case('In Lavorazione')
                                                    bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100
                                                    @break
                                                @case('Risolto')
                                                    bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                                    @break
                                                @case('Chiuso')
                                                    bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100
                                                    @break
                                                @default
                                                    bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100
                                            @endswitch">
                                            {{ $ticket->stato }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Priorità:</dt>
                                    <dd class="text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            @switch($ticket->priorita)
                                                @case('Urgente')
                                                    bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100
                                                    @break
                                                @case('Alta')
                                                    bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100
                                                    @break
                                                @case('Media')
                                                    bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100
                                                    @break
                                                @case('Bassa')
                                                    bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100
                                                    @break
                                                @default
                                                    bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100
                                            @endswitch">
                                            {{ $ticket->priorita }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Aperto da:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->apertoUser->name ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Assegnato a:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->assegnatoUser->name ?? $ticket->assegnatoFornitore->ragione_sociale ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Descrizione -->
                    @if($ticket->descrizione)
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Descrizione</h4>
                            <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $ticket->descrizione }}</p>
                        </div>
                    @endif

                    <!-- Date e Scadenze -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Date e Scadenze</h4>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data Apertura:</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->data_apertura->format('d/m/Y H:i') }}</dd>
                            </div>
                            @if($ticket->data_scadenza_prevista)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Scadenza Prevista:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->data_scadenza_prevista->format('d/m/Y') }}</dd>
                                </div>
                            @endif
                            @if($ticket->data_risoluzione_effettiva)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data Risoluzione:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->data_risoluzione_effettiva->format('d/m/Y') }}</dd>
                                </div>
                            @endif
                            @if($ticket->data_chiusura_effettiva)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data Chiusura:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->data_chiusura_effettiva->format('d/m/Y') }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Informazioni Sistema -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Informazioni Sistema</h4>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Creato il:</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ultimo aggiornamento:</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $ticket->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>