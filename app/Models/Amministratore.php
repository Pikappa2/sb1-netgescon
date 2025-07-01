<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // Aggiunto per condomini()
use Illuminate\Database\Eloquent\SoftDeletes; // Aggiunto per soft deletes

class Amministratore extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'amministratori'; // Specifica il nome corretto della tabella
    protected $primaryKey = 'id_amministratore';    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    
    protected $fillable = [
        'user_id',
        'nome',
        'cognome',
        'denominazione_studio',
        'partita_iva',
        'codice_fiscale_studio',
        'indirizzo_studio',
        'cap_studio',
        'citta_studio',
        'provincia_studio',
        'telefono_studio',
        'email_studio',
        'pec_studio',
    ];

    /**
     * Get the user associated with the Amministratore.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the condomini for the Amministratore.
     */
    public function stabili(): HasMany
    {
        return $this->hasMany(Stabile::class, 'amministratore_id', 'id_amministratore');
    }
    /**
     * Get the fornitori for the Amministratore.
     */
    public function fornitori(): HasMany
    {
        return $this->hasMany(Fornitore::class, 'amministratore_id', 'id_amministratore');
    }
}