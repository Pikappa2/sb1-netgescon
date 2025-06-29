@extends('superadmin.layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Crea Nuova Categoria Ticket</h2>
            <a href="{{ route('superadmin.categorie-ticket.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Torna alla Lista
            </a>
        </div>

        <form method="POST" action="{{ route('superadmin.categorie-ticket.store') }}" class="space-y-6">
            @csrf

            <!-- Sezione Dati Categoria -->
            <div class="bg-blue-50 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informazioni Categoria</h3>
                <div class="space-y-6">
                    <!-- Nome -->
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700">
                            Nome Categoria <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="nome" 
                               id="nome" 
                               value="{{ old('nome') }}" 
                               required
                               maxlength="255"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('nome') border-red-500 @enderror"
                               placeholder="Es. Manutenzione, Amministrativo, Tecnico...">
                        @error('nome')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Il nome deve essere unico nel sistema</p>
                    </div>

                    <!-- Descrizione -->
                    <div>
                        <label for="descrizione" class="block text-sm font-medium text-gray-700">
                            Descrizione
                        </label>
                        <textarea name="descrizione" 
                                  id="descrizione" 
                                  rows="4"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('descrizione') border-red-500 @enderror"
                                  placeholder="Descrizione dettagliata della categoria (opzionale)">{{ old('descrizione') }}</textarea>
                        @error('descrizione')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Fornisci una descrizione per aiutare gli utenti a comprendere quando utilizzare questa categoria</p>
                    </div>
                </div>
            </div>

            <!-- Informazioni aggiuntive -->
            <div class="bg-gray-50 p-4 rounded-md">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Informazioni</h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• I campi contrassegnati con <span class="text-red-500">*</span> sono obbligatori</li>
                    <li>• Il nome della categoria deve essere unico nel sistema</li>
                    <li>• La descrizione aiuta gli utenti a scegliere la categoria corretta per i loro ticket</li>
                </ul>
            </div>

            <!-- Pulsanti -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('superadmin.categorie-ticket.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Annulla
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Crea Categoria
                </button>
            </div>
        </form>
    </div>
</div>
@endsection