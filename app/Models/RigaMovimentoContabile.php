<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class RigaMovimentoContabile extends Model
{
    
    use HasFactory;

    protected $table = 'righe_movimenti_contabili';
    protected $primaryKey = 'id_riga_movimento';

    protected $fillable = [
        'id_transazione',
        'id_piano_conto_condominio_pc',
        'id_gestione_imputazione',
        'descrizione_riga',
        'importo_dare',
        'importo_avere',
        'id_unita_immobiliare',
        'id_soggetto',
        'id_fornitore',
        'id_voce_spesa_originale',
        'note_riga',
    ];

    public function transazione(): BelongsTo { return $this->belongsTo(TransazioneContabile::class, 'id_transazione', 'id_transazione'); }
    public function pianoContoCondominio(): BelongsTo { return $this->belongsTo(PianoContoCondominio::class, 'id_piano_conto_condominio_pc', 'id_conto_condominio_pc'); }
    public function gestioneImputazione(): BelongsTo { return $this->belongsTo(Gestione::class, 'id_gestione_imputazione', 'id_gestione'); }
    public function unitaImmobiliare(): BelongsTo { return $this->belongsTo(UnitaImmobiliare::class, 'id_unita_immobiliare', 'id_unita'); }
    public function soggetto(): BelongsTo { return $this->belongsTo(Soggetto::class, 'id_soggetto', 'id_soggetto'); }
    public function fornitore(): BelongsTo { return $this->belongsTo(Fornitore::class, 'id_fornitore', 'id_fornitore'); }
    public function voceSpesaOriginale(): BelongsTo { return $this->belongsTo(VoceSpesa::class, 'id_voce_spesa_originale', 'id_voce'); }
}
// Compare this snippet from netgescon-laravel/app/Models/TransazioneContabile.php:
// <?php
//
// namespace App\Models;
//
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Database\Eloquent\SoftDeletes;
//          