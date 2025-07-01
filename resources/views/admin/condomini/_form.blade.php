@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
{{-- SEZIONE 1: DATI ANAGRAFICI DELLO STABILE --}}
<div class="p-6 border-b border-gray-200 dark:border-gray-700">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Dati Anagrafici Stabile</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <x-input-label for="denominazione" :value="__('Denominazione Stabile *')" />
            <x-text-input id="denominazione" class="block mt-1 w-full" type="text" name="denominazione" :value="old('denominazione', $stabile->denominazione ?? '')" required autofocus />
            <x-input-error :messages="$errors->get('denominazione')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="codice_fiscale" :value="__('Codice Fiscale Stabile')" />
            <x-text-input id="codice_fiscale" class="block mt-1 w-full" type="text" name="codice_fiscale" :value="old('codice_fiscale', $stabile->codice_fiscale ?? '')" />
            <x-input-error :messages="$errors->get('codice_fiscale')" class="mt-2" />
        </div>
        <div class="md:col-span-2">
            <x-input-label for="indirizzo" :value="__('Indirizzo *')" />
            <x-text-input id="indirizzo" class="block mt-1 w-full" type="text" name="indirizzo" :value="old('indirizzo', $stabile->indirizzo ?? '')" required />
            <x-input-error :messages="$errors->get('indirizzo')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="cap" :value="__('CAP *')" />
            <x-text-input id="cap" class="block mt-1 w-full" type="text" name="cap" :value="old('cap', $stabile->cap ?? '')" required maxlength="5" />
            <x-input-error :messages="$errors->get('cap')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="citta" :value="__('Città *')" />
            <x-text-input id="citta" class="block mt-1 w-full" type="text" name="citta" :value="old('citta', $stabile->citta ?? '')" required />
            <x-input-error :messages="$errors->get('citta')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="provincia" :value="__('Provincia (sigla) *')" />
            <x-text-input id="provincia" class="block mt-1 w-full" type="text" name="provincia" :value="old('provincia', $stabile->provincia ?? '')" required maxlength="2" />
            <x-input-error :messages="$errors->get('provincia')" class="mt-2" />
        </div>
    </div>
</div>

{{-- SEZIONE 2: GESTIONE RATE --}}
<div class="p-6 border-b border-gray-200 dark:border-gray-700">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Configurazione Emissione Rate</h3>
    
    {{-- Rate Ordinarie --}}
    <div class="mb-6">
        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Mesi Emissione Rate Ordinarie</label>
        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mt-2">
            @foreach (['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'] as $key => $mese)
                @php $meseNum = $key + 1; @endphp
                <label class="flex items-center">
                    <input type="checkbox" name="rate_ordinarie_mesi[]" value="{{ $meseNum }}" 
                           class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                           {{ in_array($meseNum, old('rate_ordinarie_mesi', $stabile->rate_ordinarie_mesi ?? [])) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $mese }}</span>
                </label>
            @endforeach
        </div>
   {{-- Rate Riscaldamento --}}
    <div class="mb-6">
        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Mesi Emissione Rate Riscaldamento</label>
        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mt-2">
            @foreach (['Gennaio', 'Febbraio', 'Marzo', 'Aprile', 'Maggio', 'Giugno', 'Luglio', 'Agosto', 'Settembre', 'Ottobre', 'Novembre', 'Dicembre'] as $key => $mese)
                @php $meseNum = $key + 1; @endphp
                <label class="flex items-center">
                    <input type="checkbox" name="rate_riscaldamento_mesi[]" value="{{ $meseNum }}"
                           class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                           {{ in_array($meseNum, old('rate_riscaldamento_mesi', $stabile->rate_riscaldamento_mesi ?? [])) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ $mese }}</span>
                </label>
            @endforeach
        </div>
    </div>

    {{-- Descrizione Rate --}}
    <div>
        <x-input-label for="descrizione_rate" :value="__('Descrizione Rate (es. Rata 1 di 4, Acconto Riscaldamento)')" />
        <textarea id="descrizione_rate" name="descrizione_rate" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('descrizione_rate', $stabile->descrizione_rate ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('descrizione_rate')" class="mt-2" />
    </div>
</div>

{{-- SEZIONE 3: NOTE E PULSANTI --}}
<div class="p-6">
    <x-input-label for="note" :value="__('Note Generali')" />
    <textarea id="note" name="note" rows="3" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('note', $stabile->note ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('note')" class="mt-2" />

    <div class="mt-6 flex justify-end">
        <x-secondary-button onclick="window.history.back()" class="mr-2">
            {{ __('Annulla') }}
        </x-secondary-button>
        <x-primary-button type="submit">
            {{ isset($stabile) ? __('Aggiorna Stabile') : __('Crea Stabile') }}
        </x-primary-button>
    </div>
</div>