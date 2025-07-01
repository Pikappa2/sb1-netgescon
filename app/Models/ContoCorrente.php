<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContoCorrente extends Model
{
    use HasFactory;    // Corretto il nome della tabella e la chiave primaria
    protected $table = 'conti_condominio'; // Corretto il nome della tabella
    protected $primaryKey = 'id_conto_condominio'; // Chiave primaria corretta

    protected $fillable = [
        'id_condominio', // Corretto il nome della colonna
        'codice_conto',
        'nome_conto',
        'iban',
        'bic_swift',
        'nome_banca',
        'filiale_banca',
        'tipo_conto',
        'saldo_iniziale',
        'data_saldo_iniziale',
        'valuta',
        'attivo',
        'note',
    ];

    public function condominio(): BelongsTo
    {
        return $this->belongsTo(Condominio::class, 'id_condominio', 'id_condominio');
    }
}
