<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nuova Anagrafica (Soggetto)') }}
        </h2>
    </x-slot>



    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Crea Anagrafica</h3>
                        <a href="{{ route('admin.soggetti.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Torna alla Lista
                        </a>
                    </div>

                    <form method="POST" action="{{ route('admin.soggetti.store') }}" class="space-y-6">
                        @csrf

                        <!-- Nome e Cognome -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome</label>
                                <input type="text" name="nome" id="nome" value="{{ old('nome') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="cognome" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cognome</label>
                                <input type="text" name="cognome" id="cognome" value="{{ old('cognome') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>

                        <!-- Ragione Sociale (Alternativa a Nome/Cognome) -->
                        <div>
                            <label for="ragione_sociale" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ragione Sociale</label>
                            <input type="text" name="ragione_sociale" id="ragione_sociale" value="{{ old('ragione_sociale') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                        </div>

                        <!-- Altri campi -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="codice_fiscale" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Codice Fiscale</label>
                                <input type="text" name="codice_fiscale" id="codice_fiscale" value="{{ old('codice_fiscale') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="partita_iva" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Partita IVA</label>
                                <input type="text" name="partita_iva" id="partita_iva" value="{{ old('partita_iva') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            </div>
                            <div>
                                <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefono</label>
                                <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            </div>
                        </div>

                        <!-- Tipo Soggetto -->
                        <div>
                            <label for="tipo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo Soggetto</label>
                            <select name="tipo" id="tipo" required class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                <option value="">Seleziona Tipo</option>
                                @foreach($tipi_anagrafica as $tipo)
                                    <option value="{{ $tipo }}" {{ old('tipo') == $tipo ? 'selected' : '' }}>{{ ucfirst($tipo) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pulsanti -->
                        <div class="flex items-center justify-end">
                            <a href="{{ route('admin.soggetti.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">Annulla</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Crea Anagrafica</button>
                        </div>
                    </form>

                    @if ($errors->any())
                        <div class="mt-4 text-red-600">
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
