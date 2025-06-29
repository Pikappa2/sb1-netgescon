<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdineGiorno extends Model
{
    use HasFactory;

    protected $table = 'ordine_giorno';

    protected $fillable = [
        'assemblea_id',
        'numero_punto',
        'titolo',
        'descrizione',
        'tipo_voce',
        'collegamento_preventivo_id',
        'importo_spesa',
        'tabella_millesimale_id',
        'esito_votazione',
        'voti_favorevoli',
        'voti_contrari',
        'astenuti',
        'millesimi_favorevoli',
        'millesimi_contrari',
        'millesimi_astenuti',
        'note_delibera',
    ];

    protected $casts = [
        'numero_punto' => 'integer',
        'importo_spesa' => 'decimal:2',
        'voti_favorevoli' => 'integer',
        'voti_contrari' => 'integer',
        'astenuti' => 'integer',
        'millesimi_favorevoli' => 'decimal:4',
        'millesimi_contrari' => 'decimal:4',
        'millesimi_astenuti' => 'decimal:4',
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
     * Relazione con Preventivo collegato
     */
    public function preventivo()
    {
        return $this->belongsTo(Preventivo::class, 'collegamento_preventivo_id');
    }

    /**
     * Relazione con Tabella Millesimale
     */
    public function tabellaMillesimale()
    {
        return $this->belongsTo(TabellaMillesimale::class, 'tabella_millesimale_id');
    }

    /**
     * Relazione con Votazioni
     */
    public function votazioni()
    {
        return $this->hasMany(Votazione::class, 'ordine_giorno_id');
    }

    /**
     * Relazione con Delibera
     */
    public function delibera()
    {
        return $this->hasOne(Delibera::class, 'ordine_giorno_id');
    }

    /**
     * Registra votazione
     */
    public function registraVotazione($soggetto, $unitaImmobiliare, $voto, $millesimi, $motivazione = null)
    {
        return Votazione::updateOrCreate(
            [
                'ordine_giorno_id' => $this->id,
                'soggetto_id' => $soggetto->id_soggetto,
                'unita_immobiliare_id' => $unitaImmobiliare->id_unita,
            ],
            [
                'voto' => $voto,
                'millesimi_voto' => $millesimi,
                'data_voto' => now(),
                'motivazione' => $motivazione,
            ]
        );
    }

    /**
     * Calcola risultato votazione
     */
    public function calcolaRisultato()
    {
        $votazioni = $this->votazioni;
        
        $favorevoli = $votazioni->where('voto', 'favorevole');
        $contrari = $votazioni->where('voto', 'contrario');
        $astenuti = $votazioni->where('voto', 'astenuto');

        $totaleMillesimiFavorevoli = $favorevoli->sum('millesimi_voto');
        $totaleMillesimiContrari = $contrari->sum('millesimi_voto');
        $totaleMillesimiAstenuti = $astenuti->sum('millesimi_voto');
        
        $totaleMillesimiVotanti = $totaleMillesimiFavorevoli + $totaleMillesimiContrari + $totaleMillesimiAstenuti;
        
        // Calcola maggioranza (50% + 1 dei millesimi votanti)
        $maggioranzaRichiesta = ($totaleMillesimiVotanti / 2) + 0.0001;
        $maggioranzaRaggiunta = $totaleMillesimiFavorevoli >= $maggioranzaRichiesta;

        $esito = $maggioranzaRaggiunta ? 'approvato' : 'respinto';

        // Aggiorna il punto dell'ordine del giorno
        $this->update([
            'esito_votazione' => $esito,
            'voti_favorevoli' => $favorevoli->count(),
            'voti_contrari' => $contrari->count(),
            'astenuti' => $astenuti->count(),
            'millesimi_favorevoli' => $totaleMillesimiFavorevoli,
            'millesimi_contrari' => $totaleMillesimiContrari,
            'millesimi_astenuti' => $totaleMillesimiAstenuti,
        ]);

        // Crea delibera
        if ($esito === 'approvato') {
            $this->creaDelibera($totaleMillesimiFavorevoli, $totaleMillesimiContrari, $totaleMillesimiAstenuti);
        }

        return [
            'esito' => $esito,
            'maggioranza_raggiunta' => $maggioranzaRaggiunta,
            'percentuale_favorevoli' => $totaleMillesimiVotanti > 0 ? ($totaleMillesimiFavorevoli / $totaleMillesimiVotanti) * 100 : 0,
            'dettagli' => [
                'favorevoli' => ['voti' => $favorevoli->count(), 'millesimi' => $totaleMillesimiFavorevoli],
                'contrari' => ['voti' => $contrari->count(), 'millesimi' => $totaleMillesimiContrari],
                'astenuti' => ['voti' => $astenuti->count(), 'millesimi' => $totaleMillesimiAstenuti],
            ]
        ];
    }

    /**
     * Crea delibera se approvata
     */
    private function creaDelibera($millFav, $millContr, $millAst)
    {
        $numeroDelibera = $this->generaNumeroDelibera();
        
        $delibera = Delibera::create([
            'ordine_giorno_id' => $this->id,
            'numero_delibera' => $numeroDelibera,
            'esito' => 'approvata',
            'testo_delibera' => $this->generaTestoDelibera(),
            'totale_voti_favorevoli' => $this->voti_favorevoli,
            'totale_voti_contrari' => $this->voti_contrari,
            'totale_astenuti' => $this->astenuti,
            'totale_millesimi_favorevoli' => $millFav,
            'totale_millesimi_contrari' => $millContr,
            'totale_millesimi_astenuti' => $millAst,
            'percentuale_approvazione' => ($millFav / ($millFav + $millContr + $millAst)) * 100,
            'maggioranza_raggiunta' => true,
            'data_delibera' => now(),
        ]);

        // Se è una spesa approvata, avvia automazione
        if ($this->tipo_voce === 'spesa' && $this->importo_spesa > 0) {
            $this->avviaAutomazioneSpesa($delibera);
        }

        return $delibera;
    }

    /**
     * Genera numero delibera
     */
    private function generaNumeroDelibera()
    {
        $anno = $this->assemblea->data_prima_convocazione->year;
        $ultimaDelibera = Delibera::whereHas('ordineGiorno.assemblea', function($q) use ($anno) {
            $q->whereYear('data_prima_convocazione', $anno);
        })->orderBy('numero_delibera', 'desc')->first();

        if ($ultimaDelibera) {
            $numero = intval(substr($ultimaDelibera->numero_delibera, -3)) + 1;
        } else {
            $numero = 1;
        }

        return 'DEL/' . $anno . '/' . str_pad($numero, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Genera testo delibera
     */
    private function generaTestoDelibera()
    {
        $testo = "DELIBERA N. {$this->generaNumeroDelibera()}\n\n";
        $testo .= "OGGETTO: {$this->titolo}\n\n";
        $testo .= "DESCRIZIONE:\n{$this->descrizione}\n\n";
        
        if ($this->tipo_voce === 'spesa' && $this->importo_spesa > 0) {
            $testo .= "IMPORTO APPROVATO: € " . number_format($this->importo_spesa, 2, ',', '.') . "\n";
            if ($this->tabellaMillesimale) {
                $testo .= "RIPARTIZIONE: {$this->tabellaMillesimale->nome}\n";
            }
        }

        $testo .= "\nRISULTATO VOTAZIONE:\n";
        $testo .= "- Voti favorevoli: {$this->voti_favorevoli} (millesimi: {$this->millesimi_favorevoli})\n";
        $testo .= "- Voti contrari: {$this->voti_contrari} (millesimi: {$this->millesimi_contrari})\n";
        $testo .= "- Astenuti: {$this->astenuti} (millesimi: {$this->millesimi_astenuti})\n";

        return $testo;
    }

    /**
     * Avvia automazione per spese approvate
     */
    private function avviaAutomazioneSpesa($delibera)
    {
        AutomazioneSpesaApprovata::create([
            'delibera_id' => $delibera->id,
            'stato_automazione' => 'in_attesa',
        ]);

        // Qui si potrebbe implementare una coda per processare l'automazione
        // Ad esempio: dispatch(new ProcessaSpesaApprovata($delibera));
    }
}