<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gestioni', function (Blueprint $table) {
            $table->id('id_gestione');
            $table->unsignedBigInteger('stabile_id');
            $table->year('anno_gestione');
            $table->string('tipo_gestione', 20)->default('Ord.'); // Ord., Risc., Straord.
            $table->date('data_inizio')->nullable();
            $table->date('data_fine')->nullable();
            $table->enum('stato', ['aperta', 'in_corso', 'chiusa'])->default('aperta');
            $table->text('descrizione')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('stabile_id')->references('id')->on('stabili')->onDelete('cascade');
            $table->index(['stabile_id', 'anno_gestione']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gestioni');
    }
};
