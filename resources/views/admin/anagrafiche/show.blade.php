<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dettaglio Anagrafica: ') }} {{ $anagrafica->ragione_sociale ?: $anagrafica->cognome . ' ' . $anagrafica->nome }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Informazioni Anagrafiche</h3>
                    <dl class="mt-5 grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        @if($anagrafica->ragione_sociale)
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ragione Sociale</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $anagrafica->ragione_sociale }}</dd>
                        </div>
                        @else
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cognome</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $anagrafica->cognome ?: '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $anagrafica->nome ?: '-' }}</dd>
                        </div>
                        @endif
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Codice Fiscale</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $anagrafica->codice_fiscale ?: '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Partita IVA</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $anagrafica->partita_iva ?: '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $anagrafica->email ?: '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Telefono</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $anagrafica->telefono ?: '-' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Indirizzo</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $anagrafica->indirizzo ?: '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">CAP</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $anagrafica->cap ?: '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Città</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $anagrafica->citta ?: '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Provincia</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $anagrafica->provincia ?: '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ ucfirst($anagrafica->tipo) }}</dd>
                        </div>
                         <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Vecchio Gestionale</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $anagrafica->old_id ?: '-' }}</dd>
                        </div>
                    </dl>
                    <div class="mt-6">
                         <a href="{{ route('admin.anagrafiche.edit', $anagrafica) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-200">Modifica Anagrafica</a>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">Unità Immobiliari Associate</h3>
                    @if($anagrafica->unitaImmobiliari->isNotEmpty())
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($anagrafica->unitaImmobiliari as $unita)
                                <li>
                                    <a href="{{ route('admin.condomini.show', $unita->condominio_id) }}#unita_{{$unita->id}}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                        {{ $unita->condominio->nome }} - Scala: {{ $unita->scala ?: 'N/D' }}, Int: {{ $unita->interno ?: 'N/D' }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500 dark:text-gray-400">Nessuna unità immobiliare associata a questa anagrafica.</p>
                    @endif
                    <div class="mt-4">
                        <a href="{{ route('admin.anagrafiche.edit', $anagrafica) }}#unita_ids" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                            Modifica associazioni unità
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
