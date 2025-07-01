<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rata extends Model
{
    use HasFactory;

    protected $table = 'rate';

    protected $fillable = [
        'gestione_id',
        'unita_immobiliare_id',
        'soggetto_id',
        'numero_rata',
        'descrizione',
        'importo',
        'data_scadenza',
        'data_pagamento',
        'importo_pagato',
        'stato',
        'tipo_rata',
        'note',
    ];

    protected $casts = [
        'importo' => 'decimal:2',
        'importo_pagato' => 'decimal:2',
        'data_scadenza' => 'date',
        'data_pagamento' => 'date',
        'numero_rata' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con Gestione
     */
    public function gestione()
    {
        return $this->belongsTo(Gestione::class, 'gestione_id', 'id_gestione');
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
     * Scope per stato
     */
    public function scopeStato($query, $stato)
    {
        return $query->where('stato', $stato);
    }

    /**
     * Scope per scadute
     */
    public function scopeScadute($query)
    {
        return $query->where('data_scadenza', '<', now())
                    ->where('stato', '!=', 'pagata');
    }

    /**
     * Scope per in scadenza
     */
    public function scopeInScadenza($query, $giorni = 30)
    {
        return $query->whereBetween('data_scadenza', [now(), now()->addDays($giorni)])
                    ->where('stato', '!=', 'pagata');
    }

    /**
     * Accessor per importo residuo
     */
    public function getImportoResiduoAttribute()
    {
        return $this->importo - $this->importo_pagato;
    }
}