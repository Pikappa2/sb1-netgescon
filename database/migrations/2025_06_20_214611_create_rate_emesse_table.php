<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rate_emesse', function (Blueprint $table) {
            $table->bigIncrements('id_rata_emessa');
            $table->unsignedBigInteger('id_piano_rateizzazione'); // A quale piano di rateizzazione appartiene
            $table->unsignedBigInteger('id_unita_immobiliare'); // A quale unità immobiliare si riferisce
            $table->unsignedBigInteger('id_soggetto_responsabile'); // Chi deve pagare la rata
            $table->integer('numero_rata_progressivo');
            $table->text('descrizione')->nullable();
            $table->decimal('importo_originario_unita', 15, 2); // Importo totale per l'unità
            $table->decimal('percentuale_addebito_soggetto', 7, 4)->default(100.0000); // % per questo soggetto
            $table->decimal('importo_addebitato_soggetto', 15, 2); // Importo effettivo per questo soggetto
            $table->date('data_emissione');
            $table->date('data_scadenza');
            $table->string('stato_rata', 50)->default('EMESSA');
            $table->date('data_ultimo_pagamento')->nullable();
            $table->decimal('importo_pagato', 15, 2)->default(0.00);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('id_transazione_contabile_emissione')->nullable(); // Link alla scrittura di accertamento credito
            $table->foreign('id_piano_rateizzazione')->references('id_piano_rateizzazione')->on('piani_rateizzazione')->onDelete('cascade');
            $table->foreign('id_unita_immobiliare')->references('id_unita')->on('unita_immobiliari')->onDelete('cascade');
            $table->foreign('id_soggetto_responsabile')->references('id_soggetto')->on('soggetti')->onDelete('restrict');
            $table->foreign('id_transazione_contabile_emissione')->references('id_transazione')->on('transazioni_contabili')->onDelete('set null');
            $table->unique(['id_piano_rateizzazione', 'id_unita_immobiliare', 'id_soggetto_responsabile', 'numero_rata_progressivo'], 'unique_rata_per_soggetto_unita');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rate_emesse');
    }
};
