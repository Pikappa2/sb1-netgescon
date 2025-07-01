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
        Schema::create('piani_rateizzazione', function (Blueprint $table) {
            $table->bigIncrements('id_piano_rateizzazione');
            $table->unsignedBigInteger('id_preventivo')->nullable();
            $table->unsignedBigInteger('id_gestione');
            $table->string('descrizione');
            $table->integer('numero_rate');
            $table->date('data_prima_scadenza');
            $table->string('periodicita', 50);
            $table->jsonb('config_scadenze_personalizzate')->nullable();
            $table->string('stato', 50)->default('ATTIVO');
            $table->text('note')->nullable();
            $table->foreign('id_preventivo')->references('id_preventivo')->on('preventivi')->onDelete('set null');
            $table->foreign('id_gestione')->references('id_gestione')->on('gestioni')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('piani_rateizzazione');
    }
};
