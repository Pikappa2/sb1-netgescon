<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('amministratori', function (Blueprint $table) {
            $table->bigIncrements('id_amministratore'); // Chiave primaria corretta
            $table->string('nome');
            $table->string('cognome');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('denominazione_studio')->nullable();
            $table->string('partita_iva')->nullable()->unique(); // Assicurati che sia unica se necessario
            $table->string('codice_fiscale_studio')->nullable();
            $table->string('indirizzo_studio')->nullable();
            $table->string('cap_studio', 10)->nullable();
            $table->string('citta_studio', 60)->nullable();
            $table->string('provincia_studio', 2)->nullable();
            $table->string('telefono_studio')->nullable();
            $table->string('email_studio')->nullable();
            $table->string('pec_studio')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amministratori');
    }
};
