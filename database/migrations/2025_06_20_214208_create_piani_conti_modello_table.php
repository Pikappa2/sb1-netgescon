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
        Schema::create('piani_conti_modello', function (Blueprint $table) {
            $table->bigIncrements('id'); // PK uniformata
            $table->string('codice', 20)->unique();
            $table->string('descrizione');
            $table->string('tipo_conto', 50)->comment('Es. PATRIMONIALE_ATTIVITA, ECONOMICO_COSTO, FINANZIARIO_ATTIVITA');
            $table->string('natura_saldo_tipico', 5)->nullable()->comment('DARE o AVERE');
            $table->boolean('is_conto_finanziario')->default(false);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piani_conti_modello');
    }
};
