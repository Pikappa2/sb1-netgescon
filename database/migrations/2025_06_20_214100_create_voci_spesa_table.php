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
    if (!Schema::hasTable('voci_spesa')) {
        Schema::create('voci_spesa', function (Blueprint $table) {
            $table->bigIncrements('id_voce');
            $table->string('codice')->nullable()->unique();
            $table->string('descrizione');
            $table->string('tipo', 50)->nullable()->comment('ordinaria/straordinaria/riscaldamento/altro');
            $table->text('note')->nullable();
            $table->timestamps();
        });
        }   
    }
        
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voci_spesa');
    }
};
// Questo file crea la tabella 'voci_spesa' per gestire le voci di spesa nel gestionale.
// La tabella include:
// - id_voce: ID univoco della voce di spesa
// - codice: Codice univoco della voce di spesa
// - descrizione: Descrizione della voce di spesa
// - tipo: Tipo di spesa (ordinaria, straordinaria, riscaldamento, altro)
// - note: Note aggiuntive sulla voce di spesa
// - timestamps: Campi created_at e updated_at per la gestione delle date
//