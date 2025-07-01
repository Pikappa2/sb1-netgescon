<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Condominio;
use App\Models\Fornitore; // Aggiungi questo
    use App\Models\UnitaImmobiliare; // Aggiungi questo
use App\Models\Anagrafica; // Aggiungi questo
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImportController extends Controller
{
    // Funzione per importare i condomini (invariata)
    public function importCondominio(Request $request)
    {
        // ... (codice esistente e funzionante)
    }

    /**
     * NUOVA FUNZIONE: Importa o aggiorna un singolo fornitore.
     */
    public function importFornitore(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'amministratore' || !$user->amministratore()->exists()) {
            return response()->json(['message' => 'Non autorizzato'], 403);
        }

        $datiValidati = $request->validate([
            'id_fornitore' => ['required', 'integer'],
            'cognome' => ['nullable', 'string', 'max:100'],
            'nome' => ['nullable', 'string', 'max:100'],
            'indirizzo' => ['nullable', 'string', 'max:255'],
            'cap' => ['nullable', 'string', 'max:10'],
            'citta' => ['nullable', 'string', 'max:60'], // Corretto da 'citt' a 'citta'
            'pr' => ['nullable', 'string', 'max:2'],
            'p_iva' => ['nullable', 'string', 'max:20'],
            'cod_fisc' => ['nullable', 'string', 'max:20'],
            'Indir_Email' => ['nullable', 'email', 'max:100'],
            'Cellulare' => ['nullable', 'string', 'max:30'],
            'PEC_Fornitore' => ['nullable', 'email', 'max:100'],
        ]);
    
        $fornitore = Fornitore::updateOrCreate(
            [
                'old_id' => $datiValidati['id_fornitore'],
                'amministratore_id' => $user->amministratore->id // Assicurati che l'utente abbia un amministratore associato
            ],
            [
                // 'amministratore_id' => $user->amministratore->id, // Già nel primo array per la ricerca
                'ragione_sociale' => $datiValidati['cognome'] . ' ' . $datiValidati['nome'],
                'partita_iva' => $datiValidati['p_iva'],
                'codice_fiscale' => $datiValidati['cod_fisc'],
                'email' => $datiValidati['Indir_Email'],
                'indirizzo' => $datiValidati['indirizzo'],
                'cap' => $datiValidati['cap'],
                'citta' => $datiValidati['citta'],
                'provincia' => $datiValidati['pr'],
                'telefono' => $datiValidati['Cellulare'],
                'pec' => $datiValidati['PEC_Fornitore'],
            ]
        );
    
        return response()->json(['message' => 'Fornitore importato/aggiornato con successo.', 'data' => $fornitore]);
    }

    /**
     * NUOVA FUNZIONE: Importa o aggiorna una singola anagrafica (condomino/inquilino).
     */
    public function importAnagrafica(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'amministratore' || !$user->amministratore()->exists()) {
            return response()->json(['message' => 'Non autorizzato'], 403);
        }

        $datiValidati = $request->validate([
            'id_cond' => ['required', 'integer'], // ID anagrafica vecchio gestionale
            'nom_cond' => ['required', 'string', 'max:150'], // Nome/Cognome o Ragione Sociale
            'ind' => ['nullable', 'string', 'max:255'],
            'cap' => ['nullable', 'string', 'max:10'],
            'citta' => ['nullable', 'string', 'max:60'],
            'pr' => ['nullable', 'string', 'max:2'],
            'E_mail_condomino' => ['nullable', 'email', 'max:100'],
            // 'E_mail_inquilino' => ['nullable', 'email', 'max:100'], // Se necessario
            'id_stabile' => ['required', 'integer'], // ID stabile vecchio gestionale per trovare il Condominio
            'scala' => ['nullable', 'string', 'max:10'],
            'int' => ['nullable', 'string', 'max:10'], // Interno
            'tipo_pr' => ['nullable', 'string', 'max:4'], // Es. PR, IN per mappare a 'proprietario', 'inquilino'
            // Aggiungere altri campi se necessari (cod_fisc, p_iva, telefono)
            // 'cod_fisc' => ['nullable', 'string', 'max:20'],
        ]);

        // Troviamo il condominio corrispondente nel nostro nuovo sistema
        $condominio = Condominio::where('old_id', $datiValidati['id_stabile'])
                                ->where('amministratore_id', $user->amministratore->id)
                                ->first();

        if (!$condominio) {
            return response()->json(['message' => 'Condominio (stabile) non trovato con old_id: ' . $datiValidati['id_stabile']], 404);
        }

        // Mappatura tipo_pr a tipo anagrafica
        $tipoAnagrafica = 'proprietario'; // Default
        if (isset($datiValidati['tipo_pr'])) {
            if (strtoupper($datiValidati['tipo_pr']) === 'IN') {
                $tipoAnagrafica = 'inquilino';
            }
            // Aggiungere altre mappature se necessario (es. usufruttuario)
        }

        $anagrafica = Anagrafica::updateOrCreate(
            ['old_id' => $datiValidati['id_cond']],
            [
                'ragione_sociale' => $datiValidati['nom_cond'],
                'email' => $datiValidati['E_mail_condomino'],
                'indirizzo' => $datiValidati['ind'],
                'cap' => $datiValidati['cap'],
                'citta' => $datiValidati['citta'],
                'provincia' => $datiValidati['pr'],
                'tipo' => $tipoAnagrafica,
                // Aggiungere altri campi mappati qui
                // 'codice_fiscale' => $datiValidati['cod_fisc'],
            ]
        );

        // Logica per trovare/creare e associare l'UnitaImmobiliare
        $unitaImmobiliare = null;
        if ($datiValidati['scala'] || $datiValidati['int']) {
            $unitaImmobiliare = UnitaImmobiliare::firstOrCreate(
                [
                    'condominio_id' => $condominio->id,
                    'scala' => $datiValidati['scala'],
                    'interno' => $datiValidati['int'],
                ]
                // Eventualmente aggiungere altri campi se necessari per l'unità
            );

            // Associa anagrafica a unità immobiliare (se non già associata)
            // La tabella pivot ha una chiave primaria composta, quindi attach non duplicherà
            $anagrafica->unitaImmobiliari()->syncWithoutDetaching([$unitaImmobiliare->id]);
        }

        return response()->json(['message' => 'Anagrafica importata/aggiornata con successo.', 'data' => $anagrafica, 'unita_associata' => $unitaImmobiliare]);
    }
}
