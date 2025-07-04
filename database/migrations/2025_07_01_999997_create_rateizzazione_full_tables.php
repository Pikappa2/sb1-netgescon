<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migration legacy svuotata: le tabelle di rateizzazione sono ora gestite nella migration master/unificata.
    }

    public function down(): void
    {
        // Nessuna azione: le tabelle sono gestite dalla migration master.
    }
};
