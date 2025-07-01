<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VoceSpesa extends Model
{
    use HasFactory;

    protected $table = 'voci_spesa';

    protected $fillable = [
        'stabile_id',
        'codice',
        'descrizione',
        'tipo_gestione',
        'categoria',
        'tabella_millesimale_default_id',
        'ritenuta_acconto_default',
        'attiva',
        'ordinamento',
    ];

    protected $casts = [
        'ritenuta_acconto_default' => 'decimal:2',
        'attiva' => 'boolean',
        'ordinamento' => 'integer',
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
     * Relazione con Tabella Millesimale Default
     */
    public function tabellaMillesimaleDefault()
    {
        return $this->belongsTo(TabellaMillesimale::class, 'tabella_millesimale_default_id');
    }

    /**
     * Scope per voci attive
     */
    public function scopeAttive($query)
    {
        return $query->where('attiva', true);
    }

    /**
     * Scope per tipo gestione
     */
    public function scopeTipoGestione($query, $tipo)
    {
        return $query->where('tipo_gestione', $tipo);
    }

    /**
     * Scope ordinato
     */
    public function scopeOrdinato($query)
    {
        return $query->orderBy('ordinamento')->orderBy('descrizione');
    }
}