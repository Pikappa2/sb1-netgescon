<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RipartizionePreventivo extends Model
{
    use HasFactory;

    protected $table = 'ripartizioni_preventivo';

    protected $fillable = [
        'voce_preventivo_id',
        'unita_immobiliare_id',
        'quota_calcolata',
        'quota_modificata',
        'quota_finale',
        'versione',
        'modificato_da_user_id',
        'motivo_modifica',
        'data_modifica',
    ];

    protected $casts = [
        'quota_calcolata' => 'decimal:2',
        'quota_modificata' => 'decimal:2',
        'quota_finale' => 'decimal:2',
        'versione' => 'integer',
        'data_modifica' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con Voce Preventivo
     */
    public function vocePreventivo()
    {
        return $this->belongsTo(VocePreventivo::class, 'voce_preventivo_id');
    }

    /**
     * Relazione con Unità Immobiliare
     */
    public function unitaImmobiliare()
    {
        return $this->belongsTo(UnitaImmobiliare::class, 'unita_immobiliare_id', 'id_unita');
    }

    /**
     * Relazione con User che ha modificato
     */
    public function modificatoDa()
    {
        return $this->belongsTo(User::class, 'modificato_da_user_id');
    }

    /**
     * Modifica quota con versionamento
     */
    public function modificaQuota($nuovaQuota, $motivo, $userId)
    {
        $versionePrecedente = $this->versione;
        $quotaPrecedente = $this->quota_finale;

        // Log della modifica
        LogModificaPreventivo::create([
            'entita' => 'ripartizione',
            'entita_id' => $this->id,
            'versione_precedente' => $versionePrecedente,
            'versione_nuova' => $versionePrecedente + 1,
            'utente_id' => $userId,
            'tipo_operazione' => 'update',
            'motivo' => $motivo,
            'dati_precedenti' => ['quota_finale' => $quotaPrecedente],
            'dati_nuovi' => ['quota_finale' => $nuovaQuota],
            'diff' => [
                'campo' => 'quota_finale',
                'da' => $quotaPrecedente,
                'a' => $nuovaQuota,
                'differenza' => $nuovaQuota - $quotaPrecedente,
            ],
        ]);

        $this->update([
            'quota_modificata' => $nuovaQuota,
            'quota_finale' => $nuovaQuota,
            'versione' => $versionePrecedente + 1,
            'modificato_da_user_id' => $userId,
            'motivo_modifica' => $motivo,
            'data_modifica' => now(),
        ]);

        return $this;
    }
}