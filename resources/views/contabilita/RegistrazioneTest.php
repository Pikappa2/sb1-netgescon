{{-- resources/views/contabilita/registrazione.blade.php --}}
<div>
    <form wire:submit.prevent="salvaRegistrazione">
        {{-- Testata documento --}}
        <div class="mb-3">
            <label>Fornitore</label>
            <select wire:model="fornitore_id" class="form-control">
                <option value="">Seleziona...</option>
                @foreach($fornitori as $fornitore)
                    <option value="{{ $fornitore->id }}">{{ $fornitore->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Data documento</label>
            <input type="date" wire:model="data_documento" class="form-control">
        </div>
        <div class="mb-3">
            <label>Descrizione</label>
            <input type="text" wire:model="descrizione" class="form-control">
        </div>
        <div class="mb-3">
            <label>Importo totale</label>
            <input type="number" wire:model="importo_totale" class="form-control" step="0.01">
        </div>

        {{-- Ritenuta d'acconto --}}
        <div class="mb-3">
            <label>Ritenuta d'acconto</label>
            <select wire:model="percentuale_ra" class="form-control">
                <option value="0">Nessuna</option>
                <option value="4">4%</option>
                <option value="20">20%</option>
            </select>
        </div>

        {{-- Voci di spesa --}}
        <h5>Voci di spesa</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Conto</th>
                    <th>Tabella</th>
                    <th>Descrizione</th>
                    <th>Importo</th>
                    <th>RA imputata</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($voci as $i => $voce)
                    <tr>
                        <td>
                            <select wire:model="voci.{{ $i }}.conto_id" class="form-control">
                                <option value="">Seleziona...</option>
                                @foreach($conti as $conto)
                                    <option value="{{ $conto->id }}">{{ $conto->descrizione }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select wire:model="voci.{{ $i }}.tabella_id" class="form-control">
                                <option value="">Seleziona...</option>
                                @foreach($tabelle as $tabella)
                                    <option value="{{ $tabella->id }}">{{ $tabella->nome_tabella }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" wire:model="voci.{{ $i }}.descrizione" class="form-control">
                        </td>
                        <td>
                            <input type="number" wire:model="voci.{{ $i }}.importo" class="form-control" step="0.01">
                        </td>
                        <td>
                            <input type="number" wire:model="voci.{{ $i }}.ra_imputata" class="form-control" step="0.01" readonly>
                        </td>
                        <td>
                            <button type="button" wire:click="rimuoviVoce({{ $i }})" class="btn btn-danger btn-sm">X</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="button" wire:click="aggiungiVoce" class="btn btn-secondary">Aggiungi voce</button>

        {{-- Allegati --}}
        <div class="mb-3 mt-3">
            <label>Allegati</label>
            <input type="file" wire:model="allegati" multiple class="form-control">
        </div>

        {{-- Totali --}}
        <div class="mt-3">
            <strong>Totale spese:</strong> {{ number_format($totale_spese, 2) }}<br>
            <strong>Totale RA:</strong> {{ number_format($totale_ra, 2) }}<br>
            <strong>Totale da pagare:</strong> {{ number_format($totale_da_pagare, 2) }}
        </div>

        <button type="submit" class="btn btn-primary">Salva registrazione</button>
    </form>

    {{-- Messaggio di successo --}}
    @if (session()->has('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif
</div>