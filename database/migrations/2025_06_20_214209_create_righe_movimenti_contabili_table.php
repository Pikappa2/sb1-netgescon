<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migration legacy svuotata: la tabella righe_movimenti_contabili è ora gestita nella migration master/unificata.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nessuna azione: la tabella viene gestita dalla migration master.
    }
};
