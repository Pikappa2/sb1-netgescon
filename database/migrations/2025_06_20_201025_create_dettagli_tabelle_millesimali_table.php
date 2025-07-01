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
        Schema::create('dettagli_tabelle_millesimali', function (Blueprint $table) {
            $table->bigIncrements('id_dettaglio_millesimale');
            $table->unsignedBigInteger('id_tabella_millesimale');
            $table->unsignedBigInteger('id_unita');
            $table->decimal('valore_millesimale', 10, 4);
            $table->decimal('coefficiente_correttivo', 8, 4)->default(1.0000);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->foreign('id_tabella_millesimale')->references('id_tabella_millesimale')->on('tabelle_millesimali')->onDelete('cascade');
            $table->foreign('id_unita')->references('id_unita')->on('unita_immobiliari')->onDelete('cascade');
            $table->unique(['id_tabella_millesimale', 'id_unita'], 'dett_milles_tab_unita_unique');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dettagli_tabelle_millesimali');
    }
};
