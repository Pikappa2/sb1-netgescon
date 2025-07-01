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
        Schema::create('soggetti', function (Blueprint $table) {
            $table->id('id_soggetto');
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soggetti');
    }
};
