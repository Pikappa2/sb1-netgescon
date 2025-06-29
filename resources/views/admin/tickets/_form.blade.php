<div class="space-y-6">
    <!-- Sezione Informazioni Principali -->
    <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-lg">
        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informazioni Principali</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Titolo -->
            <div class="md:col-span-2">
                <x-input-label for="titolo" :value="__('Titolo')" />
                <x-text-input id="titolo" name="titolo" type="text" class="mt-1 block w-full" 
                              :value="old('titolo', $ticket->titolo ?? '')" required />
                <x-input-error class="mt-2" :messages="$errors->get('titolo')" />
            </div>

            <!-- Stabile -->
            <div>
                <x-input-label for="stabile_id" :value="__('Stabile')" />
                <select id="stabile_id" name="stabile_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                    <option value="">Seleziona uno stabile</option>
                    @foreach($stabili ?? [] as $stabile)
                        <option value="{{ $stabile->id_stabile }}" {{ old('stabile_id', $ticket->stabile_id ?? '') == $stabile->id_stabile ? 'selected' : '' }}>
                            {{ $stabile->denominazione }}
                        </option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('stabile_id')" />
            </div>

            <!-- Categoria -->
            <div>
                <x-input-label for="categoria_ticket_id" :value="__('Categoria')" />
                <select id="categoria_ticket_id" name="categoria_ticket_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="">Seleziona una categoria</option>
                    @foreach($categorieTicket ?? [] as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('categoria_ticket_id', $ticket->categoria_ticket_id ?? '') == $categoria->id ? 'selected' : '' }}>
                            {{ $categoria->nome }}
                        </option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('categoria_ticket_id')" />
            </div>

            <!-- Stato -->
            <div>
                <x-input-label for="stato" :value="__('Stato')" />
                <select id="stato" name="stato" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                    <option value="Aperto" {{ old('stato', $ticket->stato ?? 'Aperto') === 'Aperto' ? 'selected' : '' }}>Aperto</option>
                    <option value="Preso in Carico" {{ old('stato', $ticket->stato ?? '') === 'Preso in Carico' ? 'selected' : '' }}>Preso in Carico</option>
                    <option value="In Lavorazione" {{ old('stato', $ticket->stato ?? '') === 'In Lavorazione' ? 'selected' : '' }}>In Lavorazione</option>
                    <option value="In Attesa Approvazione" {{ old('stato', $ticket->stato ?? '') === 'In Attesa Approvazione' ? 'selected' : '' }}>In Attesa Approvazione</option>
                    <option value="In Attesa Ricambi" {{ old('stato', $ticket->stato ?? '') === 'In Attesa Ricambi' ? 'selected' : '' }}>In Attesa Ricambi</option>
                    <option value="Risolto" {{ old('stato', $ticket->stato ?? '') === 'Risolto' ? 'selected' : '' }}>Risolto</option>
                    <option value="Chiuso" {{ old('stato', $ticket->stato ?? '') === 'Chiuso' ? 'selected' : '' }}>Chiuso</option>
                    <option value="Annullato" {{ old('stato', $ticket->stato ?? '') === 'Annullato' ? 'selected' : '' }}>Annullato</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('stato')" />
            </div>

            <!-- Priorità -->
            <div>
                <x-input-label for="priorita" :value="__('Priorità')" />
                <select id="priorita" name="priorita" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                    <option value="Bassa" {{ old('priorita', $ticket->priorita ?? 'Media') === 'Bassa' ? 'selected' : '' }}>Bassa</option>
                    <option value="Media" {{ old('priorita', $ticket->priorita ?? 'Media') === 'Media' ? 'selected' : '' }}>Media</option>
                    <option value="Alta" {{ old('priorita', $ticket->priorita ?? '') === 'Alta' ? 'selected' : '' }}>Alta</option>
                    <option value="Urgente" {{ old('priorita', $ticket->priorita ?? '') === 'Urgente' ? 'selected' : '' }}>Urgente</option>
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('priorita')" />
            </div>
        </div>
    </div>

    <!-- Sezione Descrizione -->
    <div class="bg-green-50 dark:bg-green-900/20 p-6 rounded-lg">
        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Descrizione</h4>
        <div>
            <x-input-label for="descrizione" :value="__('Descrizione')" />
            <textarea id="descrizione" name="descrizione" rows="4" 
                      class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('descrizione', $ticket->descrizione ?? '') }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('descrizione')" />
        </div>

        <div class="mt-4">
            <x-input-label for="luogo_intervento" :value="__('Luogo Intervento')" />
            <x-text-input id="luogo_intervento" name="luogo_intervento" type="text" class="mt-1 block w-full" 
                          :value="old('luogo_intervento', $ticket->luogo_intervento ?? '')" />
            <x-input-error class="mt-2" :messages="$errors->get('luogo_intervento')" />
        </div>
    </div>

    <!-- Sezione Assegnazione -->
    <div class="bg-yellow-50 dark:bg-yellow-900/20 p-6 rounded-lg">
        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Assegnazione</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Assegnato a Utente -->
            <div>
                <x-input-label for="assegnato_a_user_id" :value="__('Assegnato a Utente')" />
                <select id="assegnato_a_user_id" name="assegnato_a_user_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="">Nessun utente</option>
                    @foreach($users ?? [] as $user)
                        <option value="{{ $user->id }}" {{ old('assegnato_a_user_id', $ticket->assegnato_a_user_id ?? '') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('assegnato_a_user_id')" />
            </div>

            <!-- Assegnato a Fornitore -->
            <div>
                <x-input-label for="assegnato_a_fornitore_id" :value="__('Assegnato a Fornitore')" />
                <select id="assegnato_a_fornitore_id" name="assegnato_a_fornitore_id" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="">Nessun fornitore</option>
                    @foreach($fornitori ?? [] as $fornitore)
                        <option value="{{ $fornitore->id_fornitore }}" {{ old('assegnato_a_fornitore_id', $ticket->assegnato_a_fornitore_id ?? '') == $fornitore->id_fornitore ? 'selected' : '' }}>
                            {{ $fornitore->ragione_sociale }}
                        </option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('assegnato_a_fornitore_id')" />
            </div>
        </div>
    </div>

    <!-- Sezione Date -->
    <div class="bg-purple-50 dark:bg-purple-900/20 p-6 rounded-lg">
        <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Date</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Data Scadenza Prevista -->
            <div>
                <x-input-label for="data_scadenza_prevista" :value="__('Data Scadenza Prevista')" />
                <x-text-input id="data_scadenza_prevista" name="data_scadenza_prevista" type="date" class="mt-1 block w-full" 
                              :value="old('data_scadenza_prevista', $ticket->data_scadenza_prevista?->format('Y-m-d') ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('data_scadenza_prevista')" />
            </div>

            <!-- Data Risoluzione Effettiva -->
            <div>
                <x-input-label for="data_risoluzione_effettiva" :value="__('Data Risoluzione Effettiva')" />
                <x-text-input id="data_risoluzione_effettiva" name="data_risoluzione_effettiva" type="date" class="mt-1 block w-full" 
                              :value="old('data_risoluzione_effettiva', $ticket->data_risoluzione_effettiva?->format('Y-m-d') ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('data_risoluzione_effettiva')" />
            </div>

            <!-- Data Chiusura Effettiva -->
            <div>
                <x-input-label for="data_chiusura_effettiva" :value="__('Data Chiusura Effettiva')" />
                <x-text-input id="data_chiusura_effettiva" name="data_chiusura_effettiva" type="date" class="mt-1 block w-full" 
                              :value="old('data_chiusura_effettiva', $ticket->data_chiusura_effettiva?->format('Y-m-d') ?? '')" />
                <x-input-error class="mt-2" :messages="$errors->get('data_chiusura_effettiva')" />
            </div>
        </div>
    </div>
</div>