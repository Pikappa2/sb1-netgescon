<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dettagli Stabile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $stabile->denominazione }}</h3>
                        <div class="space-x-2">
                            <a href="{{ route('admin.stabili.edit', $stabile) }}" 
                               class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Modifica
                            </a>
                            <a href="{{ route('admin.stabili.index') }}" 
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
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID Stabile:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $stabile->id_stabile }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Denominazione:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $stabile->denominazione }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Codice Fiscale:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $stabile->codice_fiscale ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">CF Amministratore:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $stabile->cod_fisc_amministratore ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Stato:</dt>
                                    <dd class="text-sm">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $stabile->stato === 'attivo' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                            {{ ucfirst($stabile->stato) }}
                                        </span>
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Indirizzo</h4>
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Indirizzo:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $stabile->indirizzo }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Città:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $stabile->citta }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">CAP:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $stabile->cap }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Provincia:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $stabile->provincia ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Note -->
                    @if($stabile->note)
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Note</h4>
                            <p class="text-sm text-gray-900 dark:text-gray-100">{{ $stabile->note }}</p>
                        </div>
                    @endif

                    <!-- Informazioni Sistema -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Informazioni Sistema</h4>
                        <dl class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Creato il:</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $stabile->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ultimo aggiornamento:</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $stabile->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            @if($stabile->old_id)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Old ID:</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $stabile->old_id }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>