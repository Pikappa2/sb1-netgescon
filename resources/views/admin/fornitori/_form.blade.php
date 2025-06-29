<div class="space-y-6">
    <!-- Sezione Informazioni Generali -->
    <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg">
        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informazioni Generali</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Ragione Sociale -->
            <div class="md:col-span-2">
                <x-input-label for="ragione_sociale" :value="__('Ragione Sociale')" />
                <x-text-input id="ragione_sociale" name="ragione_sociale" type="text" class="mt-1 block w-full" 
                              :value="old('ragione_sociale', $fornitore->ragione_sociale ?? '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('ragione_sociale')" />
            </div>

            <!-- Partita IVA -->
            <div>
                <x-input-label for="partita_iva" :value="__('Partita IVA')" />
                <x-text-input id="partita_iva" name="partita_iva" type="text" class="mt-1 block w-full" 
                              :value="old('partita_iva', $fornitore->partita_iva ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('partita_iva')" />
            </div>

            <!-- Codice Fiscale -->
            <div>
                <x-input-label for="codice_fiscale" :value="__('Codice Fiscale')" />
                <x-text-input id="codice_fiscale" name="codice_fiscale" type="text" class="mt-1 block w-full" 
                              :value="old('codice_fiscale', $fornitore->codice_fiscale ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('codice_fiscale')" />
            </div>
        </div>
    </div>

    <!-- Sezione Contatti -->
    <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg">
        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Contatti</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" 
                              :value="old('email', $fornitore->email ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <!-- PEC -->
            <div>
                <x-input-label for="pec" :value="__('PEC')" />
                <x-text-input id="pec" name="pec" type="email" class="mt-1 block w-full" 
                              :value="old('pec', $fornitore->pec ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('pec')" />
            </div>

            <!-- Telefono -->
            <div>
                <x-input-label for="telefono" :value="__('Telefono')" />
                <x-text-input id="telefono" name="telefono" type="text" class="mt-1 block w-full" 
                              :value="old('telefono', $fornitore->telefono ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('telefono')" />
            </div>
        </div>
    </div>

    <!-- Sezione Indirizzo -->
    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-lg">
        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Indirizzo</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Indirizzo -->
            <div class="md:col-span-2">
                <x-input-label for="indirizzo" :value="__('Indirizzo')" />
                <x-text-input id="indirizzo" name="indirizzo" type="text" class="mt-1 block w-full" 
                              :value="old('indirizzo', $fornitore->indirizzo ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('indirizzo')" />
            </div>

            <!-- Città -->
            <div>
                <x-input-label for="citta" :value="__('Città')" />
                <x-text-input id="citta" name="citta" type="text" class="mt-1 block w-full" 
                              :value="old('citta', $fornitore->citta ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('citta')" />
            </div>

            <!-- CAP -->
            <div>
                <x-input-label for="cap" :value="__('CAP')" />
                <x-text-input id="cap" name="cap" type="text" class="mt-1 block w-full" 
                              :value="old('cap', $fornitore->cap ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('cap')" />
            </div>

            <!-- Provincia -->
            <div>
                <x-input-label for="provincia" :value="__('Provincia')" />
                <x-text-input id="provincia" name="provincia" type="text" class="mt-1 block w-full" maxlength="2"
                              :value="old('provincia', $fornitore->provincia ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('provincia')" />
            </div>
        </div>
    </div>
</div>