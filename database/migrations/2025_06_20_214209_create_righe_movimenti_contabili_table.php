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
        Schema::create('righe_movimenti_contabili', function (Blueprint $table) {
            $table->bigIncrements('id_riga_movimento');
            $table->unsignedBigInteger('id_transazione');
            $table->unsignedBigInteger('id_piano_conto_condominio_pc');
            $table->unsignedBigInteger('id_gestione_imputazione')->nullable(); // Gestione specifica per la riga
            $table->text('descrizione_riga')->nullable();
            $table->decimal('importo_dare', 15, 2)->default(0.00);
            $table->decimal('importo_avere', 15, 2)->default(0.00);
            $table->unsignedBigInteger('id_unita_immobiliare')->nullable();
            $table->unsignedBigInteger('id_soggetto')->nullable();
            $table->unsignedBigInteger('id_fornitore')->nullable();
            $table->unsignedBigInteger('id_voce_spesa_originale')->nullable(); // Per compatibilità/import
            $table->text('note_riga')->nullable();
            $table->foreign('id_transazione')->references('id_transazione')->on('transazioni_contabili')->onDelete('cascade');
            $table->foreign('id_piano_conto_condominio_pc')->references('id_conto_condominio_pc')->on('piano_conti_condominio')->onDelete('restrict'); // Non eliminare un conto se usato
            $table->foreign('id_gestione_imputazione')->references('id_gestione')->on('gestioni')->onDelete('set null');
            $table->foreign('id_unita_immobiliare')->references('id_unita')->on('unita_immobiliari')->onDelete('set null');
            $table->foreign('id_soggetto')->references('id_soggetto')->on('soggetti')->onDelete('set null');
            $table->foreign('id_fornitore')->references('id_fornitore')->on('fornitori')->onDelete('set null');
            $table->foreign('id_voce_spesa_originale')->references('id_voce')->on('voci_spesa')->onDelete('set null');

            $table->index('id_piano_conto_condominio_pc');
            $table->index('id_gestione_imputazione');            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('righe_movimenti_contabili');
    }
};
