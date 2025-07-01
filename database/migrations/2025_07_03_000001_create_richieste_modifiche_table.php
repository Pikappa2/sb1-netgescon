<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('richieste_modifiche')) {
            Schema::create('richieste_modifiche', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('unita_immobiliare_id');
                $table->unsignedBigInteger('soggetto_richiedente_id');
                $table->enum('tipo_modifica', ['anagrafica', 'catastale', 'proprieta']);
                $table->text('descrizione');
                $table->json('dati_attuali');
                $table->json('dati_proposti');
                $table->enum('stato', ['in_attesa', 'approvata', 'rifiutata'])->default('in_attesa');
                $table->text('note_amministratore')->nullable();
                $table->datetime('data_approvazione')->nullable();
                $table->unsignedBigInteger('approvato_da_user_id')->nullable();
                $table->timestamps();

                $table->foreign('unita_immobiliare_id')->references('id_unita')->on('unita_immobiliari')->onDelete('cascade');
                $table->foreign('soggetto_richiedente_id')->references('id_soggetto')->on('soggetti')->onDelete('cascade');
                $table->foreign('approvato_da_user_id')->references('id')->on('users')->onDelete('set null');
                
                $table->index(['stato', 'created_at']);
            });
        }
        // Se servono aggiunte/modifiche, usa Schema::table qui
    }

    public function down(): void
    {
        Schema::dropIfExists('richieste_modifiche');
    }
};