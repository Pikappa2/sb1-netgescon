<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registrazione Movimento Contabile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Nuova Registrazione Contabile</h3>
                        <a href="{{ route('admin.contabilita.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Torna alla Dashboard
                        </a>
                    </div>

                    <form method="POST" action="{{ route('admin.contabilita.store-registrazione') }}" id="registrazione-form">
                        @csrf
                        
                        <!-- Sezione Dati Generali -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg mb-6">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Dati Generali Documento</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Stabile -->
                                <div>
                                    <x-input-label for="stabile_id" :value="__('Stabile')" />
                                    <select id="stabile_id" name="stabile_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                        <option value="">Seleziona stabile</option>
                                        @foreach($stabili as $stabile)
                                            <option value="{{ $stabile->id_stabile }}" {{ old('stabile_id') == $stabile->id_stabile ? 'selected' : '' }}>
                                                {{ $stabile->denominazione }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('stabile_id')" />
                                </div>

                                <!-- Gestione -->
                                <div>
                                    <x-input-label for="gestione_id" :value="__('Gestione')" />
                                    <select id="gestione_id" name="gestione_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                        <option value="">Seleziona gestione</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('gestione_id')" />
                                </div>

                                <!-- Tipo Movimento -->
                                <div>
                                    <x-input-label for="tipo_movimento" :value="__('Tipo Movimento')" />
                                    <select id="tipo_movimento" name="tipo_movimento" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                        <option value="">Seleziona tipo</option>
                                        <option value="entrata" {{ old('tipo_movimento') == 'entrata' ? 'selected' : '' }}>Entrata</option>
                                        <option value="uscita" {{ old('tipo_movimento') == 'uscita' ? 'selected' : '' }}>Uscita</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('tipo_movimento')" />
                                </div>

                                <!-- Data Documento -->
                                <div>
                                    <x-input-label for="data_documento" :value="__('Data Documento')" />
                                    <x-text-input id="data_documento" name="data_documento" type="date" class="mt-1 block w-full" 
                                                  :value="old('data_documento', date('Y-m-d'))" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('data_documento')" />
                                </div>

                                <!-- Numero Documento -->
                                <div>
                                    <x-input-label for="numero_documento" :value="__('Numero Documento')" />
                                    <x-text-input id="numero_documento" name="numero_documento" type="text" class="mt-1 block w-full" 
                                                  :value="old('numero_documento')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('numero_documento')" />
                                </div>

                                <!-- Fornitore -->
                                <div>
                                    <x-input-label for="fornitore_id" :value="__('Fornitore')" />
                                    <select id="fornitore_id" name="fornitore_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">Seleziona fornitore</option>
                                        @foreach($fornitori as $fornitore)
                                            <option value="{{ $fornitore->id_fornitore }}" {{ old('fornitore_id') == $fornitore->id_fornitore ? 'selected' : '' }}>
                                                {{ $fornitore->ragione_sociale }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('fornitore_id')" />
                                </div>

                                <!-- Descrizione -->
                                <div class="md:col-span-2">
                                    <x-input-label for="descrizione" :value="__('Descrizione')" />
                                    <x-text-input id="descrizione" name="descrizione" type="text" class="mt-1 block w-full" 
                                                  :value="old('descrizione')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('descrizione')" />
                                </div>

                                <!-- Importo Totale -->
                                <div>
                                    <x-input-label for="importo_totale" :value="__('Importo Totale')" />
                                    <x-text-input id="importo_totale" name="importo_totale" type="number" step="0.01" class="mt-1 block w-full" 
                                                  :value="old('importo_totale')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('importo_totale')" />
                                </div>

                                <!-- Ritenuta d'Acconto -->
                                <div>
                                    <x-input-label for="ritenuta_acconto" :value="__('Ritenuta d\'Acconto')" />
                                    <x-text-input id="ritenuta_acconto" name="ritenuta_acconto" type="number" step="0.01" class="mt-1 block w-full" 
                                                  :value="old('ritenuta_acconto', '0')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('ritenuta_acconto')" />
                                </div>
                            </div>
                        </div>

                        <!-- Sezione Dettaglio Spese -->
                        <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">Dettaglio Spese</h4>
                                <button type="button" id="aggiungi-dettaglio" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Aggiungi Voce
                                </button>
                            </div>
                            
                            <div id="dettagli-container">
                                <!-- I dettagli verranno aggiunti dinamicamente qui -->
                            </div>
                        </div>

                        <!-- Pulsanti -->
                        <div class="flex items-center justify-end space-x-4">
                            <x-secondary-button type="button" onclick="window.location='{{ route('admin.contabilita.index') }}'">
                                {{ __('Annulla') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Registra Movimento') }}
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

    <script>
        let dettaglioIndex = 0;
        
        document.getElementById('aggiungi-dettaglio').addEventListener('click', function() {
            aggiungiDettaglio();
        });

        function aggiungiDettaglio() {
            const container = document.getElementById('dettagli-container');
            const dettaglioHtml = `
                <div class="dettaglio-item border border-gray-300 dark:border-gray-600 p-4 rounded-lg mb-4" data-index="${dettaglioIndex}">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Voce Spesa</label>
                            <select name="dettagli[${dettaglioIndex}][voce_spesa_id]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Seleziona voce</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Importo</label>
                            <input type="number" step="0.01" name="dettagli[${dettaglioIndex}][importo]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tabella Millesimale</label>
                            <select name="dettagli[${dettaglioIndex}][tabella_millesimale_id]" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Seleziona tabella</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="button" onclick="rimuoviDettaglio(${dettaglioIndex})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Rimuovi
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', dettaglioHtml);
            dettaglioIndex++;
        }

        function rimuoviDettaglio(index) {
            const item = document.querySelector(`[data-index="${index}"]`);
            if (item) {
                item.remove();
            }
        }

        // Aggiungi il primo dettaglio automaticamente
        document.addEventListener('DOMContentLoaded', function() {
            aggiungiDettaglio();
        });
    </script>
</x-app-layout>