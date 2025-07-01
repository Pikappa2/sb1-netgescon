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
        Schema::create('tabelle_millesimali', function (Blueprint $table) {
            $table->bigIncrements('id_tabella_millesimale');
            $table->unsignedBigInteger('id_stabile');
            $table->string('nome_tabella', 100);
            $table->text('descrizione')->nullable();
            $table->string('tipo_tabella', 50)->nullable();
            $table->decimal('totale_millesimi_teorico', 10, 4)->default(1000.0000);
            $table->boolean('attiva')->default(true);
            $table->timestamps();
        $table->foreign('id_stabile')->references('id_stabile')->on('stabili')->onDelete('cascade');
        $table->unique(['id_stabile', 'nome_tabella']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tabelle_millesimali');
    }
};
