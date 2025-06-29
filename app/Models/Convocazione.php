<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convocazione extends Model
{
    use HasFactory;

    protected $table = 'convocazioni';

    protected $fillable = [
        'assemblea_id',
        'soggetto_id',
        'unita_immobiliare_id',
        'canale_invio',
        'data_invio',
        'esito_invio',
        'data_lettura',
        'riferimento_invio',
        'note_invio',
        'delega_presente',
        'delegato_soggetto_id',
        'documento_delega',
        'presenza_confermata',
        'data_conferma_presenza',
    ];

    protected $casts = [
        'data_invio' => 'datetime',
        'data_lettura' => 'datetime',
        'delega_presente' => 'boolean',
        'presenza_confermata' => 'boolean',
        'data_conferma_presenza' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con Assemblea
     */
    public function assemblea()
    {
        return $this->belongsTo(Assemblea::class, 'assemblea_id');
    }

    /**
     * Relazione con Soggetto destinatario
     */
    public function soggetto()
    {
        return $this->belongsTo(Soggetto::class, 'soggetto_id', 'id_soggetto');
    }

    /**
     * Relazione con Unità Immobiliare
     */
    public function unitaImmobiliare()
    {
        return $this->belongsTo(UnitaImmobiliare::class, 'unita_immobiliare_id', 'id_unita');
    }

    /**
     * Relazione con Soggetto delegato
     */
    public function delegato()
    {
        return $this->belongsTo(Soggetto::class, 'delegato_soggetto_id', 'id_soggetto');
    }

    /**
     * Scope per esito
     */
    public function scopeEsito($query, $esito)
    {
        return $query->where('esito_invio', $esito);
    }

    /**
     * Scope per canale
     */
    public function scopeCanale($query, $canale)
    {
        return $query->where('canale_invio', $canale);
    }

    /**
     * Conferma lettura
     */
    public function confermaLettura()
    {
        $this->update([
            'esito_invio' => 'letto',
            'data_lettura' => now(),
        ]);

        return $this;
    }

    /**
     * Conferma presenza
     */
    public function confermaPresenza()
    {
        $this->update([
            'presenza_confermata' => true,
            'data_conferma_presenza' => now(),
        ]);

        return $this;
    }

    /**
     * Carica delega
     */
    public function caricaDelega($delegatoId, $documentoPath)
    {
        $this->update([
            'delega_presente' => true,
            'delegato_soggetto_id' => $delegatoId,
            'documento_delega' => $documentoPath,
        ]);

        return $this;
    }
}