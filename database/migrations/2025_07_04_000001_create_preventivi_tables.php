<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('preventivi')) {
            Schema::create('preventivi', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stabile_id');
                $table->year('anno_gestione');
                $table->enum('tipo_gestione', ['ordinaria', 'riscaldamento', 'straordinaria', 'acqua', 'altro']);
                $table->string('descrizione');
                $table->enum('stato', ['bozza', 'provvisorio', 'definitivo', 'approvato', 'archiviato'])->default('bozza');
                $table->decimal('importo_totale', 12, 2)->default(0);
                $table->date('data_creazione');
                $table->date('data_approvazione')->nullable();
                $table->unsignedBigInteger('approvato_da_user_id')->nullable();
                $table->text('note')->nullable();
                $table->integer('versione')->default(1);
                $table->timestamps();
                $table->softDeletes();
                $table->foreign('stabile_id')->references('id_stabile')->on('stabili')->onDelete('cascade');
                $table->foreign('approvato_da_user_id')->references('id')->on('users')->onDelete('set null');
                $table->index(['stabile_id', 'anno_gestione', 'tipo_gestione']);
            });
        }

        if (!Schema::hasTable('voci_preventivo')) {
            Schema::create('voci_preventivo', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('preventivo_id');
                $table->string('codice', 20);
                $table->string('descrizione');
                $table->decimal('importo_preventivato', 10, 2);
                $table->decimal('importo_effettivo', 10, 2)->default(0);
                $table->unsignedBigInteger('tabella_millesimale_id')->nullable();
                $table->unsignedBigInteger('voce_spesa_id')->nullable();
                $table->boolean('ricorrente')->default(false);
                $table->enum('frequenza', ['mensile', 'trimestrale', 'semestrale', 'annuale'])->nullable();
                $table->date('data_scadenza_prevista')->nullable();
                $table->integer('ordinamento')->default(0);
                $table->text('note')->nullable();
                $table->timestamps();
                $table->foreign('preventivo_id')->references('id_preventivo')->on('preventivi')->onDelete('set null');
                $table->foreign('tabella_millesimale_id')->references('id')->on('tabelle_millesimali')->onDelete('set null');
                $table->foreign('voce_spesa_id')->references('id')->on('voci_spesa')->onDelete('set null');
            });
        }

        if (!Schema::hasTable('ripartizioni_preventivo')) {
            Schema::create('ripartizioni_preventivo', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('voce_preventivo_id');
                $table->unsignedBigInteger('unita_immobiliare_id');
                $table->decimal('quota_calcolata', 10, 2);
                $table->decimal('quota_modificata', 10, 2)->nullable();
                $table->decimal('quota_finale', 10, 2);
                $table->integer('versione')->default(1);
                $table->unsignedBigInteger('modificato_da_user_id')->nullable();
                $table->string('motivo_modifica')->nullable();
                $table->timestamp('data_modifica')->nullable();
                $table->timestamps();
                $table->foreign('voce_preventivo_id')->references('id_voce_preventivo')->on('voci_preventivo')->onDelete('cascade');
                $table->foreign('unita_immobiliare_id')->references('id_unita')->on('unita_immobiliari')->onDelete('cascade');
                $table->foreign('modificato_da_user_id')->references('id')->on('users')->onDelete('set null');
                $table->unique(['voce_preventivo_id', 'unita_immobiliare_id'], 'unique_voce_unita');
            });
        }

        if (!Schema::hasTable('rate')) {
            Schema::create('rate', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('preventivo_id');
                $table->string('numero_rata', 50)->unique();
                $table->string('descrizione');
                $table->date('data_scadenza');
                $table->enum('stato', ['bozza', 'emessa', 'modificata', 'annullata'])->default('bozza');
                $table->decimal('importo_totale', 12, 2);
                $table->integer('versione')->default(1);
                $table->unsignedBigInteger('creato_da_user_id');
                $table->text('note')->nullable();
                $table->timestamps();
                $table->foreign('preventivo_id')->references('id_preventivo')->on('preventivi')->onDelete('cascade');
                $table->foreign('creato_da_user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('rate_unita')) {
            Schema::create('rate_unita', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('rata_id');
                $table->unsignedBigInteger('unita_immobiliare_id');
                $table->unsignedBigInteger('soggetto_id');
                $table->decimal('importo_originale', 10, 2);
                $table->decimal('importo_modificato', 10, 2)->nullable();
                $table->decimal('importo_finale', 10, 2);
                $table->decimal('importo_pagato', 10, 2)->default(0);
                $table->enum('stato_pagamento', ['da_pagare', 'parziale', 'pagata', 'insoluta'])->default('da_pagare');
                $table->date('data_pagamento')->nullable();
                $table->integer('versione')->default(1);
                $table->unsignedBigInteger('modificato_da_user_id')->nullable();
                $table->string('motivo_modifica')->nullable();
                $table->timestamp('data_modifica')->nullable();
                $table->timestamps();
                $table->foreign('rata_id')->references('id')->on('rate')->onDelete('cascade');
                $table->foreign('unita_immobiliare_id')->references('id_unita')->on('unita_immobiliari')->onDelete('cascade');
                $table->foreign('soggetto_id')->references('id_soggetto')->on('soggetti')->onDelete('cascade');
                $table->foreign('modificato_da_user_id')->references('id')->on('users')->onDelete('set null');
                $table->unique(['rata_id', 'unita_immobiliare_id'], 'unique_rata_unita');
            });
        }

        if (!Schema::hasTable('incassi')) {
            Schema::create('incassi', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('rata_unita_id');
                $table->date('data_incasso');
                $table->decimal('importo', 10, 2);
                $table->enum('metodo_pagamento', ['bonifico', 'contanti', 'assegno', 'pos', 'altro']);
                $table->string('riferimento_bancario')->nullable();
                $table->string('numero_documento')->nullable();
                $table->unsignedBigInteger('movimento_bancario_id')->nullable();
                $table->text('note')->nullable();
                $table->timestamps();
                $table->foreign('rata_unita_id')->references('id')->on('rate_unita')->onDelete('cascade');
                $table->index(['data_incasso', 'metodo_pagamento']);
            });
        }

        if (!Schema::hasTable('log_modifiche_preventivo')) {
            Schema::create('log_modifiche_preventivo', function (Blueprint $table) {
                $table->id();
                $table->string('entita'); // 'preventivo', 'voce', 'ripartizione', 'rata'
                $table->unsignedBigInteger('entita_id');
                $table->integer('versione_precedente');
                $table->integer('versione_nuova');
                $table->unsignedBigInteger('utente_id');
                $table->string('tipo_operazione'); // 'create', 'update', 'delete'
                $table->text('motivo');
                $table->json('dati_precedenti')->nullable();
                $table->json('dati_nuovi');
                $table->json('diff')->nullable(); // Differenze stile GIT
                $table->timestamps();
                $table->foreign('utente_id')->references('id')->on('users')->onDelete('cascade');
                $table->index(['entita', 'entita_id', 'versione_nuova']);
            });
        }

        if (!Schema::hasTable('pianificazione_spese')) {
            Schema::create('pianificazione_spese', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stabile_id');
                $table->unsignedBigInteger('preventivo_id')->nullable();
                $table->string('descrizione');
                $table->decimal('importo_previsto', 10, 2);
                $table->date('data_scadenza_prevista');
                $table->enum('tipo', ['ricorrente', 'straordinaria', 'manutenzione']);
                $table->enum('stato', ['pianificata', 'confermata', 'pagata', 'annullata'])->default('pianificata');
                $table->boolean('notifica_inviata')->default(false);
                $table->integer('giorni_preavviso')->default(30);
                $table->text('note')->nullable();
                $table->timestamps();
                $table->foreign('stabile_id')->references('id_stabile')->on('stabili')->onDelete('cascade');
                $table->foreign('preventivo_id')->references('id_preventivo')->on('preventivi')->onDelete('set null');
                $table->index(['data_scadenza_prevista', 'stato']);
            });
        }

        if (!Schema::hasTable('configurazioni_banche')) {
            Schema::create('configurazioni_banche', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('stabile_id');
                $table->string('nome_banca');
                $table->string('iban', 34);
                $table->string('api_endpoint')->nullable();
                $table->text('credenziali_api')->nullable(); // Encrypted
                $table->enum('tipo_importazione', ['api', 'csv', 'cbi', 'manuale']);
                $table->json('mapping_campi')->nullable();
                $table->boolean('attiva')->default(true);
                $table->timestamp('ultima_sincronizzazione')->nullable();
                $table->timestamps();
                $table->foreign('stabile_id')->references('id_stabile')->on('stabili')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('configurazioni_banche');
        Schema::dropIfExists('pianificazione_spese');
        Schema::dropIfExists('log_modifiche_preventivo');
        Schema::dropIfExists('incassi');
        Schema::dropIfExists('rate_unita');
        Schema::dropIfExists('rate');
        Schema::dropIfExists('ripartizioni_preventivo');
        Schema::dropIfExists('voci_preventivo');
        Schema::dropIfExists('preventivi');
    }
};