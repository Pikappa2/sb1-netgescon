<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banca extends Model
{
    use HasFactory;

    protected $table = 'banche';

    protected $fillable = [
        'stabile_id',
        'denominazione',
        'iban',
        'bic_swift',
        'agenzia',
        'indirizzo_agenzia',
        'tipo_conto',
        'saldo_iniziale',
        'data_apertura',
        'stato',
        'note',
    ];

    protected $casts = [
        'saldo_iniziale' => 'decimal:2',
        'data_apertura' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con Stabile
     */
    public function stabile()
    {
        return $this->belongsTo(Stabile::class, 'stabile_id', 'id_stabile');
    }

    /**
     * Relazione con Movimenti Bancari
     */
    public function movimentiBancari()
    {
        return $this->hasMany(MovimentoBancario::class, 'banca_id');
    }

    /**
     * Scope per conti attivi
     */
    public function scopeAttivi($query)
    {
        return $query->where('stato', 'attivo');
    }

    /**
     * Calcola il saldo attuale
     */
    public function getSaldoAttualeAttribute()
    {
        $movimenti = $this->movimentiBancari()
                          ->selectRaw('SUM(CASE WHEN tipo_movimento = "entrata" THEN importo ELSE -importo END) as saldo')
                          ->first();
        
        return $this->saldo_iniziale + ($movimenti->saldo ?? 0);
    }
}