<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assemblea;
use App\Models\OrdineGiorno;
use App\Models\Convocazione;
use App\Models\PresenzaAssemblea;
use App\Models\Votazione;
use App\Models\Verbale;
use App\Models\RegistroProtocollo;
use App\Models\Stabile;
use App\Models\Preventivo;
use App\Models\TabellaMillesimale;
use App\Models\Soggetto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AssembleaController extends Controller
{
    /**
     * Dashboard assemblee
     */
    public function index()
    {
        $amministratore_id = Auth::user()->amministratore->id_amministratore ?? null;
        
        $assemblee = Assemblea::with(['stabile', 'creatoDa'])
            ->whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })
            ->orderBy('data_prima_convocazione', 'desc')
            ->paginate(15);

        // Statistiche
        $stats = [
            'assemblee_programmate' => Assemblea::whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })->whereIn('stato', ['bozza', 'convocata'])->count(),
            
            'assemblee_svolte' => Assemblea::whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })->where('stato', 'svolta')->count(),
            
            'convocazioni_inviate' => Convocazione::whereHas('assemblea.stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })->where('data_invio', '>=', now()->subDays(30))->count(),
            
            'delibere_approvate' => OrdineGiorno::whereHas('assemblea.stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })->where('esito_votazione', 'approvato')->count(),
        ];

        return view('admin.assemblee.index', compact('assemblee', 'stats'));
    }

    /**
     * Form creazione assemblea
     */
    public function create()
    {
        $amministratore_id = Auth::user()->amministratore->id_amministratore ?? null;
        $stabili = Stabile::where('amministratore_id', $amministratore_id)->attivi()->get();
        
        return view('admin.assemblee.create', compact('stabili'));
    }

    /**
     * Store assemblea
     */
    public function store(Request $request)
    {
        $request->validate([
            'stabile_id' => 'required|exists:stabili,id_stabile',
            'tipo' => 'required|in:ordinaria,straordinaria',
            'data_prima_convocazione' => 'required|date|after:now',
            'data_seconda_convocazione' => 'required|date|after:data_prima_convocazione',
            'luogo' => 'required|string|max:255',
            'note' => 'nullable|string',
            'ordine_giorno' => 'required|array|min:1',
            'ordine_giorno.*.titolo' => 'required|string|max:255',
            'ordine_giorno.*.descrizione' => 'required|string',
            'ordine_giorno.*.tipo_voce' => 'required|in:discussione,delibera,spesa,preventivo,altro',
            'ordine_giorno.*.importo_spesa' => 'nullable|numeric|min:0',
            'ordine_giorno.*.tabella_millesimale_id' => 'nullable|exists:tabelle_millesimali,id',
        ]);

        DB::beginTransaction();
        try {
            $assemblea = Assemblea::create([
                'stabile_id' => $request->stabile_id,
                'tipo' => $request->tipo,
                'data_prima_convocazione' => $request->data_prima_convocazione,
                'data_seconda_convocazione' => $request->data_seconda_convocazione,
                'luogo' => $request->luogo,
                'note' => $request->note,
                'stato' => 'bozza',
                'creato_da_user_id' => Auth::id(),
            ]);

            // Crea ordine del giorno
            foreach ($request->ordine_giorno as $index => $punto) {
                OrdineGiorno::create([
                    'assemblea_id' => $assemblea->id,
                    'numero_punto' => $index + 1,
                    'titolo' => $punto['titolo'],
                    'descrizione' => $punto['descrizione'],
                    'tipo_voce' => $punto['tipo_voce'],
                    'importo_spesa' => $punto['importo_spesa'] ?? null,
                    'tabella_millesimale_id' => $punto['tabella_millesimale_id'] ?? null,
                ]);
            }

            DB::commit();
            
            return redirect()->route('admin.assemblee.show', $assemblea)
                           ->with('success', 'Assemblea creata con successo.');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Errore durante la creazione: ' . $e->getMessage()]);
        }
    }

    /**
     * Visualizza assemblea
     */
    public function show(Assemblea $assemblea)
    {
        // Verifica accesso
        if ($assemblea->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        $assemblea->load([
            'stabile',
            'ordineGiorno.preventivo',
            'ordineGiorno.tabellaMillesimale',
            'convocazioni.soggetto',
            'presenze.soggetto',
            'verbale',
            'documenti'
        ]);

        // Calcola statistiche convocazioni
        $statsConvocazioni = [
            'totale_inviate' => $assemblea->convocazioni->count(),
            'consegnate' => $assemblea->convocazioni->where('esito_invio', 'consegnato')->count(),
            'lette' => $assemblea->convocazioni->where('esito_invio', 'letto')->count(),
            'conferme_presenza' => $assemblea->convocazioni->where('presenza_confermata', true)->count(),
            'deleghe' => $assemblea->convocazioni->where('delega_presente', true)->count(),
        ];

        // Calcola quorum se assemblea svolta
        $quorum = null;
        if ($assemblea->stato === 'svolta') {
            $quorum = $assemblea->calcolaQuorum();
        }

        return view('admin.assemblee.show', compact('assemblea', 'statsConvocazioni', 'quorum'));
    }

    /**
     * Invia convocazioni
     */
    public function inviaConvocazioni(Request $request, Assemblea $assemblea)
    {
        $request->validate([
            'canali' => 'required|array',
            'canali.*' => 'in:email,pec,whatsapp,telegram,raccomandata,mano,portiere',
        ]);

        // Verifica accesso
        if ($assemblea->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        if ($assemblea->stato !== 'bozza') {
            return back()->withErrors(['error' => 'Le convocazioni possono essere inviate solo per assemblee in bozza.']);
        }

        try {
            $convocazioniInviate = $assemblea->inviaConvocazioni($request->canali, Auth::id());
            
            return back()->with('success', "Inviate {$convocazioniInviate} convocazioni con successo.");
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Errore nell\'invio convocazioni: ' . $e->getMessage()]);
        }
    }

    /**
     * Gestione presenze
     */
    public function presenze(Assemblea $assemblea)
    {
        // Verifica accesso
        if ($assemblea->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        $unitaImmobiliari = $assemblea->stabile->unitaImmobiliari()
            ->with(['proprieta.soggetto'])
            ->get();

        $presenzeEsistenti = $assemblea->presenze()
            ->with(['soggetto', 'unitaImmobiliare'])
            ->get()
            ->keyBy(function($presenza) {
                return $presenza->soggetto_id . '_' . $presenza->unita_immobiliare_id;
            });

        return view('admin.assemblee.presenze', compact('assemblea', 'unitaImmobiliari', 'presenzeEsistenti'));
    }

    /**
     * Registra presenza
     */
    public function registraPresenza(Request $request, Assemblea $assemblea)
    {
        $request->validate([
            'presenze' => 'required|array',
            'presenze.*.soggetto_id' => 'required|exists:soggetti,id_soggetto',
            'presenze.*.unita_immobiliare_id' => 'required|exists:unita_immobiliari,id_unita',
            'presenze.*.tipo_presenza' => 'required|in:presente,delegato,assente',
            'presenze.*.millesimi_rappresentati' => 'required|numeric|min:0',
            'presenze.*.delegante_soggetto_id' => 'nullable|exists:soggetti,id_soggetto',
        ]);

        // Verifica accesso
        if ($assemblea->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            // Elimina presenze esistenti
            $assemblea->presenze()->delete();

            // Registra nuove presenze
            foreach ($request->presenze as $presenzaData) {
                if ($presenzaData['tipo_presenza'] !== 'assente') {
                    PresenzaAssemblea::create([
                        'assemblea_id' => $assemblea->id,
                        'soggetto_id' => $presenzaData['soggetto_id'],
                        'unita_immobiliare_id' => $presenzaData['unita_immobiliare_id'],
                        'tipo_presenza' => $presenzaData['tipo_presenza'],
                        'millesimi_rappresentati' => $presenzaData['millesimi_rappresentati'],
                        'delegante_soggetto_id' => $presenzaData['delegante_soggetto_id'] ?? null,
                        'ora_arrivo' => now(),
                    ]);
                }
            }

            // Aggiorna stato assemblea
            $assemblea->update(['stato' => 'svolta', 'data_svolgimento' => now()]);

            DB::commit();
            return back()->with('success', 'Presenze registrate con successo.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Errore nella registrazione presenze: ' . $e->getMessage()]);
        }
    }

    /**
     * Gestione votazioni
     */
    public function votazioni(Assemblea $assemblea, OrdineGiorno $ordineGiorno)
    {
        // Verifica accesso
        if ($assemblea->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        if ($assemblea->stato !== 'svolta') {
            return back()->withErrors(['error' => 'Le votazioni possono essere gestite solo per assemblee svolte.']);
        }

        $presenze = $assemblea->presenze()->with(['soggetto', 'unitaImmobiliare'])->get();
        $votazioniEsistenti = $ordineGiorno->votazioni()
            ->get()
            ->keyBy(function($voto) {
                return $voto->soggetto_id . '_' . $voto->unita_immobiliare_id;
            });

        return view('admin.assemblee.votazioni', compact('assemblea', 'ordineGiorno', 'presenze', 'votazioniEsistenti'));
    }

    /**
     * Registra votazioni
     */
    public function registraVotazioni(Request $request, Assemblea $assemblea, OrdineGiorno $ordineGiorno)
    {
        $request->validate([
            'voti' => 'required|array',
            'voti.*.soggetto_id' => 'required|exists:soggetti,id_soggetto',
            'voti.*.unita_immobiliare_id' => 'required|exists:unita_immobiliari,id_unita',
            'voti.*.voto' => 'required|in:favorevole,contrario,astenuto,non_votante',
            'voti.*.millesimi_voto' => 'required|numeric|min:0',
        ]);

        // Verifica accesso
        if ($assemblea->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            // Elimina votazioni esistenti
            $ordineGiorno->votazioni()->delete();

            // Registra nuovi voti
            foreach ($request->voti as $votoData) {
                if ($votoData['voto'] !== 'non_votante') {
                    Votazione::create([
                        'ordine_giorno_id' => $ordineGiorno->id,
                        'soggetto_id' => $votoData['soggetto_id'],
                        'unita_immobiliare_id' => $votoData['unita_immobiliare_id'],
                        'voto' => $votoData['voto'],
                        'millesimi_voto' => $votoData['millesimi_voto'],
                        'data_voto' => now(),
                    ]);
                }
            }

            // Calcola risultato
            $risultato = $ordineGiorno->calcolaRisultato();

            DB::commit();
            
            return back()->with('success', 'Votazioni registrate. Esito: ' . $risultato['esito']);
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Errore nella registrazione voti: ' . $e->getMessage()]);
        }
    }

    /**
     * Gestione verbale
     */
    public function verbale(Assemblea $assemblea)
    {
        // Verifica accesso
        if ($assemblea->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        $assemblea->load(['ordineGiorno.delibera', 'presenze.soggetto']);
        $verbale = $assemblea->verbale;

        return view('admin.assemblee.verbale', compact('assemblea', 'verbale'));
    }

    /**
     * Store/Update verbale
     */
    public function storeVerbale(Request $request, Assemblea $assemblea)
    {
        $request->validate([
            'testo_verbale' => 'required|string',
            'allegati.*' => 'nullable|file|max:10240',
        ]);

        // Verifica accesso
        if ($assemblea->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        try {
            $numeroVerbale = $this->generaNumeroVerbale($assemblea);
            
            // Gestione allegati
            $allegati = [];
            if ($request->hasFile('allegati')) {
                foreach ($request->file('allegati') as $file) {
                    $path = $file->store('verbali/allegati', 'public');
                    $allegati[] = [
                        'nome' => $file->getClientOriginalName(),
                        'path' => $path,
                        'size' => $file->getSize(),
                    ];
                }
            }

            $verbale = Verbale::updateOrCreate(
                ['assemblea_id' => $assemblea->id],
                [
                    'numero_verbale' => $numeroVerbale,
                    'testo_verbale' => $request->testo_verbale,
                    'allegati' => $allegati,
                    'data_redazione' => now(),
                    'redatto_da_user_id' => Auth::id(),
                    'stato' => 'definitivo',
                ]
            );

            return back()->with('success', 'Verbale salvato con successo.');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Errore nel salvataggio verbale: ' . $e->getMessage()]);
        }
    }

    /**
     * Invia verbale ai condomini
     */
    public function inviaVerbale(Request $request, Assemblea $assemblea)
    {
        // Verifica accesso
        if ($assemblea->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        $verbale = $assemblea->verbale;
        if (!$verbale) {
            return back()->withErrors(['error' => 'Nessun verbale da inviare.']);
        }

        try {
            // Invia verbale a tutti i condomini
            $inviiRiusciti = $this->inviaVerbaleCondomini($assemblea, $verbale);
            
            $verbale->update([
                'inviato_condomini' => true,
                'data_invio_condomini' => now(),
                'stato' => 'inviato',
            ]);

            return back()->with('success', "Verbale inviato a {$inviiRiusciti} condomini.");
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Errore nell\'invio verbale: ' . $e->getMessage()]);
        }
    }

    /**
     * Registro protocollo
     */
    public function registroProtocollo(Request $request)
    {
        $amministratore_id = Auth::user()->amministratore->id_amministratore ?? null;
        
        $query = RegistroProtocollo::with(['assemblea.stabile', 'soggettoDestinatario', 'creatoDa'])
            ->whereHas('assemblea.stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            });

        // Filtri
        if ($request->filled('tipo_comunicazione')) {
            $query->where('tipo_comunicazione', $request->tipo_comunicazione);
        }
        
        if ($request->filled('data_da')) {
            $query->where('data_invio', '>=', $request->data_da);
        }
        
        if ($request->filled('data_a')) {
            $query->where('data_invio', '<=', $request->data_a);
        }

        $comunicazioni = $query->orderBy('data_invio', 'desc')->paginate(20);

        return view('admin.assemblee.registro-protocollo', compact('comunicazioni'));
    }

    /**
     * Genera numero verbale
     */
    private function generaNumeroVerbale(Assemblea $assemblea)
    {
        $anno = $assemblea->data_prima_convocazione->year;
        $ultimoVerbale = Verbale::whereHas('assemblea', function($q) use ($anno) {
            $q->whereYear('data_prima_convocazione', $anno);
        })->orderBy('numero_verbale', 'desc')->first();

        if ($ultimoVerbale) {
            $numero = intval(substr($ultimoVerbale->numero_verbale, -3)) + 1;
        } else {
            $numero = 1;
        }

        return 'VERB/' . $anno . '/' . str_pad($numero, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Invia verbale ai condomini
     */
    private function inviaVerbaleCondomini(Assemblea $assemblea, Verbale $verbale)
    {
        $unitaImmobiliari = $assemblea->stabile->unitaImmobiliari()->with('proprieta.soggetto')->get();
        $inviiRiusciti = 0;

        foreach ($unitaImmobiliari as $unita) {
            foreach ($unita->proprieta as $proprieta) {
                $soggetto = $proprieta->soggetto;
                
                if ($soggetto->email) {
                    // Simula invio email
                    $numeroProtocollo = RegistroProtocollo::generaNumeroProtocollo();
                    
                    RegistroProtocollo::create([
                        'numero_protocollo' => $numeroProtocollo,
                        'tipo_comunicazione' => 'verbale',
                        'assemblea_id' => $assemblea->id,
                        'soggetto_destinatario_id' => $soggetto->id_soggetto,
                        'oggetto' => "Verbale Assemblea {$assemblea->tipo} del {$assemblea->data_prima_convocazione->format('d/m/Y')}",
                        'contenuto' => $verbale->testo_verbale,
                        'canale' => 'email',
                        'data_invio' => now(),
                        'esito' => 'inviato',
                        'creato_da_user_id' => Auth::id(),
                    ]);
                    
                    $inviiRiusciti++;
                }
            }
        }

        return $inviiRiusciti;
    }
}