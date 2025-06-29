<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assemblea extends Model
{
    use HasFactory;

    protected $table = 'assemblee';

    protected $fillable = [
        'stabile_id',
        'tipo',
        'data_prima_convocazione',
        'data_seconda_convocazione',
        'luogo',
        'note',
        'stato',
        'data_convocazione',
        'data_svolgimento',
        'creato_da_user_id',
    ];

    protected $casts = [
        'data_prima_convocazione' => 'datetime',
        'data_seconda_convocazione' => 'datetime',
        'data_convocazione' => 'date',
        'data_svolgimento' => 'date',
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
     * Relazione con Ordine del Giorno
     */
    public function ordineGiorno()
    {
        return $this->hasMany(OrdineGiorno::class, 'assemblea_id')->orderBy('numero_punto');
    }

    /**
     * Relazione con Convocazioni
     */
    public function convocazioni()
    {
        return $this->hasMany(Convocazione::class, 'assemblea_id');
    }

    /**
     * Relazione con Presenze
     */
    public function presenze()
    {
        return $this->hasMany(PresenzaAssemblea::class, 'assemblea_id');
    }

    /**
     * Relazione con Verbale
     */
    public function verbale()
    {
        return $this->hasOne(Verbale::class, 'assemblea_id');
    }

    /**
     * Relazione con Documenti
     */
    public function documenti()
    {
        return $this->hasMany(DocumentoAssemblea::class, 'assemblea_id');
    }

    /**
     * Relazione con User creatore
     */
    public function creatoDa()
    {
        return $this->belongsTo(User::class, 'creato_da_user_id');
    }

    /**
     * Scope per stato
     */
    public function scopeStato($query, $stato)
    {
        return $query->where('stato', $stato);
    }

    /**
     * Scope per tipo
     */
    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Invia convocazioni massive
     */
    public function inviaConvocazioni($canali = ['email'], $userId)
    {
        $unitaImmobiliari = $this->stabile->unitaImmobiliari()->with('proprieta.soggetto')->get();
        $convocazioniInviate = 0;

        foreach ($unitaImmobiliari as $unita) {
            foreach ($unita->proprieta as $proprieta) {
                $soggetto = $proprieta->soggetto;
                
                foreach ($canali as $canale) {
                    if ($this->verificaCanaleDisponibile($soggetto, $canale)) {
                        $convocazione = $this->creaConvocazione($soggetto, $unita, $canale);
                        
                        if ($this->inviaConvocazione($convocazione)) {
                            $convocazioniInviate++;
                            
                            // Registra nel protocollo
                            $this->registraProtocollo($convocazione, $userId);
                        }
                    }
                }
            }
        }

        // Aggiorna stato assemblea
        $this->update([
            'stato' => 'convocata',
            'data_convocazione' => now(),
        ]);

        return $convocazioniInviate;
    }

    /**
     * Verifica se il canale è disponibile per il soggetto
     */
    private function verificaCanaleDisponibile($soggetto, $canale)
    {
        switch ($canale) {
            case 'email':
                return !empty($soggetto->email);
            case 'pec':
                return !empty($soggetto->pec);
            case 'whatsapp':
            case 'telegram':
                return !empty($soggetto->telefono);
            default:
                return true;
        }
    }

    /**
     * Crea record convocazione
     */
    private function creaConvocazione($soggetto, $unita, $canale)
    {
        return Convocazione::create([
            'assemblea_id' => $this->id,
            'soggetto_id' => $soggetto->id_soggetto,
            'unita_immobiliare_id' => $unita->id_unita,
            'canale_invio' => $canale,
            'data_invio' => now(),
            'esito_invio' => 'inviato',
        ]);
    }

    /**
     * Invia singola convocazione
     */
    private function inviaConvocazione($convocazione)
    {
        // Implementazione invio basata sul canale
        // Qui si integrerebbe con servizi email, SMS, etc.
        
        // Simula invio riuscito
        $convocazione->update([
            'esito_invio' => 'consegnato',
            'riferimento_invio' => 'REF-' . time(),
        ]);

        return true;
    }

    /**
     * Registra comunicazione nel protocollo
     */
    private function registraProtocollo($convocazione, $userId)
    {
        $numeroProtocollo = $this->generaNumeroProtocollo();

        RegistroProtocollo::create([
            'numero_protocollo' => $numeroProtocollo,
            'tipo_comunicazione' => 'convocazione',
            'assemblea_id' => $this->id,
            'soggetto_destinatario_id' => $convocazione->soggetto_id,
            'oggetto' => "Convocazione Assemblea {$this->tipo} - {$this->data_prima_convocazione->format('d/m/Y')}",
            'canale' => $convocazione->canale_invio,
            'data_invio' => $convocazione->data_invio,
            'esito' => $convocazione->esito_invio,
            'riferimento_esterno' => $convocazione->riferimento_invio,
            'creato_da_user_id' => $userId,
        ]);
    }

    /**
     * Genera numero protocollo progressivo
     */
    private function generaNumeroProtocollo()
    {
        $anno = date('Y');
        $ultimoProtocollo = RegistroProtocollo::whereYear('created_at', $anno)
            ->orderBy('numero_protocollo', 'desc')
            ->first();

        if ($ultimoProtocollo) {
            $numero = intval(substr($ultimoProtocollo->numero_protocollo, -4)) + 1;
        } else {
            $numero = 1;
        }

        return 'PROT/' . $anno . '/' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Calcola quorum assemblea
     */
    public function calcolaQuorum()
    {
        $totaleMillesimi = $this->stabile->unitaImmobiliari()->sum('millesimi_proprieta');
        $millesimiPresenti = $this->presenze()->sum('millesimi_rappresentati');
        
        return [
            'totale_millesimi' => $totaleMillesimi,
            'millesimi_presenti' => $millesimiPresenti,
            'percentuale_presenza' => $totaleMillesimi > 0 ? ($millesimiPresenti / $totaleMillesimi) * 100 : 0,
            'quorum_raggiunto' => $millesimiPresenti >= ($totaleMillesimi / 2),
        ];
    }

    /**
     * Genera report presenze
     */
    public function generaReportPresenze()
    {
        $presenze = $this->presenze()->with(['soggetto', 'unitaImmobiliare'])->get();
        $quorum = $this->calcolaQuorum();

        return [
            'quorum' => $quorum,
            'presenze' => $presenze,
            'totale_presenti' => $presenze->where('tipo_presenza', 'presente')->count(),
            'totale_delegati' => $presenze->where('tipo_presenza', 'delegato')->count(),
            'totale_assenti' => $this->stabile->unitaImmobiliari()->count() - $presenze->count(),
        ];
    }
}