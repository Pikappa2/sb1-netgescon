@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="ragione_sociale" :value="__('Ragione Sociale (se persona giuridica)')" />
        <x-text-input id="ragione_sociale" class="block mt-1 w-full" type="text" name="ragione_sociale" :value="old('ragione_sociale', $anagrafica->ragione_sociale ?? '')" />
        <x-input-error :messages="$errors->get('ragione_sociale')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="cognome" :value="__('Cognome (se persona fisica)')" />
        <x-text-input id="cognome" class="block mt-1 w-full" type="text" name="cognome" :value="old('cognome', $anagrafica->cognome ?? '')" />
        <x-input-error :messages="$errors->get('cognome')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="nome" :value="__('Nome (se persona fisica)')" />
        <x-text-input id="nome" class="block mt-1 w-full" type="text" name="nome" :value="old('nome', $anagrafica->nome ?? '')" />
        <x-input-error :messages="$errors->get('nome')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="codice_fiscale" :value="__('Codice Fiscale')" />
        <x-text-input id="codice_fiscale" class="block mt-1 w-full" type="text" name="codice_fiscale" :value="old('codice_fiscale', $anagrafica->codice_fiscale ?? '')" maxlength="16" />
        <x-input-error :messages="$errors->get('codice_fiscale')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="partita_iva" :value="__('Partita IVA')" />
        <x-text-input id="partita_iva" class="block mt-1 w-full" type="text" name="partita_iva" :value="old('partita_iva', $anagrafica->partita_iva ?? '')" maxlength="11" />
        <x-input-error :messages="$errors->get('partita_iva')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="email" :value="__('Email')" />
        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $anagrafica->email ?? '')" />
        <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="telefono" :value="__('Telefono')" />
        <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono', $anagrafica->telefono ?? '')" />
        <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="tipo" :value="__('Tipo Anagrafica')" />
        <select id="tipo" name="tipo" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
            @foreach($tipi_anagrafica as $tipo)
                <option value="{{ $tipo }}" @selected(old('tipo', $anagrafica->tipo ?? '') == $tipo)>
                    {{ ucfirst($tipo) }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
    </div>
    <div class="md:col-span-2">
        <x-input-label for="indirizzo" :value="__('Indirizzo (residenza/sede)')" />
        <x-text-input id="indirizzo" class="block mt-1 w-full" type="text" name="indirizzo" :value="old('indirizzo', $anagrafica->indirizzo ?? '')" />
        <x-input-error :messages="$errors->get('indirizzo')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="cap" :value="__('CAP')" />
        <x-text-input id="cap" class="block mt-1 w-full" type="text" name="cap" :value="old('cap', $anagrafica->cap ?? '')" maxlength="10" />
        <x-input-error :messages="$errors->get('cap')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="citta" :value="__('Città')" />
        <x-text-input id="citta" class="block mt-1 w-full" type="text" name="citta" :value="old('citta', $anagrafica->citta ?? '')" />
        <x-input-error :messages="$errors->get('citta')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="provincia" :value="__('Provincia (sigla)')" />
        <x-text-input id="provincia" class="block mt-1 w-full" type="text" name="provincia" :value="old('provincia', $anagrafica->provincia ?? '')" maxlength="2" />
        <x-input-error :messages="$errors->get('provincia')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="old_id" :value="__('ID Vecchio Gestionale (opzionale)')" />
        <x-text-input id="old_id" class="block mt-1 w-full" type="number" name="old_id" :value="old('old_id', $anagrafica->old_id ?? '')" />
        <x-input-error :messages="$errors->get('old_id')" class="mt-2" />
    </div>
</div>
@if ($errors->has('identificativo'))
    <div class="mt-2 text-sm text-red-600 dark:text-red-400">
        {{ $errors->first('identificativo') }}
    </div>
@endif

{{-- Sezione per associare unità immobiliari (solo in edit) --}}
@if(isset($anagrafica) && isset($unita_disponibili))
    <hr class="my-6 dark:border-gray-600">
    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Associa a Unità Immobiliari</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-60 overflow-y-auto p-2 border dark:border-gray-700 rounded">
        @forelse($unita_disponibili as $unita)
            <div>
                <label for="unita_{{ $unita['id'] }}" class="flex items-center">
                    <input type="checkbox" id="unita_{{ $unita['id'] }}" name="unita_ids[]" value="{{ $unita['id'] }}"
                           class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                           @if(in_array($unita['id'], old('unita_ids', $anagrafica->unitaImmobiliari->pluck('id')->toArray() ))) checked @endif>
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $unita['display'] }}</span>
                </label>
            </div>
        @empty
            <p class="text-sm text-gray-500 dark:text-gray-400 md:col-span-3">Nessuna unità immobiliare disponibile per l'associazione o creane prima qualcuna.</p>
        @endforelse
    </div>
    <x-input-error :messages="$errors->get('unita_ids')" class="mt-2" />
    <x-input-error :messages="$errors->get('unita_ids.*')" class="mt-2" />
@endif


<div class="mt-6 flex justify-end">
    <x-secondary-button onclick="window.location='{{ route('admin.anagrafiche.index') }}'" class="mr-2">
        {{ __('Annulla') }}
    </x-secondary-button>
    <x-primary-button type="submit">
        {{ isset($anagrafica) ? __('Aggiorna Anagrafica') : __('Crea Anagrafica') }}
    </x-primary-button>
</div>
