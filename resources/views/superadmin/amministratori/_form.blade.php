{{-- Questo partial è usato sia per la creazione che per la modifica --}}

@if (isset($is_new) && $is_new)
    <div class="card mb-4">
        <div class="card-header">Dati Utente (Login)</div>
        <div class="card-body">
            <div class="mb-3">
                <label for="name" class="form-label">Nome Utente</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Utente</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Conferma Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
        </div>
    </div>
@endif

<div class="card mb-4">
    <div class="card-header">Dati Amministratore</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="{{ old('nome', $amministratore->nome) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="cognome" class="form-label">Cognome</label>
                <input type="text" class="form-control" id="cognome" name="cognome" value="{{ old('cognome', $amministratore->cognome) }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="denominazione_studio" class="form-label">Denominazione Studio</label>
            <input type="text" class="form-control" id="denominazione_studio" name="denominazione_studio" value="{{ old('denominazione_studio', $amministratore->denominazione_studio) }}">
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="partita_iva" class="form-label">Partita IVA</label>
                <input type="text" class="form-control" id="partita_iva" name="partita_iva" value="{{ old('partita_iva', $amministratore->partita_iva) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label for="codice_fiscale_studio" class="form-label">Codice Fiscale Studio</label>
                <input type="text" class="form-control" id="codice_fiscale_studio" name="codice_fiscale_studio" value="{{ old('codice_fiscale_studio', $amministratore->codice_fiscale_studio) }}">
            </div>
        </div>

        <div class="mb-3">
            <label for="indirizzo_studio" class="form-label">Indirizzo Studio</label>
            <input type="text" class="form-control" id="indirizzo_studio" name="indirizzo_studio" value="{{ old('indirizzo_studio', $amministratore->indirizzo_studio) }}">
        </div>

        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="cap_studio" class="form-label">CAP Studio</label>
                <input type="text" class="form-control" id="cap_studio" name="cap_studio" value="{{ old('cap_studio', $amministratore->cap_studio) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label for="citta_studio" class="form-label">Città Studio</label>
                <input type="text" class="form-control" id="citta_studio" name="citta_studio" value="{{ old('citta_studio', $amministratore->citta_studio) }}">
            </div>
            <div class="col-md-2 mb-3">
                <label for="provincia_studio" class="form-label">Provincia Studio</label>
                <input type="text" class="form-control" id="provincia_studio" name="provincia_studio" value="{{ old('provincia_studio', $amministratore->provincia_studio) }}">
            </div>
        </div>

        <div class="mb-3">
            <label for="telefono_studio" class="form-label">Telefono Studio</label>
            <input type="text" class="form-control" id="telefono_studio" name="telefono_studio" value="{{ old('telefono_studio', $amministratore->telefono_studio) }}">
        </div>

        <div class="mb-3">
            <label for="email_studio" class="form-label">Email Studio</label>
            <input type="email" class="form-control" id="email_studio" name="email_studio" value="{{ old('email_studio', $amministratore->email_studio) }}">
        </div>

        <div class="mb-3">
            <label for="pec_studio" class="form-label">PEC Studio</label>
            <input type="email" class="form-control" id="pec_studio" name="pec_studio" value="{{ old('pec_studio', $amministratore->pec_studio) }}">
        </div>
    </div>
</div>