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
        // Rinomina la tabella condomini in stabili
        Schema::rename('condomini', 'stabili');
        
        // Rinomina la colonna nome in denominazione
        Schema::table('stabili', function (Blueprint $table) {
            $table->renameColumn('nome', 'denominazione');
            $table->renameColumn('id_condominio', 'id_stabile');
        });
        
        // Aggiorna le foreign key nelle tabelle correlate
        if (Schema::hasTable('unita_immobiliari')) {
            Schema::table('unita_immobiliari', function (Blueprint $table) {
                $table->dropForeign(['condominio_id']);
                $table->renameColumn('condominio_id', 'stabile_id');
                $table->foreign('stabile_id')->references('id_stabile')->on('stabili')->onDelete('cascade');
            });
        }
        
        if (Schema::hasTable('tickets')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->dropForeign(['condominio_id']);
                $table->renameColumn('condominio_id', 'stabile_id');
                $table->foreign('stabile_id')->references('id_stabile')->on('stabili')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ripristina le foreign key nelle tabelle correlate
        if (Schema::hasTable('tickets')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->dropForeign(['stabile_id']);
                $table->renameColumn('stabile_id', 'condominio_id');
                $table->foreign('condominio_id')->references('id_condominio')->on('condomini')->onDelete('cascade');
            });
        }
        
        if (Schema::hasTable('unita_immobiliari')) {
            Schema::table('unita_immobiliari', function (Blueprint $table) {
                $table->dropForeign(['stabile_id']);
                $table->renameColumn('stabile_id', 'condominio_id');
                $table->foreign('condominio_id')->references('id_condominio')->on('condomini')->onDelete('cascade');
            });
        }
        
        // Ripristina le colonne originali
        Schema::table('stabili', function (Blueprint $table) {
            $table->renameColumn('denominazione', 'nome');
            $table->renameColumn('id_stabile', 'id_condominio');
        });
        
        // Rinomina la tabella stabili in condomini
        Schema::rename('stabili', 'condomini');
    }
};