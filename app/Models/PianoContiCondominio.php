<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class PianoContiCondominio extends Model
{
    use HasFactory;

    protected $table = 'piano_conti_condominio';
    protected $primaryKey = 'id_conto_condominio_pc';

    protected $fillable = [
        'id_stabile',
        'id_conto_modello_riferimento',
        'codice',
        'descrizione',
        'tipo_conto',
        'is_conto_finanziario',
        'attivo',
        'note',
    ];

    public function stabile(): BelongsTo
    {
        return $this->belongsTo(Stabile::class, 'id_stabile', 'id_stabile');
    }

    public function vociPreventivo(): HasMany
    {
        return $this->hasMany(VocePreventivo::class, 'id_piano_conto_condominio_pc', 'id_conto_condominio_pc');
    }
}