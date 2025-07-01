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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stabile_id')->constrained(table: 'stabili', column: 'id_stabile')->onDelete('cascade');
            $table->foreignId('unita_immobiliare_id')->nullable()->constrained(table: 'unita_immobiliari', column: 'id_unita')->onDelete('set null');
            $table->foreignId('soggetto_richiedente_id')->nullable()->constrained(table: 'soggetti', column: 'id_soggetto')->onDelete('set null');
            $table->foreignId('aperto_da_user_id')->comment('Utente che ha aperto il ticket')->constrained('users')->onDelete('cascade');
            $table->foreignId('categoria_ticket_id')->nullable()->constrained(table: 'categorie_ticket', column: 'id')->onDelete('set null');
            $table->string('titolo');
            $table->text('descrizione');
            $table->string('luogo_intervento')->nullable()->comment('Es. Scala A, Piano 3, Interno 5');

            $table->enum('stato', [
                'Aperto', 'Preso in Carico', 'In Lavorazione', 'In Attesa Approvazione',
                'In Attesa Ricambi', 'Risolto', 'Chiuso', 'Annullato'
            ])->default('Aperto');
            $table->enum('priorita', ['Bassa', 'Media', 'Alta', 'Urgente'])->default('Media');

            $table->foreignId('assegnato_a_user_id')->nullable()->comment('Utente interno (manutentore/collaboratore)')->constrained('users')->onDelete('set null'); // Utente del sistema a cui è assegnato
            $table->foreignId('assegnato_a_fornitore_id')->nullable()->constrained(table: 'fornitori', column: 'id_fornitore')->onDelete('set null'); // Fornitore esterno assegnato

            $table->timestamp('data_apertura')->useCurrent();
            $table->date('data_scadenza_prevista')->nullable();
            $table->timestamp('data_risoluzione_effettiva')->nullable();
            $table->timestamp('data_chiusura_effettiva')->nullable();

            $table->timestamps(); // created_at, updated_at
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
