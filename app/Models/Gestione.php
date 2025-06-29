<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gestione extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gestioni';
    protected $primaryKey = 'id_gestione';

    protected $fillable = [
        'stabile_id',
        'anno_gestione',
        'tipo_gestione',
        'data_inizio',
        'data_fine',
        'descrizione',
        'stato',
        'preventivo_approvato',
        'data_approvazione',
        'note',
    ];

    protected $casts = [
        'data_inizio' => 'date',
        'data_fine' => 'date',
        'data_approvazione' => 'date',
        'preventivo_approvato' => 'boolean',
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
     * Relazione con Preventivi
     */
    public function preventivi()
    {
        return $this->hasMany(Preventivo::class, 'gestione_id', 'id_gestione');
    }

    /**
     * Relazione con Movimenti Contabili
     */
    public function movimentiContabili()
    {
        return $this->hasMany(MovimentoContabile::class, 'gestione_id', 'id_gestione');
    }

    /**
     * Relazione con Rate
     */
    public function rate()
    {
        return $this->hasMany(Rata::class, 'gestione_id', 'id_gestione');
    }

    /**
     * Scope per gestioni attive
     */
    public function scopeAttive($query)
    {
        return $query->where('stato', 'attiva');
    }

    /**
     * Scope per tipo gestione
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_gestione', $tipo);
    }

    /**
     * Accessor per il nome completo della gestione
     */
    public function getNomeCompletoAttribute()
    {
        return $this->anno_gestione . ' - ' . ucfirst($this->tipo_gestione) . 
               ($this->descrizione ? ' (' . $this->descrizione . ')' : '');
    }
}