<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fornitore extends Model
{
    use HasFactory;

    protected $table = 'fornitori';

    protected $fillable = [
        'amministratore_id',
        'ragione_sociale',
        'partita_iva',
        'codice_fiscale',
        'indirizzo',
        'cap',
        'citta',
        'provincia',
        'email',
        'pec',
        'telefono',
        'old_id',
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
     * Relazione con Tickets assegnati
     */
    public function ticketsAssegnati()
    {
        return $this->hasMany(Ticket::class, 'assegnato_a_fornitore_id', 'id');
    }

    /**
     * Accessor per l'indirizzo completo
     */
    public function getIndirizzoCompletoAttribute()
    {
        $parts = [];
        
        if ($this->indirizzo) $parts[] = $this->indirizzo;
        if ($this->cap && $this->citta) {
            $parts[] = $this->cap . ' ' . $this->citta;
        }
        if ($this->provincia) $parts[] = '(' . $this->provincia . ')';
        
        return implode(', ', $parts);
    }

    /**
     * Scope per ricerca
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('ragione_sociale', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('telefono', 'like', "%{$search}%")
              ->orWhere('partita_iva', 'like', "%{$search}%");
        });
    }
}