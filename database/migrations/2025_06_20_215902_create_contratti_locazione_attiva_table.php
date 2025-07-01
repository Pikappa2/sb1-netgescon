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
        Schema::create('contratti_locazione_attiva', function (Blueprint $table) {
            $table->bigIncrements('id_contratto_locazione');
            $table->unsignedBigInteger('id_stabile');
            $table->unsignedBigInteger('id_unita_immobiliare'); // Unità di proprietà del condominio
            $table->unsignedBigInteger('id_soggetto_conduttore'); // L'inquilino
            $table->string('codice_contratto', 50)->nullable()->unique();
            $table->date('data_stipula');
            $table->date('data_inizio_validita');
            $table->integer('durata_mesi');
            $table->date('data_prima_scadenza');
            $table->date('data_termine_contratto')->nullable();
            $table->decimal('canone_mensile_base', 10, 2);
            $table->integer('giorno_scadenza_pagamento_canone')->default(5);
            $table->decimal('cauzione_importo', 10, 2)->nullable();
            $table->date('cauzione_data_versamento')->nullable();
            $table->boolean('cauzione_restituita')->default(false);
            $table->string('regime_fiscale', 50)->nullable();
            $table->boolean('opzione_cedolare_secca')->default(false);
            $table->text('riferimenti_registrazione_agenzia_entrate')->nullable();
            $table->date('data_registrazione_contratto')->nullable();
            $table->text('note_aggiornamento_istat')->nullable();
            $table->string('stato_contratto', 50)->default('ATTIVO');
            $table->text('note')->nullable();
            $table->foreign('id_stabile')->references('id_stabile')->on('stabili')->onDelete('cascade');
            $table->foreign('id_unita_immobiliare')->references('id_unita')->on('unita_immobiliari')->onDelete('cascade');
            $table->foreign('id_soggetto_conduttore')->references('id_soggetto')->on('soggetti')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratti_locazione_attiva');
    }
};
