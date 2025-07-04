<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Migration legacy svuotata: la tabella richieste_modifiche è ora gestita nella migration master/unificata.
    }

    public function down(): void
    {
        // Nessuna azione: la tabella viene gestita dalla migration master.
    }
};