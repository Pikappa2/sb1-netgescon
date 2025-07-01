<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimentoBancario extends Model
{
    use HasFactory;

    protected $table = 'movimenti_bancari';

    protected $fillable = [
        'banca_id',
        'movimento_contabile_id',
        'data_valuta',
        'data_contabile',
        'tipo_movimento',
        'importo',
        'causale',
        'beneficiario',
        'ordinante',
        'cro_tro',
        'stato_riconciliazione',
        'note',
    ];

    protected $casts = [
        'data_valuta' => 'date',
        'data_contabile' => 'date',
        'importo' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con Banca
     */
    public function banca()
    {
        return $this->belongsTo(Banca::class, 'banca_id');
    }

    /**
     * Relazione con Movimento Contabile
     */
    public function movimentoContabile()
    {
        return $this->belongsTo(MovimentoContabile::class, 'movimento_contabile_id');
    }

    /**
     * Scope per tipo movimento
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_movimento', $tipo);
    }

    /**
     * Scope per stato riconciliazione
     */
    public function scopeRiconciliazione($query, $stato)
    {
        return $query->where('stato_riconciliazione', $stato);
    }
}