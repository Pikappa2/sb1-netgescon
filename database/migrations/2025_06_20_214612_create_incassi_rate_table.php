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
        Schema::create('incassi_rate', function (Blueprint $table) {
            $table->bigIncrements('id_incasso_rata');
            $table->unsignedBigInteger('id_rata_emessa');
            $table->unsignedBigInteger('id_transazione_contabile_incasso'); // Link alla scrittura di incasso
            $table->date('data_incasso');
            $table->decimal('importo_incassato', 15, 2);
            $table->unsignedBigInteger('id_conto_condominio_accredito'); // Conto su cui è avvenuto l'incasso
            $table->string('mezzo_pagamento', 50)->nullable();
            $table->string('riferimento_pagamento')->nullable(); // Es. CRO, ID Transazione
            $table->text('note')->nullable();
            $table->foreign('id_rata_emessa')->references('id_rata_emessa')->on('rate_emesse')->onDelete('cascade');
            $table->foreign('id_transazione_contabile_incasso')->references('id_transazione')->on('transazioni_contabili')->onDelete('cascade');
            $table->foreign('id_conto_condominio_accredito')->references('id_conto_condominio')->on('conti_condominio')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incassi_rate');
    }
};
