<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea tutte le tabelle e relazioni per la gestione dei ticket (categorie, ticket, aggiornamenti, messaggi, allegati).
     */
    public function up(): void
    {
        // --- Categorie Ticket ---
        Schema::create('categorie_ticket', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->unique();
            $table->text('descrizione')->nullable();
            $table->timestamps();
        });

        // --- Tickets ---
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stabile_id');
            $table->unsignedBigInteger('unita_immobiliare_id')->nullable();
            $table->unsignedBigInteger('soggetto_richiedente_id')->nullable();
            $table->unsignedBigInteger('aperto_da_user_id');
            $table->unsignedBigInteger('categoria_ticket_id')->nullable();
            $table->string('titolo');
            $table->text('descrizione');
            $table->string('luogo_intervento')->nullable()->comment('Es. Scala A, Piano 3, Interno 5');
            $table->enum('stato', [
                'Aperto', 'Preso in Carico', 'In Lavorazione', 'In Attesa Approvazione',
                'In Attesa Ricambi', 'Risolto', 'Chiuso', 'Annullato'
            ])->default('Aperto');
            $table->enum('priorita', ['Bassa', 'Media', 'Alta', 'Urgente'])->default('Media');
            $table->unsignedBigInteger('assegnato_a_user_id')->nullable();
            $table->unsignedBigInteger('assegnato_a_fornitore_id')->nullable();
            $table->timestamp('data_apertura')->useCurrent();
            $table->date('data_scadenza_prevista')->nullable();
            $table->timestamp('data_risoluzione_effettiva')->nullable();
            $table->timestamp('data_chiusura_effettiva')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('stabile_id')->references('id')->on('stabili')->onDelete('cascade');
            $table->foreign('unita_immobiliare_id')->references('id')->on('unita_immobiliari')->onDelete('set null');
            $table->foreign('soggetto_richiedente_id')->references('id')->on('soggetti')->onDelete('set null');
            $table->foreign('aperto_da_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('categoria_ticket_id')->references('id')->on('categorie_ticket')->onDelete('set null');
            $table->foreign('assegnato_a_user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('assegnato_a_fornitore_id')->references('id')->on('fornitori')->onDelete('set null');
        });

        // --- Ticket Updates ---
        Schema::create('ticket_updates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('update_text')->comment("Testo dell'aggiornamento o nota interna");
            $table->timestamps();
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        // --- Ticket Messages ---
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->text('messaggio');
            $table->timestamps();
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        // --- Ticket Attachments ---
        Schema::create('ticket_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->foreignId('ticket_update_id')->nullable()->constrained('ticket_updates')->onDelete('cascade');
            $table->foreignId('user_id')->comment('Utente che ha caricato l allegato')->constrained('users')->onDelete('cascade');
            $table->string('file_path');
            $table->string('original_file_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable()->comment('Dimensione in bytes');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_attachments');
        Schema::dropIfExists('ticket_messages');
        Schema::dropIfExists('ticket_updates');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('categorie_ticket');
    }
};
