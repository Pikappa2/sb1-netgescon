<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabella assemblee
        Schema::create('assemblee', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stabile_id');
            $table->enum('tipo', ['ordinaria', 'straordinaria']);
            $table->datetime('data_prima_convocazione');
            $table->datetime('data_seconda_convocazione');
            $table->string('luogo');
            $table->text('note')->nullable();
            $table->enum('stato', ['bozza', 'convocata', 'svolta', 'chiusa', 'archiviata'])->default('bozza');
            $table->date('data_convocazione')->nullable();
            $table->date('data_svolgimento')->nullable();
            $table->unsignedBigInteger('creato_da_user_id');
            $table->timestamps();

            $table->foreign('stabile_id')->references('id_stabile')->on('stabili')->onDelete('cascade');
            $table->foreign('creato_da_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['stabile_id', 'data_prima_convocazione']);
        });

        // Tabella ordine del giorno
        Schema::create('ordine_giorno', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assemblea_id');
            $table->integer('numero_punto');
            $table->string('titolo');
            $table->text('descrizione');
            $table->enum('tipo_voce', ['discussione', 'delibera', 'spesa', 'preventivo', 'altro']);
            $table->unsignedBigInteger('collegamento_preventivo_id')->nullable();
            $table->decimal('importo_spesa', 12, 2)->nullable();
            $table->unsignedBigInteger('tabella_millesimale_id')->nullable();
            $table->enum('esito_votazione', ['non_votato', 'approvato', 'respinto', 'rinviato'])->default('non_votato');
            $table->integer('voti_favorevoli')->default(0);
            $table->integer('voti_contrari')->default(0);
            $table->integer('astenuti')->default(0);
            $table->decimal('millesimi_favorevoli', 10, 4)->default(0);
            $table->decimal('millesimi_contrari', 10, 4)->default(0);
            $table->decimal('millesimi_astenuti', 10, 4)->default(0);
            $table->text('note_delibera')->nullable();
            $table->timestamps();

            $table->foreign('assemblea_id')->references('id')->on('assemblee')->onDelete('cascade');
            $table->foreign('collegamento_preventivo_id')->references('id')->on('preventivi')->onDelete('set null');
            $table->foreign('tabella_millesimale_id')->references('id')->on('tabelle_millesimali')->onDelete('set null');
        });

        // Tabella convocazioni (tracciamento invii)
        Schema::create('convocazioni', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assemblea_id');
            $table->unsignedBigInteger('soggetto_id');
            $table->unsignedBigInteger('unita_immobiliare_id');
            $table->enum('canale_invio', ['email', 'pec', 'whatsapp', 'telegram', 'raccomandata', 'mano', 'portiere', 'postale']);
            $table->datetime('data_invio');
            $table->enum('esito_invio', ['inviato', 'consegnato', 'letto', 'errore', 'rifiutato']);
            $table->datetime('data_lettura')->nullable();
            $table->string('riferimento_invio')->nullable(); // ID email, numero raccomandata, etc.
            $table->text('note_invio')->nullable();
            $table->boolean('delega_presente')->default(false);
            $table->unsignedBigInteger('delegato_soggetto_id')->nullable();
            $table->string('documento_delega')->nullable();
            $table->boolean('presenza_confermata')->default(false);
            $table->datetime('data_conferma_presenza')->nullable();
            $table->timestamps();

            $table->foreign('assemblea_id')->references('id')->on('assemblee')->onDelete('cascade');
            $table->foreign('soggetto_id')->references('id_soggetto')->on('soggetti')->onDelete('cascade');
            $table->foreign('unita_immobiliare_id')->references('id_unita')->on('unita_immobiliari')->onDelete('cascade');
            $table->foreign('delegato_soggetto_id')->references('id_soggetto')->on('soggetti')->onDelete('set null');
            $table->index(['assemblea_id', 'soggetto_id']);
        });

        // Tabella presenze assemblea
        Schema::create('presenze_assemblea', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assemblea_id');
            $table->unsignedBigInteger('soggetto_id');
            $table->unsignedBigInteger('unita_immobiliare_id');
            $table->enum('tipo_presenza', ['presente', 'delegato', 'assente']);
            $table->datetime('ora_arrivo')->nullable();
            $table->datetime('ora_uscita')->nullable();
            $table->string('firma_digitale')->nullable();
            $table->string('qr_code')->nullable();
            $table->boolean('firma_fisica')->default(false);
            $table->decimal('millesimi_rappresentati', 10, 4);
            $table->unsignedBigInteger('delegante_soggetto_id')->nullable(); // Se è un delegato
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('assemblea_id')->references('id')->on('assemblee')->onDelete('cascade');
            $table->foreign('soggetto_id')->references('id_soggetto')->on('soggetti')->onDelete('cascade');
            $table->foreign('unita_immobiliare_id')->references('id_unita')->on('unita_immobiliari')->onDelete('cascade');
            $table->foreign('delegante_soggetto_id')->references('id_soggetto')->on('soggetti')->onDelete('set null');
        });

        // Tabella votazioni
        Schema::create('votazioni', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ordine_giorno_id');
            $table->unsignedBigInteger('soggetto_id');
            $table->unsignedBigInteger('unita_immobiliare_id');
            $table->enum('voto', ['favorevole', 'contrario', 'astenuto', 'non_votante']);
            $table->decimal('millesimi_voto', 10, 4);
            $table->datetime('data_voto');
            $table->text('motivazione')->nullable();
            $table->timestamps();

            $table->foreign('ordine_giorno_id')->references('id')->on('ordine_giorno')->onDelete('cascade');
            $table->foreign('soggetto_id')->references('id_soggetto')->on('soggetti')->onDelete('cascade');
            $table->foreign('unita_immobiliare_id')->references('id_unita')->on('unita_immobiliari')->onDelete('cascade');
            $table->unique(['ordine_giorno_id', 'soggetto_id', 'unita_immobiliare_id'], 'unique_voto');
        });

        // Tabella delibere (risultati votazioni)
        Schema::create('delibere', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ordine_giorno_id');
            $table->string('numero_delibera');
            $table->enum('esito', ['approvata', 'respinta', 'rinviata']);
            $table->text('testo_delibera');
            $table->integer('totale_voti_favorevoli');
            $table->integer('totale_voti_contrari');
            $table->integer('totale_astenuti');
            $table->decimal('totale_millesimi_favorevoli', 10, 4);
            $table->decimal('totale_millesimi_contrari', 10, 4);
            $table->decimal('totale_millesimi_astenuti', 10, 4);
            $table->decimal('percentuale_approvazione', 5, 2);
            $table->boolean('maggioranza_raggiunta');
            $table->date('data_delibera');
            $table->json('allegati')->nullable();
            $table->timestamps();

            $table->foreign('ordine_giorno_id')->references('id')->on('ordine_giorno')->onDelete('cascade');
            $table->unique('numero_delibera');
        });

        // Tabella verbali
        Schema::create('verbali', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assemblea_id');
            $table->string('numero_verbale');
            $table->text('testo_verbale');
            $table->json('allegati')->nullable();
            $table->date('data_redazione');
            $table->unsignedBigInteger('redatto_da_user_id');
            $table->string('firma_digitale')->nullable();
            $table->boolean('inviato_condomini')->default(false);
            $table->datetime('data_invio_condomini')->nullable();
            $table->enum('stato', ['bozza', 'definitivo', 'inviato', 'archiviato'])->default('bozza');
            $table->timestamps();

            $table->foreign('assemblea_id')->references('id')->on('assemblee')->onDelete('cascade');
            $table->foreign('redatto_da_user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Tabella registro protocollo (per tutte le comunicazioni)
        Schema::create('registro_protocollo', function (Blueprint $table) {
            $table->id();
            $table->string('numero_protocollo')->unique();
            $table->enum('tipo_comunicazione', ['convocazione', 'verbale', 'delibera', 'comunicazione', 'delega', 'altro']);
            $table->unsignedBigInteger('assemblea_id')->nullable();
            $table->unsignedBigInteger('soggetto_destinatario_id')->nullable();
            $table->unsignedBigInteger('soggetto_mittente_id')->nullable();
            $table->string('oggetto');
            $table->text('contenuto')->nullable();
            $table->enum('canale', ['email', 'pec', 'whatsapp', 'telegram', 'raccomandata', 'mano', 'portiere', 'postale']);
            $table->datetime('data_invio');
            $table->enum('esito', ['inviato', 'consegnato', 'letto', 'errore', 'rifiutato']);
            $table->datetime('data_consegna')->nullable();
            $table->datetime('data_lettura')->nullable();
            $table->string('riferimento_esterno')->nullable();
            $table->json('allegati')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('creato_da_user_id');
            $table->timestamps();

            $table->foreign('assemblea_id')->references('id')->on('assemblee')->onDelete('set null');
            $table->foreign('soggetto_destinatario_id')->references('id_soggetto')->on('soggetti')->onDelete('set null');
            $table->foreign('soggetto_mittente_id')->references('id_soggetto')->on('soggetti')->onDelete('set null');
            $table->foreign('creato_da_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['data_invio', 'tipo_comunicazione']);
        });

        // Tabella documenti assemblea
        Schema::create('documenti_assemblea', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assemblea_id');
            $table->string('nome_documento');
            $table->string('tipo_documento'); // convocazione, verbale, allegato, delega, etc.
            $table->string('path_file');
            $table->string('mime_type');
            $table->unsignedBigInteger('dimensione_file');
            $table->string('hash_file');
            $table->text('descrizione')->nullable();
            $table->unsignedBigInteger('caricato_da_user_id');
            $table->timestamps();

            $table->foreign('assemblea_id')->references('id')->on('assemblee')->onDelete('cascade');
            $table->foreign('caricato_da_user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // Tabella automazioni spese approvate
        Schema::create('automazioni_spese_approvate', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('delibera_id');
            $table->unsignedBigInteger('preventivo_generato_id')->nullable();
            $table->unsignedBigInteger('ripartizione_generata_id')->nullable();
            $table->json('rate_generate')->nullable();
            $table->enum('stato_automazione', ['in_attesa', 'in_corso', 'completata', 'errore']);
            $table->text('log_automazione')->nullable();
            $table->datetime('data_esecuzione')->nullable();
            $table->timestamps();

            $table->foreign('delibera_id')->references('id')->on('delibere')->onDelete('cascade');
            $table->foreign('preventivo_generato_id')->references('id')->on('preventivi')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('automazioni_spese_approvate');
        Schema::dropIfExists('documenti_assemblea');
        Schema::dropIfExists('registro_protocollo');
        Schema::dropIfExists('verbali');
        Schema::dropIfExists('delibere');
        Schema::dropIfExists('votazioni');
        Schema::dropIfExists('presenze_assemblea');
        Schema::dropIfExists('convocazioni');
        Schema::dropIfExists('ordine_giorno');
        Schema::dropIfExists('assemblee');
    }
};