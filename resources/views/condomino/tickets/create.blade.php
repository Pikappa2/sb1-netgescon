<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nuovo Ticket') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Crea Nuovo Ticket</h3>
                        <a href="{{ route('condomino.tickets.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Torna ai Ticket
                        </a>
                    </div>

                    <form method="POST" action="{{ route('condomino.tickets.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Sezione Informazioni Principali -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informazioni Principali</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Titolo -->
                                    <div class="md:col-span-2">
                                        <x-input-label for="titolo" :value="__('Titolo')" />
                                        <x-text-input id="titolo" name="titolo" type="text" class="mt-1 block w-full" 
                                                      :value="old('titolo')" required placeholder="Descrivi brevemente il problema..." />
                                        <x-input-error class="mt-2" :messages="$errors->get('titolo')" />
                                    </div>

                                    <!-- Unità Immobiliare -->
                                    <div>
                                        <x-input-label for="unita_immobiliare_id" :value="__('Unità Immobiliare')" />
                                        <select id="unita_immobiliare_id" name="unita_immobiliare_id" required
                                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="">Seleziona unità</option>
                                            @foreach($unitaImmobiliari as $unita)
                                                <option value="{{ $unita->id_unita }}" {{ old('unita_immobiliare_id') == $unita->id_unita ? 'selected' : '' }}>
                                                    {{ $unita->stabile->denominazione }} - {{ $unita->identificazione_completa }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error class="mt-2" :messages="$errors->get('unita_immobiliare_id')" />
                                    </div>

                                    <!-- Categoria -->
                                    <div>
                                        <x-input-label for="categoria_ticket_id" :value="__('Categoria')" />
                                        <select id="categoria_ticket_id" name="categoria_ticket_id"
                                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="">Seleziona categoria</option>
                                            @foreach($categorieTicket as $categoria)
                                                <option value="{{ $categoria->id }}" {{ old('categoria_ticket_id') == $categoria->id ? 'selected' : '' }}>
                                                    {{ $categoria->nome }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error class="mt-2" :messages="$errors->get('categoria_ticket_id')" />
                                    </div>

                                    <!-- Priorità -->
                                    <div>
                                        <x-input-label for="priorita" :value="__('Priorità')" />
                                        <select id="priorita" name="priorita" required
                                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="Bassa" {{ old('priorita', 'Media') === 'Bassa' ? 'selected' : '' }}>Bassa</option>
                                            <option value="Media" {{ old('priorita', 'Media') === 'Media' ? 'selected' : '' }}>Media</option>
                                            <option value="Alta" {{ old('priorita') === 'Alta' ? 'selected' : '' }}>Alta</option>
                                            <option value="Urgente" {{ old('priorita') === 'Urgente' ? 'selected' : '' }}>Urgente</option>
                                        </select>
                                        <x-input-error class="mt-2" :messages="$errors->get('priorita')" />
                                    </div>

                                    <!-- Luogo Intervento -->
                                    <div>
                                        <x-input-label for="luogo_intervento" :value="__('Luogo Intervento')" />
                                        <x-text-input id="luogo_intervento" name="luogo_intervento" type="text" class="mt-1 block w-full" 
                                                      :value="old('luogo_intervento')" placeholder="Es. Bagno, Cucina, Parti comuni..." />
                                        <x-input-error class="mt-2" :messages="$errors->get('luogo_intervento')" />
                                    </div>
                                </div>
                            </div>

                            <!-- Sezione Descrizione -->
                            <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Descrizione Dettagliata</h4>
                                <div>
                                    <x-input-label for="descrizione" :value="__('Descrizione')" />
                                    <textarea id="descrizione" name="descrizione" rows="6" required
                                              class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                              placeholder="Descrivi dettagliatamente il problema, quando si è verificato, eventuali danni...">{{ old('descrizione') }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('descrizione')" />
                                </div>
                            </div>

                            <!-- Sezione Allegati -->
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Allegati (Opzionale)</h4>
                                <div>
                                    <x-input-label for="allegati" :value="__('Carica File')" />
                                    <input type="file" id="allegati" name="allegati[]" multiple accept="image/*,.pdf,.doc,.docx"
                                           class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" />
                                    <x-input-error class="mt-2" :messages="$errors->get('allegati.*')" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Puoi caricare foto, documenti PDF, Word. Massimo 10MB per file.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end space-x-4 mt-6">
                            <x-secondary-button type="button" onclick="window.location='{{ route('condomino.tickets.index') }}'">
                                {{ __('Annulla') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Crea Ticket') }}
                            </x-primary-button>
                        </div>
                    </form>

                    @if ($errors->any())
                        <div class="mt-4 text-red-600 dark:text-red-400">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>