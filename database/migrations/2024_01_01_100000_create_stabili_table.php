<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stabili', function (Blueprint $table) {
            $table->id('id_stabile');
            $table->unsignedBigInteger('amministratore_id');
            $table->string('denominazione');
            $table->string('indirizzo');
            $table->string('cap', 10);
            $table->string('citta', 60);
            $table->string('provincia', 2);
            $table->string('codice_fiscale', 20)->nullable()->unique();
            $table->text('note')->nullable();
            $table->json('rate_ordinarie_mesi')->nullable();
            $table->json('rate_riscaldamento_mesi')->nullable();
            $table->text('descrizione_rate')->nullable();
            $table->string('stato', 50)->default('attivo');
            $table->integer('old_id')->nullable()->unique();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('amministratore_id')->references('id_amministratore')->on('amministratori')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stabili');
    }
};