@csrf
<div>
    <x-input-label for="id_stabile" :value="__('Stabile')" />
    @if(isset($stabileSelezionato) && $stabileSelezionato)
        <input type="hidden" name="id_stabile" value="{{ $stabileSelezionato->id_stabile }}">
        <x-text-input id="stabile_nome" class="block mt-1 w-full bg-gray-100 dark:bg-gray-700" type="text" name="stabile_nome" :value="$stabileSelezionato->denominazione" disabled readonly />
    @else
        <select id="id_stabile" name="id_stabile" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
            <option value="">{{ __('Seleziona uno stabile') }}</option>
            @foreach ($stabili as $stabile)
                <option value="{{ $stabile->id_stabile }}" {{ (old('id_stabile', $unitaImmobiliare->id_stabile ?? $stabile_id ?? '') == $stabile->id_stabile) ? 'selected' : '' }}>
                    {{ $stabile->denominazione }}
                </option>
            @endforeach
        </select>
    @endif
    <x-input-error :messages="$errors->get('id_stabile')" class="mt-2" />
</div>

<div>
    <x-input-label for="fabbricato" :value="__('Fabbricato / Palazzina')" />
    <x-text-input id="fabbricato" class="block mt-1 w-full" type="text" name="fabbricato" :value="old('fabbricato', $unitaImmobiliare->fabbricato ?? '')" />
    <x-input-error :messages="$errors->get('fabbricato')" class="mt-2" />
</div>

<div>
    <x-input-label for="scala" :value="__('Scala')" />
    <x-text-input id="scala" class="block mt-1 w-full" type="text" name="scala" :value="old('scala', $unitaImmobiliare->scala ?? '')" />
    <x-input-error :messages="$errors->get('scala')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="interno" :value="__('Interno')" />
    <x-text-input id="interno" class="block mt-1 w-full" type="text" name="interno" :value="old('interno', $unitaImmobiliare->interno ?? '')" />
    <x-input-error :messages="$errors->get('interno')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="piano" :value="__('Piano')" />
    <x-text-input id="piano" class="block mt-1 w-full" type="text" name="piano" :value="old('piano', $unitaImmobiliare->piano ?? '')" />
    <x-input-error :messages="$errors->get('piano')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="millesimi_proprieta" :value="__('Millesimi Proprietà')" />
    <x-text-input id="millesimi_proprieta" class="block mt-1 w-full" type="number" step="0.0001" name="millesimi_proprieta" :value="old('millesimi_proprieta', $unitaImmobiliare->millesimi_proprieta ?? '0.0000')" />
    <x-input-error :messages="$errors->get('millesimi_proprieta')" class="mt-2" />
</div>

<div class="mt-6 flex justify-end">
    <x-secondary-button onclick="window.history.back()" class="mr-2">
        {{ __('Annulla') }}
    </x-secondary-button>
    <x-primary-button type="submit">
        {{ isset($unitaImmobiliare) ? __('Aggiorna Unità') : __('Crea Unità') }}
    </x-primary-button>
</div>
