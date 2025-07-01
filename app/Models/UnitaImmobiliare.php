<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitaImmobiliare extends Model
{
    use HasFactory;

    protected $table = 'unita_immobiliari';
    protected $primaryKey = 'id_unita';

    protected $fillable = [
        'stabile_id',
        'interno',
        'scala',
        'piano',
        'fabbricato',
        'millesimi_proprieta',
        'categoria_catastale',
        'superficie',
        'vani',
        'indirizzo',
        'note',
    ];

    protected $casts = [
        'millesimi_proprieta' => 'decimal:4',
        'superficie' => 'decimal:2',
        'vani' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con Stabile
     */
    public function stabile()
    {
        return $this->belongsTo(Stabile::class, 'stabile_id', 'id_stabile');
    }

    /**
     * Relazione con Tickets
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'unita_immobiliare_id', 'id_unita');
    }

    /**
     * Relazione con Proprietà
     */
    public function proprieta()
    {
        return $this->hasMany(\App\Models\Proprieta::class, 'unita_immobiliare_id', 'id_unita');
    }

    /**
     * Accessor per identificazione completa dell'unità
     */
    public function getIdentificazioneCompiletaAttribute()
    {
        $parts = [];
        if ($this->fabbricato) $parts[] = 'Fabb. ' . $this->fabbricato;
        if ($this->scala) $parts[] = 'Scala ' . $this->scala;
        if ($this->piano) $parts[] = 'Piano ' . $this->piano;
        if ($this->interno) $parts[] = 'Int. ' . $this->interno;
        return implode(', ', $parts) ?: 'N/A';
    }

    /**
     * Accessor per l'indirizzo completo
     */
    public function getIndirizzoCompletoAttribute()
    {
        return $this->indirizzo ?: $this->stabile->indirizzo_completo;
    }
}