<?php

namespace App\Livewire\Contabilita;

use Livewire\Component;

class RegistrazioneTest extends Component
{
    public $fornitore_id;
    public $data_documento;
    public $descrizione;
    public $importo_totale;
    public $percentuale_ra = 0;
    public $voci = [];
    public $allegati = [];
    public $conti = [];
    public $tabelle = [];
    public $fornitori = [];
    public $totale_spese = 0;
    public $totale_ra = 0;
    public $totale_da_pagare = 0;

    public function mount()
    {
        $this->conti = [
            ['id' => 1, 'descrizione' => 'Manutenzione'],
            ['id' => 2, 'descrizione' => 'Pulizie'],
        ];
        $this->tabelle = [
            ['id' => 1, 'nome_tabella' => 'Tabella A'],
            ['id' => 2, 'nome_tabella' => 'Tabella B'],
        ];
        $this->fornitori = [
            ['id' => 1, 'nome' => 'Mario Rossi'],
            ['id' => 2, 'nome' => 'Studio Bianchi'],
        ];
        $this->aggiungiVoce();


    }

    public function updated($property)
    {
        $this->ricalcolaTotali();
    }

    public function aggiungiVoce()
    {
        $this->voci[] = [
            'conto_id' => '',
            'tabella_id' => '',
            'descrizione' => '',
            'importo' => 0,
            'ra_imputata' => 0,
        ];
        $this->ricalcolaTotali();
    }

    public function rimuoviVoce($index)
    {
        unset($this->voci[$index]);
        $this->voci = array_values($this->voci);
        $this->ricalcolaTotali();
    }

    public function ricalcolaTotali()
    {
        $this->totale_spese = collect($this->voci)->sum('importo');
        $this->totale_ra = $this->percentuale_ra > 0
            ? round($this->totale_spese * $this->percentuale_ra / 100, 2)
            : 0;
        foreach ($this->voci as $i => $voce) {
            $importo = $voce['importo'] ?? 0;
            $this->voci[$i]['ra_imputata'] = $this->totale_spese > 0
                ? round($importo * $this->totale_ra / $this->totale_spese, 2)
                : 0;
        }
        $this->totale_da_pagare = $this->totale_spese - $this->totale_ra;
    }

    public function salvaRegistrazione()
    {
        session()->flash('success', 'Registrazione di prova salvata!');
    }

    public function render()
    {
        return view('livewire.contabilita.registrazione-test');
    }
    public static function layout()
    {
        return 'layouts.app';
    }
}
