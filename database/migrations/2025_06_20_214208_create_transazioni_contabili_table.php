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
        Schema::create('transazioni_contabili', function (Blueprint $table) {
            $table->bigIncrements('id_transazione');
            $table->unsignedBigInteger('id_stabile');
            $table->unsignedBigInteger('id_gestione')->nullable();
            $table->date('data_registrazione');
            $table->date('data_documento')->nullable();
            $table->date('data_competenza')->nullable();
            $table->string('numero_protocollo_interno', 50)->nullable(); // Rimosso il vincolo unique
            $table->string('tipo_documento_origine', 100)->nullable();
            $table->string('riferimento_documento_esterno')->nullable();
            $table->text('descrizione_generale')->nullable();
            $table->decimal('importo_totale_transazione', 15, 2)->nullable(); // Può essere calcolato
            $table->string('stato_transazione', 50)->default('PROVVISORIA');
            $table->unsignedBigInteger('id_utente_registrazione')->nullable(); // Chi ha registrato
            $table->foreign('id_stabile')->references('id_stabile')->on('stabili')->onDelete('cascade');
            $table->foreign('id_gestione')->references('id_gestione')->on('gestioni')->onDelete('set null');
            // Potresti aggiungere una foreign key per id_utente_registrazione
            // $table->foreign('id_utente_registrazione')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transazioni_contabili');
    }
};
