<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DettaglioMovimento extends Model
{
    use HasFactory;

    protected $table = 'dettagli_movimenti';

    protected $fillable = [
        'movimento_id',
        'conto_id',
        'voce_spesa_id',
        'tabella_millesimale_id',
        'descrizione',
        'importo_dare',
        'importo_avere',
        'note',
    ];

    protected $casts = [
        'importo_dare' => 'decimal:2',
        'importo_avere' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con Movimento Contabile
     */
    public function movimento()
    {
        return $this->belongsTo(MovimentoContabile::class, 'movimento_id');
    }

    /**
     * Relazione con Piano Conti
     */
    public function conto()
    {
        return $this->belongsTo(PianoConti::class, 'conto_id');
    }

    /**
     * Relazione con Voce Spesa
     */
    public function voceSpesa()
    {
        return $this->belongsTo(VoceSpesa::class, 'voce_spesa_id');
    }

    /**
     * Relazione con Tabella Millesimale
     */
    public function tabellaMillesimale()
    {
        return $this->belongsTo(TabellaMillesimale::class, 'tabella_millesimale_id');
    }

    /**
     * Scope per dare
     */
    public function scopeDare($query)
    {
        return $query->where('importo_dare', '>', 0);
    }

    /**
     * Scope per avere
     */
    public function scopeAvere($query)
    {
        return $query->where('importo_avere', '>', 0);
    }
}