<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea tutte le tabelle anagrafiche principali e le relative foreign key.
     * Ordine di creazione: amministratori -> fornitori -> soggetti -> stabili -> unita_immobiliari
     */
    public function up(): void
    {
        // --- Amministratori ---
        Schema::create('amministratori', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cognome');
            $table->unsignedBigInteger('user_id');
            $table->string('denominazione_studio')->nullable();
            $table->string('partita_iva')->nullable()->unique();
            $table->string('codice_fiscale_studio')->nullable();
            $table->string('indirizzo_studio')->nullable();
            $table->string('cap_studio', 10)->nullable();
            $table->string('citta_studio', 60)->nullable();
            $table->string('provincia_studio', 2)->nullable();
            $table->string('telefono_studio')->nullable();
            $table->string('email_studio')->nullable();
            $table->string('pec_studio')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // --- Fornitori ---
        Schema::create('fornitori', function (Blueprint $table) {
            $table->id();
            $table->integer('old_id')->nullable()->unique()->comment('ID dal vecchio gestionale');
            $table->unsignedBigInteger('amministratore_id');
            $table->string('ragione_sociale');
            $table->string('partita_iva', 20)->nullable();
            $table->string('codice_fiscale', 20)->nullable();
            $table->string('indirizzo')->nullable();
            $table->string('cap', 10)->nullable();
            $table->string('citta', 60)->nullable();
            $table->string('provincia', 2)->nullable();
            $table->string('email')->nullable();
            $table->string('pec')->nullable();
            $table->string('telefono')->nullable();
            $table->timestamps();
            $table->foreign('amministratore_id')->references('id')->on('amministratori')->onDelete('cascade');
        });

        // --- Soggetti ---
        Schema::create('soggetti', function (Blueprint $table) {
            $table->id();
            $table->integer('old_id')->nullable()->unique()->comment('ID dal vecchio gestionale');
            $table->string('nome')->nullable();
            $table->string('cognome')->nullable();
            $table->string('ragione_sociale')->nullable();
            $table->string('codice_fiscale', 16)->nullable()->index();
            $table->string('partita_iva', 11)->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('telefono')->nullable();
            $table->string('indirizzo')->nullable();
            $table->string('cap', 10)->nullable();
            $table->string('citta', 60)->nullable();
            $table->string('provincia', 2)->nullable();
            $table->enum('tipo', ['proprietario', 'inquilino', 'usufruttuario', 'altro']);
            $table->timestamps();
        });

        // --- Stabili ---
        Schema::create('stabili', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('amministratore_id');
            $table->string('denominazione');
            $table->string('indirizzo');
            $table->string('cap', 10);
            $table->string('citta', 60);
            $table->string('provincia', 2);
            $table->string('codice_fiscale', 20)->nullable()->unique();
            $table->text('note')->nullable();
            $table->json('rate_ordinarie_mesi')->nullable();
            $table->json('rate_riscaldamento_mesi')->nullable();
            $table->text('descrizione_rate')->nullable();
            $table->string('stato', 50)->default('attivo');
            $table->integer('old_id')->nullable()->unique();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('amministratore_id')->references('id')->on('amministratori')->onDelete('cascade');
        });

        // --- Piano Conti Condominio ---
        Schema::create('piano_conti_condominio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stabile_id');
            $table->string('codice', 20);
            $table->string('descrizione');
            $table->string('tipo_conto', 20)->nullable();
            $table->boolean('attivo')->default(true);
            $table->timestamps();
            $table->foreign('stabile_id')->references('id')->on('stabili')->onDelete('cascade');
            $table->unique(['stabile_id', 'codice'], 'unique_conto_per_stabile');
        });

        // --- Unita Immobiliari ---
        Schema::create('unita_immobiliari', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stabile_id');
            $table->string('fabbricato')->nullable();
            $table->string('interno')->nullable();
            $table->string('scala')->nullable();
            $table->string('piano')->nullable();
            $table->string('subalterno')->nullable();
            $table->string('categoria_catastale', 10)->nullable();
            $table->decimal('superficie', 8, 2)->nullable();
            $table->decimal('vani', 5, 2)->nullable();
            $table->string('indirizzo')->nullable()->comment('Indirizzo specifico se diverso da quello del condominio');
            $table->text('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('stabile_id')->references('id')->on('stabili')->onDelete('cascade');
        });

        // --- Proprieta ---
        Schema::create('proprieta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('soggetto_id');
            $table->unsignedBigInteger('unita_immobiliare_id');
            $table->string('tipo_diritto', 50)->nullable();
            $table->decimal('percentuale_possesso', 7, 4)->nullable();
            $table->date('data_inizio')->nullable();
            $table->date('data_fine')->nullable();
            $table->timestamps();
            $table->foreign('soggetto_id')->references('id')->on('soggetti')->onDelete('cascade');
            $table->foreign('unita_immobiliare_id')->references('id')->on('unita_immobiliari')->onDelete('cascade');
            $table->unique(['soggetto_id', 'unita_immobiliare_id', 'tipo_diritto'], 'unique_proprieta_per_unita_soggetto');
        });

        // --- Tabelle Millesimali ---
        Schema::create('tabelle_millesimali', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stabile_id');
            $table->string('nome_tabella_millesimale');
            $table->text('descrizione')->nullable();
            $table->timestamps();
            $table->foreign('stabile_id')->references('id')->on('stabili')->onDelete('cascade');
            $table->unique(['stabile_id', 'nome_tabella_millesimale'], 'unique_tabella_per_stabile');
        });

        // --- Dettagli Tabelle Millesimali ---
        Schema::create('dettagli_tabelle_millesimali', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tabella_millesimale_id');
            $table->unsignedBigInteger('unita_immobiliare_id');
            $table->decimal('millesimi', 10, 4);
            $table->timestamps();
            $table->foreign('tabella_millesimale_id')->references('id')->on('tabelle_millesimali')->onDelete('cascade');
            $table->foreign('unita_immobiliare_id')->references('id')->on('unita_immobiliari')->onDelete('cascade');
            $table->unique(['tabella_millesimale_id', 'unita_immobiliare_id'], 'unique_tabella_unita');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dettagli_tabelle_millesimali');
        Schema::dropIfExists('tabelle_millesimali');
        Schema::dropIfExists('proprieta');
        Schema::dropIfExists('unita_immobiliari');
        Schema::dropIfExists('stabili');
        Schema::dropIfExists('soggetti');
        Schema::dropIfExists('fornitori');
        Schema::dropIfExists('amministratori');
        Schema::dropIfExists('piano_conti_condominio');
    }
};
