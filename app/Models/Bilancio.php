<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bilancio extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'stabile_id',
        'gestione_id',
        'anno_esercizio',
        'data_inizio_esercizio',
        'data_fine_esercizio',
        'tipo_gestione',
        'descrizione',
        'stato',
        'totale_entrate',
        'totale_uscite',
        'risultato_gestione',
        'data_approvazione',
        'approvato_da_user_id',
        'data_chiusura',
        'chiuso_da_user_id',
        'note',
        'versione',
    ];

    protected $casts = [
        'anno_esercizio' => 'integer',
        'data_inizio_esercizio' => 'date',
        'data_fine_esercizio' => 'date',
        'totale_entrate' => 'decimal:2',
        'totale_uscite' => 'decimal:2',
        'risultato_gestione' => 'decimal:2',
        'data_approvazione' => 'date',
        'data_chiusura' => 'date',
        'versione' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relazione con Stabile
     */
    public function stabile()
    {
        return $this->belongsTo(Stabile::class, 'stabile_id', 'id_stabile');
    }

    /**
     * Relazione con Gestione
     */
    public function gestione()
    {
        return $this->belongsTo(Gestione::class, 'gestione_id', 'id_gestione');
    }

    /**
     * Relazione con Scritture
     */
    public function scritture()
    {
        return $this->hasMany(ScritturaBilancio::class, 'bilancio_id');
    }

    /**
     * Relazione con Conguagli
     */
    public function conguagli()
    {
        return $this->hasMany(Conguaglio::class, 'bilancio_id');
    }

    /**
     * Relazione con Quadrature
     */
    public function quadrature()
    {
        return $this->hasMany(Quadratura::class, 'bilancio_id');
    }

    /**
     * Relazione con Rimborsi Assicurativi
     */
    public function rimborsiAssicurativi()
    {
        return $this->hasMany(RimborsoAssicurativo::class, 'bilancio_id');
    }

    /**
     * Relazione con User che ha approvato
     */
    public function approvatoDa()
    {
        return $this->belongsTo(User::class, 'approvato_da_user_id');
    }

    /**
     * Relazione con User che ha chiuso
     */
    public function chiusoDa()
    {
        return $this->belongsTo(User::class, 'chiuso_da_user_id');
    }

    /**
     * Scope per stato
     */
    public function scopeStato($query, $stato)
    {
        return $query->where('stato', $stato);
    }

    /**
     * Calcola totali da scritture
     */
    public function calcolaTotali()
    {
        $entrate = $this->scritture()
            ->whereHas('dettagli', function($q) {
                $q->whereHas('conto', function($c) {
                    $c->where('tipo_conto', 'ricavo');
                });
            })
            ->sum('importo_totale');

        $uscite = $this->scritture()
            ->whereHas('dettagli', function($q) {
                $q->whereHas('conto', function($c) {
                    $c->where('tipo_conto', 'costo');
                });
            })
            ->sum('importo_totale');

        $this->update([
            'totale_entrate' => $entrate,
            'totale_uscite' => $uscite,
            'risultato_gestione' => $entrate - $uscite,
        ]);

        return $this;
    }

    /**
     * Calcola conguagli per tutte le unità
     */
    public function calcolaConguagli()
    {
        $unitaImmobiliari = $this->stabile->unitaImmobiliari;

        foreach ($unitaImmobiliari as $unita) {
            $this->calcolaConguaglioUnita($unita);
        }

        return $this;
    }

    /**
     * Calcola conguaglio per singola unità
     */
    public function calcolaConguaglioUnita($unitaImmobiliare)
    {
        // Calcola totale rate pagate
        $totaleRatePagate = $this->calcolaTotaleRatePagate($unitaImmobiliare);
        
        // Calcola totale spese effettive ripartite
        $totaleSpese = $this->calcolaTotaleSpese($unitaImmobiliare);
        
        // Calcola conguaglio
        $conguaglioDovuto = $totaleRatePagate - $totaleSpese;
        
        $tipoConguaglio = $conguaglioDovuto > 0 ? 'a_credito' : 
                         ($conguaglioDovuto < 0 ? 'a_debito' : 'pareggio');

        // Trova il soggetto principale dell'unità
        $soggetto = $unitaImmobiliare->proprieta()
            ->where('tipo_diritto', 'proprietario')
            ->first()?->soggetto;

        if (!$soggetto) {
            return null;
        }

        return Conguaglio::updateOrCreate(
            [
                'bilancio_id' => $this->id,
                'unita_immobiliare_id' => $unitaImmobiliare->id_unita,
                'soggetto_id' => $soggetto->id_soggetto,
            ],
            [
                'totale_rate_pagate' => $totaleRatePagate,
                'totale_spese_effettive' => $totaleSpese,
                'conguaglio_dovuto' => abs($conguaglioDovuto),
                'tipo_conguaglio' => $tipoConguaglio,
                'data_calcolo' => now(),
            ]
        );
    }

    /**
     * Calcola totale rate pagate per unità
     */
    private function calcolaTotaleRatePagate($unitaImmobiliare)
    {
        // Implementazione del calcolo rate pagate
        // Collegamento con tabella rate e incassi
        return 0; // Placeholder
    }

    /**
     * Calcola totale spese per unità
     */
    private function calcolaTotaleSpese($unitaImmobiliare)
    {
        return $this->scritture()
            ->whereHas('ripartizioni', function($q) use ($unitaImmobiliare) {
                $q->where('unita_immobiliare_id', $unitaImmobiliare->id_unita);
            })
            ->sum('quota_finale');
    }

    /**
     * Genera scritture di chiusura
     */
    public function generaScritture ChiusuraEsercizio($userId)
    {
        // Scrittura di chiusura costi
        $totaleCosti = $this->scritture()
            ->whereHas('dettagli.conto', function($q) {
                $q->where('tipo_conto', 'costo');
            })
            ->sum('importo_totale');

        // Scrittura di chiusura ricavi
        $totaleRicavi = $this->scritture()
            ->whereHas('dettagli.conto', function($q) {
                $q->where('tipo_conto', 'ricavo');
            })
            ->sum('importo_totale');

        // Crea scrittura di chiusura
        $scritturaChiusura = ScritturaBilancio::create([
            'bilancio_id' => $this->id,
            'numero_scrittura' => $this->generaNumeroScrittura('CHIUS'),
            'data_scrittura' => $this->data_fine_esercizio,
            'descrizione' => 'Chiusura esercizio ' . $this->anno_esercizio,
            'tipo_scrittura' => 'chiusura',
            'importo_totale' => abs($totaleRicavi - $totaleCosti),
            'creato_da_user_id' => $userId,
        ]);

        return $scritturaChiusura;
    }

    /**
     * Genera numero scrittura progressivo
     */
    private function generaNumeroScrittura($prefisso = 'SCR')
    {
        $ultimaScrittura = $this->scritture()
            ->where('numero_scrittura', 'like', $prefisso . '%')
            ->orderBy('numero_scrittura', 'desc')
            ->first();

        if ($ultimaScrittura) {
            $numero = intval(substr($ultimaScrittura->numero_scrittura, -4)) + 1;
        } else {
            $numero = 1;
        }

        return $prefisso . '/' . $this->anno_esercizio . '/' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }
}