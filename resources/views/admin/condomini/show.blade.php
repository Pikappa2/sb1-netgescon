<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dettaglio Condominio: ') }} {{ $condominio->nome }}
            </h2>
            <x-primary-button-link href="{{ route('admin.condomini.unitaImmobiliari.create', $condominio) }}">
                {{ __('Nuova Unità Immobiliare') }}
            </x-primary-button-link>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Informazioni Condominio</h3>
                    <dl class="mt-5 grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $condominio->nome }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Codice Fiscale</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $condominio->codice_fiscale ?: '-' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Indirizzo</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $condominio->indirizzo }}, {{ $condominio->cap }} {{ $condominio->citta }} ({{ $condominio->provincia }})</dd>
                        </div>
                         <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">CF Amministratore (del condominio)</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $condominio->cod_fisc_amministratore ?: '-' }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Vecchio Gestionale</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $condominio->old_id ?: '-' }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Note</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $condominio->note ?: '-' }}</dd>
                        </div>
                    </dl>
                    <div class="mt-6">
                         <a href="{{ route('admin.condomini.edit', $condominio) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-200">Modifica Condominio</a>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">Unità Immobiliari</h3>
                     @if (session('success_unita'))
                        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                            {{ session('success_unita') }}
                        </div>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Scala</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Interno</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Piano</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Millesimi</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Azioni</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($unitaImmobiliari as $unita)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $unita->scala ?: '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $unita->interno ?: '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $unita->piano ?: '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ number_format($unita->millesimi_proprieta, 4, ',', '.') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('admin.unitaImmobiliari.edit', $unita) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-200 mr-2">Modifica</a>
                                            <form action="{{ route('admin.unitaImmobiliari.destroy', $unita) }}" method="POST" class="inline-block" onsubmit="return confirm('Sei sicuro di voler eliminare questa unità immobiliare?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-200">Elimina</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">Nessuna unità immobiliare trovata per questo condominio.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $unitaImmobiliari->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
