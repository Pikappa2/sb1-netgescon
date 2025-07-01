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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->bigIncrements('id_audit_log');
            $table->unsignedBigInteger('id_utente')->nullable(); // Utente che ha effettuato la modifica
            $table->string('nome_tabella', 100);
            $table->unsignedBigInteger('id_record_modificato'); // ID del record modificato
            $table->string('azione', 50); // INSERT, UPDATE, DELETE
            $table->jsonb('valori_precedenti')->nullable(); // Stato prima della modifica
            $table->jsonb('valori_nuovi')->nullable();     // Stato dopo la modifica
            $table->text('note')->nullable();
            $table->timestamps(); // created_at sarà la data_modifica
            // Potresti aggiungere una foreign key per id_utente
            // $table->foreign('id_utente')->references('id')->on('users')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
