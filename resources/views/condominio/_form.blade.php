<div class="mb-3">
    <label for="denominazione" class="form-label">Denominazione</label>
    <input type="text" class="form-control" id="denominazione" name="denominazione" value="{{ old('denominazione', $condominio->denominazione) }}" required>
</div>

<div class="mb-3">
    <label for="id_amministratore" class="form-label">Amministratore</label>
    <select class="form-control" id="id_amministratore" name="id_amministratore" required>
        <option value="">Seleziona un amministratore</option>
        @foreach ($amministratori as $amministratore)
            <option value="{{ $amministratore->id_amministratore }}" {{ old('id_amministratore', $condominio->id_amministratore) == $amministratore->id_amministratore ? 'selected' : '' }}>
                {{ $amministratore->user->name }} ({{ $amministratore->denominazione_studio }})
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="indirizzo" class="form-label">Indirizzo</label>
    <input type="text" class="form-control" id="indirizzo" name="indirizzo" value="{{ old('indirizzo', $condominio->indirizzo) }}" required>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label for="cap" class="form-label">CAP</label>
        <input type="text" class="form-control" id="cap" name="cap" value="{{ old('cap', $condominio->cap) }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label for="citta" class="form-label">Città</label>
        <input type="text" class="form-control" id="citta" name="citta" value="{{ old('citta', $condominio->citta) }}" required>
    </div>
    <div class="col-md-2 mb-3">
        <label for="provincia" class="form-label">Provincia</label>
        <input type="text" class="form-control" id="provincia" name="provincia" value="{{ old('provincia', $condominio->provincia) }}" required>
    </div>
</div>

<div class="mb-3">
    <label for="codice_fiscale" class="form-label">Codice Fiscale</label>
    <input type="text" class="form-control" id="codice_fiscale" name="codice_fiscale" value="{{ old('codice_fiscale', $condominio->codice_fiscale) }}">
</div>

<div class="mb-3">
    <label for="note" class="form-label">Note</label>
    <textarea class="form-control" id="note" name="note" rows="3">{{ old('note', $condominio->note) }}</textarea>
</div>

<div class="mb-3">
    <label for="stato" class="form-label">Stato</label>
    <select class="form-control" id="stato" name="stato">
        <option value="attivo" {{ old('stato', $condominio->stato) == 'attivo' ? 'selected' : '' }}>Attivo</option>
        <option value="chiuso" {{ old('stato', $condominio->stato) == 'chiuso' ? 'selected' : '' }}>Chiuso</option>
        <option value="archiviato" {{ old('stato', $condominio->stato) == 'archiviato' ? 'selected' : '' }}>Archiviato</option>
    </select>
</div>
