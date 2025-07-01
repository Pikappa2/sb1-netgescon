@extends('superadmin.layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Crea Nuovo Amministratore</h2>
            <a href="{{ route('superadmin.amministratori.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Torna alla Lista
            </a>
        </div>

        <form method="POST" action="{{ route('superadmin.amministratori.store') }}" class="space-y-8">
            @csrf

            <!-- Sezione Dati Utente -->
            <div class="bg-gray-50 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Dati Utente (Account di accesso)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome Utente -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nome Utente</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Utente -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Utente</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Conferma Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Conferma Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </div>

            <!-- Sezione Dati Amministratore -->
            <div class="bg-blue-50 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Dati Personali Amministratore</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome -->
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
                        <input type="text" name="nome" id="nome" value="{{ old('nome') }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('nome') border-red-500 @enderror">
                        @error('nome')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cognome -->
                    <div>
                        <label for="cognome" class="block text-sm font-medium text-gray-700">Cognome</label>
                        <input type="text" name="cognome" id="cognome" value="{{ old('cognome') }}" required
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('cognome') border-red-500 @enderror">
                        @error('cognome')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Sezione Dati Studio -->
            <div class="bg-green-50 p-6 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Dati Studio Professionale</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Denominazione Studio -->
                    <div class="md:col-span-2">
                        <label for="denominazione_studio" class="block text-sm font-medium text-gray-700">Denominazione Studio</label>
                        <input type="text" name="denominazione_studio" id="denominazione_studio" value="{{ old('denominazione_studio') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('denominazione_studio') border-red-500 @enderror">
                        @error('denominazione_studio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Partita IVA -->
                    <div>
                        <label for="partita_iva" class="block text-sm font-medium text-gray-700">Partita IVA</label>
                        <input type="text" name="partita_iva" id="partita_iva" value="{{ old('partita_iva') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('partita_iva') border-red-500 @enderror">
                        @error('partita_iva')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Codice Fiscale Studio -->
                    <div>
                        <label for="codice_fiscale_studio" class="block text-sm font-medium text-gray-700">Codice Fiscale Studio</label>
                        <input type="text" name="codice_fiscale_studio" id="codice_fiscale_studio" value="{{ old('codice_fiscale_studio') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('codice_fiscale_studio') border-red-500 @enderror">
                        @error('codice_fiscale_studio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Indirizzo Studio -->
                    <div class="md:col-span-2">
                        <label for="indirizzo_studio" class="block text-sm font-medium text-gray-700">Indirizzo Studio</label>
                        <input type="text" name="indirizzo_studio" id="indirizzo_studio" value="{{ old('indirizzo_studio') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('indirizzo_studio') border-red-500 @enderror">
                        @error('indirizzo_studio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- CAP Studio -->
                    <div>
                        <label for="cap_studio" class="block text-sm font-medium text-gray-700">CAP Studio</label>
                        <input type="text" name="cap_studio" id="cap_studio" value="{{ old('cap_studio') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('cap_studio') border-red-500 @enderror">
                        @error('cap_studio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Città Studio -->
                    <div>
                        <label for="citta_studio" class="block text-sm font-medium text-gray-700">Città Studio</label>
                        <input type="text" name="citta_studio" id="citta_studio" value="{{ old('citta_studio') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('citta_studio') border-red-500 @enderror">
                        @error('citta_studio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Provincia Studio -->
                    <div>
                        <label for="provincia_studio" class="block text-sm font-medium text-gray-700">Provincia Studio</label>
                        <input type="text" name="provincia_studio" id="provincia_studio" value="{{ old('provincia_studio') }}" maxlength="2"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('provincia_studio') border-red-500 @enderror">
                        @error('provincia_studio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Telefono Studio -->
                    <div>
                        <label for="telefono_studio" class="block text-sm font-medium text-gray-700">Telefono Studio</label>
                        <input type="text" name="telefono_studio" id="telefono_studio" value="{{ old('telefono_studio') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('telefono_studio') border-red-500 @enderror">
                        @error('telefono_studio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Studio -->
                    <div>
                        <label for="email_studio" class="block text-sm font-medium text-gray-700">Email Studio</label>
                        <input type="email" name="email_studio" id="email_studio" value="{{ old('email_studio') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('email_studio') border-red-500 @enderror">
                        @error('email_studio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- PEC Studio -->
                    <div>
                        <label for="pec_studio" class="block text-sm font-medium text-gray-700">PEC Studio</label>
                        <input type="email" name="pec_studio" id="pec_studio" value="{{ old('pec_studio') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('pec_studio') border-red-500 @enderror">
                        @error('pec_studio')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pulsanti -->
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('superadmin.amministratori.index') }}" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                    Annulla
                </a>
                <button type="submit" 
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Crea Amministratore
                </button>
            </div>
        </form>
    </div>
</div>
@endsection