<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroProtocollo extends Model
{
    use HasFactory;

    protected $table = 'registro_protocollo';

    protected $fillable = [
        'numero_protocollo',
        'tipo_comunicazione',
        'assemblea_id',
        'soggetto_destinatario_id',
        'soggetto_mittente_id',
        'oggetto',
        'contenuto',
        'canale',
        'data_invio',
        'esito',
        'data_consegna',
        'data_lettura',
        'riferimento_esterno',
        'allegati',
        'note',
        'creato_da_user_id',
    ];

    protected $casts = [
        'data_invio' => 'datetime',
        'data_consegna' => 'datetime',
        'data_lettura' => 'datetime',
        'allegati' => 'array',
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
    public function soggettoDestinatario()
    {
        return $this->belongsTo(Soggetto::class, 'soggetto_destinatario_id', 'id_soggetto');
    }

    /**
     * Relazione con Soggetto mittente
     */
    public function soggettoMittente()
    {
        return $this->belongsTo(Soggetto::class, 'soggetto_mittente_id', 'id_soggetto');
    }

    /**
     * Relazione con User creatore
     */
    public function creatoDa()
    {
        return $this->belongsTo(User::class, 'creato_da_user_id');
    }

    /**
     * Scope per tipo comunicazione
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_comunicazione', $tipo);
    }

    /**
     * Scope per periodo
     */
    public function scopePeriodo($query, $dataInizio, $dataFine)
    {
        return $query->whereBetween('data_invio', [$dataInizio, $dataFine]);
    }

    /**
     * Genera numero protocollo automatico
     */
    public static function generaNumeroProtocollo()
    {
        $anno = date('Y');
        $ultimoProtocollo = self::whereYear('created_at', $anno)
            ->orderBy('numero_protocollo', 'desc')
            ->first();

        if ($ultimoProtocollo) {
            $numero = intval(substr($ultimoProtocollo->numero_protocollo, -4)) + 1;
        } else {
            $numero = 1;
        }

        return 'PROT/' . $anno . '/' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }
}