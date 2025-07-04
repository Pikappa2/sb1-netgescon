<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabella allegati per la gestione degli allegati generici (morphable).
     */
    public function up(): void
    {
        Schema::create('allegati', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('stabile_id')->nullable();
            $table->string('nome_file_originale');
            $table->string('nome_file_storage')->unique();
            $table->text('percorso_file_storage');
            $table->string('tipo_mime', 100);
            $table->bigInteger('dimensione_byte')->unsigned();
            $table->text('descrizione')->nullable();
            $table->unsignedBigInteger('allegabile_id');
            $table->string('allegabile_type', 100);
            $table->unsignedBigInteger('id_utente_caricamento')->nullable();
            $table->string('tags')->nullable();
            $table->index(['allegabile_id', 'allegabile_type']);
            $table->foreign('stabile_id')->references('id')->on('stabili')->onDelete('set null');
            // $table->foreign('id_utente_caricamento')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allegati');
    }
};
