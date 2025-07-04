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

    // Relazione con Stabile
    public function stabile()
    {
        return $this->belongsTo(Stabile::class, 'stabile_id', 'id');
    }

    // Relazione con UnitaImmobiliare
    public function unitaImmobiliare()
    {
        return $this->belongsTo(UnitaImmobiliare::class, 'unita_immobiliare_id', 'id');
    }

    // Relazione con Soggetto richiedente
    public function soggettoRichiedente()
    {
        return $this->belongsTo(Soggetto::class, 'soggetto_richiedente_id', 'id');
    }

    // Relazione con CategoriaTicket
    public function categoriaTicket()
    {
        return $this->belongsTo(CategoriaTicket::class, 'categoria_ticket_id', 'id');
    }

    // Relazione con User che ha aperto il ticket
    public function apertoDaUser()
    {
        return $this->belongsTo(User::class, 'aperto_da_user_id', 'id');
    }

    // Relazione con User assegnato
    public function assegnatoAUser()
    {
        return $this->belongsTo(User::class, 'assegnato_a_user_id', 'id');
    }

    // Relazione con Fornitore assegnato
    public function assegnatoAFornitore()
    {
        return $this->belongsTo(Fornitore::class, 'assegnato_a_fornitore_id', 'id');
    }

    // Relazione con TicketUpdate
    public function updates()
    {
        return $this->hasMany(TicketUpdate::class, 'ticket_id', 'id');
    }

    // Relazione con TicketMessage
    public function messages()
    {
        return $this->hasMany(TicketMessage::class, 'ticket_id', 'id');
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