<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabella gestioni
        Schema::create('gestioni', function (Blueprint $table) {
            $table->id('id_gestione');
            $table->unsignedBigInteger('stabile_id');
            $table->year('anno_gestione');
            $table->enum('tipo_gestione', ['ordinaria', 'riscaldamento', 'straordinaria', 'acqua', 'altro']);
            $table->date('data_inizio');
            $table->date('data_fine');
            $table->string('descrizione')->nullable();
            $table->enum('stato', ['bozza', 'attiva', 'chiusa'])->default('bozza');
            $table->boolean('preventivo_approvato')->default(false);
            $table->date('data_approvazione')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('stabile_id')->references('id_stabile')->on('stabili')->onDelete('cascade');
            $table->index(['stabile_id', 'anno_gestione', 'tipo_gestione']);
        });

        // Tabella voci_spesa
        Schema::create('voci_spesa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stabile_id');
            $table->string('codice', 20);
            $table->string('descrizione');
            $table->enum('tipo_gestione', ['ordinaria', 'riscaldamento', 'straordinaria', 'acqua', 'altro']);
            $table->string('categoria', 100)->nullable();
            $table->unsignedBigInteger('tabella_millesimale_default_id')->nullable();
            $table->decimal('ritenuta_acconto_default', 5, 2)->default(0);
            $table->boolean('attiva')->default(true);
            $table->integer('ordinamento')->default(0);
            $table->timestamps();
            
            $table->foreign('stabile_id')->references('id_stabile')->on('stabili')->onDelete('cascade');
            $table->unique(['stabile_id', 'codice']);
        });

        // Tabella tabelle_millesimali
        Schema::create('tabelle_millesimali', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stabile_id');
            $table->string('nome');
            $table->text('descrizione')->nullable();
            $table->enum('tipo', ['proprieta', 'riscaldamento', 'ascensore', 'scale', 'altro']);
            $table->boolean('attiva')->default(true);
            $table->date('data_approvazione')->nullable();
            $table->integer('ordinamento')->default(0);
            $table->timestamps();
            
            $table->foreign('stabile_id')->references('id_stabile')->on('stabili')->onDelete('cascade');
        });

        // Tabella dettagli_tabelle_millesimali
        Schema::create('dettagli_tabelle_millesimali', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tabella_millesimale_id');
            $table->unsignedBigInteger('unita_immobiliare_id');
            $table->decimal('millesimi', 10, 4);
            $table->text('note')->nullable();
            $table->timestamps();
            
            $table->foreign('tabella_millesimale_id')->references('id')->on('tabelle_millesimali')->onDelete('cascade');
            $table->foreign('unita_immobiliare_id')->references('id_unita')->on('unita_immobiliari')->onDelete('cascade');
            $table->unique(['tabella_millesimale_id', 'unita_immobiliare_id'], 'unique_tabella_unita');
        });

        // Tabella documenti
        Schema::create('documenti', function (Blueprint $table) {
            $table->id();
            $table->morphs('documentable');
            $table->string('nome_file');
            $table->string('path_file');
            $table->string('tipo_documento', 100);
            $table->unsignedBigInteger('dimensione_file')->nullable();
            $table->string('mime_type', 100)->nullable();
            $table->text('descrizione')->nullable();
            $table->json('xml_data')->nullable();
            $table->string('hash_file', 64)->nullable();
            $table->timestamps();
            
            $table->index(['documentable_type', 'documentable_id']);
            $table->index('tipo_documento');
        });

        // Tabella movimenti_contabili
        Schema::create('movimenti_contabili', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stabile_id');
            $table->unsignedBigInteger('gestione_id');
            $table->unsignedBigInteger('fornitore_id')->nullable();
            $table->unsignedBigInteger('documento_id')->nullable();
            $table->string('protocollo', 50)->unique();
            $table->date('data_registrazione');
            $table->date('data_documento');
            $table->string('numero_documento', 50);
            $table->text('descrizione');
            $table->enum('tipo_movimento', ['entrata', 'uscita']);
            $table->decimal('importo_totale', 10, 2);
            $table->decimal('ritenuta_acconto', 10, 2)->default(0);
            $table->decimal('importo_netto', 10, 2);
            $table->enum('stato', ['bozza', 'registrato', 'contabilizzato', 'annullato'])->default('bozza');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('stabile_id')->references('id_stabile')->on('stabili')->onDelete('cascade');
            $table->foreign('gestione_id')->references('id_gestione')->on('gestioni')->onDelete('cascade');
            $table->foreign('fornitore_id')->references('id_fornitore')->on('fornitori')->onDelete('set null');
            $table->foreign('documento_id')->references('id')->on('documenti')->onDelete('set null');
            
            $table->index(['stabile_id', 'data_registrazione']);
            $table->index(['gestione_id', 'tipo_movimento']);
        });

        // Tabella dettagli_movimenti (partita doppia)
        Schema::create('dettagli_movimenti', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movimento_id');
            $table->unsignedBigInteger('conto_id')->nullable();
            $table->unsignedBigInteger('voce_spesa_id')->nullable();
            $table->unsignedBigInteger('tabella_millesimale_id')->nullable();
            $table->text('descrizione')->nullable();
            $table->decimal('importo_dare', 10, 2)->default(0);
            $table->decimal('importo_avere', 10, 2)->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
            
            $table->foreign('movimento_id')->references('id')->on('movimenti_contabili')->onDelete('cascade');
            $table->foreign('voce_spesa_id')->references('id')->on('voci_spesa')->onDelete('set null');
            $table->foreign('tabella_millesimale_id')->references('id')->on('tabelle_millesimali')->onDelete('set null');
        });

        // Tabella banche
        Schema::create('banche', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stabile_id');
            $table->string('denominazione');
            $table->string('iban', 34);
            $table->string('bic_swift', 11)->nullable();
            $table->string('agenzia')->nullable();
            $table->string('indirizzo_agenzia')->nullable();
            $table->enum('tipo_conto', ['corrente', 'deposito', 'altro'])->default('corrente');
            $table->decimal('saldo_iniziale', 10, 2)->default(0);
            $table->date('data_apertura')->nullable();
            $table->enum('stato', ['attivo', 'chiuso'])->default('attivo');
            $table->text('note')->nullable();
            $table->timestamps();
            
            $table->foreign('stabile_id')->references('id_stabile')->on('stabili')->onDelete('cascade');
            $table->unique(['stabile_id', 'iban']);
        });

        // Tabella movimenti_bancari
        Schema::create('movimenti_bancari', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banca_id');
            $table->unsignedBigInteger('movimento_contabile_id')->nullable();
            $table->date('data_valuta');
            $table->date('data_contabile');
            $table->enum('tipo_movimento', ['entrata', 'uscita']);
            $table->decimal('importo', 10, 2);
            $table->text('causale');
            $table->string('beneficiario')->nullable();
            $table->string('ordinante')->nullable();
            $table->string('cro_tro', 50)->nullable();
            $table->enum('stato_riconciliazione', ['da_riconciliare', 'riconciliato', 'sospeso'])->default('da_riconciliare');
            $table->text('note')->nullable();
            $table->timestamps();
            
            $table->foreign('banca_id')->references('id')->on('banche')->onDelete('cascade');
            $table->foreign('movimento_contabile_id')->references('id')->on('movimenti_contabili')->onDelete('set null');
            
            $table->index(['banca_id', 'data_valuta']);
            $table->index('stato_riconciliazione');
        });

        // Tabella rate
        Schema::create('rate', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gestione_id');
            $table->unsignedBigInteger('unita_immobiliare_id');
            $table->unsignedBigInteger('soggetto_id');
            $table->integer('numero_rata');
            $table->string('descrizione');
            $table->decimal('importo', 10, 2);
            $table->date('data_scadenza');
            $table->date('data_pagamento')->nullable();
            $table->decimal('importo_pagato', 10, 2)->default(0);
            $table->enum('stato', ['da_pagare', 'pagata', 'parziale', 'insoluta'])->default('da_pagare');
            $table->enum('tipo_rata', ['ordinaria', 'riscaldamento', 'straordinaria', 'conguaglio'])->default('ordinaria');
            $table->text('note')->nullable();
            $table->timestamps();
            
            $table->foreign('gestione_id')->references('id_gestione')->on('gestioni')->onDelete('cascade');
            $table->foreign('unita_immobiliare_id')->references('id_unita')->on('unita_immobiliari')->onDelete('cascade');
            $table->foreign('soggetto_id')->references('id_soggetto')->on('soggetti')->onDelete('cascade');
            
            $table->index(['gestione_id', 'data_scadenza']);
            $table->index(['unita_immobiliare_id', 'stato']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate');
        Schema::dropIfExists('movimenti_bancari');
        Schema::dropIfExists('banche');
        Schema::dropIfExists('dettagli_movimenti');
        Schema::dropIfExists('movimenti_contabili');
        Schema::dropIfExists('documenti');
        Schema::dropIfExists('dettagli_tabelle_millesimali');
        Schema::dropIfExists('tabelle_millesimali');
        Schema::dropIfExists('voci_spesa');
        Schema::dropIfExists('gestioni');
    }
};