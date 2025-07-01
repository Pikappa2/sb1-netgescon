<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('fornitori', function (Blueprint $table) {
            $table->bigIncrements('id_fornitore');
            $table->integer('old_id')->nullable()->unique()->comment('ID dal vecchio gestionale');
            $table->foreignId('amministratore_id')->constrained(table: 'amministratori', column: 'id_amministratore')->onDelete('cascade');
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
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('fornitori'); // Già corretto, ma confermo
    }
};
