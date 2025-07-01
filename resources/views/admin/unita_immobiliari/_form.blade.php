<div class="space-y-6">
    <!-- Campo nascosto per stabile_id -->
    <input type="hidden" name="stabile_id" value="{{ $stabile->id_stabile }}">

    <!-- Sezione Identificazione -->
    <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg">
        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Identificazione Unità</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Interno -->
            <div>
                <x-input-label for="interno" :value="__('Interno')" />
                <x-text-input id="interno" name="interno" type="text" class="mt-1 block w-full" 
                              :value="old('interno', $unitaImmobiliare->interno ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('interno')" />
            </div>

            <!-- Scala -->
            <div>
                <x-input-label for="scala" :value="__('Scala')" />
                <x-text-input id="scala" name="scala" type="text" class="mt-1 block w-full" 
                              :value="old('scala', $unitaImmobiliare->scala ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('scala')" />
            </div>

            <!-- Piano -->
            <div>
                <x-input-label for="piano" :value="__('Piano')" />
                <x-text-input id="piano" name="piano" type="text" class="mt-1 block w-full" 
                              :value="old('piano', $unitaImmobiliare->piano ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('piano')" />
            </div>

            <!-- Fabbricato/Palazzina -->
            <div>
                <x-input-label for="fabbricato" :value="__('Fabbricato/Palazzina')" />
                <x-text-input id="fabbricato" name="fabbricato" type="text" class="mt-1 block w-full" 
                              :value="old('fabbricato', $unitaImmobiliare->fabbricato ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('fabbricato')" />
            </div>
        </div>
    </div>

    <!-- Sezione Dati Catastali -->
    <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg">
        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Dati Catastali e Tecnici</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Categoria Catastale -->
            <div>
                <x-input-label for="categoria_catastale" :value="__('Categoria Catastale')" />
                <x-text-input id="categoria_catastale" name="categoria_catastale" type="text" class="mt-1 block w-full" 
                              :value="old('categoria_catastale', $unitaImmobiliare->categoria_catastale ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('categoria_catastale')" />
            </div>

            <!-- Millesimi Proprietà -->
            <div>
                <x-input-label for="millesimi_proprieta" :value="__('Millesimi Proprietà')" />
                <x-text-input id="millesimi_proprieta" name="millesimi_proprieta" type="number" step="0.0001" class="mt-1 block w-full" 
                              :value="old('millesimi_proprieta', $unitaImmobiliare->millesimi_proprieta ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('millesimi_proprieta')" />
            </div>

            <!-- Superficie -->
            <div>
                <x-input-label for="superficie" :value="__('Superficie (mq)')" />
                <x-text-input id="superficie" name="superficie" type="number" step="0.01" class="mt-1 block w-full" 
                              :value="old('superficie', $unitaImmobiliare->superficie ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('superficie')" />
            </div>

            <!-- Vani -->
            <div>
                <x-input-label for="vani" :value="__('Vani')" />
                <x-text-input id="vani" name="vani" type="number" step="0.01" class="mt-1 block w-full" 
                              :value="old('vani', $unitaImmobiliare->vani ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('vani')" />
            </div>
        </div>
    </div>

    <!-- Sezione Indirizzo -->
    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-lg">
        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Indirizzo Specifico</h4>
        <div>
            <x-input-label for="indirizzo" :value="__('Indirizzo (se diverso dallo stabile)')" />
            <x-text-input id="indirizzo" name="indirizzo" type="text" class="mt-1 block w-full" 
                          :value="old('indirizzo', $unitaImmobiliare->indirizzo ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('indirizzo')" />
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Lascia vuoto se l'indirizzo è lo stesso dello stabile: {{ $stabile->indirizzo }}, {{ $stabile->citta }}
            </p>
        </div>
    </div>

    <!-- Sezione Note -->
    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Note</h4>
        <div>
            <x-input-label for="note" :value="__('Note')" />
            <textarea id="note" name="note" rows="3" 
                      class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('note', $unitaImmobiliare->note ?? '') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('note')" />
        </div>
    </div>
</div>