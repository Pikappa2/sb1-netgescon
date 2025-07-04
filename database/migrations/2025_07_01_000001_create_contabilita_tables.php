<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Migration patch svuotata: la tabella gestioni e i nuovi campi sono ora gestiti nella migration master/unificata.
    }

    public function down(): void
    {
        // Nessuna azione: la tabella viene gestita dalla migration master.
    }
};