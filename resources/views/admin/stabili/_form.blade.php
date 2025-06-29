<div class="space-y-6">
    <!-- Sezione Informazioni Generali -->
    <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg">
        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informazioni Generali</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Denominazione -->
            <div>
                <x-input-label for="denominazione" :value="__('Denominazione Stabile')" />
                <x-text-input id="denominazione" name="denominazione" type="text" class="mt-1 block w-full" 
                              :value="old('denominazione', $stabile->denominazione ?? '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('denominazione')" />
            </div>

            <!-- Codice Fiscale -->
            <div>
                <x-input-label for="codice_fiscale" :value="__('Codice Fiscale')" />
                <x-text-input id="codice_fiscale" name="codice_fiscale" type="text" class="mt-1 block w-full" 
                              :value="old('codice_fiscale', $stabile->codice_fiscale ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('codice_fiscale')" />
            </div>

            <!-- CF Amministratore -->
            <div>
                <x-input-label for="cod_fisc_amministratore" :value="__('CF Amministratore')" />
                <x-text-input id="cod_fisc_amministratore" name="cod_fisc_amministratore" type="text" class="mt-1 block w-full" 
                              :value="old('cod_fisc_amministratore', $stabile->cod_fisc_amministratore ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('cod_fisc_amministratore')" />
            </div>

            <!-- Stato -->
            <div>
                <x-input-label for="stato" :value="__('Stato')" />
                <select id="stato" name="stato" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="attivo" {{ old('stato', $stabile->stato ?? 'attivo') === 'attivo' ? 'selected' : '' }}>Attivo</option>
                    <option value="inattivo" {{ old('stato', $stabile->stato ?? '') === 'inattivo' ? 'selected' : '' }}>Inattivo</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('stato')" />
            </div>
        </div>
    </div>

    <!-- Sezione Indirizzo -->
    <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg">
        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Indirizzo</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Indirizzo -->
            <div class="md:col-span-2">
                <x-input-label for="indirizzo" :value="__('Indirizzo')" />
                <x-text-input id="indirizzo" name="indirizzo" type="text" class="mt-1 block w-full" 
                              :value="old('indirizzo', $stabile->indirizzo ?? '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('indirizzo')" />
            </div>

            <!-- Città -->
            <div>
                <x-input-label for="citta" :value="__('Città')" />
                <x-text-input id="citta" name="citta" type="text" class="mt-1 block w-full" 
                              :value="old('citta', $stabile->citta ?? '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('citta')" />
            </div>

            <!-- CAP -->
            <div>
                <x-input-label for="cap" :value="__('CAP')" />
                <x-text-input id="cap" name="cap" type="text" class="mt-1 block w-full" 
                              :value="old('cap', $stabile->cap ?? '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('cap')" />
            </div>

            <!-- Provincia -->
            <div>
                <x-input-label for="provincia" :value="__('Provincia')" />
                <x-text-input id="provincia" name="provincia" type="text" class="mt-1 block w-full" maxlength="2"
                              :value="old('provincia', $stabile->provincia ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('provincia')" />
            </div>
        </div>
    </div>

    <!-- Sezione Note -->
    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Note</h4>
        <div>
            <x-input-label for="note" :value="__('Note')" />
            <textarea id="note" name="note" rows="4" 
                      class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('note', $stabile->note ?? '') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('note')" />
        </div>
    </div>
</div>