<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransazioneContabile extends Model
{
     
    use HasFactory, SoftDeletes;

    protected $table = 'transazioni_contabili';
    protected $primaryKey = 'id_transazione';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_condominio',
        'id_gestione',
        'data_registrazione',
        'data_documento',
        'data_competenza',
        'numero_protocollo_interno',
        'protocollo_gestione_tipo',
        'anno_protocollo_documento',
        'data_protocollo',
        'tipo_documento_origine',
        'riferimento_documento_esterno',
        'descrizione_generale',
        'importo_totale_transazione',
        'stato_transazione',
        'id_utente_registrazione',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'data_registrazione' => 'date',
        'data_documento' => 'date',
        'data_competenza' => 'date',
        'data_protocollo' => 'date',
    ];

    public function condominio(): BelongsTo
    {
        return $this->belongsTo(Condominio::class, 'id_condominio', 'id_condominio');
    }

    public function gestione(): BelongsTo
    {
        return $this->belongsTo(Gestione::class, 'id_gestione', 'id_gestione');
    }

    public function righeMovimenti(): HasMany
    {
        return $this->hasMany(RigaMovimentoContabile::class, 'id_transazione', 'id_transazione');
    }

    // Se hai una tabella 'users' per gli utenti che registrano i movimenti
    public function utenteRegistrazione(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_utente_registrazione');
    }
}