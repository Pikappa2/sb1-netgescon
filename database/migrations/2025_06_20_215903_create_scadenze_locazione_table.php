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
        Schema::create('scadenze_locazione', function (Blueprint $table) {
            $table->bigIncrements('id_scadenza_locazione');
            $table->unsignedBigInteger('id_contratto_locazione');
            $table->string('tipo_scadenza', 50);
            $table->text('descrizione')->nullable();
            $table->date('data_scadenza');
            $table->decimal('importo_dovuto', 10, 2)->nullable();
            $table->date('data_pagamento')->nullable();
            $table->decimal('importo_pagato', 10, 2)->nullable();
            $table->unsignedBigInteger('id_transazione_contabile_collegata')->nullable();
            $table->string('stato_scadenza', 50)->default('APERTA');
            $table->text('note')->nullable();
            $table->foreign('id_contratto_locazione')->references('id_contratto_locazione')->on('contratti_locazione_attiva')->onDelete('cascade');
            $table->foreign('id_transazione_contabile_collegata')->references('id_transazione')->on('transazioni_contabili')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scadenze_locazione');
    }
};
