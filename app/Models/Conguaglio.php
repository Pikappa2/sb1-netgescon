<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conguaglio extends Model
{
    use HasFactory;

    protected $fillable = [
        'bilancio_id',
        'unita_immobiliare_id',
        'soggetto_id',
        'totale_rate_pagate',
        'totale_spese_effettive',
        'conguaglio_dovuto',
        'tipo_conguaglio',
        'stato',
        'data_calcolo',
        'data_pagamento',
        'importo_pagato',
        'note',
    ];

    protected $casts = [
        'totale_rate_pagate' => 'decimal:2',
        'totale_spese_effettive' => 'decimal:2',
        'conguaglio_dovuto' => 'decimal:2',
        'data_calcolo' => 'date',
        'data_pagamento' => 'date',
        'importo_pagato' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con Bilancio
     */
    public function bilancio()
    {
        return $this->belongsTo(Bilancio::class, 'bilancio_id');
    }

    /**
     * Relazione con Unità Immobiliare
     */
    public function unitaImmobiliare()
    {
        return $this->belongsTo(UnitaImmobiliare::class, 'unita_immobiliare_id', 'id_unita');
    }

    /**
     * Relazione con Soggetto
     */
    public function soggetto()
    {
        return $this->belongsTo(Soggetto::class, 'soggetto_id', 'id_soggetto');
    }

    /**
     * Relazione con Rate Conguaglio
     */
    public function rateConguaglio()
    {
        return $this->hasMany(RataConguaglio::class, 'conguaglio_id');
    }

    /**
     * Scope per tipo conguaglio
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_conguaglio', $tipo);
    }

    /**
     * Scope per stato
     */
    public function scopeStato($query, $stato)
    {
        return $query->where('stato', $stato);
    }

    /**
     * Genera rate per conguaglio
     */
    public function generaRate($numeroRate, $dataInizio, $userId)
    {
        if ($this->tipo_conguaglio === 'pareggio') {
            return collect();
        }

        $importoPerRata = $this->conguaglio_dovuto / $numeroRate;
        $rate = collect();

        for ($i = 1; $i <= $numeroRate; $i++) {
            $dataScadenza = $dataInizio->copy()->addMonths($i - 1);
            $numeroRata = $this->generaNumeroRata($i);

            $rata = RataConguaglio::create([
                'conguaglio_id' => $this->id,
                'numero_rata' => $numeroRata,
                'descrizione' => "Conguaglio rata {$i} di {$numeroRate} - " . $this->unitaImmobiliare->identificazione_completa,
                'data_scadenza' => $dataScadenza,
                'importo_rata' => $importoPerRata,
                'rateizzato' => $numeroRate > 1,
                'numero_rate_totali' => $numeroRate,
                'numero_rata_corrente' => $i,
            ]);

            $rate->push($rata);
        }

        return $rate;
    }

    /**
     * Genera numero rata univoco
     */
    private function generaNumeroRata($numeroRata)
    {
        $prefisso = $this->tipo_conguaglio === 'a_credito' ? 'RIMB' : 'CONG';
        return $prefisso . '/' . $this->bilancio->anno_esercizio . '/' . 
               $this->unita_immobiliare_id . '/' . str_pad($numeroRata, 2, '0', STR_PAD_LEFT);
    }

    /**
     * Calcola importo residuo
     */
    public function getImportoResiduoAttribute()
    {
        return $this->conguaglio_dovuto - $this->importo_pagato;
    }
}