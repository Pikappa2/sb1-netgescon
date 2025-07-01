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
        Schema::create('preventivi', function (Blueprint $table) {
            $table->bigIncrements('id_preventivo');
            $table->unsignedBigInteger('id_gestione');
            $table->string('descrizione');
            $table->date('data_approvazione')->nullable();
            $table->string('stato', 50)->default('BOZZA');
            $table->text('note')->nullable();
            $table->foreign('id_gestione')->references('id_gestione')->on('gestioni')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preventivi');
    }
};
