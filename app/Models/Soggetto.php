<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Soggetto extends Model
{
    use HasFactory;

    protected $table = 'soggetti';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'old_id',
        'nome',
        'cognome',
        'ragione_sociale',
        'codice_fiscale',
        'partita_iva',
        'email',
        'telefono',
        'indirizzo',
        'cap',
        'citta',
        'provincia',
        'tipo',
    ];

    /**
     * The units that belong to the subject.
     */
     
    public function ticketsRichiesti(): HasMany
    {
        return $this->hasMany(Ticket::class, 'soggetto_richiedente_id', 'id');
    }

    public function rateEmessaResponsabile(): HasMany
    {
        return $this->hasMany(RataEmessa::class, 'id_soggetto_responsabile', 'id');
    }

    public function proprieta() {
        return $this->hasMany(Proprieta::class);
    }
}