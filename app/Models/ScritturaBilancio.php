<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScritturaBilancio extends Model
{
    use HasFactory;

    protected $table = 'scritture_bilancio';

    protected $fillable = [
        'bilancio_id',
        'numero_scrittura',
        'data_scrittura',
        'descrizione',
        'tipo_scrittura',
        'importo_totale',
        'riferimento_documento',
        'movimento_contabile_id',
        'creato_da_user_id',
        'note',
    ];

    protected $casts = [
        'data_scrittura' => 'date',
        'importo_totale' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con Bilancio
     */
    public function bilancio()
    {
        return $this->belongsTo(Bilancio::class, 'bilancio_id');
    }

    /**
     * Relazione con Dettagli (Dare/Avere)
     */
    public function dettagli()
    {
        return $this->hasMany(DettaglioScritturaBilancio::class, 'scrittura_bilancio_id');
    }

    /**
     * Relazione con Ripartizioni
     */
    public function ripartizioni()
    {
        return $this->hasMany(RipartizioneBilancio::class, 'scrittura_bilancio_id');
    }

    /**
     * Relazione con Movimento Contabile
     */
    public function movimentoContabile()
    {
        return $this->belongsTo(MovimentoContabile::class, 'movimento_contabile_id');
    }

    /**
     * Relazione con User creatore
     */
    public function creatoDa()
    {
        return $this->belongsTo(User::class, 'creato_da_user_id');
    }

    /**
     * Verifica quadratura dare/avere
     */
    public function verificaQuadratura()
    {
        $totaleDare = $this->dettagli()->sum('importo_dare');
        $totaleAvere = $this->dettagli()->sum('importo_avere');
        
        return abs($totaleDare - $totaleAvere) < 0.01; // Tolleranza centesimi
    }

    /**
     * Crea scrittura in partita doppia
     */
    public static function creaScrittura($bilancioId, $data, $descrizione, $dettagli, $userId)
    {
        $importoTotale = collect($dettagli)->sum(function($dettaglio) {
            return max($dettaglio['dare'] ?? 0, $dettaglio['avere'] ?? 0);
        });

        $scrittura = self::create([
            'bilancio_id' => $bilancioId,
            'numero_scrittura' => self::generaNumeroScrittura($bilancioId),
            'data_scrittura' => $data,
            'descrizione' => $descrizione,
            'tipo_scrittura' => 'gestione',
            'importo_totale' => $importoTotale,
            'creato_da_user_id' => $userId,
        ]);

        // Crea dettagli dare/avere
        foreach ($dettagli as $dettaglio) {
            DettaglioScritturaBilancio::create([
                'scrittura_bilancio_id' => $scrittura->id,
                'conto_id' => $dettaglio['conto_id'],
                'importo_dare' => $dettaglio['dare'] ?? 0,
                'importo_avere' => $dettaglio['avere'] ?? 0,
                'descrizione_dettaglio' => $dettaglio['descrizione'] ?? null,
            ]);
        }

        // Verifica quadratura
        if (!$scrittura->verificaQuadratura()) {
            throw new \Exception('Scrittura non quadra: totale dare diverso da totale avere');
        }

        return $scrittura;
    }

    /**
     * Genera numero scrittura progressivo
     */
    private static function generaNumeroScrittura($bilancioId)
    {
        $bilancio = Bilancio::find($bilancioId);
        $ultimaScrittura = self::where('bilancio_id', $bilancioId)
            ->orderBy('numero_scrittura', 'desc')
            ->first();

        if ($ultimaScrittura) {
            $numero = intval(substr($ultimaScrittura->numero_scrittura, -4)) + 1;
        } else {
            $numero = 1;
        }

        return 'SCR/' . $bilancio->anno_esercizio . '/' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }
}