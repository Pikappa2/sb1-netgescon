<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Preventivo;
use App\Models\VocePreventivo;
use App\Models\Stabile;
use App\Models\TabellaMillesimale;
use App\Models\VoceSpesa;
use App\Models\LogModificaPreventivo;
use App\Models\PianificazioneSpesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PreventivoController extends Controller
{
    /**
     * Dashboard preventivi
     */
    public function index()
    {
        $amministratore_id = Auth::user()->amministratore->id_amministratore ?? null;
        
        $preventivi = Preventivo::with(['stabile', 'approvatoDa'])
            ->whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })
            ->orderBy('anno_gestione', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Statistiche
        $stats = [
            'preventivi_bozza' => Preventivo::whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })->where('stato', 'bozza')->count(),
            
            'preventivi_approvati' => Preventivo::whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })->where('stato', 'approvato')->count(),
            
            'importo_totale_anno' => Preventivo::whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })->where('anno_gestione', date('Y'))->sum('importo_totale'),
        ];

        return view('admin.preventivi.index', compact('preventivi', 'stats'));
    }

    /**
     * Form creazione preventivo
     */
    public function create()
    {
        $amministratore_id = Auth::user()->amministratore->id_amministratore ?? null;
        $stabili = Stabile::where('amministratore_id', $amministratore_id)->attivi()->get();
        
        return view('admin.preventivi.create', compact('stabili'));
    }

    /**
     * Store preventivo
     */
    public function store(Request $request)
    {
        $request->validate([
            'stabile_id' => 'required|exists:stabili,id_stabile',
            'anno_gestione' => 'required|integer|min:2020|max:2030',
            'tipo_gestione' => 'required|in:ordinaria,riscaldamento,straordinaria,acqua,altro',
            'descrizione' => 'required|string|max:255',
            'voci' => 'required|array|min:1',
            'voci.*.codice' => 'required|string|max:20',
            'voci.*.descrizione' => 'required|string|max:255',
            'voci.*.importo' => 'required|numeric|min:0',
            'voci.*.tabella_millesimale_id' => 'nullable|exists:tabelle_millesimali,id',
        ]);

        DB::beginTransaction();
        try {
            $preventivo = Preventivo::create([
                'stabile_id' => $request->stabile_id,
                'anno_gestione' => $request->anno_gestione,
                'tipo_gestione' => $request->tipo_gestione,
                'descrizione' => $request->descrizione,
                'stato' => 'bozza',
                'data_creazione' => now(),
                'versione' => 1,
            ]);

            $importoTotale = 0;
            foreach ($request->voci as $index => $voceData) {
                $voce = VocePreventivo::create([
                    'preventivo_id' => $preventivo->id,
                    'codice' => $voceData['codice'],
                    'descrizione' => $voceData['descrizione'],
                    'importo_preventivato' => $voceData['importo'],
                    'tabella_millesimale_id' => $voceData['tabella_millesimale_id'] ?? null,
                    'ordinamento' => $index + 1,
                ]);

                // Calcola ripartizione se specificata tabella millesimale
                if ($voce->tabella_millesimale_id) {
                    $voce->calcolaRipartizione();
                }

                $importoTotale += $voceData['importo'];
            }

            $preventivo->update(['importo_totale' => $importoTotale]);

            // Log creazione
            LogModificaPreventivo::create([
                'entita' => 'preventivo',
                'entita_id' => $preventivo->id,
                'versione_precedente' => 0,
                'versione_nuova' => 1,
                'utente_id' => Auth::id(),
                'tipo_operazione' => 'create',
                'motivo' => 'Creazione preventivo',
                'dati_nuovi' => $preventivo->toArray(),
            ]);

            DB::commit();
            
            return redirect()->route('admin.preventivi.show', $preventivo)
                           ->with('success', 'Preventivo creato con successo.');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Errore durante la creazione: ' . $e->getMessage()]);
        }
    }

    /**
     * Visualizza preventivo
     */
    public function show(Preventivo $preventivo)
    {
        // Verifica accesso
        if ($preventivo->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        $preventivo->load([
            'stabile', 
            'voci.tabellaMillesimale', 
            'voci.ripartizioni.unitaImmobiliare',
            'rate',
            'logModifiche.utente'
        ]);

        return view('admin.preventivi.show', compact('preventivo'));
    }

    /**
     * Approva preventivo
     */
    public function approva(Request $request, Preventivo $preventivo)
    {
        $request->validate([
            'motivo' => 'required|string',
        ]);

        // Verifica accesso
        if ($preventivo->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        $preventivo->creaVersione($request->motivo, Auth::id());
        
        $preventivo->update([
            'stato' => 'approvato',
            'data_approvazione' => now(),
            'approvato_da_user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Preventivo approvato con successo.');
    }

    /**
     * Genera rate dal preventivo
     */
    public function generaRate(Request $request, Preventivo $preventivo)
    {
        $request->validate([
            'numero_rate' => 'required|integer|min:1|max:12',
            'data_inizio' => 'required|date',
        ]);

        // Verifica accesso e stato
        if ($preventivo->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        if ($preventivo->stato !== 'approvato') {
            return back()->withErrors(['error' => 'Il preventivo deve essere approvato prima di generare le rate.']);
        }

        $dataInizio = Carbon::parse($request->data_inizio);
        $rate = $preventivo->generaRate($request->numero_rate, $dataInizio, Auth::id());

        return back()->with('success', 'Generate ' . count($rate) . ' rate con successo.');
    }

    /**
     * Dashboard pianificazione
     */
    public function pianificazione()
    {
        $amministratore_id = Auth::user()->amministratore->id_amministratore ?? null;
        
        // Spese in scadenza
        $speseInScadenza = PianificazioneSpesa::with('stabile')
            ->whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })
            ->where('data_scadenza_prevista', '<=', now()->addDays(30))
            ->where('stato', 'pianificata')
            ->orderBy('data_scadenza_prevista')
            ->get();

        // Cashflow previsto (prossimi 6 mesi)
        $cashflow = $this->calcolaCashflow($amministratore_id);

        return view('admin.preventivi.pianificazione', compact('speseInScadenza', 'cashflow'));
    }

    /**
     * Calcola cashflow previsto
     */
    private function calcolaCashflow($amministratore_id)
    {
        $mesi = [];
        
        for ($i = 0; $i < 6; $i++) {
            $dataInizio = now()->startOfMonth()->addMonths($i);
            $dataFine = $dataInizio->copy()->endOfMonth();
            
            // Entrate previste (rate)
            $entrate = DB::table('rate')
                ->join('preventivi', 'rate.preventivo_id', '=', 'preventivi.id')
                ->join('stabili', 'preventivi.stabile_id', '=', 'stabili.id_stabile')
                ->where('stabili.amministratore_id', $amministratore_id)
                ->whereBetween('rate.data_scadenza', [$dataInizio, $dataFine])
                ->sum('rate.importo_totale');

            // Uscite previste (spese pianificate)
            $uscite = PianificazioneSpesa::whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })
            ->whereBetween('data_scadenza_prevista', [$dataInizio, $dataFine])
            ->sum('importo_previsto');

            $mesi[] = [
                'mese' => $dataInizio->format('M Y'),
                'entrate' => $entrate,
                'uscite' => $uscite,
                'saldo' => $entrate - $uscite,
            ];
        }

        return $mesi;
    }

    /**
     * Storico modifiche (stile GIT)
     */
    public function storicoModifiche(Preventivo $preventivo)
    {
        // Verifica accesso
        if ($preventivo->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        $modifiche = LogModificaPreventivo::with('utente')
            ->where(function($q) use ($preventivo) {
                $q->where('entita', 'preventivo')->where('entita_id', $preventivo->id)
                  ->orWhereIn('entita_id', $preventivo->voci->pluck('id'))
                  ->where('entita', 'voce');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.preventivi.storico', compact('preventivo', 'modifiche'));
    }
}