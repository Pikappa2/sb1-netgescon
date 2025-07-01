<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proprieta extends Model
{
    use HasFactory;
    protected $table = 'proprieta';
    protected $fillable = [
        'unita_immobiliare_id', 'soggetto_id', 'tipo_diritto',
        'percentuale_possesso', 'data_inizio', 'data_fine', 'note'
    ];

    public function unitaImmobiliare() {
        return $this->belongsTo(UnitaImmobiliare::class, 'unita_immobiliare_id', 'id_unita');
    }
    public function soggetto() {
        return $this->belongsTo(Soggetto::class, 'soggetto_id', 'id_soggetto');
    }
}