<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'stabile_id',
        'unita_immobiliare_id',
        'soggetto_richiedente_id',
        'categoria_ticket_id',
        'aperto_da_user_id',
        'assegnato_a_user_id',
        'assegnato_a_fornitore_id',
        'titolo',
        'descrizione',
        'luogo_intervento',
        'data_apertura',
        'data_scadenza_prevista',
        'data_risoluzione_effettiva',
        'data_chiusura_effettiva',
        'stato',
        'priorita',
    ];

    protected $casts = [
        'data_apertura' => 'datetime',
        'data_scadenza_prevista' => 'date',
        'data_risoluzione_effettiva' => 'date',
        'data_chiusura_effettiva' => 'date',
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
     * Relazione con UnitaImmobiliare
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
     * Relazione con CategoriaTicket
     */
    public function categoriaTicket()
    {
        return $this->belongsTo(CategoriaTicket::class, 'categoria_ticket_id');
    }

    /**
     * Relazione con User che ha aperto il ticket
     */
    public function apertoUser()
    {
        return $this->belongsTo(User::class, 'aperto_da_user_id');
    }

    /**
     * Relazione con User assegnato
     */
    public function assegnatoUser()
    {
        return $this->belongsTo(User::class, 'assegnato_a_user_id');
    }

    /**
     * Relazione con Fornitore assegnato
     */
    public function assegnatoFornitore()
    {
        return $this->belongsTo(Fornitore::class, 'assegnato_a_fornitore_id', 'id_fornitore');
    }

    /**
     * Scope per ticket aperti
     */
    public function scopeAperti($query)
    {
        return $query->whereIn('stato', ['Aperto', 'Preso in Carico', 'In Lavorazione']);
    }

    /**
     * Scope per ticket chiusi
     */
    public function scopeChiusi($query)
    {
        return $query->whereIn('stato', ['Risolto', 'Chiuso']);
    }

    /**
     * Scope per priorità
     */
    public function scopePriorita($query, $priorita)
    {
        return $query->where('priorita', $priorita);
    }
}