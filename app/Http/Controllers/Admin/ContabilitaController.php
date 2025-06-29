<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MovimentoContabile;
use App\Models\Gestione;
use App\Models\Stabile;
use App\Models\Fornitore;
use App\Models\VoceSpesa;
use App\Models\TabellaMillesimale;
use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ContabilitaController extends Controller
{
    /**
     * Dashboard contabilità
     */
    public function index()
    {
        $amministratore_id = Auth::user()->amministratore->id_amministratore ?? null;
        
        // Statistiche generali
        $stats = [
            'movimenti_mese' => MovimentoContabile::whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })->whereMonth('data_registrazione', now()->month)->count(),
            
            'importo_entrate_mese' => MovimentoContabile::whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })->where('tipo_movimento', 'entrata')
              ->whereMonth('data_registrazione', now()->month)
              ->sum('importo_totale'),
              
            'importo_uscite_mese' => MovimentoContabile::whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })->where('tipo_movimento', 'uscita')
              ->whereMonth('data_registrazione', now()->month)
              ->sum('importo_totale'),
        ];

        // Ultimi movimenti
        $ultimiMovimenti = MovimentoContabile::with(['stabile', 'gestione', 'fornitore'])
            ->whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.contabilita.index', compact('stats', 'ultimiMovimenti'));
    }

    /**
     * Lista movimenti contabili
     */
    public function movimenti(Request $request)
    {
        $amministratore_id = Auth::user()->amministratore->id_amministratore ?? null;
        
        $query = MovimentoContabile::with(['stabile', 'gestione', 'fornitore'])
            ->whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            });

        // Filtri
        if ($request->filled('stabile_id')) {
            $query->where('stabile_id', $request->stabile_id);
        }
        
        if ($request->filled('gestione_id')) {
            $query->where('gestione_id', $request->gestione_id);
        }
        
        if ($request->filled('tipo_movimento')) {
            $query->where('tipo_movimento', $request->tipo_movimento);
        }
        
        if ($request->filled('data_da')) {
            $query->where('data_registrazione', '>=', $request->data_da);
        }
        
        if ($request->filled('data_a')) {
            $query->where('data_registrazione', '<=', $request->data_a);
        }

        $movimenti = $query->orderBy('data_registrazione', 'desc')->paginate(20);
        
        // Dati per i filtri
        $stabili = Stabile::where('amministratore_id', $amministratore_id)->get();
        $gestioni = Gestione::whereIn('stabile_id', $stabili->pluck('id_stabile'))->get();

        return view('admin.contabilita.movimenti', compact('movimenti', 'stabili', 'gestioni'));
    }

    /**
     * Form registrazione movimento
     */
    public function registrazione()
    {
        $amministratore_id = Auth::user()->amministratore->id_amministratore ?? null;
        
        $stabili = Stabile::where('amministratore_id', $amministratore_id)->attivi()->get();
        $fornitori = Fornitore::where('amministratore_id', $amministratore_id)->get();
        
        return view('admin.contabilita.registrazione', compact('stabili', 'fornitori'));
    }

    /**
     * Store registrazione movimento
     */
    public function storeRegistrazione(Request $request)
    {
        $request->validate([
            'stabile_id' => 'required|exists:stabili,id_stabile',
            'gestione_id' => 'required|exists:gestioni,id_gestione',
            'tipo_movimento' => 'required|in:entrata,uscita',
            'data_documento' => 'required|date',
            'numero_documento' => 'required|string|max:50',
            'descrizione' => 'required|string|max:255',
            'importo_totale' => 'required|numeric|min:0',
            'fornitore_id' => 'nullable|exists:fornitori,id_fornitore',
            'ritenuta_acconto' => 'nullable|numeric|min:0',
            'dettagli' => 'required|array|min:1',
            'dettagli.*.voce_spesa_id' => 'required|exists:voci_spesa,id',
            'dettagli.*.importo' => 'required|numeric|min:0',
            'dettagli.*.tabella_millesimale_id' => 'nullable|exists:tabelle_millesimali,id',
        ]);

        DB::beginTransaction();
        try {
            // Genera protocollo univoco
            $protocollo = $this->generaProtocollo($request->stabile_id);
            
            // Crea movimento principale
            $movimento = MovimentoContabile::create([
                'stabile_id' => $request->stabile_id,
                'gestione_id' => $request->gestione_id,
                'fornitore_id' => $request->fornitore_id,
                'protocollo' => $protocollo,
                'data_registrazione' => now(),
                'data_documento' => $request->data_documento,
                'numero_documento' => $request->numero_documento,
                'descrizione' => $request->descrizione,
                'tipo_movimento' => $request->tipo_movimento,
                'importo_totale' => $request->importo_totale,
                'ritenuta_acconto' => $request->ritenuta_acconto ?? 0,
                'importo_netto' => $request->importo_totale - ($request->ritenuta_acconto ?? 0),
                'stato' => 'registrato',
            ]);

            // Crea dettagli movimento (partita doppia)
            foreach ($request->dettagli as $dettaglio) {
                $movimento->dettagli()->create([
                    'voce_spesa_id' => $dettaglio['voce_spesa_id'],
                    'tabella_millesimale_id' => $dettaglio['tabella_millesimale_id'] ?? null,
                    'descrizione' => $dettaglio['descrizione'] ?? '',
                    'importo_dare' => $request->tipo_movimento === 'uscita' ? $dettaglio['importo'] : 0,
                    'importo_avere' => $request->tipo_movimento === 'entrata' ? $dettaglio['importo'] : 0,
                ]);
            }

            DB::commit();
            
            return redirect()->route('admin.contabilita.movimenti')
                           ->with('success', 'Movimento registrato con successo. Protocollo: ' . $protocollo);
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Errore durante la registrazione: ' . $e->getMessage()]);
        }
    }

    /**
     * Import da XML (Fattura Elettronica)
     */
    public function importXml(Request $request)
    {
        $request->validate([
            'xml_file' => 'required|file|mimes:xml|max:2048',
            'stabile_id' => 'required|exists:stabili,id_stabile',
        ]);

        try {
            $xmlContent = file_get_contents($request->file('xml_file')->path());
            $xml = simplexml_load_string($xmlContent);
            
            // Parsing XML fattura elettronica
            $fatturaData = $this->parseXmlFattura($xml);
            
            // Salva documento
            $documento = Documento::create([
                'documentable_type' => Stabile::class,
                'documentable_id' => $request->stabile_id,
                'nome_file' => $request->file('xml_file')->getClientOriginalName(),
                'path_file' => $request->file('xml_file')->store('documenti/xml'),
                'tipo_documento' => 'fattura_elettronica',
                'xml_data' => $fatturaData,
                'mime_type' => 'application/xml',
                'dimensione_file' => $request->file('xml_file')->getSize(),
            ]);

            return view('admin.contabilita.import-xml-review', compact('fatturaData', 'documento'));
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Errore durante l\'importazione XML: ' . $e->getMessage()]);
        }
    }

    /**
     * Genera protocollo univoco
     */
    private function generaProtocollo($stabile_id)
    {
        $anno = date('Y');
        $ultimoProtocollo = MovimentoContabile::where('stabile_id', $stabile_id)
                                            ->whereYear('data_registrazione', $anno)
                                            ->max('protocollo');
        
        if ($ultimoProtocollo) {
            $numero = intval(substr($ultimoProtocollo, -4)) + 1;
        } else {
            $numero = 1;
        }
        
        return $stabile_id . '/' . $anno . '/' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Parse XML Fattura Elettronica
     */
    private function parseXmlFattura($xml)
    {
        $ns = $xml->getNamespaces(true);
        $xml->registerXPathNamespace('fe', $ns['']);
        
        // Dati generali fattura
        $datiGenerali = [
            'numero' => (string) $xml->xpath('//DatiGeneraliDocumento/Numero')[0] ?? '',
            'data' => (string) $xml->xpath('//DatiGeneraliDocumento/Data')[0] ?? '',
            'importo_totale' => (float) $xml->xpath('//DatiGeneraliDocumento/ImportoTotaleDocumento')[0] ?? 0,
        ];
        
        // Dati fornitore
        $fornitore = [
            'denominazione' => (string) $xml->xpath('//CedentePrestatore//Denominazione')[0] ?? '',
            'partita_iva' => (string) $xml->xpath('//CedentePrestatore//IdFiscaleIVA/IdCodice')[0] ?? '',
            'codice_fiscale' => (string) $xml->xpath('//CedentePrestatore//CodiceFiscale')[0] ?? '',
        ];
        
        // Righe fattura
        $righe = [];
        $dettaglioLinee = $xml->xpath('//DettaglioLinee');
        foreach ($dettaglioLinee as $linea) {
            $righe[] = [
                'descrizione' => (string) $linea->Descrizione ?? '',
                'quantita' => (float) $linea->Quantita ?? 1,
                'prezzo_unitario' => (float) $linea->PrezzoUnitario ?? 0,
                'importo_totale' => (float) $linea->PrezzoTotale ?? 0,
            ];
        }
        
        return [
            'dati_generali' => $datiGenerali,
            'fornitore' => $fornitore,
            'righe' => $righe,
        ];
    }
}