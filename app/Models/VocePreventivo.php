<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VocePreventivo extends Model
{
    use HasFactory;

    protected $table = 'voci_preventivo';

    protected $fillable = [
        'preventivo_id',
        'codice',
        'descrizione',
        'importo_preventivato',
        'importo_effettivo',
        'tabella_millesimale_id',
        'voce_spesa_id',
        'ricorrente',
        'frequenza',
        'data_scadenza_prevista',
        'ordinamento',
        'note',
    ];

    protected $casts = [
        'importo_preventivato' => 'decimal:2',
        'importo_effettivo' => 'decimal:2',
        'ricorrente' => 'boolean',
        'data_scadenza_prevista' => 'date',
        'ordinamento' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con Preventivo
     */
    public function preventivo()
    {
        return $this->belongsTo(Preventivo::class, 'preventivo_id');
    }

    /**
     * Relazione con Tabella Millesimale
     */
    public function tabellaMillesimale()
    {
        return $this->belongsTo(TabellaMillesimale::class, 'tabella_millesimale_id');
    }

    /**
     * Relazione con Voce Spesa
     */
    public function voceSpesa()
    {
        return $this->belongsTo(VoceSpesa::class, 'voce_spesa_id');
    }

    /**
     * Relazione con Ripartizioni
     */
    public function ripartizioni()
    {
        return $this->hasMany(RipartizionePreventivo::class, 'voce_preventivo_id');
    }

    /**
     * Calcola ripartizione automatica
     */
    public function calcolaRipartizione()
    {
        if (!$this->tabella_millesimale_id) {
            return false;
        }

        $dettagliMillesimi = $this->tabellaMillesimale->dettagli;
        $totaleMillesimi = $dettagliMillesimi->sum('millesimi');

        foreach ($dettagliMillesimi as $dettaglio) {
            $quota = ($this->importo_preventivato * $dettaglio->millesimi) / $totaleMillesimi;

            RipartizionePreventivo::updateOrCreate(
                [
                    'voce_preventivo_id' => $this->id,
                    'unita_immobiliare_id' => $dettaglio->unita_immobiliare_id,
                ],
                [
                    'quota_calcolata' => $quota,
                    'quota_finale' => $quota,
                    'versione' => 1,
                ]
            );
        }

        return true;
    }
}