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
        Schema::table('transazioni_contabili', function (Blueprint $table) {
            // Aggiungi i nuovi campi per la gestione del protocollo
            $table->string('protocollo_gestione_tipo', 50)->nullable()->after('numero_protocollo_interno');
            $table->integer('anno_protocollo_documento')->nullable()->after('protocollo_gestione_tipo');
            $table->date('data_protocollo')->nullable()->after('anno_protocollo_documento');

            // Aggiungi un vincolo unico composito per il protocollo master (per condominio, per anno)
            // Questo assicura che numero_protocollo_interno sia unico per ogni anno e condominio.
            $table->unique(['id_stabile', 'anno_protocollo_documento', 'numero_protocollo_interno'], 'unique_master_protocol_per_year_stabile');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transazioni_contabili', function (Blueprint $table) {
            $table->dropUnique('unique_master_protocol_per_year_stabile');
            $table->dropColumn('data_protocollo');
            $table->dropColumn('anno_protocollo_documento');
            $table->dropColumn('protocollo_gestione_tipo');
        });
    }
};
