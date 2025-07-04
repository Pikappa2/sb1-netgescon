<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabella rate_emesse con tutte le foreign key.
     */
    public function up(): void
    {
        if (!Schema::hasTable('rate_emesse')) {
            Schema::create('rate_emesse', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('piano_rateizzazione_id');
                $table->unsignedBigInteger('unita_immobiliare_id');
                $table->unsignedBigInteger('soggetto_responsabile_id');
                $table->integer('numero_rata_progressivo');
                $table->text('descrizione')->nullable();
                $table->decimal('importo_originario_unita', 15, 2);
                $table->decimal('percentuale_addebito_soggetto', 7, 4)->default(100.0000);
                $table->decimal('importo_addebitato_soggetto', 15, 2);
                $table->date('data_emissione');
                $table->date('data_scadenza');
                $table->string('stato_rata', 50)->default('EMESSA');
                $table->date('data_ultimo_pagamento')->nullable();
                $table->decimal('importo_pagato', 15, 2)->default(0.00);
                $table->text('note')->nullable();
                $table->unsignedBigInteger('transazione_contabile_emissione_id')->nullable();
                $table->unique(['piano_rateizzazione_id', 'unita_immobiliare_id', 'soggetto_responsabile_id', 'numero_rata_progressivo'], 'unique_rata_per_soggetto_unita');
                $table->timestamps();
            });
        }
        // Foreign key su piani_rateizzazione
        if (Schema::hasTable('rate_emesse') && Schema::hasTable('piani_rateizzazione')) {
            $fkExists = \Illuminate\Support\Facades\DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'rate_emesse' AND COLUMN_NAME = 'piano_rateizzazione_id' AND CONSTRAINT_SCHEMA = DATABASE() AND REFERENCED_TABLE_NAME IS NOT NULL");
            if (empty($fkExists)) {
                Schema::table('rate_emesse', function (Blueprint $table) {
                    $table->foreign('piano_rateizzazione_id')
                        ->references('id_piano_rateizzazione')
                        ->on('piani_rateizzazione')
                        ->onDelete('cascade');
                });
            }
        }
        // Foreign key su unita_immobiliari
        if (Schema::hasTable('rate_emesse') && Schema::hasTable('unita_immobiliari')) {
            $fkExists = \Illuminate\Support\Facades\DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'rate_emesse' AND COLUMN_NAME = 'unita_immobiliare_id' AND CONSTRAINT_SCHEMA = DATABASE() AND REFERENCED_TABLE_NAME IS NOT NULL");
            if (empty($fkExists)) {
                Schema::table('rate_emesse', function (Blueprint $table) {
                    $table->foreign('unita_immobiliare_id')
                        ->references('id_unita')
                        ->on('unita_immobiliari')
                        ->onDelete('cascade');
                });
            }
        }
        // Foreign key su soggetti
        if (Schema::hasTable('rate_emesse') && Schema::hasTable('soggetti')) {
            $fkExists = \Illuminate\Support\Facades\DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'rate_emesse' AND COLUMN_NAME = 'soggetto_responsabile_id' AND CONSTRAINT_SCHEMA = DATABASE() AND REFERENCED_TABLE_NAME IS NOT NULL");
            if (empty($fkExists)) {
                Schema::table('rate_emesse', function (Blueprint $table) {
                    $table->foreign('soggetto_responsabile_id')
                        ->references('id_soggetto')
                        ->on('soggetti')
                        ->onDelete('restrict');
                });
            }
        }
        // Foreign key su transazioni_contabili
        if (Schema::hasTable('rate_emesse') && Schema::hasTable('transazioni_contabili')) {
            $fkExists = \Illuminate\Support\Facades\DB::select("SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_NAME = 'rate_emesse' AND COLUMN_NAME = 'transazione_contabile_emissione_id' AND CONSTRAINT_SCHEMA = DATABASE() AND REFERENCED_TABLE_NAME IS NOT NULL");
            if (empty($fkExists)) {
                Schema::table('rate_emesse', function (Blueprint $table) {
                    $table->foreign('transazione_contabile_emissione_id')
                        ->references('id_transazione')
                        ->on('transazioni_contabili')
                        ->onDelete('set null');
                });
            }
        }
    }
    public function down(): void
    {
        Schema::dropIfExists('rate_emesse');
    }
};
