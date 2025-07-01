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
        Schema::create('voci_preventivo', function (Blueprint $table) {
            $table->bigIncrements('id_voce_preventivo');
            $table->unsignedBigInteger('id_preventivo');
            $table->unsignedBigInteger('id_piano_conto_condominio_pc');
            $table->text('descrizione_personalizzata')->nullable();
            $table->decimal('importo_previsto', 15, 2);
            $table->unsignedBigInteger('id_tabella_millesimale_ripartizione')->nullable();
            $table->jsonb('criterio_ripartizione_speciale')->nullable(); // Per logiche complesse
            $table->text('note')->nullable();
            $table->foreign('id_preventivo')->references('id_preventivo')->on('preventivi')->onDelete('cascade');
            $table->foreign('id_piano_conto_condominio_pc')->references('id_conto_condominio_pc')->on('piano_conti_condominio')->onDelete('restrict');
            $table->foreign('id_tabella_millesimale_ripartizione')->references('id_tabella_millesimale')->on('tabelle_millesimali')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voci_preventivo');
    }
};
