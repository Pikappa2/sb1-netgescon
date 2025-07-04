<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stabile extends Model
{
    use HasFactory;

    protected $table = 'stabili';

    protected $fillable = [
        'amministratore_id',
        'denominazione',
        'codice_fiscale',
        'cod_fisc_amministratore',
        'indirizzo',
        'citta',
        'cap',
        'provincia',
        'note',
        'old_id',
        'stato',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con Amministratore
     */
    public function amministratore()
    {
        return $this->belongsTo(Amministratore::class, 'amministratore_id', 'id');
    }

    /**
     * Relazione con UnitaImmobiliari
     */
    public function unitaImmobiliari()
    {
        return $this->hasMany(UnitaImmobiliare::class, 'stabile_id', 'id');
    }

    /**
     * Relazione con Tickets
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'stabile_id', 'id');
    }

    /**
     * Scope per stabili attivi
     */
    public function scopeAttivi($query)
    {
        return $query->where('stato', 'attivo');
    }

    /**
     * Accessor per il nome completo dell'indirizzo
     */
    public function getIndirizzoCompletoAttribute()
    {
        return $this->indirizzo . ', ' . $this->cap . ' ' . $this->citta . 
               ($this->provincia ? ' (' . $this->provincia . ')' : '');
    }
}