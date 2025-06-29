<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Rubrica') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Rubrica Contatti</h3>
                        <a href="{{ route('admin.soggetti.create') }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Nuovo Contatto
                        </a>
                    </div>

                    <!-- Filtri di Ricerca -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                        <form method="GET" action="{{ route('admin.rubrica.index') }}">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <!-- Ricerca per Nome -->
                                <div>
                                    <x-input-label for="search" :value="__('Cerca per Nome/Cognome')" />
                                    <x-text-input id="search" name="search" type="text" class="mt-1 block w-full" 
                                                  :value="request('search')" placeholder="Nome o cognome..." />
                                </div>

                                <!-- Filtro per Tipo -->
                                <div>
                                    <x-input-label for="tipo_soggetto" :value="__('Tipo Soggetto')" />
                                    <select id="tipo_soggetto" name="tipo_soggetto" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">Tutti i tipi</option>
                                        <option value="Persona Fisica" {{ request('tipo_soggetto') === 'Persona Fisica' ? 'selected' : '' }}>Persona Fisica</option>
                                        <option value="Persona Giuridica" {{ request('tipo_soggetto') === 'Persona Giuridica' ? 'selected' : '' }}>Persona Giuridica</option>
                                    </select>
                                </div>

                                <!-- Filtro per Email -->
                                <div>
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" 
                                                  :value="request('email')" placeholder="email@esempio.com" />
                                </div>

                                <!-- Pulsanti -->
                                <div class="flex items-end space-x-2">
                                    <x-primary-button type="submit">
                                        {{ __('Filtra') }}
                                    </x-primary-button>
                                    <x-secondary-button type="button" onclick="window.location='{{ route('admin.rubrica.index') }}'">
                                        {{ __('Reset') }}
                                    </x-secondary-button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Tabella Contatti -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Nome/Ragione Sociale
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Tipo
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Telefono
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Città
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 dark:border-gray-600 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                        Azioni
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($soggetti as $soggetto)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            @if($soggetto->tipo_soggetto === 'Persona Fisica')
                                                {{ $soggetto->nome }} {{ $soggetto->cognome }}
                                            @else
                                                {{ $soggetto->ragione_sociale }}
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $soggetto->tipo_soggetto === 'Persona Fisica' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' : 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' }}">
                                                {{ $soggetto->tipo_soggetto }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $soggetto->email ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $soggetto->telefono ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                            {{ $soggetto->citta ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                            <a href="{{ route('admin.soggetti.show', $soggetto) }}" 
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                Visualizza
                                            </a>
                                            <a href="{{ route('admin.soggetti.edit', $soggetto) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                Modifica
                                            </a>
                                            @if($soggetto->email)
                                                <a href="mailto:{{ $soggetto->email }}" 
                                                   class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                    Email
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Nessun contatto trovato
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginazione -->
                    <div class="mt-6">
                        {{ $soggetti->appends(request()->query())->links() }}
                    </div>

                    <!-- Statistiche -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200">Persone Fisiche</h4>
                            <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                                {{ $soggetti->where('tipo_soggetto', 'Persona Fisica')->count() }}
                            </p>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-green-800 dark:text-green-200">Persone Giuridiche</h4>
                            <p class="text-2xl font-bold text-green-900 dark:text-green-100">
                                {{ $soggetti->where('tipo_soggetto', 'Persona Giuridica')->count() }}
                            </p>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-purple-800 dark:text-purple-200">Totale Contatti</h4>
                            <p class="text-2xl font-bold text-purple-900 dark:text-purple-100">
                                {{ $soggetti->count() }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>