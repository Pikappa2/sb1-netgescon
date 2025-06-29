<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bilancio;
use App\Models\ScritturaBilancio;
use App\Models\Conguaglio;
use App\Models\Quadratura;
use App\Models\Stabile;
use App\Models\Gestione;
use App\Models\PianoConto;
use App\Models\MovimentoContabile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BilancioController extends Controller
{
    /**
     * Dashboard bilanci
     */
    public function index()
    {
        $amministratore_id = Auth::user()->amministratore->id_amministratore ?? null;
        
        $bilanci = Bilancio::with(['stabile', 'gestione', 'approvatoDa'])
            ->whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })
            ->orderBy('anno_esercizio', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Statistiche
        $stats = [
            'bilanci_aperti' => Bilancio::whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })->whereIn('stato', ['bozza', 'provvisorio'])->count(),
            
            'bilanci_approvati' => Bilancio::whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })->where('stato', 'approvato')->count(),
            
            'conguagli_da_pagare' => Conguaglio::whereHas('bilancio.stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })->where('stato', 'calcolato')->count(),
            
            'totale_avanzi' => Bilancio::whereHas('stabile', function($q) use ($amministratore_id) {
                $q->where('amministratore_id', $amministratore_id);
            })->where('risultato_gestione', '>', 0)->sum('risultato_gestione'),
        ];

        return view('admin.bilanci.index', compact('bilanci', 'stats'));
    }

    /**
     * Form creazione bilancio
     */
    public function create()
    {
        $amministratore_id = Auth::user()->amministratore->id_amministratore ?? null;
        $stabili = Stabile::where('amministratore_id', $amministratore_id)->attivi()->get();
        
        return view('admin.bilanci.create', compact('stabili'));
    }

    /**
     * Store bilancio
     */
    public function store(Request $request)
    {
        $request->validate([
            'stabile_id' => 'required|exists:stabili,id_stabile',
            'gestione_id' => 'required|exists:gestioni,id_gestione',
            'anno_esercizio' => 'required|integer|min:2020|max:2030',
            'data_inizio_esercizio' => 'required|date',
            'data_fine_esercizio' => 'required|date|after:data_inizio_esercizio',
            'tipo_gestione' => 'required|in:ordinaria,riscaldamento,straordinaria,acqua,altro',
            'descrizione' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $bilancio = Bilancio::create([
                'stabile_id' => $request->stabile_id,
                'gestione_id' => $request->gestione_id,
                'anno_esercizio' => $request->anno_esercizio,
                'data_inizio_esercizio' => $request->data_inizio_esercizio,
                'data_fine_esercizio' => $request->data_fine_esercizio,
                'tipo_gestione' => $request->tipo_gestione,
                'descrizione' => $request->descrizione,
                'stato' => 'bozza',
                'versione' => 1,
            ]);

            // Importa movimenti contabili del periodo
            $this->importaMovimentiContabili($bilancio);

            DB::commit();
            
            return redirect()->route('admin.bilanci.show', $bilancio)
                           ->with('success', 'Bilancio creato con successo.');
                           
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Errore durante la creazione: ' . $e->getMessage()]);
        }
    }

    /**
     * Visualizza bilancio
     */
    public function show(Bilancio $bilancio)
    {
        // Verifica accesso
        if ($bilancio->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        $bilancio->load([
            'stabile', 
            'gestione',
            'scritture.dettagli.conto',
            'conguagli.unitaImmobiliare',
            'quadrature',
            'rimborsiAssicurativi'
        ]);

        // Calcola totali aggiornati
        $bilancio->calcolaTotali();

        return view('admin.bilanci.show', compact('bilancio'));
    }

    /**
     * Importa movimenti contabili nel bilancio
     */
    private function importaMovimentiContabili(Bilancio $bilancio)
    {
        $movimenti = MovimentoContabile::where('stabile_id', $bilancio->stabile_id)
            ->where('gestione_id', $bilancio->gestione_id)
            ->whereBetween('data_registrazione', [
                $bilancio->data_inizio_esercizio,
                $bilancio->data_fine_esercizio
            ])
            ->with('dettagli')
            ->get();

        foreach ($movimenti as $movimento) {
            $this->creaScritturaDaMovimento($bilancio, $movimento);
        }
    }

    /**
     * Crea scrittura bilancio da movimento contabile
     */
    private function creaScritturaDaMovimento(Bilancio $bilancio, MovimentoContabile $movimento)
    {
        $scrittura = ScritturaBilancio::create([
            'bilancio_id' => $bilancio->id,
            'numero_scrittura' => $this->generaNumeroScrittura($bilancio),
            'data_scrittura' => $movimento->data_registrazione,
            'descrizione' => $movimento->descrizione,
            'tipo_scrittura' => 'gestione',
            'importo_totale' => $movimento->importo_totale,
            'movimento_contabile_id' => $movimento->id,
            'creato_da_user_id' => Auth::id(),
        ]);

        // Crea dettagli in partita doppia
        foreach ($movimento->dettagli as $dettaglio) {
            $scrittura->dettagli()->create([
                'conto_id' => $dettaglio->conto_id ?? $this->getContoDefault($movimento->tipo_movimento),
                'importo_dare' => $dettaglio->importo_dare,
                'importo_avere' => $dettaglio->importo_avere,
                'descrizione_dettaglio' => $dettaglio->descrizione,
            ]);
        }

        return $scrittura;
    }

    /**
     * Calcola conguagli
     */
    public function calcolaConguagli(Bilancio $bilancio)
    {
        // Verifica accesso
        if ($bilancio->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            $bilancio->calcolaConguagli();
            
            DB::commit();
            return back()->with('success', 'Conguagli calcolati con successo.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Errore nel calcolo conguagli: ' . $e->getMessage()]);
        }
    }

    /**
     * Genera rate conguaglio
     */
    public function generaRateConguaglio(Request $request, Bilancio $bilancio)
    {
        $request->validate([
            'conguaglio_ids' => 'required|array',
            'numero_rate' => 'required|integer|min:1|max:12',
            'data_inizio' => 'required|date',
        ]);

        // Verifica accesso
        if ($bilancio->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        DB::beginTransaction();
        try {
            $dataInizio = Carbon::parse($request->data_inizio);
            $rateGenerate = 0;

            foreach ($request->conguaglio_ids as $conguaglioId) {
                $conguaglio = Conguaglio::findOrFail($conguaglioId);
                
                if ($conguaglio->bilancio_id !== $bilancio->id) {
                    continue;
                }

                $rate = $conguaglio->generaRate($request->numero_rate, $dataInizio, Auth::id());
                $rateGenerate += $rate->count();
            }

            DB::commit();
            return back()->with('success', "Generate {$rateGenerate} rate di conguaglio.");
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Errore nella generazione rate: ' . $e->getMessage()]);
        }
    }

    /**
     * Quadratura bilancio
     */
    public function quadratura(Request $request, Bilancio $bilancio)
    {
        $request->validate([
            'data_quadratura' => 'required|date',
            'saldo_banca_effettivo' => 'required|numeric',
        ]);

        // Verifica accesso
        if ($bilancio->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        // Calcola saldo contabile
        $saldoContabile = $this->calcolaSaldoContabile($bilancio, $request->data_quadratura);
        
        // Calcola totali crediti/debiti
        $totaleCrediti = $bilancio->conguagli()->where('tipo_conguaglio', 'a_credito')->sum('conguaglio_dovuto');
        $totaleDebiti = $bilancio->conguagli()->where('tipo_conguaglio', 'a_debito')->sum('conguaglio_dovuto');
        
        // Calcola rate
        $totaleRateEmesse = $this->calcolaTotaleRateEmesse($bilancio);
        $totaleRateIncassate = $this->calcolaTotaleRateIncassate($bilancio);

        $differenza = $request->saldo_banca_effettivo - $saldoContabile;

        $quadratura = Quadratura::create([
            'bilancio_id' => $bilancio->id,
            'data_quadratura' => $request->data_quadratura,
            'saldo_banca_effettivo' => $request->saldo_banca_effettivo,
            'saldo_contabile_calcolato' => $saldoContabile,
            'differenza' => $differenza,
            'totale_crediti_condomini' => $totaleCrediti,
            'totale_debiti_condomini' => $totaleDebiti,
            'totale_rate_emesse' => $totaleRateEmesse,
            'totale_rate_incassate' => $totaleRateIncassate,
            'quadratura_ok' => abs($differenza) < 0.01,
            'verificato_da_user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Quadratura eseguita con successo.');
    }

    /**
     * Chiusura esercizio
     */
    public function chiusuraEsercizio(Request $request, Bilancio $bilancio)
    {
        $request->validate([
            'motivo_chiusura' => 'required|string',
        ]);

        // Verifica accesso e stato
        if ($bilancio->stabile->amministratore_id !== Auth::user()->amministratore->id_amministratore ?? null) {
            abort(403);
        }

        if ($bilancio->stato !== 'approvato') {
            return back()->withErrors(['error' => 'Il bilancio deve essere approvato prima della chiusura.']);
        }

        DB::beginTransaction();
        try {
            // Genera scritture di chiusura
            $bilancio->generaScritture ChiusuraEsercizio(Auth::id());
            
            // Aggiorna stato bilancio
            $bilancio->update([
                'stato' => 'chiuso',
                'data_chiusura' => now(),
                'chiuso_da_user_id' => Auth::id(),
            ]);

            DB::commit();
            return back()->with('success', 'Esercizio chiuso con successo.');
            
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Errore nella chiusura: ' . $e->getMessage()]);
        }
    }

    /**
     * Calcola saldo contabile alla data
     */
    private function calcolaSaldoContabile(Bilancio $bilancio, $data)
    {
        // Implementazione calcolo saldo contabile
        return $bilancio->totale_entrate - $bilancio->totale_uscite;
    }

    /**
     * Calcola totale rate emesse
     */
    private function calcolaTotaleRateEmesse(Bilancio $bilancio)
    {
        // Implementazione calcolo rate emesse
        return 0; // Placeholder
    }

    /**
     * Calcola totale rate incassate
     */
    private function calcolaTotaleRateIncassate(Bilancio $bilancio)
    {
        // Implementazione calcolo rate incassate
        return 0; // Placeholder
    }

    /**
     * Genera numero scrittura
     */
    private function generaNumeroScrittura(Bilancio $bilancio)
    {
        $ultimaScrittura = ScritturaBilancio::where('bilancio_id', $bilancio->id)
            ->orderBy('numero_scrittura', 'desc')
            ->first();

        if ($ultimaScrittura) {
            $numero = intval(substr($ultimaScrittura->numero_scrittura, -4)) + 1;
        } else {
            $numero = 1;
        }

        return 'SCR/' . $bilancio->anno_esercizio . '/' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get conto default per tipo movimento
     */
    private function getContoDefault($tipoMovimento)
    {
        // Implementazione per ottenere conto default
        return 1; // Placeholder
    }
}