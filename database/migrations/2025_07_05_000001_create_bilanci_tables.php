<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('bilanci')) {
            Schema::create('bilanci', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stabile_id');
                $table->unsignedBigInteger('gestione_id');
                $table->year('anno_esercizio');
                $table->date('data_inizio_esercizio');
                $table->date('data_fine_esercizio');
                $table->enum('tipo_gestione', ['ordinaria', 'riscaldamento', 'straordinaria', 'acqua', 'altro']);
                $table->string('descrizione');
                $table->enum('stato', ['bozza', 'provvisorio', 'definitivo', 'approvato', 'chiuso'])->default('bozza');
                $table->decimal('totale_entrate', 12, 2)->default(0);
                $table->decimal('totale_uscite', 12, 2)->default(0);
                $table->decimal('risultato_gestione', 12, 2)->default(0); // Avanzo/Disavanzo
                $table->date('data_approvazione')->nullable();
                $table->unsignedBigInteger('approvato_da_user_id')->nullable();
                $table->date('data_chiusura')->nullable();
                $table->unsignedBigInteger('chiuso_da_user_id')->nullable();
                $table->text('note')->nullable();
                $table->integer('versione')->default(1);
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('stabile_id')->references('id_stabile')->on('stabili')->onDelete('cascade');
                $table->foreign('gestione_id')->references('id_gestione')->on('gestioni')->onDelete('cascade');
                $table->foreign('approvato_da_user_id')->references('id')->on('users')->onDelete('set null');
                $table->foreign('chiuso_da_user_id')->references('id')->on('users')->onDelete('set null');
                $table->index(['stabile_id', 'anno_esercizio', 'tipo_gestione']);
            });
        }

        if (!Schema::hasTable('piano_conti')) {
            Schema::create('piano_conti', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stabile_id');
                $table->string('codice', 20);
                $table->string('descrizione');
                $table->enum('tipo_conto', ['attivo', 'passivo', 'costo', 'ricavo']);
                $table->enum('categoria', ['patrimoniale', 'economico']);
                $table->unsignedBigInteger('conto_padre_id')->nullable();
                $table->integer('livello')->default(1);
                $table->boolean('attivo')->default(true);
                $table->integer('ordinamento')->default(0);
                $table->timestamps();

                $table->foreign('stabile_id')->references('id_stabile')->on('stabili')->onDelete('cascade');
                $table->foreign('conto_padre_id')->references('id')->on('piano_conti')->onDelete('set null');
                $table->unique(['stabile_id', 'codice']);
            });
        }

        if (!Schema::hasTable('scritture_bilancio')) {
            Schema::create('scritture_bilancio', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('bilancio_id');
                $table->string('numero_scrittura', 50);
                $table->date('data_scrittura');
                $table->string('descrizione');
                $table->enum('tipo_scrittura', ['apertura', 'gestione', 'chiusura', 'rettifica']);
                $table->decimal('importo_totale', 12, 2);
                $table->string('riferimento_documento')->nullable();
                $table->unsignedBigInteger('movimento_contabile_id')->nullable();
                $table->unsignedBigInteger('creato_da_user_id');
                $table->text('note')->nullable();
                $table->timestamps();

                $table->foreign('bilancio_id')->references('id')->on('bilanci')->onDelete('cascade');
                $table->foreign('movimento_contabile_id')->references('id')->on('movimenti_contabili')->onDelete('set null');
                $table->foreign('creato_da_user_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['bilancio_id', 'data_scrittura']);
            });
        }

        if (!Schema::hasTable('dettagli_scritture_bilancio')) {
            Schema::create('dettagli_scritture_bilancio', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('scrittura_bilancio_id');
                $table->unsignedBigInteger('conto_id');
                $table->decimal('importo_dare', 12, 2)->default(0);
                $table->decimal('importo_avere', 12, 2)->default(0);
                $table->string('descrizione_dettaglio')->nullable();
                $table->timestamps();

                $table->foreign('scrittura_bilancio_id')->references('id')->on('scritture_bilancio')->onDelete('cascade');
                $table->foreign('conto_id')->references('id')->on('piano_conti')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('ripartizioni_bilancio')) {
            Schema::create('ripartizioni_bilancio', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('scrittura_bilancio_id');
                $table->unsignedBigInteger('unita_immobiliare_id');
                $table->unsignedBigInteger('tabella_millesimale_id');
                $table->decimal('quota_calcolata', 10, 2);
                $table->decimal('quota_modificata', 10, 2)->nullable();
                $table->decimal('quota_finale', 10, 2);
                $table->integer('versione')->default(1);
                $table->unsignedBigInteger('modificato_da_user_id')->nullable();
                $table->string('motivo_modifica')->nullable();
                $table->timestamp('data_modifica')->nullable();
                $table->timestamps();

                $table->foreign('scrittura_bilancio_id')->references('id')->on('scritture_bilancio')->onDelete('cascade');
                $table->foreign('unita_immobiliare_id')->references('id_unita')->on('unita_immobiliari')->onDelete('cascade');
                $table->foreign('tabella_millesimale_id')->references('id_tabella_millesimale')->on('tabelle_millesimali')->onDelete('cascade');
                $table->foreign('modificato_da_user_id')->references('id')->on('users')->onDelete('set null');
            });
        }

        if (!Schema::hasTable('conguagli')) {
            Schema::create('conguagli', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('bilancio_id');
                $table->unsignedBigInteger('unita_immobiliare_id');
                $table->unsignedBigInteger('soggetto_id');
                $table->decimal('totale_rate_pagate', 10, 2)->default(0);
                $table->decimal('totale_spese_effettive', 10, 2)->default(0);
                $table->decimal('conguaglio_dovuto', 10, 2)->default(0); // Positivo = a credito, Negativo = a debito
                $table->enum('tipo_conguaglio', ['a_credito', 'a_debito', 'pareggio']);
                $table->enum('stato', ['calcolato', 'confermato', 'pagato', 'rimborsato'])->default('calcolato');
                $table->date('data_calcolo');
                $table->date('data_pagamento')->nullable();
                $table->decimal('importo_pagato', 10, 2)->default(0);
                $table->text('note')->nullable();
                $table->timestamps();

                $table->foreign('bilancio_id')->references('id')->on('bilanci')->onDelete('cascade');
                $table->foreign('unita_immobiliare_id')->references('id_unita')->on('unita_immobiliari')->onDelete('cascade');
                $table->foreign('soggetto_id')->references('id_soggetto')->on('soggetti')->onDelete('cascade');
                $table->index(['bilancio_id', 'tipo_conguaglio']);
            });
        }

        if (!Schema::hasTable('rate_conguaglio')) {
            Schema::create('rate_conguaglio', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('conguaglio_id');
                $table->string('numero_rata', 50)->unique();
                $table->string('descrizione');
                $table->date('data_scadenza');
                $table->decimal('importo_rata', 10, 2);
                $table->enum('stato_pagamento', ['da_pagare', 'parziale', 'pagata', 'insoluta'])->default('da_pagare');
                $table->decimal('importo_pagato', 10, 2)->default(0);
                $table->date('data_pagamento')->nullable();
                $table->boolean('rateizzato')->default(false);
                $table->integer('numero_rate_totali')->default(1);
                $table->integer('numero_rata_corrente')->default(1);
                $table->integer('versione')->default(1);
                $table->timestamps();

                $table->foreign('conguaglio_id')->references('id')->on('conguagli')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('quadrature')) {
            Schema::create('quadrature', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('bilancio_id');
                $table->date('data_quadratura');
                $table->decimal('saldo_banca_effettivo', 12, 2);
                $table->decimal('saldo_contabile_calcolato', 12, 2);
                $table->decimal('differenza', 12, 2);
                $table->decimal('totale_crediti_condomini', 12, 2);
                $table->decimal('totale_debiti_condomini', 12, 2);
                $table->decimal('totale_rate_emesse', 12, 2);
                $table->decimal('totale_rate_incassate', 12, 2);
                $table->boolean('quadratura_ok')->default(false);
                $table->text('note_differenze')->nullable();
                $table->unsignedBigInteger('verificato_da_user_id');
                $table->timestamps();

                $table->foreign('bilancio_id')->references('id')->on('bilanci')->onDelete('cascade');
                $table->foreign('verificato_da_user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('rimborsi_assicurativi')) {
            Schema::create('rimborsi_assicurativi', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('bilancio_id');
                $table->string('numero_sinistro');
                $table->string('compagnia_assicurativa');
                $table->date('data_sinistro');
                $table->date('data_denuncia');
                $table->decimal('importo_richiesto', 10, 2);
                $table->decimal('importo_liquidato', 10, 2)->default(0);
                $table->enum('stato', ['denunciato', 'in_valutazione', 'liquidato', 'rifiutato', 'chiuso']);
                $table->enum('tipo_accredito', ['rate_condomini', 'pagamento_diretto', 'fondo_comune']);
                $table->date('data_liquidazione')->nullable();
                $table->text('descrizione_sinistro');
                $table->text('note')->nullable();
                $table->timestamps();

                $table->foreign('bilancio_id')->references('id')->on('bilanci')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('reminder_bilancio')) {
            Schema::create('reminder_bilancio', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('bilancio_id');
                $table->enum('tipo_reminder', ['scadenza_spesa', 'rinnovo_contratto', 'verifica_quadratura', 'chiusura_esercizio']);
                $table->string('descrizione');
                $table->date('data_scadenza');
                $table->boolean('ricorrente')->default(false);
                $table->enum('frequenza', ['mensile', 'trimestrale', 'semestrale', 'annuale'])->nullable();
                $table->integer('giorni_preavviso')->default(30);
                $table->enum('stato', ['attivo', 'eseguito', 'annullato'])->default('attivo');
                $table->boolean('notifica_inviata')->default(false);
                $table->timestamp('data_notifica')->nullable();
                $table->unsignedBigInteger('ticket_generato_id')->nullable();
                $table->timestamps();

                $table->foreign('bilancio_id')->references('id')->on('bilanci')->onDelete('cascade');
                $table->foreign('ticket_generato_id')->references('id')->on('tickets')->onDelete('set null');
            });
        }

        if (!Schema::hasTable('log_modifiche_bilancio')) {
            Schema::create('log_modifiche_bilancio', function (Blueprint $table) {
                $table->id();
                $table->string('entita'); // 'bilancio', 'scrittura', 'ripartizione', 'conguaglio'
                $table->unsignedBigInteger('entita_id');
                $table->integer('versione_precedente');
                $table->integer('versione_nuova');
                $table->unsignedBigInteger('utente_id');
                $table->string('tipo_operazione'); // 'create', 'update', 'delete', 'approve', 'close'
                $table->text('motivo');
                $table->json('dati_precedenti')->nullable();
                $table->json('dati_nuovi');
                $table->json('diff')->nullable(); // Differenze stile GIT
                $table->timestamps();

                $table->foreign('utente_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['entita', 'entita_id', 'versione_nuova']);
            });
        }

        if (!Schema::hasTable('automazioni_fine_anno')) {
            Schema::create('automazioni_fine_anno', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('bilancio_id');
                $table->enum('tipo_automazione', ['chiusura_conti', 'riporto_saldi', 'calcolo_conguagli', 'generazione_rate']);
                $table->string('descrizione');
                $table->enum('stato', ['programmata', 'in_esecuzione', 'completata', 'errore']);
                $table->date('data_programmata');
                $table->timestamp('data_esecuzione')->nullable();
                $table->json('parametri')->nullable();
                $table->json('risultato')->nullable();
                $table->text('log_esecuzione')->nullable();
                $table->text('errori')->nullable();
                $table->timestamps();

                $table->foreign('bilancio_id')->references('id')->on('bilanci')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('automazioni_fine_anno');
        Schema::dropIfExists('log_modifiche_bilancio');
        Schema::dropIfExists('reminder_bilancio');
        Schema::dropIfExists('rimborsi_assicurativi');
        Schema::dropIfExists('quadrature');
        Schema::dropIfExists('rate_conguaglio');
        Schema::dropIfExists('conguagli');
        Schema::dropIfExists('ripartizioni_bilancio');
        Schema::dropIfExists('dettagli_scritture_bilancio');
        Schema::dropIfExists('scritture_bilancio');
        Schema::dropIfExists('piano_conti');
        Schema::dropIfExists('bilanci');
    }
};