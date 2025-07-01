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
        Schema::create('allegati', function (Blueprint $table) {
            $table->bigIncrements('id_allegato');
            $table->unsignedBigInteger('id_stabile')->nullable(); // Per scope e pulizia
            $table->string('nome_file_originale');
            $table->string('nome_file_storage')->unique();
            $table->text('percorso_file_storage');
            $table->string('tipo_mime', 100);
            $table->bigInteger('dimensione_byte')->unsigned();
            $table->text('descrizione')->nullable();
            $table->unsignedBigInteger('allegabile_id'); // ID del record a cui è allegato
            $table->string('allegabile_type', 100); // Nome della tabella/entità
            $table->unsignedBigInteger('id_utente_caricamento')->nullable(); // Chi ha caricato
            $table->string('tags')->nullable();
            $table->index(['allegabile_id', 'allegabile_type']);
            $table->foreign('id_stabile')->references('id_stabile')->on('stabili')->onDelete('set null');
            // Potresti aggiungere una foreign key per id_utente_caricamento se hai una tabella utenti
            // $table->foreign('id_utente_caricamento')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allegati');
    }
};
