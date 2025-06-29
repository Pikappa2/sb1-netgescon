<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogModificaPreventivo extends Model
{
    use HasFactory;

    protected $table = 'log_modifiche_preventivo';

    protected $fillable = [
        'entita',
        'entita_id',
        'versione_precedente',
        'versione_nuova',
        'utente_id',
        'tipo_operazione',
        'motivo',
        'dati_precedenti',
        'dati_nuovi',
        'diff',
    ];

    protected $casts = [
        'versione_precedente' => 'integer',
        'versione_nuova' => 'integer',
        'dati_precedenti' => 'array',
        'dati_nuovi' => 'array',
        'diff' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con User
     */
    public function utente()
    {
        return $this->belongsTo(User::class, 'utente_id');
    }

    /**
     * Relazione polimorfica con entità
     */
    public function entita()
    {
        return $this->morphTo();
    }

    /**
     * Scope per entità
     */
    public function scopePerEntita($query, $entita, $entitaId)
    {
        return $query->where('entita', $entita)->where('entita_id', $entitaId);
    }

    /**
     * Genera diff stile GIT
     */
    public static function generaDiff($datiPrecedenti, $datiNuovi)
    {
        $diff = [];
        
        foreach ($datiNuovi as $campo => $valoreNuovo) {
            $valorePrecedente = $datiPrecedenti[$campo] ?? null;
            
            if ($valorePrecedente != $valoreNuovo) {
                $diff[] = [
                    'campo' => $campo,
                    'da' => $valorePrecedente,
                    'a' => $valoreNuovo,
                    'tipo' => $valorePrecedente === null ? 'aggiunto' : 'modificato',
                ];
            }
        }

        return $diff;
    }
}