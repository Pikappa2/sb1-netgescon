<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Carica Nuovo Documento') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Carica Nuovo Documento</h3>
                        <a href="{{ route('admin.documenti.index') }}" 
                           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Torna all'Archivio
                        </a>
                    </div>

                    <form method="POST" action="{{ route('admin.documenti.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Sezione File -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">File da Caricare</h4>
                                <div>
                                    <x-input-label for="file" :value="__('Seleziona File')" />
                                    <input type="file" id="file" name="file" required
                                           class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" />
                                    <x-input-error class="mt-2" :messages="$errors->get('file')" />
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Formati supportati: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, XML. Dimensione massima: 10MB
                                    </p>
                                </div>
                            </div>

                            <!-- Sezione Classificazione -->
                            <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Classificazione Documento</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Collega a Stabile -->
                                    <div>
                                        <x-input-label for="documentable_id" :value="__('Collega a Stabile')" />
                                        <select id="documentable_id" name="documentable_id" required
                                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="">Seleziona uno stabile</option>
                                            @foreach($stabili as $stabile)
                                                <option value="{{ $stabile->id_stabile }}" {{ old('documentable_id') == $stabile->id_stabile ? 'selected' : '' }}>
                                                    {{ $stabile->denominazione }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="documentable_type" value="App\Models\Stabile">
                                        <x-input-error class="mt-2" :messages="$errors->get('documentable_id')" />
                                    </div>

                                    <!-- Tipo Documento -->
                                    <div>
                                        <x-input-label for="tipo_documento" :value="__('Tipo Documento')" />
                                        <select id="tipo_documento" name="tipo_documento" required
                                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                            <option value="">Seleziona tipo</option>
                                            <option value="Fattura" {{ old('tipo_documento') === 'Fattura' ? 'selected' : '' }}>Fattura</option>
                                            <option value="Verbale Assemblea" {{ old('tipo_documento') === 'Verbale Assemblea' ? 'selected' : '' }}>Verbale Assemblea</option>
                                            <option value="Bilancio" {{ old('tipo_documento') === 'Bilancio' ? 'selected' : '' }}>Bilancio</option>
                                            <option value="Contratto" {{ old('tipo_documento') === 'Contratto' ? 'selected' : '' }}>Contratto</option>
                                            <option value="Comunicazione" {{ old('tipo_documento') === 'Comunicazione' ? 'selected' : '' }}>Comunicazione</option>
                                            <option value="Certificato" {{ old('tipo_documento') === 'Certificato' ? 'selected' : '' }}>Certificato</option>
                                            <option value="Planimetria" {{ old('tipo_documento') === 'Planimetria' ? 'selected' : '' }}>Planimetria</option>
                                            <option value="Foto" {{ old('tipo_documento') === 'Foto' ? 'selected' : '' }}>Foto</option>
                                            <option value="Altro" {{ old('tipo_documento') === 'Altro' ? 'selected' : '' }}>Altro</option>
                                        </select>
                                        <x-input-error class="mt-2" :messages="$errors->get('tipo_documento')" />
                                    </div>
                                </div>
                            </div>

                            <!-- Sezione Descrizione -->
                            <div class="bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-lg">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Descrizione</h4>
                                <div>
                                    <x-input-label for="descrizione" :value="__('Descrizione (opzionale)')" />
                                    <textarea id="descrizione" name="descrizione" rows="3" 
                                              class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                              placeholder="Aggiungi una descrizione per identificare meglio il documento...">{{ old('descrizione') }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('descrizione')" />
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end space-x-4 mt-6">
                            <x-secondary-button type="button" onclick="window.location='{{ route('admin.documenti.index') }}'">
                                {{ __('Annulla') }}
                            </x-secondary-button>
                            <x-primary-button>
                                {{ __('Carica Documento') }}
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