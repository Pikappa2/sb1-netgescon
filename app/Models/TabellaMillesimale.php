<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TabellaMillesimale extends Model
{
    use HasFactory;

    protected $table = 'tabelle_millesimali';

    protected $fillable = [
        'stabile_id',
        'nome_tabella_millesimale',
        'descrizione',
        'tipo',
        'attiva',
        'data_approvazione',
        'ordinamento',
    ];

    protected $casts = [
        'attiva' => 'boolean',
        'data_approvazione' => 'date',
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
     * Relazione con Dettagli Millesimi
     */
    public function dettagli()
    {
        return $this->hasMany(DettaglioTabellaMillesimale::class, 'tabella_millesimale_id');
    }

    /**
     * Scope per tabelle attive
     */
    public function scopeAttive($query)
    {
        return $query->where('attiva', true);
    }

    /**
     * Scope ordinato
     */
    public function scopeOrdinato($query)
    {
        return $query->orderBy('ordinamento')->orderBy('nome_tabella_millesimale');
    }

    /**
     * Calcola il totale millesimi
     */
    public function getTotaleMillesimiAttribute()
    {
        return $this->dettagli()->sum('millesimi');
    }
}