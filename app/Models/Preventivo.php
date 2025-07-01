<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Preventivo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'stabile_id',
        'anno_gestione',
        'tipo_gestione',
        'descrizione',
        'stato',
        'importo_totale',
        'data_creazione',
        'data_approvazione',
        'approvato_da_user_id',
        'note',
        'versione',
    ];

    protected $casts = [
        'data_creazione' => 'date',
        'data_approvazione' => 'date',
        'importo_totale' => 'decimal:2',
        'versione' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relazione con Stabile
     */
    public function stabile()
    {
        return $this->belongsTo(Stabile::class, 'stabile_id', 'id_stabile');
    }

    /**
     * Relazione con Voci Preventivo
     */
    public function voci()
    {
        return $this->hasMany(VocePreventivo::class, 'preventivo_id');
    }

    /**
     * Relazione con Rate
     */
    public function rate()
    {
        return $this->hasMany(Rata::class, 'preventivo_id');
    }

    /**
     * Relazione con User che ha approvato
     */
    public function approvatoDa()
    {
        return $this->belongsTo(User::class, 'approvato_da_user_id');
    }

    /**
     * Relazione con Log Modifiche
     */
    public function logModifiche()
    {
        return $this->morphMany(LogModificaPreventivo::class, 'entita');
    }

    /**
     * Scope per stato
     */
    public function scopeStato($query, $stato)
    {
        return $query->where('stato', $stato);
    }

    /**
     * Scope per tipo gestione
     */
    public function scopeTipoGestione($query, $tipo)
    {
        return $query->where('tipo_gestione', $tipo);
    }

    /**
     * Calcola importo totale dalle voci
     */
    public function calcolaImportoTotale()
    {
        $this->importo_totale = $this->voci()->sum('importo_preventivato');
        $this->save();
        return $this->importo_totale;
    }

    /**
     * Crea nuova versione del preventivo
     */
    public function creaVersione($motivo, $userId)
    {
        $nuovaVersione = $this->versione + 1;
        
        // Log della modifica
        LogModificaPreventivo::create([
            'entita' => 'preventivo',
            'entita_id' => $this->id,
            'versione_precedente' => $this->versione,
            'versione_nuova' => $nuovaVersione,
            'utente_id' => $userId,
            'tipo_operazione' => 'update',
            'motivo' => $motivo,
            'dati_precedenti' => $this->getOriginal(),
            'dati_nuovi' => $this->getAttributes(),
        ]);

        $this->versione = $nuovaVersione;
        $this->save();

        return $this;
    }

    /**
     * Genera rate dal preventivo
     */
    public function generaRate($numeroRate, $dataInizio, $userId)
    {
        $importoPerRata = $this->importo_totale / $numeroRate;
        $rate = [];

        for ($i = 1; $i <= $numeroRate; $i++) {
            $dataScadenza = $dataInizio->copy()->addMonths($i - 1);
            $numeroRata = $this->stabile_id . '/' . $this->anno_gestione . '/' . str_pad($i, 3, '0', STR_PAD_LEFT);

            $rata = Rata::create([
                'preventivo_id' => $this->id,
                'numero_rata' => $numeroRata,
                'descrizione' => "Rata {$i} di {$numeroRate} - {$this->descrizione}",
                'data_scadenza' => $dataScadenza,
                'stato' => 'emessa',
                'importo_totale' => $importoPerRata,
                'creato_da_user_id' => $userId,
            ]);

            $rate[] = $rata;
        }

        return $rate;
    }
}