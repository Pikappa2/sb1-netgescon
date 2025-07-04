<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migration legacy svuotata: la tabella contratti_locazione_attiva è ora gestita nella migration master/unificata.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Nessuna azione: la tabella viene gestita dalla migration master.
    }
};
