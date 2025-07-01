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
        Schema::create('piano_conti_condominio', function (Blueprint $table) {
            $table->bigIncrements('id_conto_condominio_pc');
            $table->unsignedBigInteger('id_stabile');
            $table->unsignedBigInteger('id_conto_modello_riferimento')->nullable();
            $table->string('codice', 20);
            $table->string('descrizione');
            $table->string('tipo_conto', 50);
            $table->string('natura_saldo_tipico', 5)->nullable();
            $table->boolean('is_conto_finanziario')->default(false);
            $table->boolean('attivo')->default(true);
            $table->text('note')->nullable();
            $table->foreign('id_stabile')->references('id_stabile')->on('stabili')->onDelete('cascade');
            $table->foreign('id_conto_modello_riferimento')->references('id_conto_modello')->on('piani_conti_modello')->onDelete('set null');
            $table->unique(['id_stabile', 'codice']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piano_conti_condominio');
    }
};
