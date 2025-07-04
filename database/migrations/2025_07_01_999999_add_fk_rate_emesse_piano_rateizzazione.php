<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Migration patch svuotata: la tabella rate_emesse e le FK sono ora gestite nella migration master/unificata.
    }

    public function down(): void
    {
        // Nessuna azione: la tabella viene gestita dalla migration master.
    }
};
