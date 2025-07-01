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
        Schema::create('gestioni', function (Blueprint $table) {
            $table->bigIncrements('id_gestione');
            $table->unsignedBigInteger('id_stabile');
            $table->integer('anno');
            $table->enum('tipo', ['ORDINARIA', 'RISCALDAMENTO', 'STRAORDINARIA']);
            $table->date('data_inizio');
            $table->date('data_fine');
            $table->enum('stato', ['aperta', 'chiusa'])->default('aperta');
            $table->timestamps();

            $table->foreign('id_stabile')->references('id_stabile')->on('stabili')->onDelete('cascade');
            $table->unique(['id_stabile', 'anno', 'tipo'], 'unique_gestione_per_stabile_anno_tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestioni');
    }
};