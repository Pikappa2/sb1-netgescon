<?php

// Migration svuotata: la creazione della tabella 'proprieta' e le relative FK sono ora gestite dalla migration unificata delle anagrafiche o da una migration master dedicata.
// Questo file può essere cancellato dopo la bonifica.

return new class extends Illuminate\Database\Migrations\Migration {
    public function up(): void {}
    public function down(): void {}
};
