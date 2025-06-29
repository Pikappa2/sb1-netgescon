<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DettaglioTabellaMillesimale extends Model
{
    use HasFactory;

    protected $table = 'dettagli_tabelle_millesimali';

    protected $fillable = [
        'tabella_millesimale_id',
        'unita_immobiliare_id',
        'millesimi',
        'note',
    ];

    protected $casts = [
        'millesimi' => 'decimal:4',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con Tabella Millesimale
     */
    public function tabellaMillesimale()
    {
        return $this->belongsTo(TabellaMillesimale::class, 'tabella_millesimale_id');
    }

    /**
     * Relazione con Unità Immobiliare
     */
    public function unitaImmobiliare()
    {
        return $this->belongsTo(UnitaImmobiliare::class, 'unita_immobiliare_id', 'id_unita');
    }
}