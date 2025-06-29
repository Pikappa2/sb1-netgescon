<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MovimentoContabile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'movimenti_contabili';

    protected $fillable = [
        'stabile_id',
        'gestione_id',
        'fornitore_id',
        'documento_id',
        'protocollo',
        'data_registrazione',
        'data_documento',
        'numero_documento',
        'descrizione',
        'tipo_movimento',
        'importo_totale',
        'ritenuta_acconto',
        'importo_netto',
        'stato',
        'note',
    ];

    protected $casts = [
        'data_registrazione' => 'date',
        'data_documento' => 'date',
        'importo_totale' => 'decimal:2',
        'ritenuta_acconto' => 'decimal:2',
        'importo_netto' => 'decimal:2',
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
     * Relazione con Fornitore
     */
    public function fornitore()
    {
        return $this->belongsTo(Fornitore::class, 'fornitore_id', 'id_fornitore');
    }

    /**
     * Relazione con Documento
     */
    public function documento()
    {
        return $this->belongsTo(Documento::class, 'documento_id');
    }

    /**
     * Relazione con Dettagli Movimento (partita doppia)
     */
    public function dettagli()
    {
        return $this->hasMany(DettaglioMovimento::class, 'movimento_id');
    }

    /**
     * Scope per tipo movimento
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_movimento', $tipo);
    }

    /**
     * Scope per stato
     */
    public function scopeStato($query, $stato)
    {
        return $query->where('stato', $stato);
    }
}