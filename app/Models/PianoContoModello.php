<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class PianoContoModello extends Model
{
    
    use HasFactory;

    protected $table = 'piani_conti_modello';
    protected $primaryKey = 'id_conto_modello';

    protected $fillable = [
        'codice',
        'descrizione',
        'tipo_conto',
        'natura_saldo_tipico',
        'is_conto_finanziario',
        'note',
    ];

    public function pianoContiCondominio(): HasMany
    {
        return $this->hasMany(PianoContoCondominio::class, 'id_conto_modello_riferimento', 'id_conto_modello');
    }
}
