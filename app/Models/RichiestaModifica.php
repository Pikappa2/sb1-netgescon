<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RichiestaModifica extends Model
{
    use HasFactory;

    protected $table = 'richieste_modifiche';

    protected $fillable = [
        'unita_immobiliare_id',
        'soggetto_richiedente_id',
        'tipo_modifica',
        'descrizione',
        'dati_attuali',
        'dati_proposti',
        'stato',
        'note_amministratore',
        'data_approvazione',
        'approvato_da_user_id',
    ];

    protected $casts = [
        'dati_attuali' => 'array',
        'dati_proposti' => 'array',
        'data_approvazione' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con Unità Immobiliare
     */
    public function unitaImmobiliare()
    {
        return $this->belongsTo(UnitaImmobiliare::class, 'unita_immobiliare_id', 'id_unita');
    }

    /**
     * Relazione con Soggetto richiedente
     */
    public function soggettoRichiedente()
    {
        return $this->belongsTo(Soggetto::class, 'soggetto_richiedente_id', 'id_soggetto');
    }

    /**
     * Relazione con User che ha approvato
     */
    public function approvatoDa()
    {
        return $this->belongsTo(User::class, 'approvato_da_user_id');
    }

    /**
     * Scope per stato
     */
    public function scopeStato($query, $stato)
    {
        return $query->where('stato', $stato);
    }
}